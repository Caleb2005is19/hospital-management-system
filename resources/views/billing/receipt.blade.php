<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt - {{ $receipt->receipt_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 font-sans antialiased text-slate-900">

    <div class="max-w-md mx-auto mb-4 flex justify-between items-center no-print">
        <a href="javascript:history.back()" class="text-xs font-bold text-slate-600 hover:text-slate-900">← Back to Billing</a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow">
            🖨️ Print Receipt
        </button>
    </div>

    <!-- Official Sequential Thermal / POS Style Receipt -->
    <div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-200 text-xs">
        
        <div class="text-center border-b pb-4 mb-4">
            <h1 class="text-lg font-black tracking-tight">LIFECARE HOSPITAL</h1>
            <p class="text-[10px] text-slate-500">P.O. Box 00100, Nairobi • Tel: +254 700 000 000</p>
            <p class="text-[10px] text-slate-600 font-semibold mt-0.5">OFFICIAL PAYMENT RECEIPT</p>
            <div class="mt-2 inline-block px-3 py-1 bg-slate-900 text-white rounded-lg text-xs font-mono font-black">
                {{ $receipt->receipt_number }}
            </div>
        </div>

        <div class="space-y-1 mb-4 text-[11px]">
            <div class="flex justify-between"><span>Date/Time:</span><strong>{{ $receipt->created_at->format('d M Y, H:i:s') }}</strong></div>
            <div class="flex justify-between"><span>Patient:</span><strong class="text-slate-900">{{ $receipt->invoice->encounter->patient->name }}</strong></div>
            <div class="flex justify-between"><span>Patient Number:</span><span class="font-mono">{{ $receipt->invoice->encounter->patient->patient_number }}</span></div>
            <div class="flex justify-between"><span>Encounter:</span><span class="font-mono">{{ $receipt->invoice->encounter->encounter_number }}</span></div>
            <div class="flex justify-between"><span>Payment Method:</span><strong>{{ $receipt->payment->payment_method }}</strong></div>
            @if($receipt->payment->reference_number)
                <div class="flex justify-between"><span>Ref Code:</span><span class="font-mono font-bold">{{ $receipt->payment->reference_number }}</span></div>
            @endif
        </div>

        <table class="w-full border-t border-b border-slate-200 py-2 mb-4 text-[11px]">
            <thead>
                <tr class="text-slate-400 uppercase text-[9px]">
                    <th class="py-1 text-left">Charge Item</th>
                    <th class="py-1 text-center">Qty</th>
                    <th class="py-1 text-right">Total (KSh)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-mono">
                @foreach($receipt->invoice->encounter->charges as $charge)
                    @if($charge->status !== 'reversed')
                        <tr>
                            <td class="py-1.5 font-sans">{{ $charge->description }}</td>
                            <td class="py-1.5 text-center font-sans">{{ $charge->quantity }}</td>
                            <td class="py-1.5 text-right">{{ number_format($charge->total_price, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="space-y-1 text-right font-mono text-xs mb-4">
            <div class="flex justify-between"><span>Total Bill:</span><strong>KSh {{ number_format($receipt->invoice->total_amount, 2) }}</strong></div>
            <div class="flex justify-between"><span>Previous Balance:</span><span>KSh {{ number_format($receipt->previous_balance, 2) }}</span></div>
            <div class="flex justify-between font-black text-sm pt-1 border-t text-emerald-700">
                <span>Amount Tendered:</span>
                <span>KSh {{ number_format($receipt->amount_paid, 2) }}</span>
            </div>
            <div class="flex justify-between text-slate-600 font-bold">
                <span>Outstanding Balance:</span>
                <span>KSh {{ number_format($receipt->new_balance, 2) }}</span>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-400 border-t pt-3 space-y-0.5">
            <p class="font-semibold text-slate-600">Served by Cashier: {{ $receipt->payment->cashier->name ?? 'Accounts' }}</p>
            <p>Thank you for choosing LifeCare Hospital • Quick recovery!</p>
        </div>

    </div>

</body>
</html>
