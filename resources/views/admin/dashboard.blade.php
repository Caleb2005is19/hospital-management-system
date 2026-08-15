<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                🏥 Hospital Master Command Center
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                👑 Super Admin Mode
            </span>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Department Quick Jump Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('patients.index') }}" class="bg-white hover:bg-blue-50 border border-gray-200 rounded-xl p-4 text-center transition shadow-sm hover:shadow group">
                <span class="text-2xl block mb-1">📋</span>
                <span class="font-bold text-gray-800 text-xs group-hover:text-blue-600">Reception</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">{{ $stats['total_patients'] }} Patients</span>
            </a>

            <a href="{{ route('triage.index') }}" class="bg-white hover:bg-indigo-50 border border-gray-200 rounded-xl p-4 text-center transition shadow-sm hover:shadow group">
                <span class="text-2xl block mb-1">🩺</span>
                <span class="font-bold text-gray-800 text-xs group-hover:text-indigo-600">Triage Queue</span>
                <span class="text-[10px] font-bold text-indigo-600 block mt-0.5">{{ $stats['waiting_triage'] }} Waiting</span>
            </a>

            <a href="{{ route('doctor.queue') }}" class="bg-white hover:bg-teal-50 border border-gray-200 rounded-xl p-4 text-center transition shadow-sm hover:shadow group">
                <span class="text-2xl block mb-1">👨‍⚕️</span>
                <span class="font-bold text-gray-800 text-xs group-hover:text-teal-600">Consultation</span>
                <span class="text-[10px] font-bold text-teal-600 block mt-0.5">{{ $stats['waiting_doctor'] }} Queued</span>
            </a>

            <a href="{{ route('lab.index') }}" class="bg-white hover:bg-purple-50 border border-gray-200 rounded-xl p-4 text-center transition shadow-sm hover:shadow group">
                <span class="text-2xl block mb-1">🧪</span>
                <span class="font-bold text-gray-800 text-xs group-hover:text-purple-600">Laboratory</span>
                <span class="text-[10px] font-bold text-purple-600 block mt-0.5">{{ $stats['waiting_lab'] }} Tests</span>
            </a>

            <a href="{{ route('pharmacy.index') }}" class="bg-white hover:bg-emerald-50 border border-gray-200 rounded-xl p-4 text-center transition shadow-sm hover:shadow group">
                <span class="text-2xl block mb-1">💊</span>
                <span class="font-bold text-gray-800 text-xs group-hover:text-emerald-600">Pharmacy</span>
                <span class="text-[10px] font-bold text-emerald-600 block mt-0.5">{{ $stats['waiting_pharmacy'] }} Pending</span>
            </a>

            <a href="{{ route('billing.index') }}" class="bg-white hover:bg-amber-50 border border-gray-200 rounded-xl p-4 text-center transition shadow-sm hover:shadow group">
                <span class="text-2xl block mb-1">💳</span>
                <span class="font-bold text-gray-800 text-xs group-hover:text-amber-600">Billing Desk</span>
                <span class="text-[10px] font-bold text-amber-600 block mt-0.5">{{ $stats['unpaid_invoices'] }} Unpaid</span>
            </a>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Active Encounters</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $stats['active_encounters'] }}</h3>
                </div>
                <span class="p-3 bg-blue-100 text-blue-700 rounded-xl text-lg">🏥</span>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Revenue (KSh)</p>
                    <h3 class="text-2xl font-black text-green-600 mt-1">{{ number_format($stats['total_revenue'], 2) }}</h3>
                </div>
                <span class="p-3 bg-green-100 text-green-700 rounded-xl text-lg">💰</span>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Stock Warnings</p>
                    <h3 class="text-2xl font-black {{ $stats['low_stock_items'] > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">
                        {{ $stats['low_stock_items'] }}
                    </h3>
                </div>
                <span class="p-3 bg-red-100 text-red-700 rounded-xl text-lg">⚠️</span>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Registered Staff</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total_staff'] }}</h3>
                </div>
                <span class="p-3 bg-indigo-100 text-indigo-700 rounded-xl text-lg">👥</span>
            </div>
        </div>

        <!-- Main Content Area: Master Active Worklist & Inventory Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Active Patients in Hospital Journey (2 Cols) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">🔄 Master Live Patient Flow</h3>
                    <span class="text-xs text-gray-400">Real-time status across departments</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-[11px]">
                            <tr>
                                <th class="py-3 px-3 text-left">Visit / Patient</th>
                                <th class="py-3 px-3 text-left">Type</th>
                                <th class="py-3 px-3 text-left">Current Stage</th>
                                <th class="py-3 px-3 text-center">Fast-Track Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($activeEncounters as $enc)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-3">
                                        <strong class="text-gray-900 block">{{ $enc->patient->name }}</strong>
                                        <span class="text-xs text-blue-600 font-semibold">{{ $enc->encounter_number }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $enc->type == 'ER' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $enc->type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full
                                            {{ $enc->status === 'waiting_triage' ? 'bg-indigo-100 text-indigo-700' :
                                              ($enc->status === 'waiting_doctor' || $enc->status === 'in_consultation' ? 'bg-teal-100 text-teal-700' :
                                              ($enc->status === 'waiting_lab' ? 'bg-purple-100 text-purple-700' :
                                              ($enc->status === 'waiting_pharmacy' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700'))) }}">
                                            {{ strtoupper(str_replace('_', ' ', $enc->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if($enc->status === 'waiting_triage')
                                            <a href="{{ route('triage.create', $enc->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs">Take Vitals →</a>
                                        @elseif($enc->status === 'waiting_doctor' || $enc->status === 'in_consultation')
                                            <a href="{{ route('doctor.consult', $enc->id) }}" class="text-teal-600 hover:text-teal-800 font-bold text-xs">Consult →</a>
                                        @elseif($enc->status === 'waiting_lab')
                                            <a href="{{ route('lab.index') }}" class="text-purple-600 hover:text-purple-800 font-bold text-xs">View Lab →</a>
                                        @elseif($enc->status === 'waiting_pharmacy')
                                            <a href="{{ route('pharmacy.index') }}" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs">Dispense →</a>
                                        @else
                                            <a href="{{ route('billing.show', $enc->id) }}" class="text-amber-600 hover:text-amber-800 font-bold text-xs">Bill & Clear →</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 text-xs">
                                        No active encounters currently ongoing. Check in a patient at Reception.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pharmacy Critical Stock Warning (1 Col) -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-bold text-gray-800 text-sm">📦 Low Stock Alerts</h3>
                    <a href="{{ route('pharmacy.index') }}" class="text-xs text-blue-600 font-bold hover:underline">Manage All</a>
                </div>

                <div class="space-y-3 text-xs">
                    @forelse($lowStockDrugs as $drug)
                        <div class="p-3 bg-red-50 rounded-lg border border-red-200 flex justify-between items-center">
                            <div>
                                <strong class="text-gray-900 block">{{ $drug->item_name }}</strong>
                                <span class="text-red-600 font-medium">Reorder Required</span>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-sm text-red-700 block">{{ $drug->stock_quantity }}</span>
                                <span class="text-gray-400 text-[10px]">remaining</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-6">All medication stock levels are healthy.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
