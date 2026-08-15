<x-app-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🏥</span> Hospital Operations Command Center
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time departmental metrics, patient lifecycle & queue throughput</p>
            </div>
            <span class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                System Online
            </span>
        </div>

        @if(session()->has('message'))
            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                ✓ {{ session()->get('message') }}
            </div>
        @endif

        <!-- 4 Metric Cards (Responsive 1 -> 2 -> 4 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Revenue</span>
                <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">
                    KES {{ number_format($totalRevenue ?? 0, 2) }}
                </h3>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500">Active Clinicians</span>
                <h3 class="text-xl font-black text-blue-600 dark:text-blue-400 mt-1">
                    {{ $activeClinicians ?? 1 }}
                </h3>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-500">Registered Patients</span>
                <h3 class="text-xl font-black text-purple-600 dark:text-purple-400 mt-1">
                    {{ $registeredPatients ?? 1 }}
                </h3>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Total Encounters</span>
                <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">
                    {{ $totalVisits ?? 0 }}
                </h3>
            </div>
        </div>

        <!-- Solo Admin Fast Jump Department Icons -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">⚡ Quick Department Jump</h2>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                <a href="{{ route('patients.index') }}" class="p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition border border-slate-200 dark:border-slate-700">
                    <span class="text-xl block">📋</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mt-1 block">Reception</span>
                </a>
                <a href="{{ route('triage.index') }}" class="p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition border border-slate-200 dark:border-slate-700">
                    <span class="text-xl block">🩺</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mt-1 block">Triage</span>
                </a>
                <a href="{{ route('doctor.queue') }}" class="p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition border border-slate-200 dark:border-slate-700">
                    <span class="text-xl block">👨‍⚕️</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mt-1 block">Doctor</span>
                </a>
                <a href="{{ route('lab.index') }}" class="p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition border border-slate-200 dark:border-slate-700">
                    <span class="text-xl block">🧪</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mt-1 block">Lab</span>
                </a>
                <a href="{{ route('pharmacy.index') }}" class="p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition border border-slate-200 dark:border-slate-700">
                    <span class="text-xl block">💊</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mt-1 block">Pharmacy</span>
                </a>
                <a href="{{ route('billing.index') }}" class="p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition border border-slate-200 dark:border-slate-700">
                    <span class="text-xl block">💳</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mt-1 block">Billing</span>
                </a>
            </div>
        </div>

        <!-- Live Patient Flow Tracker Table -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">📊 Live Patient Flow Tracker</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Active visits and live departmental routing</p>
                </div>
                <a href="{{ route('patients.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    + Register Patient
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Patient</th>
                            <th class="py-3 px-4">Encounter ID</th>
                            <th class="py-3 px-4">Current Stage</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono">
                        @forelse($encounters ?? [] as $enc)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="py-3 px-4 font-sans font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $enc->patient->name ?? 'Patient' }}
                                </td>
                                <td class="py-3 px-4 text-blue-600 dark:text-blue-400 font-bold">
                                    {{ $enc->encounter_number }}
                                </td>
                                <td class="py-3 px-4 font-sans">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ str_replace('_', ' ', $enc->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-sans text-right">
                                    @if($enc->status === 'waiting_triage')
                                        <a href="{{ route('triage.index') }}" class="text-blue-600 font-bold hover:underline">Go to Triage →</a>
                                    @elseif($enc->status === 'waiting_doctor')
                                        <a href="{{ route('doctor.queue') }}" class="text-blue-600 font-bold hover:underline">Go to Doctor →</a>
                                    @elseif($enc->status === 'waiting_lab')
                                        <a href="{{ route('lab.index') }}" class="text-purple-600 font-bold hover:underline">Go to Lab →</a>
                                    @elseif($enc->status === 'waiting_pharmacy')
                                        <a href="{{ route('pharmacy.index') }}" class="text-emerald-600 font-bold hover:underline">Go to Pharmacy →</a>
                                    @elseif($enc->status === 'waiting_billing')
                                        <a href="{{ route('billing.show', $enc->id) }}" class="text-amber-600 font-bold hover:underline">Go to Billing →</a>
                                    @else
                                        <span class="text-slate-400">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 font-sans">
                                    No active patient encounters currently in flow.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
