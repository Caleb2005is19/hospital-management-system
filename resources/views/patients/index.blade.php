<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- Quick Access to Patient Records (EHR) -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center bg-indigo-50 border border-indigo-200 p-4 rounded-xl shadow-sm gap-3">
        <div class="flex items-center gap-3">
            <span class="text-2xl">📋</span>
            <div>
                <h3 class="text-sm font-bold text-indigo-950">Patient Longitudinal Medical Records</h3>
                <p class="text-xs text-indigo-700">Lookup previous doctor notes, prescriptions, triage vitals, and lab history.</p>
            </div>
        </div>
        <a href="{{ route('ehr.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow transition shrink-0">
            🔍 Open Medical Records Archive →
        </a>
    </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Patient Electronic Health Records</h1>
                <p class="text-sm text-gray-500">Search and view longitudinal medical histories across visits.</p>
            </div>
            <form method="GET" action="{{ route('patients.index') }}" class="flex w-full sm:w-auto gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, PAT ID..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm w-full sm:w-72 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shrink-0">Search</button>
            </form>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Patient No / Name</th>
                            <th class="px-6 py-3">Contact</th>
                            <th class="px-6 py-3">Gender / Age</th>
                            <th class="px-6 py-3">Total Visits</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($patients as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $p->first_name }} {{ $p->last_name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $p->patient_number ?? 'PAT-#' . $p->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $p->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ ucfirst($p->gender ?? 'N/A') }} / {{ $p->dob ?? $p->age ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs bg-blue-50 text-blue-700 rounded-full font-medium">{{ $p->encounters_count ?? $p->encounters->count() }} encounters</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('patients.history', $p->id) }}" class="inline-block px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold transition">
                                        View Medical Record →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">No matching patient records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $patients->links() }}</div>
    </div>
</x-app-layout>
