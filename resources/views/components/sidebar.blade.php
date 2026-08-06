@props(['role' => 'admin'])

<aside class="sidebar">
    {{-- Logo --}}
    <div class="p-5 border-b border-white/10">
        <a href="{{ route($role === 'pegawai' ? 'pegawai.dashboard' : "$role.rekapan.index") }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/company-logo.png') }}" alt="Logo" class="h-10 w-auto object-contain">
            <div class="flex flex-col">
                <span class="text-lg font-bold text-white leading-tight">Surface Mine</span>
                <span class="text-xs text-slate-400">Mining Operations</span>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="py-4 flex-1 overflow-y-auto">
        <div class="px-4 mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu</span>
        </div>

        @if($role === 'admin')
            <a href="{{ route("$role.dashboard.index") }}"
               class="sidebar-nav-item {{ request()->routeIs("$role.dashboard.*") ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('admin.rekapan.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.rekapan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">receipt_long</span>
                Rekapan Pegawai
            </a>
            <a href="{{ route('admin.utilization.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.utilization.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">build</span>
                Utilization
            </a>
            <a href="{{ route('admin.master-data.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('admin.master-data.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">storage</span>
                Master Data
            </a>
         @elseif($role === 'spv')
            <a href="{{ route("$role.dashboard.index") }}"
               class="sidebar-nav-item {{ request()->routeIs("$role.dashboard.*") ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('spv.rekapan.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('spv.rekapan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">receipt_long</span>
                Rekapan Pegawai
            </a>
            <a href="{{ route('spv.utilization.index') }}"
               class="sidebar-nav-item {{ request()->routeIs('spv.utilization.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">build</span>
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
            <a href="{{ route('pegawai.utilization.create') }}"
               class="sidebar-nav-item {{ request()->routeIs('pegawai.utilization.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">build</span>
                Utilization
            </a>
        @endif

        <div class="border-t border-white/10 my-4 mx-4"></div>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto px-2">
            @csrf
            <button type="submit" class="sidebar-nav-item w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </button>
        </form>
    </nav>
</aside>
