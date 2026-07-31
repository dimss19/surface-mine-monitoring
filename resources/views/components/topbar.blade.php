@props(['role' => 'admin'])

<header class="topbar">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors duration-200">
            <span class="material-symbols-outlined text-slate-600">menu</span>
        </button>
        <div class="hidden sm:block">
            <h1 class="text-lg font-bold" style="color: var(--text);">Mining Operationals Civil Department</h1>
            <p class="text-xs text-slate-500">Dashboard Pemantauan</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        {{-- User Badge --}}
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors">
            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-sm">person</span>
            </div>
            <span class="text-sm font-medium text-slate-700 hidden sm:inline">{{ Auth::user()->name ?? ucfirst($role) }}</span>
        </a>
    </div>
</header>

<script>
    document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('open');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        if (sidebar && toggle && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });
</script>
