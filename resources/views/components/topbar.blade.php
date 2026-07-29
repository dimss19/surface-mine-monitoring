@props(['role' => 'admin'])

<header class="topbar">
    <h1 class="text-xl font-heading font-bold text-[var(--primary)]">Mining Oprationals Civil Departement</h1>

    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-[var(--text)]">{{ ucfirst($role) }}</span>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1 text-sm text-red-600 hover:text-red-700">
                <span class="material-symbols-outlined text-lg">logout</span>
                Logout
            </button>
        </form>

        <button class="relative">
            <span class="material-symbols-outlined text-slate-600">notifications</span>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <button>
            <span class="material-symbols-outlined text-slate-600">settings</span>
        </button>
    </div>
</header>
