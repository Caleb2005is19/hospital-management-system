<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Prescription;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Services\ChargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    // Pharmacy Main Hub
    public function index()
    {
        // 1. Prescriptions Queue (Latest 25)
        $prescriptions = Prescription::with(['encounter.patient', 'drug', 'doctor', 'pharmacist'])
            ->latest()
            ->take(25)
            ->get();

        // 2. Encounters queued from doctor without items attached yet
        $queuedEncounters = Encounter::with(['patient', 'consultation', 'prescriptions.drug'])
            ->where('status', 'waiting_pharmacy')
            ->latest('updated_at')
            ->take(15)
            ->get();

        // 3. Drug Inventory Catalog
        $inventory = Inventory::orderBy('item_name', 'asc')->get();
        $lowStockCount = Inventory::where('stock_quantity', '<=', 30)->count();

        // 4. Electronic Bin Card Audit Trail (Latest 30 stock movements)
        $auditLogs = InventoryLog::with(['inventory', 'user'])
            ->latest()
            ->take(30)
            ->get();

        return view('pharmacy.index', compact('prescriptions', 'queuedEncounters', 'inventory', 'lowStockCount', 'auditLogs'));
    }

    // Add Prescription to Encounter
    public function storePrescription(Request $request, $encounterId)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'quantity_prescribed' => 'required|integer|min:1',
        ]);

        Prescription::create([
            'encounter_id' => $encounterId,
            'inventory_id' => $validated['inventory_id'],
            'doctor_id' => Auth::id(),
            'dosage' => $validated['dosage'],
            'frequency' => $validated['frequency'],
            'duration' => $validated['duration'],
            'quantity_prescribed' => $validated['quantity_prescribed'],
            'status' => 'pending',
        ]);

        Encounter::where('id', $encounterId)->update(['status' => 'waiting_pharmacy']);

        return back()->with('message', 'Prescription added and patient queued for dispensing.');
    }

    // Dispense Prescription & Deduct Stock with Atomic Logging and Auto-Charge
    public function dispense(Request $request, $prescriptionId)
    {
        $prescription = Prescription::with(['drug', 'encounter.patient'])->findOrFail($prescriptionId);
        $drug = $prescription->drug;

        if ($drug->stock_quantity < $prescription->quantity_prescribed) {
            return back()->with('error', "Stock shortage for {$drug->item_name}! Available: {$drug->stock_quantity}");
        }

        DB::transaction(function () use ($prescription, $drug) {
            $qty = $prescription->quantity_prescribed;
            $balanceBefore = $drug->stock_quantity;
            $balanceAfter = $balanceBefore - $qty;

            // 1. Deduct inventory stock
            $drug->update(['stock_quantity' => $balanceAfter]);

            // 2. Electronic Bin Card Audit Log
            InventoryLog::create([
                'inventory_id' => $drug->id,
                'user_id' => Auth::id(),
                'transaction_type' => 'DISPENSE',
                'quantity_change' => -$qty,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reason' => "Clinical Rx Dispense for {$prescription->encounter->patient->name} ({$prescription->encounter->encounter_number})",
            ]);

            // 3. Mark Prescription Status
            $prescription->update([
                'status' => 'dispensed',
                'pharmacist_id' => Auth::id(),
            ]);

            // 4. Auto-post Immutable Patient Charge via Central ChargeService
            ChargeService::postPharmacyCharge(
                $prescription->encounter_id,
                $drug->id,
                $qty
            );

            // 5. Update Encounter Status if all medications are fulfilled
            $hasPending = Prescription::where('encounter_id', $prescription->encounter_id)
                ->where('status', 'pending')
                ->exists();

            if (!$hasPending) {
                $prescription->encounter->update(['status' => 'waiting_billing']);
            }
        });

        return redirect()->route('pharmacy.index')->with('message', "Dispensed {$drug->item_name} successfully.");
    }

    // Direct Walk-In OTC Sale with Stock Audit Log and Settled Invoice
    public function otcSale(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:Cash,M-PESA,Card,Bank Transfer',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $drug = Inventory::findOrFail($validated['inventory_id']);

        if ($drug->stock_quantity < $validated['quantity']) {
            return back()->with('error', "Insufficient stock for OTC sale. Available: {$drug->stock_quantity}");
        }

        $totalAmount = $drug->unit_price * $validated['quantity'];

        DB::transaction(function () use ($drug, $validated, $totalAmount) {
            $balanceBefore = $drug->stock_quantity;
            $balanceAfter = $balanceBefore - $validated['quantity'];

            // 1. Deduct Stock
            $drug->update(['stock_quantity' => $balanceAfter]);

            // 2. Electronic Bin Card Audit Log
            InventoryLog::create([
                'inventory_id' => $drug->id,
                'user_id' => Auth::id(),
                'transaction_type' => 'OTC_SALE',
                'quantity_change' => -$validated['quantity'],
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reason' => "OTC Direct Sale to {$validated['customer_name']} (Paid via {$validated['payment_method']})",
            ]);

            // 3. Create Settled OTC Invoice
            $invoice = Invoice::create([
                'invoice_number' => 'OTC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'total_amount' => $totalAmount,
                'amount_paid' => $totalAmount,
                'status' => 'paid',
                'created_by' => Auth::id(),
            ]);

            // 4. Create Line Item
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => "OTC Drug Sale: {$drug->item_name} ({$validated['customer_name']})",
                'quantity' => $validated['quantity'],
                'unit_price' => $drug->unit_price,
                'total_price' => $totalAmount,
            ]);

            // 5. Post Payment Transaction
            Payment::create([
                'invoice_id' => $invoice->id,
                'cashier_id' => Auth::id(),
                'amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? ($validated['payment_method'] === 'Cash' ? 'CSH-' . strtoupper(substr(uniqid(), -5)) : null),
                'status' => 'completed',
            ]);
        });

        return redirect()->route('pharmacy.index')->with('message', "OTC Sale completed for {$validated['customer_name']}: KSh " . number_format($totalAmount, 2));
    }

    // Add New Drug / Inward Shipment with Log
    public function storeDrug(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'stock_quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'dosage_form' => 'required|string|max:50',
            'batch_number' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date',
            'supplier_note' => 'nullable|string',
        ]);

        $drug = Inventory::where('item_name', $validated['item_name'])->first();

        if ($drug) {
            $balanceBefore = $drug->stock_quantity;
            $balanceAfter = $balanceBefore + $validated['stock_quantity'];

            $drug->update([
                'stock_quantity' => $balanceAfter,
                'unit_price' => $validated['unit_price'],
            ]);
        } else {
            $balanceBefore = 0;
            $balanceAfter = $validated['stock_quantity'];

            $drug = Inventory::create([
                'item_name' => $validated['item_name'],
                'category' => $validated['category'],
                'dosage_form' => $validated['dosage_form'],
                'stock_quantity' => $balanceAfter,
                'unit_price' => $validated['unit_price'],
            ]);
        }

        // Audit Log Entry
        InventoryLog::create([
            'inventory_id' => $drug->id,
            'user_id' => Auth::id(),
            'transaction_type' => 'RECEIVE',
            'quantity_change' => $validated['stock_quantity'],
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'batch_number' => $validated['batch_number'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'reason' => $validated['supplier_note'] ?? 'Stock Procurement / Inward Delivery',
        ]);

        return redirect()->route('pharmacy.index')->with('message', "Stock updated for '{$drug->item_name}'. Added {$validated['stock_quantity']} units.");
    }

    // Manual Stock Adjustment & Write-off with Mandatory Reason & Log
    public function adjustStock(Request $request, $inventoryId)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:ADJUST_ADD,ADJUST_DEDUCT,DAMAGE_EXPIRED',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|min:5',
        ]);

        $drug = Inventory::findOrFail($inventoryId);
        $balanceBefore = $drug->stock_quantity;

        if ($validated['adjustment_type'] === 'ADJUST_ADD') {
            $balanceAfter = $balanceBefore + $validated['quantity'];
            $change = +$validated['quantity'];
        } else {
            if ($balanceBefore < $validated['quantity']) {
                return back()->with('error', "Cannot deduct more than current stock ({$balanceBefore})!");
            }
            $balanceAfter = $balanceBefore - $validated['quantity'];
            $change = -$validated['quantity'];
        }

        $drug->update(['stock_quantity' => $balanceAfter]);

        // Audit Log entry
        InventoryLog::create([
            'inventory_id' => $drug->id,
            'user_id' => Auth::id(),
            'transaction_type' => $validated['adjustment_type'],
            'quantity_change' => $change,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('pharmacy.index')->with('message', "Stock adjusted for {$drug->item_name}. Balance is now {$balanceAfter}.");
    }

    // Printable Dispensing Label Slip
    public function printLabel($encounterId)
    {
        $encounter = Encounter::with(['patient', 'prescriptions.drug', 'prescriptions.doctor', 'prescriptions.pharmacist'])
            ->findOrFail($encounterId);

        return view('pharmacy.print', compact('encounter'));
    }
}
