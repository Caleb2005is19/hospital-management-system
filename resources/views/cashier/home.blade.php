<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💰 Cashier Desk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Pending Payments</h3>

                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                            <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Doctor's Note</th>
                            <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $appoint)
                        <tr>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                <p class="font-bold">{{ $appoint->patient->name }}</p>
                                <p class="text-xs">{{ $appoint->patient->email }}</p>
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                {{ Str::limit($appoint->prescription, 40) }}
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                <a href="{{ url('create_bill', $appoint->id) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-1 px-3 rounded text-xs">
                                    💲 Generate Bill
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