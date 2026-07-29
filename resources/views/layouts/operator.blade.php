{{-- resources/views/layouts/operator.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Operator Dashboard') - Surface Mine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-[var(--bg)] font-['Inter',sans-serif]">
    @include('components.sidebar', ['role' => 'pegawai'])
    
    {{-- Operator Topbar --}}
    <header class="topbar">
        <h1 class="text-xl font-heading font-bold text-[var(--primary)]">@yield('page-title', 'Form Input')</h1>
        <div class="flex items-center gap-4">
            <button class="text-slate-600 hover:text-slate-800">
                <span class="material-symbols-outlined">help</span>
            </button>
            <button class="relative">
                <span class="material-symbols-outlined text-slate-600">notifications</span>
            </button>
        </div>
    </header>
    
    <main class="ml-64 pt-16 min-h-screen p-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
</body>
</html>