{{-- resources/views/layouts/operator.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f1d36">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'Operator Dashboard') - Surface Mine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        /* PWA Mobile Optimizations */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
                padding: 1rem !important;
                padding-top: 4rem !important;
            }
            
            .stat-card {
                padding: 0.75rem;
                gap: 0.5rem;
            }
            
            .stat-card .text-2xl {
                font-size: 1.125rem;
            }
            
            .card {
                padding: 0.875rem;
            }
            
            /* Stack grids on mobile */
            .grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            .grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .grid-cols-4 {
                grid-template-columns: 1fr;
            }
            
            /* Mobile-friendly buttons */
            .btn-primary, .btn-secondary {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        /* Safe area for notch devices */
        @supports (padding: env(safe-area-inset-top)) {
            .topbar {
                padding-top: env(safe-area-inset-top);
                height: calc(4rem + env(safe-area-inset-top));
            }
            
            .main-content {
                padding-top: calc(4rem + env(safe-area-inset-top));
            }
        }
    </style>
</head>
<body class="bg-[var(--bg)] font-['Inter',sans-serif]">
    @php $role = Auth::user()->role ?? 'pegawai'; @endphp
    @include('components.sidebar', ['role' => $role])
    @include('components.topbar', ['role' => $role])

    <main class="ml-64 pt-16 min-h-screen p-6 main-content">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    {{ session('success') }}
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">error</span>
                    {{ session('error') }}
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
