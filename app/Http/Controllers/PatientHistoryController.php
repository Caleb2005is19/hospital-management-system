<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PatientHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $patients = Patient::when($query, function ($q, $query) {
            $q->where('patient_number', 'like', "%{$query}%")
              ->orWhere('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%");
        })->withCount('encounters')->latest()->paginate(15);

        return view('patients.records', compact('patients', 'query'));
    }

    public function show($id)
    {
        $hasEncounterVitals = Schema::hasColumn('vitals', 'encounter_id');

        $relations = [
            'consultation',
            'prescriptions.drug',
            'labOrders'
        ];

        if ($hasEncounterVitals) {
            $relations[] = 'vitals';
        }

        $patient = Patient::with([
            'encounters' => function ($q) use ($relations) {
                $q->with($relations)->latest();
            }
        ])->findOrFail($id);

        $patientVitals = null;
        if (!$hasEncounterVitals && Schema::hasTable('vitals')) {
            $patientVitals = Vital::where('patient_id', $patient->id)->latest()->get();
        }

        return view('patients.history', compact('patient', 'patientVitals'));
    }

    public function apiHistory($id)
    {
        $patient = Patient::with('encounters.doctor', 'encounters.consultation')->findOrFail($id);
        return response()->json($patient);
    }
}
