@props(['role' => 'admin'])

<aside class="sidebar">
    {{-- Logo --}}
    <div class="p-6 border-b border-white/10">
        <a href="{{ route("$role.dashboard") }}" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings: 'FILL' 1;">construction</span>
            </div>
            <span class="text-lg font-heading font-bold">Surface Mine</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="py-4">
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard Pemantauan
            </a>
            <a href="{{ route('admin.master-data.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.master-data.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">storage</span>
                Master Data
            </a>
            <a href="{{ route('admin.laporan.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">monitoring</span>
                Laporan Pemantauan
            </a>
            <a href="{{ route('admin.hak-akses.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.hak-akses.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                Hak Akses
            </a>
        @elseif($role === 'spv')
            <a href="{{ route('spv.dashboard') }}"
               class="sidebar-nav-item {{ request()->routeIs('spv.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard Pemantauan
            </a>
            <a href="{{ route('spv.laporan.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('spv.laporan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">monitoring</span>
                Laporan Pemantauan
            </a>
        @elseif($role === 'pegawai')
            <a href="{{ route('pegawai.dashboard') }}"
               class="sidebar-nav-item {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('pegawai.ritasi.create') }}"
               class="sidebar-nav-item {{ request()->routeIs('pegawai.ritasi.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                Unit Ritasi
            </a>
            <a href="{{ route('pegawai.non-ritasi.create') }}"
               class="sidebar-nav-item {{ request()->routeIs('pegawai.non-ritasi.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">construction</span>
                Unit Non Ritasi
            </a>
            <a href="{{ route('pegawai.general.create') }}"
               class="sidebar-nav-item {{ request()->routeIs('pegawai.general.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">engineering</span>
                Pekerjaan General
            </a>
        @endif

        <div class="border-t border-white/10 my-4"></div>

        <a href="{{ route('profile.show') }}"
           class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            Profil
        </a>
    </nav>
</aside>
