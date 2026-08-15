<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LifeCare HMS | Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex flex-col justify-center items-center px-4">
    
    <!-- Main Card -->
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl text-center border-t-4 border-blue-600">
        
        <!-- Hospital Icon -->
        <div class="bg-blue-100 p-4 rounded-full inline-block mb-4 shadow-sm">
            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">LifeCare HMS</h1>
        <p class="text-gray-500 mb-8 mt-2 text-sm">Secure Mobile Gateway</p>
        
        <!-- Auth Buttons -->
        <div class="space-y-4">
            @auth
                <a href="{{ url('/home') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md">
                    Secure Log In
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block w-full bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-bold py-3 px-4 rounded-xl transition duration-200">
                        Staff Registration
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Software Branding Footer -->
    <div class="mt-10 text-center">
        <p class="text-xs text-gray-400">Powered by Dymex Digital Solutions</p>
        <p class="text-[10px] text-gray-400 mt-1">v1.0.0 • Authorized Access Only</p>
    </div>

</body>
</html>
