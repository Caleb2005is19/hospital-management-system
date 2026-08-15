<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-amber-100 text-amber-700 rounded-xl text-lg">🧾</span>
                    Invoice Terminal — {{ $encounter->patient->name }}
                </h2>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Encounter: {{ $encounter->encounter_number }} • Invoice: {{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('billing.index') }}" class="text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl transition">
                    ← Cashier Desk
                </a>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT: Itemized Charges & Receipts (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Immutable Charges Table -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-base text-slate-900">Itemized Clinical Charges (Audit Trail)</h3>
                        <span class="px-2.5 py-1 rounded-full text-xs font-black uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ str_replace('_', ' ', $invoice->status) }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50 font-bold uppercase text-slate-500 text-[10px]">
                                <tr>
                                    <th class="py-2.5 px-3 text-left">Charge Ref</th>
                                    <th class="py-2.5 px-3 text-left">Service / Item Description</th>
                                    <th class="py-2.5 px-3 text-center">Qty</th>
                                    <th class="py-2.5 px-3 text-right">Unit Price</th>
                                    <th class="py-2.5 px-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-mono">
                                @forelse($encounter->charges as $charge)
                                    <tr class="{{ $charge->status === 'reversed' ? 'line-through text-slate-400 bg-rose-50/30' : '' }}">
                                        <td class="py-3 px-3 text-[10px] text-blue-600 font-bold">{{ $charge->charge_number }}</td>
                                        <td class="py-3 px-3 font-sans font-semibold text-slate-900">{{ $charge->description }}</td>
                                        <td class="py-3 px-3 text-center font-sans">{{ $charge->quantity }}</td>
                                        <td class="py-3 px-3 text-right">KSh {{ number_format($charge->unit_price, 2) }}</td>
                                        <td class="py-3 px-3 text-right font-bold text-slate-900">KSh {{ number_format($charge->total_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-slate-400 font-sans">No charges attached to encounter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="border-t-2 border-slate-900 font-mono text-xs">
                                <tr>
                                    <td colspan="4" class="py-3 px-3 text-right font-bold uppercase font-sans">Total Billed:</td>
                                    <td class="py-3 px-3 text-right font-black text-sm text-slate-900">KSh {{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="py-2 px-3 text-right font-bold uppercase font-sans text-emerald-700">Total Paid:</td>
                                    <td class="py-2 px-3 text-right font-black text-emerald-700">KSh {{ number_format($invoice->amount_paid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="py-2 px-3 text-right font-bold uppercase font-sans text-rose-600">Balance Due:</td>
                                    <td class="py-2 px-3 text-right font-black text-rose-600">
                                        KSh {{ number_format(max(0, $invoice->total_amount - $invoice->amount_paid), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- 2. Sequential Receipts Issued -->
                @if($invoice->payments->count() > 0)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-3">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700">Official Sequential Receipts Issued</h4>
                        <div class="divide-y divide-slate-100 text-xs">
                            @foreach($invoice->payments as $pmt)
                                <div class="py-3 flex justify-between items-center">
                                    <div>
                                        <strong class="text-slate-900 text-sm font-black block">KSh {{ number_format($pmt->amount, 2) }} via {{ $pmt->payment_method }}</strong>
                                        <span class="text-[10px] text-slate-500">Ref: {{ $pmt->reference_number }} • Cashier: {{ $pmt->cashier->name ?? 'Staff' }}</span>
                                    </div>
                                    <div class="text-right">
                                        @if($pmt->receipt)
                                            <a href="{{ route('billing.receipt.print', $pmt->receipt->id) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl border border-blue-200 transition">
                                                <span>🖨️</span> {{ $pmt->receipt->receipt_number }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT: Multi-Tender Settlement Terminal (1 Col) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                <h3 class="font-bold text-base text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <span>💵</span> Settle Bill (Multi-Tender)
                </h3>

                @if(!$activeSession)
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-center space-y-2">
                        <span class="text-2xl">🔒</span>
                        <h4 class="font-bold text-rose-900 text-xs">Cashier Drawer Closed</h4>
                        <p class="text-[10px] text-rose-700">You must open your shift session on the Cashier Desk to accept payments.</p>
                        <a href="{{ route('billing.index') }}" class="inline-block bg-rose-600 text-white font-bold text-xs px-3 py-1.5 rounded-lg">
                            Open Shift Now
                        </a>
                    </div>
                @elseif($invoice->status !== 'paid')
                    <form method="POST" action="{{ route('billing.payment', $invoice->id) }}" class="space-y-3 text-xs">
                        @csrf
                        
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Payment Tender *</label>
                            <select name="payment_method" required class="w-full border-slate-300 rounded-xl p-2.5 font-bold text-xs bg-slate-50">
                                <option value="M-PESA">M-PESA (Till / Paybill)</option>
                                <option value="Cash">Cash Currency</option>
                                <option value="Card">Credit / Debit Card</option>
                                <option value="Insurance">Insurance / Corporate Scheme</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Amount to Tender (KSh) *</label>
                            <input type="number" step="0.5" name="amount" value="{{ max(0, $invoice->total_amount - $invoice->amount_paid) }}" required
                                class="w-full border-slate-300 rounded-xl p-2.5 font-black text-base text-slate-900 bg-slate-50">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">M-Pesa Ref / Transaction Code</label>
                            <input type="text" name="reference_number" placeholder="e.g. QKH82910XZ"
                                class="w-full border-slate-300 rounded-xl p-2.5 text-xs font-mono uppercase bg-slate-50">
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl text-xs transition shadow-sm hover:shadow">
                            Receive Payment & Issue Sequential Receipt 💰
                        </button>
                    </form>
                @else
                    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-xl text-center space-y-2">
                        <span class="text-3xl">✅</span>
                        <h4 class="font-black text-emerald-900 text-sm">Account Settled in Full</h4>
                        <p class="text-[11px] text-emerald-700">All services have been paid for. Patient is cleared for discharge.</p>
                        @if($invoice->payments->last()?->receipt)
                            <a href="{{ route('billing.receipt.print', $invoice->payments->last()->receipt->id) }}" target="_blank"
                               class="inline-block mt-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-sm">
                                Print Receipt ({{ $invoice->payments->last()->receipt->receipt_number }}) 🖨️
                            </a>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
