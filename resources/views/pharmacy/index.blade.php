<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-emerald-100 text-emerald-700 rounded-xl text-lg">💊</span>
                    Pharmacy Dispensary, POS & Stock Audit
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Clinical order fulfillment, retail POS, live inventory & immutable Bin Card logs</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Audit Engine Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Flash Messages -->
        @if(session('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm text-xs font-semibold text-emerald-800">
                ✓ {{ session('message') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm text-xs text-red-700 font-semibold">
                ⚠️ {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        <!-- TOP METRICS SUMMARY -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rx In Queue</span>
                    <h4 class="text-2xl font-black text-amber-600 mt-1">
                        {{ $prescriptions->where('status', 'pending')->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">⏳</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dispensed</span>
                    <h4 class="text-2xl font-black text-emerald-600 mt-1">
                        {{ $prescriptions->where('status', 'dispensed')->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">✅</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Low Stock Alert</span>
                    <h4 class="text-2xl font-black text-rose-600 mt-1">
                        {{ $lowStockCount }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base">⚠️</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Formulary Items</span>
                    <h4 class="text-2xl font-black text-slate-900 mt-1">
                        {{ $inventory->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-base">📦</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT: CLINICAL QUEUE & DISPENSING (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Queued Patients from Doctor -->
                @if($queuedEncounters->count() > 0)
                    <div class="bg-amber-50/70 border border-amber-200 p-5 rounded-2xl shadow-sm space-y-3">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-xs uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                                <span>👥</span> Patients Waiting for Prescription Fulfillment ({{ $queuedEncounters->count() }})
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($queuedEncounters as $qEnc)
                                <div class="bg-white p-3.5 rounded-xl border border-amber-200 text-xs space-y-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <strong class="text-slate-900 block font-bold">{{ $qEnc->patient->name }}</strong>
                                            <span class="text-[10px] text-blue-600 font-mono font-semibold">{{ $qEnc->encounter_number }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-amber-100 text-amber-900 uppercase">
                                            AWAITING DISPENSE
                                        </span>
                                    </div>

                                    @if($qEnc->consultation?->diagnosis)
                                        <div class="text-[11px] text-slate-600 bg-slate-50 p-1.5 rounded">
                                            <strong>Dx:</strong> {{ $qEnc->consultation->diagnosis }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('pharmacy.prescription.store', $qEnc->id) }}" class="pt-2 border-t border-slate-100 space-y-1.5">
                                        @csrf
                                        <select name="inventory_id" required class="w-full text-xs border-slate-300 rounded-lg p-1.5 bg-slate-50 font-semibold">
                                            <option value="">-- Choose Stock Medicine --</option>
                                            @foreach($inventory as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->item_name }} ({{ $item->stock_quantity }} left | KSh {{ number_format($item->unit_price, 0) }})
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="grid grid-cols-3 gap-1 text-xs">
                                            <input type="text" name="dosage" placeholder="Dose (500mg)" required class="text-xs border-slate-300 rounded p-1">
                                            <input type="text" name="frequency" placeholder="Freq (TDS)" required class="text-xs border-slate-300 rounded p-1">
                                            <input type="number" name="quantity_prescribed" placeholder="Qty" required min="1" class="text-xs border-slate-300 rounded p-1 font-bold">
                                        </div>
                                        <input type="hidden" name="duration" value="5 Days">

                                        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-1.5 rounded-lg text-xs transition">
                                            + Add & Route to Dispenser
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 2. Active Prescriptions Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-base text-slate-900">Active Prescriptions Worklist</h3>
                            <p class="text-xs text-slate-400">Dispense medicines, decrement live stock & write audit logs</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 font-bold uppercase text-slate-500 text-[10px] tracking-wider">
                                <tr>
                                    <th class="py-3 px-4 text-left">Patient Details</th>
                                    <th class="py-3 px-4 text-left">Medication & Regimen</th>
                                    <th class="py-3 px-4 text-left">Stock Check</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($prescriptions as $presc)
                                    <tr class="hover:bg-slate-50/60 transition-colors {{ $presc->status === 'dispensed' ? 'bg-slate-50/30' : '' }}">
                                        <td class="py-3.5 px-4 align-top">
                                            <div class="font-black text-slate-900 text-sm">{{ $presc->encounter->patient->name }}</div>
                                            <span class="text-xs text-blue-600 font-mono font-bold">{{ $presc->encounter->encounter_number }}</span>
                                            
                                            <div class="mt-1">
                                                <a href="{{ route('pharmacy.print', $presc->encounter_id) }}" target="_blank" 
                                                   class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 transition">
                                                    <span>🖨️</span> Print Label
                                                </a>
                                            </div>
                                        </td>

                                        <td class="py-3.5 px-4 align-top">
                                            <span class="font-bold text-slate-900 block text-sm">{{ $presc->drug->item_name }}</span>
                                            <div class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs font-semibold mt-1">
                                                {{ $presc->dosage }} • {{ $presc->frequency }} • {{ $presc->duration }}
                                            </div>
                                            <span class="text-[11px] text-slate-400 block mt-0.5">
                                                Qty: <strong class="text-slate-800 font-mono">{{ $presc->quantity_prescribed }} units</strong>
                                            </span>
                                        </td>

                                        <td class="py-3.5 px-4 align-top text-xs font-mono">
                                            <span class="px-2 py-1 rounded text-[10px] font-bold
                                                {{ $presc->drug->stock_quantity >= $presc->quantity_prescribed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                {{ $presc->drug->stock_quantity }} in stock
                                            </span>
                                            <span class="text-[10px] text-slate-400 block mt-1 font-sans">
                                                KES {{ number_format($presc->drug->unit_price * $presc->quantity_prescribed, 2) }}
                                            </span>
                                        </td>

                                        <td class="py-3.5 px-4 align-top text-center">
                                            @if($presc->status !== 'dispensed')
                                                <form method="POST" action="{{ route('pharmacy.dispense', $presc->id) }}">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm hover:shadow">
                                                        Dispense 💊
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase border border-emerald-200">
                                                    ✓ Dispensed
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400">
                                            No prescriptions in queue.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. IMMUTABLE BIN CARD AUDIT LOG LEDGER -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                                <span>📜</span> Electronic Bin Card & Stock Audit Trail
                            </h3>
                            <p class="text-xs text-slate-400">Immutable ledger recording every stock movement, dispenser ID & reason</p>
                        </div>
                        <span class="text-[10px] font-bold uppercase bg-slate-200 text-slate-700 px-2 py-1 rounded">Last 30 Movements</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-100/75 font-bold uppercase text-slate-500 text-[10px]">
                                <tr>
                                    <th class="py-3 px-3 text-left">Timestamp / Staff</th>
                                    <th class="py-3 px-3 text-left">Medication Item</th>
                                    <th class="py-3 px-3 text-left">Action Type</th>
                                    <th class="py-3 px-3 text-center">Change</th>
                                    <th class="py-3 px-3 text-left">Balance (Before → After)</th>
                                    <th class="py-3 px-3 text-left">Reason / Audit Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @forelse($auditLogs as $log)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-3">
                                            <strong class="text-slate-900 block font-bold">{{ $log->created_at->format('d M, H:i') }}</strong>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $log->user->name ?? 'System Staff' }}</span>
                                        </td>
                                        <td class="py-3 px-3 font-bold text-slate-900">
                                            {{ $log->inventory->item_name ?? 'Deleted Item' }}
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase
                                                {{ $log->transaction_type === 'RECEIVE' ? 'bg-green-100 text-green-800' :
                                                  ($log->transaction_type === 'DISPENSE' ? 'bg-blue-100 text-blue-800' :
                                                  ($log->transaction_type === 'OTC_SALE' ? 'bg-purple-100 text-purple-800' :
                                                  ($log->transaction_type === 'DAMAGE_EXPIRED' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800'))) }}">
                                                {{ str_replace('_', ' ', $log->transaction_type) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-center font-mono font-bold text-xs {{ $log->quantity_change > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $log->quantity_change > 0 ? '+' . $log->quantity_change : $log->quantity_change }}
                                        </td>
                                        <td class="py-3 px-3 font-mono text-[11px] text-slate-600">
                                            {{ $log->balance_before }} → <strong class="text-slate-900">{{ $log->balance_after }}</strong>
                                        </td>
                                        <td class="py-3 px-3 text-slate-600 text-[11px] max-w-[200px] truncate" title="{{ $log->reason }}">
                                            "{{ $log->reason ?? 'Standard transaction' }}"
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-slate-400">
                                            No audit log entries recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RIGHT: OTC POS + FORMULARY STOCK ADJUSTMENT (1 Col) -->
            <div class="space-y-6">

                <!-- 1. STANDALONE OTC CASH/M-PESA SALE -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 space-y-3">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-emerald-950 flex items-center gap-1.5">
                            <span>⚡</span> Direct OTC Walk-In Cash Sale
                        </h3>
                        <span class="text-[9px] font-black bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full uppercase">POS</span>
                    </div>

                    <form method="POST" action="{{ route('pharmacy.otc') }}" class="space-y-2.5 text-xs">
                        @csrf
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Customer / Client Name *</label>
                            <input type="text" name="customer_name" required placeholder="e.g. Walk-in Client / John" class="w-full border-slate-300 rounded-xl p-2 text-xs bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Select Medicine *</label>
                            <select name="inventory_id" required class="w-full border-slate-300 rounded-xl p-2 text-xs font-semibold bg-slate-50/50">
                                <option value="">-- Choose Stock Item --</option>
                                @foreach($inventory as $item)
                                    @if($item->stock_quantity > 0)
                                        <option value="{{ $item->id }}">
                                            {{ $item->item_name }} (Stock: {{ $item->stock_quantity }} | KSh {{ number_format($item->unit_price, 0) }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Quantity *</label>
                                <input type="number" name="quantity" min="1" value="1" required class="w-full border-slate-300 rounded-xl p-2 text-xs font-bold bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Payment Method</label>
                                <select name="payment_method" class="w-full border-slate-300 rounded-xl p-2 text-xs bg-slate-50/50 font-semibold">
                                    <option value="Cash">Cash</option>
                                    <option value="M-Pesa">M-Pesa</option>
                                    <option value="Card">Credit/Debit Card</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">
                            Complete Direct Sale & Deduct Stock 💵
                        </button>
                    </form>
                </div>

                <!-- 2. STOCK RECEIVING / NEW FORMULARY DRUG (WITH BATCH & LOG) -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                        <span>📦</span> Receive Stock Delivery / New Drug
                    </h3>

                    <form method="POST" action="{{ route('pharmacy.drug.store') }}" class="space-y-2 text-xs">
                        @csrf
                        <div>
                            <label class="block font-semibold text-slate-600 mb-0.5">Drug Name & Strength *</label>
                            <input type="text" name="item_name" placeholder="e.g. Azithromycin 500mg" required class="w-full border-slate-300 rounded-lg p-2 text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Category</label>
                                <select name="category" class="w-full border-slate-300 rounded-lg p-2 text-xs">
                                    <option value="Antibiotic">Antibiotic</option>
                                    <option value="Analgesic">Analgesic</option>
                                    <option value="Antimalarial">Antimalarial</option>
                                    <option value="Antihistamine">Antihistamine</option>
                                    <option value="Supplements">Supplements</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Form</label>
                                <select name="dosage_form" class="w-full border-slate-300 rounded-lg p-2 text-xs">
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Quantity *</label>
                                <input type="number" name="stock_quantity" placeholder="Units to add" required min="1" class="w-full border-slate-300 rounded-lg p-2 text-xs font-bold">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Unit Price (KES) *</label>
                                <input type="number" step="0.5" name="unit_price" placeholder="Price" required class="w-full border-slate-300 rounded-lg p-2 text-xs font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Batch No.</label>
                                <input type="text" name="batch_number" placeholder="e.g. BATCH-89" class="w-full border-slate-300 rounded-lg p-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Expiry Date</label>
                                <input type="date" name="expiry_date" class="w-full border-slate-300 rounded-lg p-1.5 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-600 mb-0.5">Delivery Note / Supplier Ref</label>
                            <input type="text" name="supplier_note" placeholder="e.g. Invoice #2041 from MEDS" class="w-full border-slate-300 rounded-lg p-2 text-xs">
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 rounded-lg text-xs transition shadow-sm">
                            + Receive Stock & Log Inward
                        </button>
                    </form>
                </div>

                <!-- 3. STOCK ADJUSTMENT & EXPIRED WRITE-OFF FORM -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-rose-800 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                        <span>⚖️</span> Stock Adjustment / Write-Off
                    </h3>

                    <form method="POST" action="" id="adjustForm" onsubmit="event.preventDefault(); submitAdjustment();" class="space-y-2 text-xs">
                        @csrf
                        <div>
                            <label class="block font-semibold text-slate-600 mb-0.5">Select Drug *</label>
                            <select id="adjustDrugSelect" required class="w-full border-slate-300 rounded-lg p-2 text-xs font-semibold" onchange="updateAdjustAction()">
                                <option value="">-- Choose Medication --</option>
                                @foreach($inventory as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->item_name }} (Current: {{ $item->stock_quantity }} pcs)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Adjustment Type</label>
                                <select name="adjustment_type" id="adjustType" class="w-full border-slate-300 rounded-lg p-2 text-xs">
                                    <option value="DAMAGE_EXPIRED">Write-off (Damaged/Expired)</option>
                                    <option value="ADJUST_DEDUCT">Count Shortage (Deduct)</option>
                                    <option value="ADJUST_ADD">Count Surplus (Add)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-0.5">Quantity *</label>
                                <input type="number" name="quantity" id="adjustQty" placeholder="Units" required min="1" class="w-full border-slate-300 rounded-lg p-2 text-xs font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-600 mb-0.5">Mandatory Audit Reason *</label>
                            <input type="text" name="reason" id="adjustReason" placeholder="e.g. Broken ampoule during count / Audit discrepancy" required class="w-full border-slate-300 rounded-lg p-2 text-xs">
                        </div>

                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 rounded-lg text-xs transition shadow-sm">
                            Confirm Adjustment & Write Log
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <!-- Script for handling dynamic adjustment form URL -->
    <script>
        function submitAdjustment() {
            const drugId = document.getElementById('adjustDrugSelect').value;
            if (!drugId) {
                alert('Please select a medication to adjust.');
                return;
            }
            const form = document.getElementById('adjustForm');
            form.action = `/pharmacy/adjust/${drugId}`;
            form.submit();
        }
    </script>
</x-app-layout>