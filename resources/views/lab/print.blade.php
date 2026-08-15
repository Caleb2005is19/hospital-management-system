<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - {{ $encounter->encounter_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6 text-gray-900 font-sans antialiased">

    <!-- Action Toolbar (Hidden on Print) -->
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="javascript:history.back()" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
            ← Back to Worklist
        </a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-xl shadow transition flex items-center gap-2 text-sm">
            <span>🖨️</span> Print Official Diagnostic Report
        </button>
    </div>

    <!-- Printable Official Medical Report Card -->
    <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-sm border border-gray-200 printable-area">
        
        <!-- Header / Hospital Branding -->
        <div class="flex justify-between items-start border-b-2 border-slate-900 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900">LIFECARE HOSPITAL & DIAGNOSTICS</h1>
                <p class="text-xs text-gray-500 mt-1">Department of Pathology & Clinical Laboratory Services</p>
                <p class="text-xs text-gray-500">Nairobi, Kenya • Tel: +254 700 000 000 • Email: lab@lifecare.ke</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-900 font-black text-xs uppercase tracking-wider rounded">
                    Official Diagnostic Report
                </span>
                <p class="text-xs font-mono text-gray-500 mt-2">Accession: {{ $encounter->encounter_number }}</p>
                <p class="text-xs text-gray-500">Date: {{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <!-- Patient Demographics & Order Metadata Matrix -->
        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs mb-6">
            <div>
                <span class="text-gray-500 uppercase font-bold text-[10px] block">Patient Details</span>
                <strong class="text-sm text-slate-900 block font-black">{{ $encounter->patient->name }}</strong>
                <p class="text-gray-600">ID / PT No: {{ $encounter->patient->patient_number }}</p>
                <p class="text-gray-600">Age / Gender: {{ $encounter->patient->dob ? \Carbon\Carbon::parse($encounter->patient->dob)->age . ' yrs' : 'N/A' }} | {{ $encounter->patient->gender }}</p>
            </div>
            <div>
                <span class="text-gray-500 uppercase font-bold text-[10px] block">Clinical Order Information</span>
                <p class="text-gray-700"><strong>Attending Doctor:</strong> Dr. {{ $encounter->doctor->name ?? 'Attending Clinician' }}</p>
                <p class="text-gray-700"><strong>Department:</strong> {{ $encounter->type }} (Outpatient)</p>
                <p class="text-gray-700"><strong>Working Diagnosis:</strong> {{ $encounter->consultation?->diagnosis ?? 'Clinical Workup' }}</p>
            </div>
        </div>

        <!-- Diagnostic Test Results Table -->
        <div class="mb-8">
            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 mb-2">Investigation Findings</h3>
            
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-100 font-bold uppercase text-slate-700">
                    <tr>
                        <th class="py-2.5 px-3 text-left">Test Name</th>
                        <th class="py-2.5 px-3 text-left">Sample Type</th>
                        <th class="py-2.5 px-3 text-left">Quantitative / Qualitative Result</th>
                        <th class="py-2.5 px-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($encounter->labOrders as $order)
                        <tr>
                            <td class="py-3 px-3 font-bold text-slate-900">
                                {{ $order->labTest->name }}
                            </td>
                            <td class="py-3 px-3 text-gray-600">
                                {{ $order->labTest->sample_type ?? 'Whole Blood' }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="font-black text-slate-900 text-sm block">
                                    {{ $order->result ?? 'Pending / In Progress' }}
                                </span>
                                @if($order->remarks)
                                    <span class="text-[10px] text-gray-500 italic block mt-0.5">Note: {{ $order->remarks }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <span class="font-bold {{ $order->status === 'completed' ? 'text-emerald-700' : 'text-amber-600' }}">
                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-400">No laboratory tests requested for this encounter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Verification Sign-off & Footer -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-200 mt-12 text-xs">
            <div>
                <p class="text-gray-400 uppercase text-[10px] font-bold">Medical Lab Technologist:</p>
                <div class="h-10 mt-2 border-b border-gray-400 border-dashed"></div>
                <p class="font-bold text-slate-800 mt-1">{{ $encounter->labOrders->first()?->technician->name ?? 'Chief Technologist' }}</p>
                <p class="text-[10px] text-gray-500">Verified & Released electronically</p>
            </div>
            <div class="text-right">
                <p class="text-gray-400 uppercase text-[10px] font-bold">Pathologist / Medical Reviewer:</p>
                <div class="h-10 mt-2 border-b border-gray-400 border-dashed"></div>
                <p class="font-bold text-slate-800 mt-1">Dr. Kamau, Pathologist</p>
                <p class="text-[10px] text-gray-500">Official Seal / Signature</p>
            </div>
        </div>

        <div class="mt-8 text-center text-[10px] text-gray-400 border-t pt-3">
            This document is a confidential medical laboratory report. Generated by LifeCare Hospital Management System.
        </div>

    </div>

</body>
</html>
