@props(['role' => 'admin'])

<aside class="sidebar">
    {{-- Logo --}}
    <div class="p-5 border-b border-white/10">
        <a href="{{ route("$role.dashboard") }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">engineering</span>
            </div>
            <span class="text-lg font-bold text-white">Surface Mine</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="py-4 flex-1">
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard Pemantauan
            </a>
            <a href="{{ route('admin.laporan.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">monitoring</span>
                Laporan Pemantauan
            </a>
            <a href="{{ route('admin.master-data.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.master-data.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">storage</span>
                Master Data
            </a>
            <a href="{{ route('admin.utilization.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.utilization.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">precision_manufacturing</span>
                Utilization
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
            <a href="{{ route('spv.utilization.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('spv.utilization.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">precision_manufacturing</span>
                Utilization
            </a>
        @elseif($role === 'pegawai')
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

        <div class="border-t border-white/10 my-4 mx-4"></div>

        <a href="{{ route('profile.edit') }}"
           class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            Profil
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="sidebar-nav-item w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </button>
        </form>
    </nav>
</aside>
