<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ChargeService;
use App\Models\PatientCharge;

class LabController extends Controller
{
    // Laboratory Worklist Queue
    public function index()
    {
        $orders = LabOrder::with(['encounter.patient', 'labTest', 'doctor', 'technician'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lab.index', compact('orders'));
    }

    // Doctor: Order Tests
    public function storeOrder(Request $request, $encounterId)
    {
        $request->validate([
            'lab_test_ids' => 'required|array',
            'lab_test_ids.*' => 'exists:lab_tests,id',
        ]);

        foreach ($request->lab_test_ids as $testId) {
            LabOrder::create([
                'encounter_id' => $encounterId,
                'lab_test_id' => $testId,
                'doctor_id' => Auth::id(),
                'status' => 'ordered',
            ]);
        }
      $labTest = LabTest::findOrFail($request->lab_test_id);

    // Auto-create charge under encounter
    PatientCharge::create([
        'charge_number' => 'CHG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
        'encounter_id' => $encounterId,
        'patient_id' => $encounter->patient_id,
        'description' => "Lab Investigation: {$labTest->name}",
        'quantity' => 1,
        'unit_price' => $labTest->price,
        'total_price' => $labTest->price,
        'status' => 'posted',
        'created_by' => Auth::id(),
    ]);

        // Move patient to Lab Queue
        Encounter::where('id', $encounterId)->update(['status' => 'waiting_lab']);

        return back()->with('message', 'Laboratory test(s) ordered successfully.');
    }

    // Lab Tech: Complete Findings & Route Back to Doctor
    public function updateResult(Request $request, $orderId)
    {
        $order = LabOrder::with('encounter')->findOrFail($orderId);

        $validated = $request->validate([
            'status' => 'required|in:sample_collected,processing,completed',
            'result' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $order->update([
            'status' => $validated['status'],
            'result' => $validated['result'],
            'remarks' => $validated['remarks'],
            'technician_id' => Auth::id(),
        ]);

        // If all lab orders for this encounter are completed, AUTO-ROUTE back to the Doctor
        $pendingOrders = LabOrder::where('encounter_id', $order->encounter_id)
            ->where('status', '!=', 'completed')
            ->count();

        if ($pendingOrders === 0 && $validated['status'] === 'completed') {
            $order->encounter->update([
                'status' => 'waiting_doctor'
            ]);

            return redirect()->route('lab.index')
                ->with('message', "Test verified! All tests completed for {$order->encounter->patient->name} — patient routed back to Doctor Queue 🩺.");
        }

        return redirect()->route('lab.index')->with('message', 'Lab test status updated.');
    }

    // Printable Official Laboratory Diagnostic Report
    public function printReport($encounterId)
    {
        $encounter = Encounter::with(['patient', 'labOrders.labTest', 'labOrders.doctor', 'labOrders.technician', 'consultation'])
            ->findOrFail($encounterId);

        return view('lab.print', compact('encounter'));
    }
}

