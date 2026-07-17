<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Surface Mine') }} @yield('title')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden flex text-gray-900 antialiased">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col hidden sm:flex">
        <div class="h-16 flex items-center px-6 bg-gray-900 border-b border-gray-800">
            <span class="font-bold text-xl text-blue-400">Surface Mine</span>
        </div>
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="px-2 space-y-1">
                @if(auth()->user()->role === 'admin')
                    <a href="/admin/dashboard" class="bg-gray-800 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        Dashboard Admin
                    </a>
                    <a href="/admin/spv" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        Kelola SPV
                    </a>
                    <a href="/admin/alat" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        Kelola Alat
                    </a>
                @else
                    <a href="/spv/dashboard" class="bg-gray-800 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        Dashboard SPV
                    </a>
                    <a href="/spv/pemantauan" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        Laporan Pemantauan
                    </a>
                @endif
            </nav>
        </div>
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center">
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs font-medium text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-red-400 hover:text-red-300 font-medium px-2 py-1 rounded hover:bg-gray-800">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top navbar for mobile -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:hidden">
            <span class="font-bold text-xl text-blue-600">Surface Mine</span>
            <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </header>

        <!-- Main section -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page_title', 'Dashboard')</h1>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
</body>
</html>
