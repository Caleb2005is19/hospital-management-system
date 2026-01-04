<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <h2 class="text-2xl font-bold mb-6 text-gray-800">👤 Register New Hospital Staff</h2>

                @if(session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session()->get('message') }}
                </div>
                @endif

                <form action="{{ url('upload_doctor') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                            <input type="text" name="name" class="w-full border rounded p-2" required placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                            <input type="number" name="phone" class="w-full border rounded p-2" required placeholder="0712345678">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full border rounded p-2" required placeholder="email@hospital.com">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Employee Role</label>
                        <select name="usertype" class="w-full border rounded p-2 bg-gray-50" required>
                            <option value="">-- Select Role --</option>
                            <option value="doctor">👨‍⚕️ Doctor</option>
                            <option value="nurse">👩‍⚕️ Nurse</option>
                            <option value="pharmacist">💊 Pharmacist</option>
                            <option value="receptionist">📂 Receptionist / Records</option>
                            <option value="cashier">💰 Cashier</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Department / Ward</label>
                            <select name="department" class="w-full border rounded p-2">
                                <option value="">-- None --</option>
                                <option value="General Ward">General Ward</option>
                                <option value="ICU">ICU</option>
                                <option value="Maternity">Maternity</option>
                                <option value="Outpatient">Outpatient</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Required for Nurses & Doctors</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Room Number (Doctors)</label>
                            <input type="text" name="room" class="w-full border rounded p-2" placeholder="e.g. Room 101">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Assign Password</label>
                        <input type="password" name="password" class="w-full border rounded p-2" required placeholder="********">
                    </div>

                    <button type="submit" class="w-full bg-blue-800 text-white font-bold py-3 rounded hover:bg-blue-900 shadow-lg">
                        💾 Create Employee Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>