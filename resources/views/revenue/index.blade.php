<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-indigo-100 text-indigo-700 rounded-xl text-lg">🛡️</span>
                    Revenue Protection & Anti-Fraud Control Center
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Automated clinical-to-financial cross checks, two-man approvals & audit trails</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    Integrity Monitor Active
                </span>
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

        <!-- AUTOMATED LEAKAGE CROSS-CHECK RECONCILIATION CARDS -->
        <div>
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-3">Service-to-Billing Cross Checks (Golden Rule #12 & #13)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Lab Cross Check -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Laboratory Diagnostics</span>
                            <h4 class="text-xl font-black text-slate-900 mt-0.5">
                                {{ $auditMetrics['lab']['completed'] }} Tests / {{ $auditMetrics['lab']['billed'] }} Billed
                            </h4>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $auditMetrics['lab']['unmatched'] === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $auditMetrics['lab']['status'] }}
                        </span>
                    </div>
                    <div class="text-[11px] font-mono text-slate-500 pt-2 border-t flex justify-between">
                        <span>Unmatched Leakage:</span>
                        <strong class="{{ $auditMetrics['lab']['unmatched'] > 0 ? 'text-rose-600 font-black' : 'text-emerald-700' }}">
                            {{ $auditMetrics['lab']['unmatched'] }} Unbilled Tests
                        </strong>
                    </div>
                </div>

                <!-- Pharmacy Cross Check -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pharmacy Dispensary</span>
                            <h4 class="text-xl font-black text-slate-900 mt-0.5">
                                {{ $auditMetrics['pharmacy']['dispensed_units'] }} Units / {{ $auditMetrics['pharmacy']['billed_units'] }} Billed
                            </h4>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $auditMetrics['pharmacy']['unmatched_units'] === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $auditMetrics['pharmacy']['status'] }}
                        </span>
                    </div>
                    <div class="text-[11px] font-mono text-slate-500 pt-2 border-t flex justify-between">
                        <span>Bin Card Leakage:</span>
                        <strong class="{{ $auditMetrics['pharmacy']['unmatched_units'] > 0 ? 'text-rose-600 font-black' : 'text-emerald-700' }}">
                            {{ $auditMetrics['pharmacy']['unmatched_units'] }} Units
                        </strong>
                    </div>
                </div>

                <!-- Cashier Drawer Variances -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cumulative Shift Shortages</span>
                            <h4 class="text-xl font-black text-rose-600 mt-0.5">
                                KSh {{ number_format($auditMetrics['cash_shortages'], 2) }}
                            </h4>
                        </div>
                        <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">⚠️</span>
                    </div>
                    <div class="text-[11px] font-mono text-slate-500 pt-2 border-t flex justify-between">
                        <span>Pending Approvals:</span>
                        <strong class="text-indigo-600 font-black">{{ $auditMetrics['pending_adjustments'] }} Requests</strong>
                    </div>
                </div>

            </div>
        </div>

        <!-- TWO-MAN RULE APPROVAL REGISTER -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Two-Man Rule Adjustment Authorization (Discounts / Write-Offs)</h3>
                <p class="text-xs text-slate-400">Financial adjustments require separate managerial approval before impacting receivables</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50 font-bold uppercase text-slate-500 text-[10px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Adj Ref / Type</th>
                            <th class="py-3 px-4 text-left">Invoice / Patient</th>
                            <th class="py-3 px-4 text-left">Amount (KSh)</th>
                            <th class="py-3 px-4 text-left">Requester & Reason</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center">Manager Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentAdjustments as $adj)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-4">
                                    <span class="font-mono font-bold text-blue-600 block">{{ $adj->adjustment_number }}</span>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ $adj->type }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <strong class="text-slate-900 block font-bold">{{ $adj->invoice->encounter->patient->name ?? 'Patient' }}</strong>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $adj->invoice->invoice_number }}</span>
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                    KSh {{ number_format($adj->amount, 2) }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-slate-800 block">{{ $adj->requester->name }}</span>
                                    <p class="text-[11px] text-slate-500 italic max-w-xs truncate">"{{ $adj->reason }}"</p>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase
                                        {{ $adj->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                                          ($adj->approval_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $adj->approval_status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($adj->approval_status === 'pending')
                                        <div class="flex items-center justify-center gap-1">
                                            <form method="POST" action="{{ route('revenue.adjustment.action', $adj->id) }}">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-2.5 py-1 rounded-lg">
                                                    Approve ✓
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('revenue.adjustment.action', $adj->id) }}">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="rejection_reason" value="Rejected by Finance Authority">
                                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] px-2.5 py-1 rounded-lg">
                                                    Reject ✗
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-mono">By {{ $adj->approver->name ?? 'System' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">No adjustment requests on file.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- REVERSED CHARGES AUDIT LOG -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Immutable Charge Reversals Register</h3>
                <p class="text-xs text-slate-400">Preserved records of reversed or cancelled clinical items with mandatory justification</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50 font-bold uppercase text-slate-500 text-[10px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Charge Ref</th>
                            <th class="py-3 px-4 text-left">Patient Details</th>
                            <th class="py-3 px-4 text-left">Item Description</th>
                            <th class="py-3 px-4 text-left">Reversed Value</th>
                            <th class="py-3 px-4 text-left">Reversal Reason & User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentReversedCharges as $rev)
                            <tr class="hover:bg-slate-50/60 font-mono">
                                <td class="py-3 px-4 font-bold text-blue-600">{{ $rev->charge_number }}</td>
                                <td class="py-3 px-4 font-sans font-bold text-slate-900">{{ $rev->patient->name }}</td>
                                <td class="py-3 px-4 font-sans text-slate-700">{{ $rev->description }}</td>
                                <td class="py-3 px-4 text-rose-600 font-bold">KSh {{ number_format($rev->total_price, 2) }}</td>
                                <td class="py-3 px-4 font-sans">
                                    <span class="font-bold text-slate-800 block">By {{ $rev->reverser->name ?? 'Staff' }}</span>
                                    <p class="text-[11px] text-slate-500 italic">"{{ $rev->reversal_reason }}"</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 font-sans">No charge reversals recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
