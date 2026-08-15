<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Encounter;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Search patients or list recent
    public function index(Request $request)
    {
        $query = $request->input('search');

        $patients = Patient::when($query, function ($q) use ($query) {
            return $q->where('national_id', 'like', "%{$query}%")
                     ->orWhere('patient_number', 'like', "%{$query}%")
                     ->orWhere('phone', 'like', "%{$query}%")
                     ->orWhere('name', 'like', "%{$query}%");
        })->latest()->paginate(10);

        return view('reception.patients', compact('patients', 'query'));
    }

    // Store new patient and automatically start an encounter
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'nullable|string|unique:patients,national_id',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'next_of_kin_name' => 'nullable|string',
            'next_of_kin_phone' => 'nullable|string',
            'allergies' => 'nullable|string',
            'encounter_type' => 'required|in:OPD,IPD,ER',
        ]);

        // Auto-generate Patient Number (e.g. PT-2026-0001)
        $count = Patient::count() + 1;
        $patientNumber = 'PT-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $patient = Patient::create([
            'patient_number' => $patientNumber,
            'national_id' => $validated['national_id'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'next_of_kin_name' => $validated['next_of_kin_name'],
            'next_of_kin_phone' => $validated['next_of_kin_phone'],
            'allergies' => $validated['allergies'],
        ]);

        // Create initial visit / encounter
        $encounterCount = Encounter::count() + 1;
        $encounterNumber = 'ENC-' . date('Y') . '-' . str_pad($encounterCount, 5, '0', STR_PAD_LEFT);

        Encounter::create([
            'patient_id' => $patient->id,
            'encounter_number' => $encounterNumber,
            'type' => $validated['encounter_type'],
            'status' => 'waiting_triage', // Sends patient to Nurse Triage
        ]);

        return redirect()->route('patients.index')->with('message', "Patient {$patient->name} ({$patient->patient_number}) registered & queued for Triage.");
    }

    // Start a new encounter for an existing patient
    public function startEncounter(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $request->validate([
            'encounter_type' => 'required|in:OPD,IPD,ER',
        ]);

        $encounterCount = Encounter::count() + 1;
        $encounterNumber = 'ENC-' . date('Y') . '-' . str_pad($encounterCount, 5, '0', STR_PAD_LEFT);

        Encounter::create([
            'patient_id' => $patient->id,
            'encounter_number' => $encounterNumber,
            'type' => $request->encounter_type,
            'status' => 'waiting_triage',
        ]);

        return redirect()->route('patients.index')->with('message', "New {$request->encounter_type} encounter created for {$patient->name}. Queued for Triage.");
    }
}
