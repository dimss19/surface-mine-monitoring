<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200 px-4 py-8 md:py-16 relative overflow-hidden">
        {{-- Decorative background glow --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-50 -translate-y-1/3 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-amber-100 rounded-full blur-3xl opacity-50 translate-y-1/3 -translate-x-1/3"></div>

        <div class="fade-in w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 relative z-10 border border-slate-100">
            {{-- Left column - Visual Banner (Desktop only) --}}
            <div class="hidden md:flex md:col-span-5 bg-gradient-to-b from-[#0f1d36] to-[#08101d] p-8 flex-col justify-between relative">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.08),transparent_45%)]"></div>
                
                {{-- Top --}}
                <div class="space-y-6 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="h-9 w-9 rounded-lg bg-amber-500 flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-white text-base">layers</span>
                        </div>
                        <span class="font-heading font-bold text-xs tracking-wider text-white">CIVIL PRODUCTION</span>
                    </div>
                </div>

                {{-- Middle text --}}
                <div class="space-y-4 relative z-10 my-auto">
                    <h2 class="font-heading text-xl lg:text-2xl font-bold text-white leading-tight">
                        Pencatatan Produksi & Utilitas Tambang
                    </h2>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Sistem informasi internal Civil Department untuk pencatatan ritasi harian, utilitas alat berat, dan monitoring kendala operasional secara offline-ready.
                    </p>
                </div>

                {{-- Bottom: Safety Quote --}}
                <div class="border-t border-slate-800 pt-4 mt-8 relative z-10">
                    <div class="flex items-center gap-2 text-amber-500 mb-1">
                        <span class="material-symbols-outlined text-sm">gavel</span>
                        <span class="text-3xs font-semibold uppercase tracking-widest">Safety Guideline</span>
                    </div>
                    <p class="text-4xs text-slate-400 font-medium italic">
                        "Safety first, production will follow."
                    </p>
                </div>
            </div>

            {{-- Right column - Form --}}
            <div class="col-span-12 md:col-span-7 p-6 sm:p-10 flex flex-col justify-center">
                {{-- Mobile Logo / Header --}}
                <div class="text-center md:text-left mb-8">
                    <div class="md:hidden flex justify-center mb-4">
                        <div class="h-12 w-12 rounded-lg bg-[var(--primary)] flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-white text-xl">layers</span>
                        </div>
                    </div>
                    
                    <h1 class="font-heading text-xl sm:text-2xl font-bold text-[var(--primary)]">Selamat Datang</h1>
                    <p class="text-xs text-[var(--text-secondary)] mt-1.5">
                        Masukkan akun untuk mengakses dashboard Surface Mine.
                    </p>
                </div>

                {{-- Main Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Username / Email --}}
                    <div>
                        <label class="form-label text-xs">Username</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <span class="material-symbols-outlined text-base">person</span>
                            </span>
                            <input type="text"
                                   name="login"
                                   value="{{ old('login') }}"
                                   placeholder="Masukkan username"
                                   class="form-input pl-10 text-xs py-2"
                                   required
                                   autofocus>
                        </div>
                        <x-input-error :messages="$errors->get('login')" class="mt-1" />
                    </div>

                    {{-- Password / ID Pekerja --}}
                    <div>
                        <label class="form-label text-xs">ID Pekerja (Password)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <span class="material-symbols-outlined text-base">lock</span>
                            </span>
                            <input type="password"
                                   name="password"
                                   placeholder="Masukkan ID Pekerja"
                                   class="form-input pl-10 text-xs py-2"
                                   required
                                   autocomplete="current-password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Error message --}}
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-600 px-3.5 py-2.5 rounded-lg text-xs flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">error</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Button --}}
                    <div class="pt-2">
                        <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2 text-xs py-2.5">
                            Masuk Ke Dashboard
                            <span class="material-symbols-outlined text-sm">login</span>
                        </button>
                    </div>
                </form>

                {{-- Back button to landing --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-xs text-[var(--text-muted)] hover:text-[var(--primary)] transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
