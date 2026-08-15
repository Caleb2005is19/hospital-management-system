<?php

namespace App\Http\Controllers;

use App\Models\CashierSession;
use App\Models\FinancialAdjustment;
use App\Models\Invoice;
use App\Models\PatientCharge;
use App\Models\Payment;
use App\Services\RevenueProtectionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevenueControlController extends Controller
{
    // Executive Revenue Control Center & Anti-Fraud Hub
    public function index()
    {
        $auditMetrics = RevenueProtectionService::runLeakageAudit();

        $recentAdjustments = FinancialAdjustment::with(['invoice.encounter.patient', 'requester', 'approver'])
            ->latest()
            ->take(15)
            ->get();

        $flaggedSessions = CashierSession::with('user')
            ->where('variance_cash', '!=', 0)
            ->latest('closed_at')
            ->take(10)
            ->get();

        $recentReversedCharges = PatientCharge::with(['patient', 'creator', 'reverser'])
            ->where('status', 'reversed')
            ->latest('reversed_at')
            ->take(10)
            ->get();

        return view('revenue.index', compact('auditMetrics', 'recentAdjustments', 'flaggedSessions', 'recentReversedCharges'));
    }

    // Submit Request for Two-Man Rule Approval (Discount, Refund, Reversal)
    public function requestAdjustment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'type' => 'required|in:discount,refund,write_off',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|min:10|max:500',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        if ($validated['amount'] > $invoice->total_amount) {
            return back()->withErrors(['amount' => 'Adjustment value cannot exceed total bill.']);
        }

        $adjustmentCount = FinancialAdjustment::count() + 1;
        $adjNumber = 'ADJ-' . date('Ymd') . '-' . str_pad($adjustmentCount, 4, '0', STR_PAD_LEFT);

        FinancialAdjustment::create([
            'adjustment_number' => $adjNumber,
            'type' => $validated['type'],
            'invoice_id' => $invoice->id,
            'amount' => $validated['amount'],
            'requested_by' => Auth::id(),
            'approval_status' => 'pending',
            'reason' => $validated['reason'],
        ]);

        return back()->with('message', "Adjustment request {$adjNumber} submitted for Finance Manager approval.");
    }

    // Manager Action on Two-Man Approval (Approve / Reject)
    public function actionAdjustment(Request $request, $adjustmentId)
    {
        $adjustment = FinancialAdjustment::with('invoice')->findOrFail($adjustmentId);

        if ($adjustment->approval_status !== 'pending') {
            return back()->withErrors(['error' => 'This request has already been processed.']);
        }

        // Two-Man Rule: Requester cannot approve their own adjustment
        if ($adjustment->requested_by === Auth::id()) {
            return back()->withErrors(['error' => 'Two-Man Rule Violation: You cannot approve your own adjustment request.']);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:500',
        ]);

        DB::transaction(function () use ($adjustment, $validated) {
            if ($validated['action'] === 'approve') {
                $adjustment->update([
                    'approval_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'actioned_at' => now(),
                ]);

                // Apply adjustments to Invoice
                $invoice = $adjustment->invoice;
                if ($adjustment->type === 'discount' || $adjustment->type === 'write_off') {
                    $newTotal = max(0, $invoice->total_amount - $adjustment->amount);
                    $newStatus = $invoice->amount_paid >= $newTotal ? 'paid' : 'partially_paid';

                    $invoice->update([
                        'discount_amount' => ($invoice->discount_amount ?? 0) + $adjustment->amount,
                        'total_amount' => $newTotal,
                        'status' => $newStatus,
                    ]);
                }
            } else {
                $adjustment->update([
                    'approval_status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'rejection_reason' => $validated['rejection_reason'],
                    'actioned_at' => now(),
                ]);
            }
        });

        return back()->with('message', "Adjustment {$adjustment->adjustment_number} has been {$validated['action']}d.");
    }

    // Charge Reversal with Mandatory Reason (No Deletion Policy)
    public function reverseCharge(Request $request, $chargeId)
    {
        $validated = $request->validate([
            'reversal_reason' => 'required|string|min:10|max:500',
        ]);

        $charge = PatientCharge::findOrFail($chargeId);

        if ($charge->status === 'reversed') {
            return back()->withErrors(['error' => 'This charge is already reversed.']);
        }

        DB::transaction(function () use ($charge, $validated) {
            $charge->update([
                'status' => 'reversed',
                'reversed_by' => Auth::id(),
                'reversal_reason' => $validated['reversal_reason'],
                'reversed_at' => now(),
            ]);

            // Recalculate parent invoice if linked
            $invoice = Invoice::where('encounter_id', $charge->encounter_id)->first();
            if ($invoice) {
                $remainingCharges = PatientCharge::where('encounter_id', $charge->encounter_id)
                    ->where('status', '!=', 'reversed')
                    ->sum('total_price');

                $invoice->update(['total_amount' => $remainingCharges]);
            }
        });

        return back()->with('message', "Charge {$charge->charge_number} successfully reversed with audit logging.");
    }
}
