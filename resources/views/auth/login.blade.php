<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[var(--bg)] px-4 py-8">
        <div class="card w-full max-w-xl">
            {{-- Logo --}}
            <div class="text-center mb-6">
                <img src="{{ asset('images/company-logo.png') }}" alt="Company Logo" class="h-24 w-auto mx-auto object-contain">
            </div>

            {{-- Title --}}
            <div class="text-center mb-8">
                <h1 class="font-heading text-2xl md:text-3xl font-bold text-[var(--primary)] tracking-wide whitespace-nowrap">SURFACE MINE OPERATIONALS</h1>
                <p class="text-sm text-[var(--text-secondary)] mt-2">Welcome To Civil Department</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Username --}}
                <div>
                    <label class="form-label">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="material-symbols-outlined">person</span>
                        </span>
                        <input type="text"
                               name="login"
                               value="{{ old('login') }}"
                               placeholder="Enter username"
                               class="form-input pl-10"
                               required
                               autofocus>
                    </div>
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                </div>

                {{-- ID Pekerja --}}
                <div>
                    <label class="form-label">ID Pekerja</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="material-symbols-outlined">badge</span>
                        </span>
                        <input type="password"
                               name="password"
                               placeholder="Enter ID Pekerja"
                               class="form-input pl-10"
                               required
                               autocomplete="current-password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Error message --}}
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Button --}}
                <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2 text-base">
                    Masuk
                    <span>→</span>
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
