<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    /**
     * Display the laboratory queue.
     */
    public function index()
    {
        $pendingOrders = LabOrder::with(['encounter.patient', 'labTest', 'doctor'])
            ->where('status', '!=', 'completed')
            ->latest()
            ->get();

        $completedOrders = LabOrder::with(['encounter.patient', 'labTest', 'doctor'])
            ->where('status', 'completed')
            ->latest()
            ->take(20)
            ->get();

        $labTests = LabTest::all();

        return view('lab.queue', compact('pendingOrders', 'completedOrders', 'labTests'));
    }

    /**
     * Store new lab test orders and auto-post patient charges.
     */
    public function store(Request $request, $encounterId)
    {
        $request->validate([
            'lab_test_ids' => 'required|array',
            'lab_test_ids.*' => 'exists:lab_tests,id',
        ]);

        $encounter = Encounter::with('patient')->findOrFail($encounterId);

        foreach ($request->lab_test_ids as $testId) {
            $test = LabTest::find($testId);
            $testName = $test->name ?? $test->test_name ?? 'Lab Investigation';
            $testPrice = (float) ($test->price ?? $test->cost ?? 500.00);

            LabOrder::create([
                'encounter_id' => $encounterId,
                'lab_test_id'  => $testId,
                'doctor_id'    => Auth::id(),
                'status'       => 'ordered',
            ]);

            // Automatic billing sync: post charge to patient encounter
            BillingService::postCharge(
                encounterId: (int) $encounterId,
                patientId: (int) $encounter->patient_id,
                description: 'Lab: ' . $testName,
                quantity: 1,
                unitPrice: $testPrice,
                inventoryId: null,
                serviceCatalogueId: null,
                userId: (int) (Auth::id() ?? 1)
            );
        }

        // Queue patient for laboratory work
        $encounter->update(['status' => 'waiting_lab']);

        return back()->with('message', 'Laboratory test(s) ordered and billed successfully.');
    }

    /**
     * Alias for route matching lab.order -> storeOrder
     */
    public function storeOrder(Request $request, $encounterId)
    {
        return $this->store($request, $encounterId);
    }

    /**
     * Update lab order result and status.
     */
    public function update(Request $request, $orderId)
    {
        $order = LabOrder::with('encounter')->findOrFail($orderId);

        $validated = $request->validate([
            'status'  => 'required|in:ordered,pending,sample_collected,processing,completed',
            'result'  => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $dataToUpdate = [
            'status' => $validated['status'],
        ];

        if (isset($validated['result'])) {
            $dataToUpdate['result'] = $validated['result'];
        }

        if (isset($validated['remarks'])) {
            $dataToUpdate['remarks'] = $validated['remarks'];
        }

        $order->update($dataToUpdate);

        // If all lab orders for this encounter are completed, auto-route encounter back to the doctor
        $pendingOrders = LabOrder::where('encounter_id', $order->encounter_id)
            ->where('status', '!=', 'completed')
            ->count();

        if ($pendingOrders === 0 && $validated['status'] === 'completed') {
            $order->encounter->update([
                'status' => 'waiting_doctor'
            ]);
        }

        return back()->with('message', 'Lab test order status updated.');
    }

    /**
     * Print official diagnostic laboratory investigation report.
     */
    public function printReport($encounterId)
    {
        $encounter = Encounter::with(['patient', 'labOrders.labTest', 'labOrders.doctor'])->findOrFail($encounterId);
        $labOrders = $encounter->labOrders;

        return view('lab.print', compact('encounter', 'labOrders'));
    }
}
