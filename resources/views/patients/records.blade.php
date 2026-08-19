<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Patient Longitudinal Medical Records</h1>
                <p class="text-sm text-gray-500">Search by name, phone, national ID or patient number to inspect past visit charts.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition">← Back to Reception</a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('ehr.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Type patient name, phone number, national ID, or PAT-XXXX..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg shrink-0">Search</button>
                @if(request('search'))
                    <a href="{{ route('ehr.index') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg shrink-0">Clear</a>
                @endif
            </form>
        </div>

        <!-- Patients EHR Table -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Patient No / Name</th>
                            <th class="px-6 py-3">Phone / ID</th>
                            <th class="px-6 py-3">Gender / Age</th>
                            <th class="px-6 py-3">Total Visits</th>
                            <th class="px-6 py-3 text-right">Medical History</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($patients as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $p->first_name }} {{ $p->last_name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $p->patient_number ?? 'PAT-#' . $p->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div>{{ $p->phone ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">ID: {{ $p->national_id ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ ucfirst($p->gender ?? 'N/A') }} / {{ $p->dob ?? $p->age ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs bg-blue-50 text-blue-700 rounded-full font-medium">{{ $p->encounters_count ?? $p->encounters->count() }} visits</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('patients.history', $p->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold transition">
                                        <span>📋</span> View Clinical Chart →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">No patient records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $patients->links() }}</div>
    </div>
</x-app-layout>
