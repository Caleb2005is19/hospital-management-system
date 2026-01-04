<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drug;
use App\Models\Appointment;
use App\Models\DispensedMedicine;

class PharmacyController extends Controller
{
    // 1. Show Dashboard (Inventory List)
    public function index()
    {
        $drugs = Drug::all();
        // Also show pending prescriptions (Appointments with status 'approved' or 'completed')
        // We assume 'completed' by doctor means ready for pharmacy
        $prescriptions = Appointment::where('status', 'completed')->get();

        return view('pharmacy.home', compact('drugs', 'prescriptions'));
    }

    // 2. Add New Drug to Stock
    public function upload_drug(Request $request)
    {
        $drug = new Drug;
        $drug->name = $request->name;
        $drug->quantity = $request->quantity;
        $drug->price = $request->price;
        $drug->save();

        return redirect()->back()->with('message', 'Drug Added Successfully!');
    }
    // 1. Show the Dispensing Page
    public function dispense_view($id)
    {
        $appointment = Appointment::find($id);
        $drugs = Drug::all(); // Get inventory for the dropdown

        // Get history of what has already been given to this patient
        $history = DispensedMedicine::where('appointment_id', $id)->with('drug')->get();

        return view('pharmacy.dispense', compact('appointment', 'drugs', 'history'));
    }

    // 2. Process the Dispensing (Subtract Stock)
    public function store_dispense(Request $request, $id)
    {
        $drug = Drug::find($request->drug_id);
        $quantity_needed = $request->quantity;

        // Check if we have enough stock
        if ($drug->quantity < $quantity_needed) {
            return redirect()->back()->with('error', 'Not enough stock! Available: ' . $drug->quantity);
        }

        // A. Subtract from Inventory
        $drug->quantity = $drug->quantity - $quantity_needed;
        $drug->save();

        // B. Record the Transaction
        $log = new DispensedMedicine;
        $log->appointment_id = $id;
        $log->drug_id = $drug->id;
        $log->quantity = $quantity_needed;
        $log->cost = $drug->price * $quantity_needed; // Calculate total cost
        $log->save();

        return redirect()->back()->with('message', 'Medicine dispensed successfully!');
    }
}
