<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Surface Mine - Dashboard Supervisor</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary": "#2f3131", "tertiary-container": "#737474", "outline-variant": "#5d3f3b",
                        "secondary-fixed": "#e2e2e5", "inverse-on-surface": "#2e3134", "surface-variant": "#323538",
                        "on-error": "#690005", "on-secondary-fixed": "#1a1c1e", "primary-container": "#e2231a",
                        "inverse-primary": "#c00005", "surface-tint": "#ffb4a9", "on-primary": "#690001",
                        "surface": "#111416", "on-surface": "#e1e2e6", "inverse-surface": "#e1e2e6",
                        "on-tertiary-container": "#fbfcfc", "on-error-container": "#ffdad6",
                        "tertiary-fixed-dim": "#c6c6c7", "on-surface-variant": "#e7bdb7", "on-secondary": "#2f3133",
                        "primary-fixed-dim": "#ffb4a9", "on-primary-fixed-variant": "#930003", "background": "#111416",
                        "error": "#ffb4ab", "surface-bright": "#36393d", "secondary-fixed-dim": "#c6c6c9",
                        "on-tertiary-fixed-variant": "#454747", "on-primary-container": "#fffaff", "outline": "#ae8882",
                        "error-container": "#93000a", "surface-container-high": "#272a2d",
                        "surface-container-highest": "#323538", "primary-fixed": "#ffdad5",
                        "on-tertiary-fixed": "#1a1c1c", "on-secondary-fixed-variant": "#454749",
                        "secondary": "#c6c6c9", "secondary-container": "#454749", "tertiary": "#c6c6c7",
                        "on-background": "#e1e2e6", "on-secondary-container": "#b4b5b7", "primary": "#ffb4a9",
                        "on-primary-fixed": "#410000", "surface-container": "#1d2023",
                        "surface-container-lowest": "#0b0f11", "surface-dim": "#111416", "tertiary-fixed": "#e2e2e2",
                        "surface-container-low": "#191c1f"
                    },
                    "borderRadius": { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                    "fontFamily": { "body-lg": ["Plus Jakarta Sans"], "body-md": ["Plus Jakarta Sans"], "display-lg": ["Plus Jakarta Sans"], "headline-md": ["Plus Jakarta Sans"], "label-bold": ["Plus Jakarta Sans"], "display-lg-mobile": ["Plus Jakarta Sans"], "label-sm": ["Plus Jakarta Sans"], "headline-sm": ["Plus Jakarta Sans"] },
                    "fontSize": { "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}], "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}], "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}], "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "700"}], "label-bold": ["14px", {"lineHeight": "16px", "fontWeight": "700"}], "display-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}], "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}], "headline-sm": ["18px", {"lineHeight": "24px", "fontWeight": "700"}] }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .industrial-grid { background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.03) 1px, transparent 0); background-size: 40px 40px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition.opacity></div>

    @php $isActive = fn($path) => request()->is($path) || request()->is($path . '/*'); @endphp

    <aside class="fixed top-0 left-0 z-50 h-screen w-64 bg-surface-container-lowest flex flex-col justify-between py-6 transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full"
        :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
        <div class="space-y-8">
            <div class="px-6 flex items-center justify-between lg:justify-start space-x-3">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-on-primary-container" style="font-variation-settings: 'FILL' 1;">precision_manufacturing</span>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-lg font-bold text-primary leading-tight truncate">Surface Mine</h1>
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant opacity-70 truncate">Supervisor Panel</p>
                    </div>
                </div>
                <button class="lg:hidden text-on-surface-variant hover:text-on-surface" @click="sidebarOpen = false">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="space-y-1">
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('spv/dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}" href="{{ route('spv.dashboard') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('spv/dashboard') ? 1 : 0 }};">dashboard</span>
                    <span class="font-bold text-sm">Dashboard SPV</span>
                </a>
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg mx-2 transition-all duration-200 ease-in-out {{ $isActive('spv/pemantauan') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant' }}" href="{{ route('spv.pemantauan.index') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $isActive('spv/pemantauan') ? 1 : 0 }};">monitoring</span>
                    <span class="font-bold text-sm">Laporan Pemantauan</span>
                </a>
                <div x-data="{ outbox: window.outboxCount || 0 }" x-init="window.addEventListener('outbox:changed', e => outbox = e.detail)" x-show="outbox > 0" x-cloak class="mx-2 px-4 py-3 rounded-lg bg-primary-container/20 text-primary-container border border-primary-container/40 text-sm font-bold inline-flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">cloud_upload</span>
                    <span><span x-text="outbox"></span> antrian offline</span>
                </div>
            </nav>
        </div>
        <div class="space-y-4">
            <div class="px-6 py-4 mx-4 bg-surface-container-high rounded-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-sm border-2 border-outline-variant shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-sm text-on-surface leading-tight truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant truncate">Active Duty</p>
                    </div>
                </div>
            </div>
            <div class="space-y-1">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                <a class="flex items-center space-x-3 px-4 py-3 text-error hover:bg-error-container/20 rounded-lg mx-2 transition-all duration-200 ease-in-out" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-bold text-sm">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-h-screen industrial-grid lg:ml-64">
        <header class="flex items-center w-full h-16 px-4 lg:px-6 border-b border-outline-variant bg-surface/80 backdrop-blur-md sticky top-0 z-30">
            <button class="lg:hidden mr-3 text-on-surface-variant hover:text-on-surface" @click="sidebarOpen = true">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <span class="text-lg font-bold text-primary">Dashboard Supervisor</span>
        </header>
        <div class="flex-1 p-4 sm:p-6 lg:p-10 space-y-6 sm:space-y-8 overflow-auto">
            <section class="space-y-2">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Halo, {{ auth()->user()->name }}!</h2>
                <p class="text-sm sm:text-base text-on-surface-variant max-w-2xl">
                    Pantau area yang ditugaskan kepada Anda dan buat laporan pemantauan harian untuk memastikan standar operasional terpenuhi.
                </p>
            </section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-surface-container-high rounded-xl p-4 sm:p-6 border border-outline-variant flex flex-col justify-between group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] sm:text-xs font-bold text-on-surface-variant uppercase tracking-widest">Area Ditugaskan</span>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0 ml-2">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">public</span>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-8">
                            <span class="text-4xl sm:text-5xl md:text-6xl font-bold text-on-surface leading-none">{{ $metrics['total_area'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-high rounded-xl p-4 sm:p-6 border border-outline-variant flex flex-col justify-between group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] sm:text-xs font-bold text-on-surface-variant uppercase tracking-widest">Laporan (Bulan Ini)</span>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0 ml-2">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">description</span>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-8">
                            <span class="text-4xl sm:text-5xl md:text-6xl font-bold text-on-surface leading-none">{{ $metrics['laporan_bulan_ini'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="sm:col-span-2 bg-surface-container rounded-xl p-4 sm:p-6 border-l-4 border-primary flex items-start space-x-3 sm:space-x-4 shadow-lg">
                        <span class="material-symbols-outlined text-primary-container text-3xl sm:text-4xl mt-1 shrink-0">warning</span>
                        <div class="space-y-1 min-w-0">
                            <h4 class="text-base sm:text-lg font-bold text-on-surface">Catatan Keselamatan</h4>
                            <p class="text-xs sm:text-sm text-on-surface-variant">Pastikan semua APD telah diverifikasi sebelum memulai inspeksi lapangan di area tambang terbuka.</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 h-full">
                    <a href="{{ route('spv.pemantauan.create') }}" class="block w-full h-full bg-primary-container rounded-xl p-6 sm:p-8 text-left flex flex-col justify-center items-center group relative overflow-hidden transition-all duration-300 hover:shadow-[0_0_40px_rgba(226,35,26,0.3)] hover:-translate-y-1">
                        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                            <span class="material-symbols-outlined text-[80px] sm:text-[120px] select-none">add_circle</span>
                        </div>
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-white text-4xl sm:text-5xl font-bold">add</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">Buat Laporan Baru</h3>
                        <p class="text-white/80 text-center text-sm sm:text-base max-w-xs">
                            Catat progres pemantauan lapangan hari ini untuk menjaga integritas data operasional.
                        </p>
                        <div class="absolute bottom-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-white/10" style="clip-path: polygon(100% 0, 0 100%, 100% 100%);"></div>
                    </a>
                </div>
            </div>
            <section class="relative h-[200px] sm:h-[300px] w-full rounded-2xl overflow-hidden group">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAy2t3foZaU9uHuAVtlt2u3ZVVZ3ANs7jGAd0nK89krjAPvEDvDtN0idbbJ56C5hZE5kzO_-KO9GLeU3aBNhUJy4SEgrRDEfODtICsmPC-uBHarm--uGQ-QWJtcbyVVHIwnm5UoF8eiWnxVMdcWhz146r17djHEpfVX3d_FOi64MnPZSDZir_9yCBAe580dPinyBeF7pkVyzwNjkycHrjq_jQ49gYa7S612UOp7kenM_gJ5dthGr_1-Gg')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-[10px] sm:text-xs font-bold text-white uppercase tracking-widest">Live Site View</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white">Pit A-12 Monitoring Area</h3>
                </div>
            </section>
        </div>
    </main>
    <script>
        document.querySelectorAll('.bg-surface-container-high').forEach(card => {
            if (!card.closest('table')) {
                card.addEventListener('mouseenter', () => { card.style.transform = 'translateY(-2px)'; card.style.boxShadow = '0 10px 20px -5px rgba(0,0,0,0.3)'; });
                card.addEventListener('mouseleave', () => { card.style.transform = 'translateY(0)'; card.style.boxShadow = 'none'; });
            }
        });
    </script>
</body>
</html>
