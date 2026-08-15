<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    <span>👨‍⚕️</span> Add Hospital Employee / Clinician
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Register staff members, assign clinical roles and configure access credentials</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Success/Error Feedback -->
        @if(session()->has('message'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold">
                ✓ {{ session()->get('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <!-- Add Staff Form -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <form action="{{ route('employees.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Full Name *
                        </label>
                        <input type="text" name="name" placeholder="Dr. John Doe / Nurse Jane" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Email Address (Login Username) *
                        </label>
                        <input type="email" name="email" placeholder="staff@hospital.co.ke" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Phone Number *
                        </label>
                        <input type="text" name="phone" placeholder="+254 700 000 000" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            System Role (Usertype) *
                        </label>
                        <select name="usertype" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="doctor">Medical Doctor / Clinician</option>
                            <option value="nurse">Nurse / Triage Officer</option>
                            <option value="pharmacist">Pharmacist</option>
                            <option value="lab_tech">Laboratory Technologist</option>
                            <option value="cashier">Cashier / Billing Clerk</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="admin">Hospital Administrator</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Clinical Department (Optional)
                        </label>
                        <input type="text" name="department" placeholder="OPD, Maternity, Pharmacy, Lab, ICU"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Account Password *
                        </label>
                        <input type="password" name="password" placeholder="••••••••" required minlength="6"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm transition">
                        Save & Activate Employee 👤✓
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
