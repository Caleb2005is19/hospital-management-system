<!-- Mobile Drawer Backdrop -->
<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    x-transition.opacity
    class="fixed inset-0 z-[9998] bg-black/60 lg:hidden"
></div>

<!-- Sidebar -->
<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-[9999]
           w-64 max-w-[85vw]
           bg-slate-900 text-slate-300
           flex flex-col
           border-r border-slate-800
           shadow-2xl
           overflow-hidden
           transition-transform duration-200 ease-in-out
           lg:static lg:z-auto lg:shadow-none lg:shrink-0"
>

    <!-- Brand -->
    <div class="h-16 min-h-16 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-950">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
            <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white text-lg shrink-0">
                🏥
            </div>

            <div class="min-w-0">
                <div class="font-bold text-sm text-white leading-tight truncate">
                    LifeCare HMIS
                </div>
                <div class="text-[9px] text-blue-400 font-bold uppercase tracking-wider">
                    Hospital Platform
                </div>
            </div>
        </a>

        <!-- Mobile Close -->
        <button
            type="button"
            @click="sidebarOpen = false"
            class="lg:hidden w-8 h-8 flex items-center justify-center
                   rounded-lg bg-slate-800 text-slate-300
                   hover:bg-slate-700 hover:text-white shrink-0"
        >
            ✕
        </button>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 min-h-0 overflow-y-auto px-3 py-4 space-y-1 text-sm font-semibold">

        <!-- CORE -->
        <div class="px-3 py-2 text-[10px] font-black uppercase tracking-wider text-slate-500">
            Core Portal
        </div>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('dashboard') || request()->is('home')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>📊</span>
            <span>Dashboard</span>
        </a>


        <!-- CLINICAL -->
        <div class="pt-4 px-3 py-2 text-[10px] font-black uppercase tracking-wider text-slate-500">
            Clinical Workflow
        </div>

        <a href="{{ route('patients.index') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('patients.*')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>📇</span>
            <span>Reception & Patients</span>
        </a>

        <a href="{{ route('triage.index') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('triage.*')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>🩺</span>
            <span>Triage Desk</span>
        </a>

        <a href="{{ route('doctor.queue') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('doctor.*')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>👨‍⚕️</span>
            <span>Doctor Consultation</span>
        </a>

        <a href="{{ route('lab.index') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('lab.*')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>🧪</span>
            <span>Laboratory</span>
        </a>

        <a href="{{ route('pharmacy.index') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('pharmacy.*')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>💊</span>
            <span>Pharmacy & Dispensary</span>
        </a>


        <!-- FINANCE -->
        <div class="pt-4 px-3 py-2 text-[10px] font-black uppercase tracking-wider text-slate-500">
            Finance & Admin
        </div>

        <a href="{{ route('billing.index') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('billing.*')
                ? 'bg-blue-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-white' }}">
            <span>💳</span>
            <span>Billing & Cashier</span>
        </a>

        <a href="{{ route('revenue.index') }}"
           class="flex items-center gap-3 px-3 py-3 rounded-xl transition
           {{ request()->routeIs('revenue.*')
                ? 'bg-indigo-600 text-white font-bold'
                : 'hover:bg-slate-800 hover:text-indigo-300' }}">
            <span>🛡️</span>
            <span>Revenue Control Center</span>
        </a>

        @if(Route::has('employees.create'))
            <a href="{{ route('employees.create') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl transition
               {{ request()->routeIs('employees.*')
                    ? 'bg-blue-600 text-white font-bold'
                    : 'hover:bg-slate-800 hover:text-white' }}">
                <span>👥</span>
                <span>Staff Management</span>
            </a>
        @endif

    </nav>


    <!-- User Profile -->
    <div class="p-3 border-t border-slate-800 bg-slate-950 shrink-0">

        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900">

            <div class="flex items-center gap-2 min-w-0">

                <div class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400
                            font-bold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">
                        {{ Auth::user()->name ?? 'Staff' }}
                    </p>

                    <p class="text-[10px] text-slate-400 truncate">
                        {{ Auth::user()->email ?? 'Active' }}
                    </p>
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    title="Logout"
                    class="w-8 h-8 flex items-center justify-center
                           text-slate-400 hover:text-rose-400
                           hover:bg-slate-800 rounded-lg transition"
                >
                    🚪
                </button>
            </form>

        </div>

    </div>

</aside>