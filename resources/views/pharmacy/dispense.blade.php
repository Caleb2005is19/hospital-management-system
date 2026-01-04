<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-blue-700">💊 Dispense Medicine</h3>

                <div class="bg-blue-50 p-4 rounded mb-6 border-l-4 border-blue-500">
                    <p class="text-sm font-bold text-gray-500">Doctor's Written Order:</p>
                    <p class="text-xl font-mono text-gray-900 mt-1">{{ $appointment->prescription }}</p>
                    <p class="text-xs text-gray-400 mt-2">Patient: {{ $appointment->patient->name }}</p>
                </div>

                @if(session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session()->get('error') }}
                </div>
                @endif

                <form action="{{ url('store_dispense', $appointment->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Select Drug from Inventory</label>
                        <select name="drug_id" class="w-full border rounded p-2">
                            @foreach($drugs as $drug)
                            <option value="{{ $drug->id }}">
                                {{ $drug->name }} (Stock: {{ $drug->quantity }} | ${{ $drug->price }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Quantity to Give</label>
                        <input type="number" name="quantity" class="w-full border rounded p-2" placeholder="e.g. 2" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                        📉 Dispense & Reduce Stock
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-gray-700">🧾 Dispensed Items</h3>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="pb-2">Drug</th>
                            <th class="pb-2">Qty</th>
                            <th class="pb-2">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->drug->name }}</td>
                            <td class="py-2">{{ $item->quantity }}</td>
                            <td class="py-2 font-bold">${{ $item->cost }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <p class="text-xl font-bold">Total: ${{ $history->sum('cost') }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>