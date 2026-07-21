<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surface Mine Production</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#e2231a">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Surface Mine">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: #111416; color: #e1e2e6; min-height: 100vh; display: flex; flex-direction: column; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .industrial-grid { background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.04) 1px, transparent 0); background-size: 40px 40px; }
        .btn-primary { background: #e2231a; color: #fffaff; transition: transform .15s, opacity .15s; }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0) scale(.99); }
    </style>
</head>
<body class="industrial-grid">

    <main class="flex-1 flex flex-col items-center justify-center px-6 py-12 text-center">
        <div class="w-20 h-20 bg-[#e2231a] rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-red-900/40">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1; font-size: 48px;">precision_manufacturing</span>
        </div>

        <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-[#ffb4a9] mb-4">Civil Mining Operation</p>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
            Surface Mine<br><span class="text-[#e2231a]">Production</span>
        </h1>
        <p class="text-base sm:text-lg text-[#e7bdb7] max-w-md mb-10 leading-relaxed">
            Sistem pelaporan absensi pegawai dan pemantauan lapangan tambang. Dukungan offline penuh untuk operasional di area terpencil.
        </p>

        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-2 font-bold px-10 py-4 rounded-xl text-base shadow-xl shadow-red-900/30">
            <span class="material-symbols-outlined" style="font-size: 22px;">login</span>
            Masuk
        </a>

        <div class="mt-16 grid grid-cols-3 gap-6 sm:gap-12 text-center max-w-2xl">
            <div class="flex flex-col items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb4a9] text-3xl sm:text-4xl">badge</span>
                <p class="text-xs sm:text-sm font-semibold text-[#e1e2e6]">Absensi Pegawai</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb4a9] text-3xl sm:text-4xl">monitoring</span>
                <p class="text-xs sm:text-sm font-semibold text-[#e1e2e6]">Pemantauan SPV</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb4a9] text-3xl sm:text-4xl">cloud_off</span>
                <p class="text-xs sm:text-sm font-semibold text-[#e1e2e6]">Mode Offline</p>
            </div>
        </div>
    </main>

    <footer class="px-6 py-6 text-center text-xs text-[#ae8882]">
        &copy; {{ date('Y') }} Surface Mine Production
    </footer>

</body>
</html>
