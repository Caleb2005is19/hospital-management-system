<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\DispensedMedicine;
use App\Models\User;

class BillingController extends Controller
{
    // 1. Show Pending Bills List (Patients ready for discharge)
    public function index()
    {
        // Now that the Nurse sets status to 'completed', the Cashier will see them here!
        $patients = Appointment::whereIn('status', ['approved', 'completed'])->get();

        return view('cashier.home', compact('patients'));
    }

    // 2. Generate Invoice (The Math Logic)
    public function create_bill($id)
    {
        $appointment = Appointment::find($id);

        // A. Calculate Medicine Cost
        $medicine_cost = DispensedMedicine::where('appointment_id', $id)->sum('cost');

        // B. Calculate Room Charge (Mock logic: 1000 per day if admitted)
        // In a real app, we would calculate (DateNow - DateAdmitted)
        $room_cost = 0;
        if ($appointment->status == 'approved') {
            $room_cost = 500; // Flat fee for admission
        }

        // C. Doctor Charge (Fixed)
        $doctor_cost = 500;

        // Total
        $total = $medicine_cost + $room_cost + $doctor_cost;

        return view('cashier.create_bill', compact('appointment', 'medicine_cost', 'room_cost', 'doctor_cost', 'total'));
    }

    // 3. Save the Bill
    public function store_bill(Request $request, $id)
    {
        $bill = new Bill;
        $bill->patient_id = $request->patient_id;
        $bill->appointment_id = $id;
        $bill->doctor_charge = $request->doctor_charge;
        $bill->medicine_charge = $request->medicine_charge;
        $bill->room_charge = $request->room_charge;
        $bill->total_amount = $request->total_amount;
        $bill->status = 'Paid'; // Mark as paid immediately for now

        $bill->save();

        // Old line
        // return redirect('/home')->with('message', 'Payment Received!');

        // New line
        // Redirect to the receipt page using the NEW Bill ID
        return redirect('print_receipt/' . $bill->id);
    }
    public function print_receipt($id)
    {
        // Fetch the bill using the ID
        $bill = Bill::find($id);
        return view('cashier.print_receipt', compact('bill'));
    }
}
