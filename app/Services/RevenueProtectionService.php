<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\InventoryLog;
use App\Models\LabOrder;
use App\Models\PatientCharge;
use App\Models\Payment;
use App\Models\CashierSession;
use App\Models\FinancialAdjustment;
use Illuminate\Support\Facades\DB;

class RevenueProtectionService
{
    /**
     * Cross-check clinical volume against financial billing to flag revenue leakage
     */
    public static function runLeakageAudit(): array
    {
        // 1. Lab Investigation Reconciliation
        $completedLabTests = LabOrder::where('status', 'completed')->count();
        $billedLabCharges = PatientCharge::where('description', 'like', 'Lab Investigation%')
            ->where('status', '!=', 'reversed')
            ->count();
        $unbilledLabCount = max(0, $completedLabTests - $billedLabCharges);

        // 2. Pharmacy Dispensing Reconciliation
        $pharmacyDispensedQty = (int) abs(
            InventoryLog::where('transaction_type', 'DISPENSE')->sum('quantity_change')
        );
        $pharmacyBilledQty = (int) PatientCharge::where('description', 'like', 'Pharmacy:%')
            ->where('status', '!=', 'reversed')
            ->sum('quantity');
        $unbilledPharmacyQty = max(0, $pharmacyDispensedQty - $pharmacyBilledQty);

        // 3. Consultations Reconciliation
        $totalEncounters = Encounter::whereIn('status', ['waiting_pharmacy', 'waiting_billing', 'discharged'])->count();
        $billedConsultations = PatientCharge::where('description', 'like', '%Consultation%')
            ->where('status', '!=', 'reversed')
            ->count();
        $unbilledConsults = max(0, $totalEncounters - $billedConsultations);

        // 4. Cashier Shift Variances
        $cashShortagesTotal = (float) abs(
            CashierSession::where('variance_cash', '<', 0)->sum('variance_cash')
        );

        // 5. Pending Two-Man Adjustments (Discounts, Refunds, Reversals)
        $pendingAdjustmentsCount = FinancialAdjustment::where('approval_status', 'pending')->count();

        return [
            'lab' => [
                'completed' => $completedLabTests,
                'billed' => $billedLabCharges,
                'unmatched' => $unbilledLabCount,
                'status' => $unbilledLabCount === 0 ? 'CLEARED' : 'LEAKAGE_DETECTED',
            ],
            'pharmacy' => [
                'dispensed_units' => $pharmacyDispensedQty,
                'billed_units' => $pharmacyBilledQty,
                'unmatched_units' => $unbilledPharmacyQty,
                'status' => $unbilledPharmacyQty === 0 ? 'CLEARED' : 'LEAKAGE_DETECTED',
            ],
            'consultations' => [
                'clinical_visits' => $totalEncounters,
                'billed' => $billedConsultations,
                'unmatched' => $unbilledConsults,
                'status' => $unbilledConsults === 0 ? 'CLEARED' : 'LEAKAGE_DETECTED',
            ],
            'cash_shortages' => $cashShortagesTotal,
            'pending_adjustments' => $pendingAdjustmentsCount,
        ];
    }
}
