@extends('layouts.app')

@section('title', '- Login')

@section('content')
<!-- Import Material Symbols for the industrial theme icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">

<style>
    body {
        background-color: #111416 !important;
        background-image: none !important;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }
    .industrial-grid {
        background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.03) 1px, transparent 0);
        background-size: 40px 40px;
    }
</style>

<main class="flex-grow flex items-center justify-center px-6 py-12 industrial-grid">
    <div class="relative w-full max-w-[520px] mx-auto">
        <!-- Decorative Industrial Elements -->
        <div class="absolute -top-12 -left-12 w-24 h-24 border-t-4 border-l-4 border-[#e2231a] opacity-20"></div>
        <div class="absolute -bottom-12 -right-12 w-24 h-24 border-b-4 border-r-4 border-[#e2231a] opacity-20"></div>

        <div class="bg-surface-container-high rounded-2xl border border-outline-variant shadow-2xl overflow-hidden relative z-10">
            <div class="p-10 md:p-12">
                <!-- Brand Identity -->
                <div class="flex flex-col items-center mb-10">
                    <div class="w-16 h-16 bg-primary-container flex items-center justify-center rounded-xl mb-6 shadow-lg shadow-primary-container/20">
                        <span class="material-symbols-outlined text-on-primary-container text-4xl" style="font-variation-settings: 'FILL' 1;">precision_manufacturing</span>
                    </div>
                    <h1 class="text-2xl font-bold text-on-surface mb-2">
                        Masuk ke Sistem
                    </h1>
                    <p class="text-sm text-on-surface-variant text-center px-4">
                        Login Pegawai / SPV / Admin — masukkan kredensial untuk akses Surface Mine.
                    </p>
                </div>

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    
                    <!-- Error messages -->
                    @if ($errors->any())
                        <div class="bg-error-container/20 border border-error-container text-error px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">error</span>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Step 1: Identitas Akun -->
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-0.5 w-6 h-6 bg-primary-container flex items-center justify-center rounded">
                            <span class="text-[12px] font-bold text-on-primary-container">1</span>
                        </div>
                        <label class="block text-[12px] font-bold text-on-surface mb-2 uppercase tracking-wider">
                            Identitas Akun <span class="text-primary-container">*</span>
                        </label>
                        <div class="relative">
                            <input name="login" type="text" value="{{ old('login') }}" placeholder="Username atau Alamat Email" class="w-full px-4 py-3 bg-surface-container border border-outline-variant focus:border-primary focus:ring-0 text-on-surface text-sm placeholder:text-on-surface-variant/50 rounded-xl transition-all outline-none" required>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/40">person</span>
                        </div>
                    </div>

                    <!-- Step 2: Kata Sandi -->
                    <div class="relative pl-8" x-data="{ show: false }">
                        <div class="absolute left-0 top-0.5 w-6 h-6 bg-primary-container flex items-center justify-center rounded">
                            <span class="text-[12px] font-bold text-on-primary-container">2</span>
                        </div>
                        <label class="block text-[12px] font-bold text-on-surface mb-2 uppercase tracking-wider">
                            Kata Sandi <span class="text-primary-container">*</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" class="w-full px-4 py-3 bg-surface-container border border-outline-variant focus:border-primary focus:ring-0 text-on-surface text-sm placeholder:text-on-surface-variant/50 rounded-xl transition-all outline-none" required>
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/40 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pl-8 mt-2">
                        <label class="flex items-center group cursor-pointer">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="remember" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-outline-variant bg-surface-container checked:bg-primary-container checked:border-primary-container focus:ring-0 transition-all">
                                <span class="material-symbols-outlined absolute text-on-primary-container opacity-0 peer-checked:opacity-100 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[16px]" style="font-variation-settings: 'FILL' 1;">check</span>
                            </div>
                            <span class="ml-3 text-[12px] font-bold text-on-surface">Ingat Saya</span>
                        </label>
                        
                        <a href="#" class="text-[12px] text-on-surface-variant hover:text-primary transition-colors">
                            Lupa Kata Sandi?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full flex items-center justify-center gap-3 bg-primary-container text-on-primary-container font-bold py-4 rounded-xl hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-primary-container/20" :disabled="loading">
                            <span x-show="!loading" class="flex items-center gap-3">
                                <span class="material-symbols-outlined">login</span>
                                Masuk
                            </span>
                            <span x-show="loading" x-cloak class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-on-primary-container" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Bottom Warning / Info Strip -->
            <div class="bg-surface-container px-10 py-4 flex items-center gap-3 border-t border-outline-variant">
                <span class="material-symbols-outlined text-primary-container">security</span>
                <p class="text-[12px] text-on-surface-variant">
                    Akses sistem ini dipantau untuk keperluan keamanan operasional.
                </p>
            </div>
        </div>
    </div>
</main>
@endsection
