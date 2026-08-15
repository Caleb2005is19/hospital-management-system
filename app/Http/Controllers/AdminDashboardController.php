<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Prescription;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. High-Level Live Stats
        $stats = [
            'total_patients'     => Patient::count(),
            'active_encounters'  => Encounter::whereNotIn('status', ['discharged'])->count(),
            'waiting_triage'     => Encounter::where('status', 'waiting_triage')->count(),
            'waiting_doctor'     => Encounter::whereIn('status', ['waiting_doctor', 'in_consultation'])->count(),
            'waiting_lab'        => LabOrder::where('status', '!=', 'completed')->count(),
            'waiting_pharmacy'   => Prescription::where('status', 'pending')->count(),
            'low_stock_items'    => Inventory::where('stock_quantity', '<=', 50)->count(),
            'total_revenue'      => Invoice::sum('amount_paid'),
            'unpaid_invoices'    => Invoice::where('status', '!=', 'paid')->count(),
            'total_staff'        => User::count(),
        ];

        // 2. Active Encounters Stream (Master Worklist)
        $activeEncounters = Encounter::with(['patient', 'triage', 'doctor'])
            ->whereNotIn('status', ['discharged'])
            ->latest()
            ->take(10)
            ->get();

        // 3. Low Stock Drug Alert
        $lowStockDrugs = Inventory::where('stock_quantity', '<=', 50)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'activeEncounters', 'lowStockDrugs'));
    }
}

