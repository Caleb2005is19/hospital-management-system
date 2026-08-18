<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\PatientCharge;

class BillingService
{
    /**
     * Post a charge and immediately sync the master invoice.
     */
    public static function postCharge(
        int $encounterId,
        int $patientId,
        string $description,
        int $quantity,
        float $unitPrice,
        ?int $inventoryId = null,
        ?int $serviceCatalogueId = null,
        int $userId = 1
    ): PatientCharge {
        $total = $quantity * $unitPrice;

        $charge = PatientCharge::create([
            'charge_number'        => 'CHG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
            'encounter_id'         => $encounterId,
            'patient_id'           => $patientId,
            'inventory_id'         => $inventoryId,
            'service_catalogue_id' => $serviceCatalogueId,
            'description'          => $description,
            'quantity'             => $quantity,
            'unit_price'           => $unitPrice,
            'total_price'          => $total,
            'status'               => 'posted',
            'created_by'           => $userId,
        ]);

        self::syncInvoice($encounterId);

        return $charge;
    }

    /**
     * Recompute total balance, departmental subtotals, and invoice status.
     */
    public static function syncInvoice(int $encounterId): Invoice
    {
        $charges = PatientCharge::where('encounter_id', $encounterId)
            ->where('status', '!=', 'reversed')
            ->get();

        $totalAmount = $charges->sum('total_price');

        $consultationTotal = $charges->filter(fn($c) => str_contains(strtolower($c->description), 'consultation'))->sum('total_price');
        $labTotal          = $charges->filter(fn($c) => str_contains(strtolower($c->description), 'lab') || str_contains(strtolower($c->description), 'test'))->sum('total_price');
        $pharmacyTotal     = $charges->filter(fn($c) => str_contains(strtolower($c->description), 'pharmacy') || !empty($c->inventory_id))->sum('total_price');

        $invoice = Invoice::firstOrCreate(
            ['encounter_id' => $encounterId],
            [
                'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'status'         => 'unpaid',
                'amount_paid'    => 0.00,
            ]
        );

        $status = $invoice->status;
        if ($invoice->amount_paid >= $totalAmount && $totalAmount > 0) {
            $status = 'paid';
        } elseif ($invoice->amount_paid > 0 && $invoice->amount_paid < $totalAmount) {
            $status = 'partial';
        } elseif ($totalAmount > 0 && $invoice->amount_paid == 0) {
            $status = 'unpaid';
        }

        $invoice->update([
            'total_amount'     => $totalAmount,
            'consultation_fee' => $consultationTotal,
            'lab_total'        => $labTotal,
            'pharmacy_total'   => $pharmacyTotal,
            'status'           => $status,
        ]);

        return $invoice;
    }

    /**
     * Validate if an encounter is financially clear for final clinical discharge.
     */
    public static function canDischarge(int $encounterId): bool
    {
        $invoice = Invoice::where('encounter_id', $encounterId)->first();
        if (!$invoice) {
            $hasCharges = PatientCharge::where('encounter_id', $encounterId)
                ->where('status', '!=', 'reversed')
                ->exists();
            return !$hasCharges;
        }

        return $invoice->status === 'paid' || $invoice->total_amount == 0;
    }
}
