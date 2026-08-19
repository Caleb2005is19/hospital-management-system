<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - ENC #{{ $encounter->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 py-8 px-4 font-sans">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow border border-slate-200">
        <!-- Print Controls -->
        <div class="no-print flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <button onclick="window.history.back()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-lg transition">
                &larr; Back
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow transition">
                Print Report
            </button>
        </div>

        <!-- Header / Letterhead -->
        <div class="border-b-2 border-slate-800 pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">HOSPITAL DIAGNOSTICS LAB</h1>
                    <p class="text-xs text-slate-500 font-medium">Department of Clinical Pathology & Laboratory Medicine</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full uppercase">Official Report</span>
                    <p class="text-xs text-slate-400 mt-1">Date: {{ date('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Patient Demographics & Encounter Info -->
        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg text-xs mb-6 border border-slate-200">
            <div>
                <p><span class="font-bold text-slate-500 uppercase">Patient Name:</span> <strong class="text-slate-900">{{ $encounter->patient->first_name ?? $encounter->patient->name ?? 'Patient' }} {{ $encounter->patient->last_name ?? '' }}</strong></p>
                <p class="mt-1"><span class="font-bold text-slate-500 uppercase">Patient ID:</span> #PT-{{ str_pad($encounter->patient_id ?? 1, 4, '0', STR_PAD_LEFT) }}</p>
                <p class="mt-1"><span class="font-bold text-slate-500 uppercase">Gender / Age:</span> {{ $encounter->patient->gender ?? 'N/A' }} / {{ $encounter->patient->age ?? 'N/A' }}</p>
            </div>
            <div>
                <p><span class="font-bold text-slate-500 uppercase">Encounter ID:</span> #ENC-{{ str_pad($encounter->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="mt-1"><span class="font-bold text-slate-500 uppercase">Ordering Clinician:</span> Dr. {{ $labOrders->first()->doctor->name ?? 'Attending Clinician' }}</p>
                <p class="mt-1"><span class="font-bold text-slate-500 uppercase">Date Sampled:</span> {{ $encounter->created_at ? $encounter->created_at->format('M d, Y') : date('M d, Y') }}</p>
            </div>
        </div>

        <!-- Test Results Table -->
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3">Diagnostic Panel Results</h2>
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b-2 border-slate-300 text-slate-600">
                        <th class="py-2.5 px-3">Test Investigation</th>
                        <th class="py-2.5 px-3">Findings / Value</th>
                        <th class="py-2.5 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($labOrders as $order)
                        <tr>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-800">{{ $order->labTest->name ?? $order->labTest->test_name ?? 'Diagnostic Test' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-900">
                                {{ $order->result ?? 'Pending / In Analysis' }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="uppercase text-[10px] font-bold px-2 py-0.5 rounded {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400">No laboratory tests recorded for this encounter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signatures & Verification Footer -->
        <div class="border-t border-slate-200 pt-6 mt-12 grid grid-cols-2 gap-8 text-xs">
            <div>
                <p class="font-bold text-slate-600 uppercase">Laboratory Technologist</p>
                <div class="h-12 border-b border-dashed border-slate-300"></div>
                <p class="text-[11px] text-slate-400 mt-1">Authorized Signature & Stamp</p>
            </div>
            <div>
                <p class="font-bold text-slate-600 uppercase">Consulting Clinician</p>
                <div class="h-12 border-b border-dashed border-slate-300"></div>
                <p class="text-[11px] text-slate-400 mt-1">Verification Signature</p>
            </div>
        </div>
    </div>
</body>
</html>
