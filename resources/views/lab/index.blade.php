<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-purple-100 text-purple-700 rounded-xl text-lg">🧪</span>
                    Laboratory Diagnostics & LIS Worklist
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Real-time specimen tracking, diagnostic results entry & validation</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Analyzer Sync Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Flash Messages -->
        @if(session('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <p class="text-xs font-semibold text-emerald-800">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- LIS Quick KPI Analytics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Awaiting Sample</span>
                    <h4 class="text-2xl font-black text-amber-600 mt-1">
                        {{ $orders->where('status', 'ordered')->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                    🩸
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">In Processing</span>
                    <h4 class="text-2xl font-black text-indigo-600 mt-1">
                        {{ $orders->whereIn('status', ['sample_collected', 'processing'])->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-base">
                    🔬
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Verified & Ready</span>
                    <h4 class="text-2xl font-black text-emerald-600 mt-1">
                        {{ $orders->where('status', 'completed')->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">
                    ✅
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Worklist</span>
                    <h4 class="text-2xl font-black text-slate-900 mt-1">
                        {{ $orders->count() }}
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-base">
                    📊
                </div>
            </div>
        </div>

        <!-- Main Diagnostic Worklist Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                <div>
                    <h3 class="font-bold text-base text-slate-900">Active Test Requisitions</h3>
                    <p class="text-xs text-slate-400">Process specimen accessioning and record quantified findings</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-medium">Auto-refresh ready</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/75 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 text-left">Accession / Patient</th>
                            <th class="py-3.5 px-4 text-left">Test & Specimen</th>
                            <th class="py-3.5 px-4 text-left">Ordering Clinician</th>
                            <th class="py-3.5 px-4 text-left">Lifecycle Stage</th>
                            <th class="py-3.5 px-4 text-left min-w-[320px]">Diagnostics & Result Entry</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/60 transition-colors {{ $order->status === 'completed' ? 'bg-slate-50/30' : '' }}">
                                
                                <!-- Patient & Accession ID -->
                                <td class="py-4 px-4 align-top">
                                    <div class="font-black text-slate-900 text-sm">
                                        {{ $order->encounter->patient->name }}
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono text-[11px] font-semibold">
                                            {{ $order->encounter->patient->patient_number }}
                                        </span>
                                        <span class="text-[11px] text-blue-600 font-bold font-mono">
                                            {{ $order->encounter->encounter_number }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mt-1">
                                        Req: {{ $order->created_at->format('d M, H:i') }}
                                    </span>
                                </td>

                                <!-- Test & Specimen Matrix -->
                                <td class="py-4 px-4 align-top">
                                    <span class="font-bold text-slate-900 block text-sm">
                                        {{ $order->labTest->name }}
                                    </span>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold
                                            {{ str_contains(strtolower($order->labTest->sample_type ?? ''), 'blood') ? 'bg-red-50 text-red-700 border border-red-200' :
                                              (str_contains(strtolower($order->labTest->sample_type ?? ''), 'urine') ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                              (str_contains(strtolower($order->labTest->sample_type ?? ''), 'stool') ? 'bg-yellow-50 text-yellow-800 border border-yellow-200' : 'bg-purple-50 text-purple-700 border border-purple-200')) }}">
                                            🧪 {{ $order->labTest->sample_type ?? 'Specimen' }}
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-500 font-mono">
                                            KSh {{ number_format($order->labTest->price, 2) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Ordering Doctor -->
                                <td class="py-4 px-4 align-top text-xs">
                                    <div class="font-semibold text-slate-700">Dr. {{ $order->doctor->name ?? 'Attending MD' }}</div>
                                    <span class="text-[10px] text-slate-400 block">Outpatient Dept</span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 align-top">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-extrabold rounded-full tracking-wide uppercase
                                        {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 
                                          ($order->status === 'processing' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 
                                          ($order->status === 'sample_collected' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-200')) }}">
                                        @if($order->status === 'completed')
                                            ✓ Verified
                                        @elseif($order->status === 'processing')
                                            ⚙ In Analyzer
                                        @elseif($order->status === 'sample_collected')
                                            🩸 Accessioned
                                        @else
                                            ⏳ Order Placed
                                        @endif
                                    </span>
                                </td>

                                <!-- Findings / Results Form & Actions -->
                                <td class="py-4 px-4 align-top">
                                    @if($order->status !== 'completed')
                                        <form method="POST" action="{{ route('lab.result', $order->id) }}" class="space-y-2.5">
                                            @csrf
                                            <div>
                                                <input type="text" name="result" value="{{ old('result', $order->result) }}" required
                                                    placeholder="Enter numerical/pathological findings (e.g. Parasites seen ++, Hb: 13.5 g/dL)..." 
                                                    class="w-full text-xs border-slate-300 rounded-xl px-3 py-2 focus:ring-purple-500 focus:border-purple-500 bg-slate-50/50">
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <input type="text" name="remarks" value="{{ old('remarks', $order->remarks) }}"
                                                    placeholder="Technician Notes (optional)" 
                                                    class="w-full text-xs border-slate-300 rounded-xl px-3 py-1.5 focus:ring-purple-500 focus:border-purple-500 bg-slate-50/50">

                                                <select name="status" class="text-xs font-semibold border-slate-300 rounded-xl px-2.5 py-1.5 bg-white">
                                                    <option value="sample_collected" {{ $order->status == 'sample_collected' ? 'selected' : '' }}>Accession</option>
                                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Analyzing</option>
                                                    <option value="completed">Complete & Sign</option>
                                                </select>

                                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm hover:shadow shrink-0">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <!-- Verified Readout Badge with Print Action -->
                                        <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-200/80 space-y-2">
                                            <div class="flex justify-between items-center text-[10px] text-emerald-800 font-bold uppercase tracking-wider">
                                                <span>Quantitative Finding:</span>
                                                <span class="text-emerald-600 font-mono">Validated</span>
                                            </div>
                                            <div class="font-bold text-slate-900 text-xs">
                                                {{ $order->result }}
                                            </div>
                                            @if($order->remarks)
                                                <p class="text-[11px] text-slate-600 italic">"{{ $order->remarks }}"</p>
                                            @endif
                                            <div class="text-[10px] text-slate-500 pt-1.5 border-t border-emerald-100 flex justify-between items-center">
                                                <span>Tech: {{ $order->technician->name ?? 'Lab Staff' }} ({{ $order->updated_at->format('H:i') }})</span>
                                                <a href="{{ route('lab.print', $order->encounter_id) }}" target="_blank"
                                                   class="inline-flex items-center gap-1 bg-white hover:bg-emerald-100 text-emerald-700 border border-emerald-300 font-bold text-[11px] px-2.5 py-1 rounded-lg transition shadow-xs">
                                                    <span>🖨️</span> Print Slip
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <div class="text-3xl mb-2">🧪</div>
                                    <p class="font-semibold text-slate-600 text-sm">Laboratory Worklist is Clear</p>
                                    <p class="text-xs text-slate-400 mt-0.5">No pending test requisitions from Doctor consultations.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
