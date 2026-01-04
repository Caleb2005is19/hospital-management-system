<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💊 Pharmacy & Inventory
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-bold mb-4 text-green-700">➕ Add Medicine to Stock</h3>

                <form action="{{ url('upload_drug') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Drug Name</label>
                        <input type="text" name="name" class="w-full border rounded p-2" placeholder="e.g. Amoxicillin 500mg" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Quantity (Units)</label>
                            <input type="number" name="quantity" class="w-full border rounded p-2" placeholder="100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Price (per unit)</label>
                            <input type="number" step="0.01" name="price" class="w-full border rounded p-2" placeholder="10.50" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                        Save to Inventory
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-700">📦 Current Inventory</h3>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Stock</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drugs as $drug)
                        <tr class="border-b">
                            <td class="px-3 py-2 text-sm font-bold">{{ $drug->name }}</td>
                            <td class="px-3 py-2 text-sm">
                                <span class="px-2 py-1 rounded-full text-white text-xs {{ $drug->quantity < 10 ? 'bg-red-500' : 'bg-green-500' }}">
                                    {{ $drug->quantity }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-600">${{ $drug->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-blue-700">📄 Prescriptions to Dispense</h3>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Doctor's Note</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescriptions as $appoint)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 font-bold">{{ $appoint->patient->name }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ Str::limit($appoint->prescription, 50) }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ url('dispense_view', $appoint->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                                    💊 Dispense
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>