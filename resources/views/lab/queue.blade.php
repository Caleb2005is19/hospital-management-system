<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    Laboratory Diagnostics Workbench
                </h2>
                <p class="text-sm text-slate-500 mt-1">Real-time specimen accessioning, clinical analysis & billing sync.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Auto-Billing Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('message'))
                <div class="p-4 rounded-xl bg-emerald-500 text-white shadow flex items-center justify-between text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                    <div class="text-xs font-semibold text-slate-400 uppercase">Awaiting Sample</div>
                    <div class="text-2xl font-black text-slate-800 mt-1">{{ $pendingOrders->whereIn('status', ['ordered', 'pending'])->count() }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                    <div class="text-xs font-semibold text-slate-400 uppercase">Specimen Logged</div>
                    <div class="text-2xl font-black text-slate-800 mt-1">{{ $pendingOrders->where('status', 'sample_collected')->count() }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                    <div class="text-xs font-semibold text-slate-400 uppercase">In Processing</div>
                    <div class="text-2xl font-black text-slate-800 mt-1">{{ $pendingOrders->where('status', 'processing')->count() }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                    <div class="text-xs font-semibold text-slate-400 uppercase">Completed</div>
                    <div class="text-2xl font-black text-slate-800 mt-1">{{ $completedOrders->count() }}</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Pending Diagnostics ({{ $pendingOrders->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th class="py-3 px-6">Order ID</th>
                                <th class="py-3 px-6">Patient</th>
                                <th class="py-3 px-6">Test Details</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingOrders as $order)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-mono font-bold text-indigo-700">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800">{{ $order->encounter->patient->first_name ?? $order->encounter->patient->name ?? 'Patient' }} {{ $order->encounter->patient->last_name ?? '' }}</div>
                                        <div class="text-xs text-slate-400">ENC #{{ $order->encounter_id }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-slate-800">{{ $order->labTest->name ?? $order->labTest->test_name ?? 'Diagnostic Test' }}</div>
                                        <div class="text-xs text-emerald-600 font-medium">KES {{ number_format($order->labTest->price ?? $order->labTest->cost ?? 500, 2) }} (Billed)</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if(in_array($order->status, ['ordered', 'pending']))
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Awaiting Sample</span>
                                        @elseif($order->status === 'sample_collected')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">Specimen Logged</span>
                                        @elseif($order->status === 'processing')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">Analyzing</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('lab.update', $order->id) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            @if(in_array($order->status, ['ordered', 'pending']))
                                                <input type="hidden" name="status" value="sample_collected">
                                                <button type="submit" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold shadow-sm">Sample Logged</button>
                                            @elseif($order->status === 'sample_collected')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow-sm">Run Test</button>
                                            @elseif($order->status === 'processing')
                                                <input type="hidden" name="status" value="completed">
                                                <input type="text" name="result" placeholder="Findings..." required class="px-2.5 py-1 border border-slate-300 rounded-lg text-xs w-40">
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm">Complete</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-sm">No pending lab orders in queue.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800">Verified Diagnostic History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th class="py-3 px-6">Order ID</th>
                                <th class="py-3 px-6">Patient</th>
                                <th class="py-3 px-6">Test Investigated</th>
                                <th class="py-3 px-6">Findings / Result</th>
                                <th class="py-3 px-6 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($completedOrders as $order)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3.5 px-6 font-bold text-slate-800">{{ $order->encounter->patient->first_name ?? $order->encounter->patient->name ?? 'Patient' }} {{ $order->encounter->patient->last_name ?? '' }}</td>
                                    <td class="py-3.5 px-6 text-slate-800">{{ $order->labTest->name ?? $order->labTest->test_name ?? 'Diagnostic Test' }}</td>
                                    <td class="py-3.5 px-6 font-mono text-xs text-slate-700 bg-slate-50 rounded px-2">{{ $order->result ?? 'Completed' }}</td>
                                    <td class="py-3.5 px-6 text-right">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Dispatched</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 text-sm">No recent completed test records.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
