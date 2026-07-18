<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>{{ config('app.name', 'Surface Mine') }} @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .industrial-grid {
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.03) 1px, transparent 0);
            background-size: 40px 40px;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-surface font-sans text-on-surface flex min-h-screen">
    <aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container-lowest flex flex-col justify-between py-6 z-50">
        <div class="space-y-8">
            <div class="px-6 flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary-container" style="font-variation-settings: 'FILL' 1;">precision_manufacturing</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-primary leading-tight">Surface Mine</h1>
                    <p class="text-xs text-on-surface-variant opacity-70">
                        @if(auth()->user()->role === 'admin') Admin Panel @else Supervisor Panel @endif
                    </p>
                </div>
            </div>
            @php
                $isActive = fn($path) => request()->is($path) || request()->is($path . '/*');
            @endphp
            <nav class="space-y-1">
                @if(auth()->user()->role === 'admin')
                    <a href="/admin/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('admin/dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('admin/dashboard') ? 1 : 0 }};">dashboard</span>
                        <span class="text-sm font-bold">Dashboard Admin</span>
                    </a>
                    <a href="/admin/spv" class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('admin/spv') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('admin/spv') ? 1 : 0 }};">supervisor_account</span>
                        <span class="text-sm font-bold">Kelola SPV</span>
                    </a>
                    <a href="/admin/alat" class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('admin/alat') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('admin/alat') ? 1 : 0 }};">construction</span>
                        <span class="text-sm font-bold">Kelola Alat</span>
                    </a>
                    <a href="/admin/pegawai" class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('admin/pegawai') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('admin/pegawai') ? 1 : 0 }};">badge</span>
                        <span class="text-sm font-bold">Kelola Pegawai</span>
                    </a>
                @else
                    <a href="/spv/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('spv/dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('spv/dashboard') ? 1 : 0 }};">dashboard</span>
                        <span class="text-sm font-bold">Dashboard SPV</span>
                    </a>
                    <a href="/spv/pemantauan" class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('spv/pemantauan') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('spv/pemantauan') ? 1 : 0 }};">monitoring</span>
                        <span class="text-sm font-bold">Laporan Pemantauan</span>
                    </a>
                @endif
            </nav>
        </div>
        <div class="space-y-4">
            <div class="px-6 py-4 mx-4 bg-surface-container-high rounded-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-sm border-2 border-outline-variant">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-1">
                <form method="POST" action="{{ route('logout') }}" class="px-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-error hover:bg-error-container/20 rounded-lg transition-all duration-200 ease-in-out">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text-sm font-bold">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <main class="flex-1 ml-64 min-h-screen industrial-grid relative">
        <header class="flex justify-between items-center w-full h-16 px-6 border-b border-outline-variant bg-surface/80 backdrop-blur-md sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <span class="text-lg font-bold text-primary">@yield('page_title', 'Dashboard')</span>
            </div>
        </header>
        <div class="p-6 lg:p-10 space-y-8">
            @if(session('success'))
                <div class="bg-surface-container-high border border-outline-variant rounded-xl p-4 flex items-center space-x-3" x-data="{ show: true }" x-show="show" x-transition>
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <p class="text-sm font-medium text-on-surface flex-1">{{ session('success') }}</p>
                    <button type="button" @click="show = false" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-surface-container-high border border-error-container rounded-xl p-4 flex items-center space-x-3">
                    <span class="material-symbols-outlined text-error">error</span>
                    <p class="text-sm font-medium text-on-surface">{{ session('error') }}</p>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
    <script>
        document.querySelectorAll('.bg-surface-container-high, [class*="rounded-xl"][class*="border"]').forEach(card => {
            card.addEventListener('mouseenter', () => {
                if (card.tagName !== 'BUTTON' && !card.closest('table')) {
                    card.style.transform = 'translateY(-2px)';
                    card.style.boxShadow = '0 10px 20px -5px rgba(0,0,0,0.3)';
                }
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = 'none';
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
