<nav x-data="{ open: false }" class="bg-slate-900 text-slate-200 border-b border-slate-800 sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-black text-sm shadow-sm">
                        🏥
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-white leading-none">LifeCare HMIS</span>
                        <span class="text-[9px] text-blue-400 font-bold uppercase tracking-wider mt-0.5">Hospital Platform</span>
                    </div>
                </a>

                <!-- Desktop Nav Links (Visible on Large Screens) -->
                <div class="hidden lg:flex items-center space-x-1 ml-6 text-xs font-semibold">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('dashboard') || request()->is('home') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('patients.index') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('patients.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Reception
                    </a>
                    <a href="{{ route('triage.index') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('triage.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Triage
                    </a>
                    <a href="{{ route('doctor.queue') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('doctor.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Doctor
                    </a>
                    <a href="{{ route('lab.index') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('lab.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Lab
                    </a>
                    <a href="{{ route('pharmacy.index') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('pharmacy.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Pharmacy
                    </a>
                    <a href="{{ route('billing.index') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('billing.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Billing
                    </a>
                    <a href="{{ route('revenue.index') }}" 
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs('revenue.*') ? 'bg-indigo-900 text-indigo-200 font-bold' : 'text-indigo-400 hover:bg-slate-800' }}">
                        🛡️ Revenue
                    </a>
                </div>
            </div>

            <!-- Right Controls: Add Staff & User Profile -->
            <div class="hidden lg:flex items-center gap-3">
                @if(Route::has('employees.create'))
                    <a href="{{ route('employees.create') }}" 
                       class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3 py-1.5 rounded-lg transition shadow-sm">
                        <span>➕</span> Add Staff
                    </a>
                @endif

                <div class="flex items-center gap-2 pl-2 border-l border-slate-700 text-xs">
                    <span class="text-slate-300 font-medium">{{ Auth::user()->name ?? 'Staff' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-1 text-slate-400 hover:text-rose-400 transition">
                            🚪
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex lg:hidden items-center gap-2">
                @if(Route::has('employees.create'))
                    <a href="{{ route('employees.create') }}" 
                       class="bg-blue-600 text-white font-bold text-xs px-2.5 py-1.5 rounded-lg">
                        ➕ Staff
                    </a>
                @endif

                <button @click="open = !open" 
                        class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Accordion (Expands directly underneath the topbar, pushing content down naturally) -->
    <div x-show="open" x-cloak class="lg:hidden border-t border-slate-800 bg-slate-900 px-4 pt-3 pb-5 space-y-2 text-xs font-semibold">
        
        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-2 pt-1">Navigation Menu</div>

        <div class="grid grid-cols-2 gap-1.5">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') || request()->is('home') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('patients.index') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('patients.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>📇</span> Reception
            </a>
            <a href="{{ route('triage.index') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('triage.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>🩺</span> Triage
            </a>
            <a href="{{ route('doctor.queue') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('doctor.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>👨‍⚕️</span> Doctor
            </a>
            <a href="{{ route('lab.index') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('lab.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>🧪</span> Laboratory
            </a>
            <a href="{{ route('pharmacy.index') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('pharmacy.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>💊</span> Pharmacy
            </a>
            <a href="{{ route('billing.index') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('billing.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                <span>💳</span> Billing
            </a>
            <a href="{{ route('revenue.index') }}" 
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg {{ request()->routeIs('revenue.*') ? 'bg-indigo-600 text-white font-bold' : 'text-indigo-400 hover:bg-slate-800' }}">
                <span>🛡️</span> Revenue Control
            </a>
        </div>

        @if(Route::has('employees.index'))
            <a href="{{ route('employees.index') }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
                <span>👥</span> Staff Management
            </a>
        @endif

        <!-- Mobile User Session Strip -->
        <div class="pt-3 mt-2 border-t border-slate-800 flex items-center justify-between px-2">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded bg-blue-600/20 text-blue-400 font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-white">{{ Auth::user()->name ?? 'Staff' }}</p>
                    <p class="text-[10px] text-slate-400">{{ Auth::user()->email ?? 'Active' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-bold text-rose-400 hover:underline">
                    Log Out 🚪
                </button>
            </form>
        </div>

    </div>
</nav>
