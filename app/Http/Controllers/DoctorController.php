<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Encounter;
use App\Models\Inventory;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ChargeService;
use App\Models\PatientCharge;

class DoctorController extends Controller
{
    // Display Doctor's Consultation Queue
    public function index()
    {
        $encounters = Encounter::with(['patient', 'triage'])
            ->whereIn('status', ['waiting_doctor', 'in_consultation'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('doctor.queue', compact('encounters'));
    }

    // Open Doctor Consultation Room for a patient
    public function consult($encounterId)
    {
        $encounter = Encounter::with([
            'patient', 
            'triage', 
            'consultation', 
            'labOrders.labTest', 
            'prescriptions.drug'
        ])->findOrFail($encounterId);

        if ($encounter->status === 'waiting_doctor') {
            $encounter->update([
                'status' => 'in_consultation',
                'assigned_doctor_id' => Auth::id()
            ]);
        }

        $pastEncounters = Encounter::with(['triage', 'consultation'])
            ->where('patient_id', $encounter->patient_id)
            ->where('id', '!=', $encounter->id)
            ->latest()
            ->get();

        // Catalog of available lab tests and medication stock
        $availableTests = LabTest::orderBy('name', 'asc')->get();
        $availableDrugs = Inventory::where('stock_quantity', '>', 0)->orderBy('item_name', 'asc')->get();

        return view('doctor.consult', compact('encounter', 'pastEncounters', 'availableTests', 'availableDrugs'));
    }

    // Save Clinical Notes & Set Next Step in Journey
    public function store(Request $request, $encounterId)
    {
        $encounter = Encounter::findOrFail($encounterId);

        $validated = $request->validate([
            'history_presenting_illness' => 'required|string',
            'physical_examination' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'nullable|string',
            'next_action' => 'required|in:waiting_lab,waiting_pharmacy,admitted,discharged',
        ]);

        // Save or Update Consultation Note
        Consultation::updateOrCreate(
            ['encounter_id' => $encounter->id],
            [
                'doctor_id' => Auth::id(),
                'history_presenting_illness' => $validated['history_presenting_illness'],
                'physical_examination' => $validated['physical_examination'],
                'diagnosis' => $validated['diagnosis'],
                'treatment_plan' => $validated['treatment_plan'],
            ]
        );

        // Transition Encounter Status
        $encounter->update([
            'status' => $validated['next_action'],
        ]);

        $actionLabels = [
            'waiting_lab' => 'queued for Laboratory Tests',
            'waiting_pharmacy' => 'queued for Pharmacy Dispensing',
            'admitted' => 'marked for Ward Admission',
            'discharged' => 'discharged',
        ];
        // Auto-Post OPD Consultation Charge if not already posted
    $hasConsultCharge = PatientCharge::where('encounter_id', $encounterId)
        ->where('description', 'like', '%Consultation%')
        ->exists();

    if (!$hasConsultCharge) {
        ChargeService::postServiceCharge($encounterId, 'SRV-CONS-001');
    }

        return redirect()->route('doctor.queue')
            ->with('message', "Consultation for {$encounter->patient->name} saved and patient {$actionLabels[$validated['next_action']]}.");
    }
}

