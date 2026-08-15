<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PatientCharge;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\CashierSession;
use App\Services\CashierSessionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    // Main Billing & Cashier Desk
    public function index()
    {
        $activeSession = CashierSessionService::getActiveSession(Auth::id());

        $invoices = Invoice::with(['encounter.patient', 'payments'])
            ->latest()
            ->take(30)
            ->get();

        $totalCollected = Payment::where('status', 'completed')->sum('amount');
        $totalBilled = PatientCharge::where('status', '!=', 'reversed')->sum('total_price');
        $unpaidTotal = max(0, $totalBilled - $totalCollected);
        $pendingCount = Invoice::where('status', '!=', 'paid')->count();

        // Previous closed sessions for auditing
        $pastSessions = CashierSession::with('user')->latest('opened_at')->take(10)->get();

        return view('billing.index', compact('invoices', 'activeSession', 'totalCollected', 'unpaidTotal', 'pendingCount', 'pastSessions'));
    }

    // Open Shift Session
    public function startSession(Request $request)
    {
        $validated = $request->validate([
            'opening_float' => 'required|numeric|min:0',
        ]);

        CashierSessionService::openSession(Auth::id(), $validated['opening_float']);

        return redirect()->route('billing.index')->with('message', 'Cashier shift session opened successfully.');
    }

    // Close Shift Session (Blind Count)
    public function endSession(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'closing_cash_actual' => 'required|numeric|min:0',
            'variance_reason' => 'nullable|string|max:500',
        ]);

        try {
            $session = CashierSessionService::closeSession(
                $sessionId,
                $validated['closing_cash_actual'],
                $validated['variance_reason']
            );

            $varianceMsg = $session->variance_cash == 0 
                ? "Shift balanced perfectly (0 variance)." 
                : "Shift closed with variance: KSh " . number_format($session->variance_cash, 2);

            return redirect()->route('billing.index')->with('message', "Session {$session->session_number} closed. " . $varianceMsg);
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Show Invoice & Auto-Compile Charges
    public function show($encounterId)
    {
        $encounter = Encounter::with([
            'patient',
            'charges',
            'invoice.payments.cashier',
            'invoice.payments.receipt'
        ])->findOrFail($encounterId);

        $activeSession = CashierSessionService::getActiveSession(Auth::id());

        // 1. Gather all active (unreversed) charges for this encounter
        $totalCharges = PatientCharge::where('encounter_id', $encounter->id)
            ->where('status', '!=', 'reversed')
            ->sum('total_price');

        // 2. Fetch or initialize the Invoice
        $invoice = Invoice::firstOrCreate(
            ['encounter_id' => $encounter->id],
            [
                'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'status' => 'issued',
                'total_amount' => $totalCharges,
                'amount_paid' => 0.00,
                'created_by' => Auth::id(),
            ]
        );

        // Keep invoice total synchronized with live posted charges
        if ($invoice->total_amount != $totalCharges) {
            $invoice->update(['total_amount' => $totalCharges]);
        }

        $invoice->load(['payments.cashier', 'payments.receipt']);

        return view('billing.show', compact('encounter', 'invoice', 'activeSession'));
    }

    // Multi-Tender Payment Process with Session Gate & Sequential Receipt
    public function processPayment(Request $request, $invoiceId)
    {
        $activeSession = CashierSessionService::getActiveSession(Auth::id());

        if (!$activeSession) {
            return back()->withErrors(['error' => 'You must open a Cashier Shift Session before receiving payments.']);
        }

        $invoice = Invoice::with(['encounter.patient', 'payments'])->findOrFail($invoiceId);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:Cash,M-PESA,Insurance,Card,Bank Transfer',
            'reference_number' => 'nullable|string|max:100',
        ]);

        if ($validated['payment_method'] === 'M-PESA' && empty($validated['reference_number'])) {
            return back()->withErrors(['reference_number' => 'M-PESA Transaction Code (e.g. QKH82910XZ) is mandatory.']);
        }

        DB::transaction(function () use ($invoice, $activeSession, $validated) {
            $prevAmountPaid = $invoice->payments()->where('status', 'completed')->sum('amount');
            $prevBalance = max(0, $invoice->total_amount - $prevAmountPaid);

            $paymentNumber = 'PMT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            // 1. Create Immutable Payment
            $payment = Payment::create([
                'payment_number' => $paymentNumber,
                'cashier_session_id' => $activeSession->id,
                'invoice_id' => $invoice->id,
                'cashier_id' => Auth::id(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? ($validated['payment_method'] === 'Cash' ? 'CSH-' . strtoupper(substr(uniqid(), -5)) : null),
                'status' => 'completed',
            ]);

            // 2. Recalculate Totals
            $newAmountPaid = $prevAmountPaid + $validated['amount'];
            $newBalance = max(0, $invoice->total_amount - $newAmountPaid);
            $newStatus = $newAmountPaid >= $invoice->total_amount ? 'paid' : 'partially_paid';

            $invoice->update([
                'amount_paid' => $newAmountPaid,
                'status' => $newStatus,
            ]);

            // 3. Issue Strict Sequential Receipt
            CashierSessionService::issueSequentialReceipt($payment, $invoice, $prevBalance, $newBalance);

            // 4. Update Charge Statuses to 'paid' if bill is fully cleared
            if ($newStatus === 'paid') {
                PatientCharge::where('encounter_id', $invoice->encounter_id)->update(['status' => 'paid']);
                $invoice->encounter->update(['status' => 'discharged']);
            }
        });

        return redirect()->route('billing.show', $invoice->encounter_id)
            ->with('message', 'Payment recorded and Sequential Official Receipt generated.');
    }

    // View / Print Official Receipt
    public function printReceipt($receiptId)
    {
        $receipt = Receipt::with([
            'payment.cashier',
            'invoice.encounter.patient',
            'invoice.encounter.charges'
        ])->findOrFail($receiptId);

        return view('billing.receipt', compact('receipt'));
    }
}
