<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Triage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TriageController extends Controller
{
    // Display all encounters waiting for Triage
    public function index()
    {
        $encounters = Encounter::with('patient')
            ->where('status', 'waiting_triage')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('triage.index', compact('encounters'));
    }

    // Show the vitals intake form for a specific encounter
    public function create($encounterId)
    {
        $encounter = Encounter::with('patient')->findOrFail($encounterId);

        return view('triage.create', compact('encounter'));
    }

    // Store vitals & forward encounter to Doctor's queue
    public function store(Request $request, $encounterId)
    {
        $encounter = Encounter::findOrFail($encounterId);

        $validated = $request->validate([
            'bp' => 'nullable|string|max:20',
            'temp' => 'nullable|numeric|between:30,45',
            'pulse' => 'nullable|integer|between:30,250',
            'spo2' => 'nullable|integer|between:0,100',
            'weight' => 'nullable|numeric|between:0,300',
            'height' => 'nullable|numeric|between:0,300',
            'priority' => 'required|in:Emergency,Very Urgent,Urgent,Standard,Non-Urgent',
            'chief_complaint' => 'required|string',
        ]);

        // Save Triage Record
        Triage::create([
            'encounter_id' => $encounter->id,
            'nurse_id' => Auth::id(),
            'bp' => $validated['bp'],
            'temp' => $validated['temp'],
            'pulse' => $validated['pulse'],
            'spo2' => $validated['spo2'],
            'weight' => $validated['weight'],
            'height' => $validated['height'],
            'priority' => $validated['priority'],
            'chief_complaint' => $validated['chief_complaint'],
        ]);

        // Update Encounter Status -> Ready for Doctor
        $encounter->update([
            'status' => 'waiting_doctor',
        ]);

        return redirect()->route('triage.index')
            ->with('message', "Vitals recorded for {$encounter->patient->name}. Patient queued for Doctor.");
    }
}
