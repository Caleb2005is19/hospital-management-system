<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Prescribe for {{ $appointment->patient->name }}</h2>

                <form action="{{ url('update_prescription', $appointment->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosis / Doctor's Notes</label>
                        <textarea name="reason" class="w-full border rounded p-2" rows="3">{{ $appointment->reason }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">💊 Prescription (Medicine)</label>
                        <h3 class="font-bold mb-2">💊 Medication Chart</h3>

                        <table class="w-full text-left border mb-4" id="medTable">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border">Drug Name</th>
                                    <th class="p-2 border">Dosage</th>
                                    <th class="p-2 border">Frequency</th>
                                    <th class="p-2 border">Duration</th>
                                    <th class="p-2 border">Qty</th>
                                    <th class="p-2 border">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-1 border"><input type="text" name="inputs[0][drug_name]" class="w-full text-sm" required></td>
                                    <td class="p-1 border"><input type="text" name="inputs[0][dosage]" class="w-full text-sm" placeholder="500mg"></td>
                                    <td class="p-1 border"><input type="text" name="inputs[0][frequency]" class="w-full text-sm" placeholder="3x Daily"></td>
                                    <td class="p-1 border"><input type="text" name="inputs[0][duration]" class="w-full text-sm" placeholder="5 Days"></td>
                                    <td class="p-1 border"><input type="number" name="inputs[0][quantity]" class="w-full text-sm" placeholder="15"></td>
                                    <td class="p-1 border"><button type="button" class="text-red-500 remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" id="addRow" class="bg-gray-200 px-3 py-1 rounded text-sm mb-4">+ Add Medicine</button>

                        <script>
                            let i = 0;
                            document.getElementById('addRow').addEventListener('click', function() {
                                ++i;
                                let table = document.getElementById('medTable').getElementsByTagName('tbody')[0];
                                let row = table.insertRow(table.rows.length);
                                row.innerHTML = `
            <td class="p-1 border"><input type="text" name="inputs[${i}][drug_name]" class="w-full text-sm"></td>
            <td class="p-1 border"><input type="text" name="inputs[${i}][dosage]" class="w-full text-sm"></td>
            <td class="p-1 border"><input type="text" name="inputs[${i}][frequency]" class="w-full text-sm"></td>
            <td class="p-1 border"><input type="text" name="inputs[${i}][duration]" class="w-full text-sm"></td>
            <td class="p-1 border"><input type="number" name="inputs[${i}][quantity]" class="w-full text-sm"></td>
            <td class="p-1 border"><button type="button" class="text-red-500 remove-row" onclick="this.closest('tr').remove()">Remove</button></td>
        `;
                            });
                        </script>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">🏥 Admission Decision</label>
                        <select name="target_ward" class="w-full border rounded p-2">
                            <option value="">-- No Admission (Send Home) --</option>
                            <option value="General Ward">Admit to General Ward</option>
                            <option value="ICU">Admit to ICU</option>
                            <option value="Maternity">Admit to Maternity</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
                        ✅ Complete Treatment
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>