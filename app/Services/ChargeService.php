<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\Inventory;
use App\Models\PatientCharge;
use App\Models\ServiceCatalogue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChargeService
{
    /**
     * Create an immutable charge from a Service Catalogue item (Consultation, Lab, Procedures, Wards)
     */
    public static function postServiceCharge(int $encounterId, string $serviceCode, int $quantity = 1, ?string $customDescription = null): PatientCharge
    {
        return DB::transaction(function () use ($encounterId, $serviceCode, $quantity, $customDescription) {
            $encounter = Encounter::findOrFail($encounterId);
            $service = ServiceCatalogue::where('code', $serviceCode)->where('is_active', true)->firstOrFail();

            $chargeNumber = 'CHG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $totalPrice = $service->unit_price * $quantity;

            return PatientCharge::create([
                'charge_number' => $chargeNumber,
                'encounter_id' => $encounter->id,
                'patient_id' => $encounter->patient_id,
                'service_catalogue_id' => $service->id,
                'description' => $customDescription ?? $service->name,
                'quantity' => $quantity,
                'unit_price' => $service->unit_price,
                'total_price' => $totalPrice,
                'status' => 'posted',
                'created_by' => Auth::id(),
            ]);
        });
    }

    /**
     * Create an immutable charge for dispensed Pharmacy Medication
     */
    public static function postPharmacyCharge(int $encounterId, int $inventoryId, int $quantity): PatientCharge
    {
        return DB::transaction(function () use ($encounterId, $inventoryId, $quantity) {
            $encounter = Encounter::findOrFail($encounterId);
            $drug = Inventory::findOrFail($inventoryId);

            $chargeNumber = 'CHG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $totalPrice = $drug->unit_price * $quantity;

            return PatientCharge::create([
                'charge_number' => $chargeNumber,
                'encounter_id' => $encounter->id,
                'patient_id' => $encounter->patient_id,
                'inventory_id' => $drug->id,
                'description' => "Pharmacy: {$drug->item_name} ({$quantity} units)",
                'quantity' => $quantity,
                'unit_price' => $drug->unit_price,
                'total_price' => $totalPrice,
                'status' => 'posted',
                'created_by' => Auth::id(),
            ]);
        });
    }
}
