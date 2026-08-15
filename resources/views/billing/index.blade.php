<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-amber-100 text-amber-700 rounded-xl text-lg">💳</span>
                    Hospital Revenue Desk & Cashier Sessions
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Session-gated checkout, multi-tender transactions & blind shift balancing</p>
            </div>
            <div>
                @if($activeSession)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Shift #{{ $activeSession->session_number }} Open
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Shift Closed (Checkout Locked)
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm text-xs font-semibold text-emerald-800">
                ✓ {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm text-xs font-semibold text-rose-800">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <!-- CASHIER SHIFT SESSION CARD -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                @if($activeSession)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Current Active Shift</span>
                        <h3 class="text-base font-black text-slate-900 mt-0.5">
                            {{ $activeSession->session_number }} • Float: KSh {{ number_format($activeSession->opening_float, 2) }}
                        </h3>
                        <p class="text-xs text-slate-500">Opened at {{ $activeSession->opened_at->format('d M Y, H:i') }} by {{ Auth::user()->name }}</p>
                    </div>

                    <!-- Close Shift Form -->
                    <form method="POST" action="{{ route('billing.session.end', $activeSession->id) }}" class="flex flex-col sm:flex-row items-end sm:items-center gap-2">
                        @csrf
                        <div>
                            <input type="number" step="0.5" name="closing_cash_actual" placeholder="Physical Cash Counted" required
                                class="border-slate-300 rounded-xl p-2 text-xs font-bold bg-slate-50">
                        </div>
                        <div>
                            <input type="text" name="variance_reason" placeholder="Variance note (if any)"
                                class="border-slate-300 rounded-xl p-2 text-xs bg-slate-50">
                        </div>
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                            Close & Reconcile Shift 🔒
                        </button>
                    </form>
                @else
                    <div>
                        <span class="text-[10px] uppercase font-bold text-rose-500 tracking-wider block">No Shift Open</span>
                        <h3 class="text-base font-black text-slate-900 mt-0.5">Start Cashier Session to Accept Payments</h3>
                        <p class="text-xs text-slate-500">Enter your opening drawer float to unlock checkout capabilities</p>
                    </div>

                    <!-- Open Shift Form -->
                    <form method="POST" action="{{ route('billing.session.start') }}" class="flex items-center gap-2">
                        @csrf
                        <input type="number" step="0.5" name="opening_float" placeholder="Opening Float (e.g. 5000)" required min="0"
                            class="border-slate-300 rounded-xl p-2 text-xs font-bold bg-slate-50">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                            Open Shift Drawer 🔓
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- REVENUE METRICS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Collected</span>
                    <h4 class="text-2xl font-black text-emerald-600 mt-1">
                        KSh {{ number_format($totalCollected, 2) }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">💰</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending Receivables</span>
                    <h4 class="text-2xl font-black text-amber-600 mt-1">
                        KSh {{ number_format($unpaidTotal, 2) }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">⏳</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unsettled Accounts</span>
                    <h4 class="text-2xl font-black text-rose-600 mt-1">
                        {{ $pendingCount }} Invoices
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">📄</div>
            </div>
        </div>

        <!-- INVOICES LIST -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Patient Encounters Billing Worklist</h3>
                <p class="text-xs text-slate-400">Select encounter to review itemized charges, record payments and generate sequential receipts</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 font-bold uppercase text-slate-500 text-[10px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Invoice No / Date</th>
                            <th class="py-3 px-4 text-left">Patient Details</th>
                            <th class="py-3 px-4 text-left">Total Bill</th>
                            <th class="py-3 px-4 text-left">Amount Paid</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoices as $inv)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-blue-600 text-xs block">{{ $inv->invoice_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $inv->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <strong class="text-slate-900 block font-bold text-sm">{{ $inv->encounter->patient->name ?? 'Walk-in Client' }}</strong>
                                    <span class="text-[11px] text-slate-500 font-mono">{{ $inv->encounter->encounter_number ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                    KSh {{ number_format($inv->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold text-emerald-700">
                                    KSh {{ number_format($inv->amount_paid, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase
                                        {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800' :
                                          ($inv->status === 'partially_paid' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ str_replace('_', ' ', $inv->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('billing.show', $inv->encounter_id) }}" 
                                       class="inline-flex items-center gap-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-3.5 py-1.5 rounded-xl transition shadow-sm">
                                        Open Checkout 💳
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    No invoices recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

