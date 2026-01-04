<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-8 border-green-500">

                <h1 class="text-2xl font-bold text-center mb-6">🏥 Hospital Invoice</h1>

                <div class="flex justify-between mb-6 text-sm text-gray-600">
                    <div>
                        <p>Billed To:</p>
                        <p class="font-bold text-black text-lg">{{ $appointment->patient->name }}</p>
                        <p>ID: #{{ $appointment->patient->id }}</p>
                    </div>
                    <div class="text-right">
                        <p>Date: {{ date('Y-m-d') }}</p>
                        <p>Invoice #: {{ rand(1000,9999) }}</p>
                    </div>
                </div>

                <table class="w-full mb-8">
                    <tr class="border-b-2 border-black">
                        <th class="text-left py-2">Description</th>
                        <th class="text-right py-2">Amount (KES)</th>
                    </tr>
                    <tr>
                        <td class="py-2">👨‍⚕️ Doctor Consultation</td>
                        <td class="text-right font-mono">{{ number_format($doctor_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2">💊 Pharmacy Charges</td>
                        <td class="text-right font-mono">{{ number_format($medicine_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2">🛏️ Ward/Room Charges</td>
                        <td class="text-right font-mono">{{ number_format($room_cost, 2) }}</td>
                    </tr>
                    <tr class="border-t-2 border-black font-bold text-xl">
                        <td class="py-4">TOTAL DUE</td>
                        <td class="text-right py-4">{{ number_format($total, 2) }}</td>
                    </tr>
                </table>

                <form action="{{ url('store_bill', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $appointment->patient->id }}">
                    <input type="hidden" name="doctor_charge" value="{{ $doctor_cost }}">
                    <input type="hidden" name="medicine_charge" value="{{ $medicine_cost }}">
                    <input type="hidden" name="room_charge" value="{{ $room_cost }}">
                    <input type="hidden" name="total_amount" value="{{ $total }}">

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-800 text-white font-bold py-4 rounded text-lg shadow-lg">
                        ✅ Mark as Paid & Print Receipt
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>