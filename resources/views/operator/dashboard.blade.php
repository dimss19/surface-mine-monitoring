@extends('layouts.operator')

@section('title', 'Dashboard Operator')
@section('page-title', 'Dashboard Operator')

@section('content')
<div class="mb-6 fade-in">
    <h1 class="text-2xl font-bold" style="color: var(--text); font-family: 'Plus Jakarta Sans', sans-serif;">Dashboard Operator</h1>
    <p class="text-slate-500">Selamat datang, {{ Auth::user()->name }}</p>
</div>

{{-- Info Cards --}}
<div class="grid grid-cols-2 gap-4 mb-6 fade-in" style="animation-delay: 50ms;">
    <div class="stat-card border-l-4 border-blue-500">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-500">local_shipping</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Total Ritasi</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $totalRitasi ?? 0 }}</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-green-500">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-green-500">construction</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Total Non-Ritasi</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $totalNonRitasi ?? 0 }}</p>
        </div>
    </div>
</div>

{{-- Input Section --}}
<div class="mb-6 fade-in" style="animation-delay: 100ms;">
    <h2 class="section-title mb-4">Input Data</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('pegawai.ritasi.create') }}" 
           class="card p-5 hover:shadow-lg transition-all duration-200 cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                    <span class="material-symbols-outlined text-amber-500">local_shipping</span>
                </div>
                <div>
                    <h3 class="font-bold" style="color: var(--text);">Unit Ritasi</h3>
                    <p class="text-sm text-slate-500">Input data ritasi harian</p>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm text-amber-600 font-medium">
                <span>Input Sekarang</span>
                <span class="material-symbols-outlined text-lg ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </div>
        </a>
        
        <a href="{{ route('pegawai.non-ritasi.create') }}" 
           class="card p-5 hover:shadow-lg transition-all duration-200 cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                    <span class="material-symbols-outlined text-blue-500">construction</span>
                </div>
                <div>
                    <h3 class="font-bold" style="color: var(--text);">Unit Non-Ritasi</h3>
                    <p class="text-sm text-slate-500">Input data non-ritasi</p>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm text-blue-600 font-medium">
                <span>Input Sekarang</span>
                <span class="material-symbols-outlined text-lg ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </div>
        </a>
        
        <a href="{{ route('pegawai.general.create') }}" 
           class="card p-5 hover:shadow-lg transition-all duration-200 cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 transition-colors">
                    <span class="material-symbols-outlined text-green-500">engineering</span>
                </div>
                <div>
                    <h3 class="font-bold" style="color: var(--text);">Pekerjaan General</h3>
                    <p class="text-sm text-slate-500">Input pekerjaan general</p>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm text-green-600 font-medium">
                <span>Input Sekarang</span>
                <span class="material-symbols-outlined text-lg ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </div>
        </a>
    </div>
</div>

{{-- Riwayat Section --}}
<div class="fade-in" style="animation-delay: 150ms;">
    <h2 class="section-title mb-4">Riwayat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('pegawai.ritasi.riwayat') }}" 
           class="card p-5 hover:shadow-lg transition-all duration-200 cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-slate-600">history</span>
                </div>
                <div>
                    <h3 class="font-bold" style="color: var(--text);">Riwayat Ritasi</h3>
                    <p class="text-sm text-slate-500">Lihat data ritasi sebelumnya</p>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm text-slate-600 font-medium">
                <span>Lihat Riwayat</span>
                <span class="material-symbols-outlined text-lg ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </div>
        </a>
        
        <a href="{{ route('pegawai.non-ritasi.riwayat') }}" 
           class="card p-5 hover:shadow-lg transition-all duration-200 cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-slate-600">history</span>
                </div>
                <div>
                    <h3 class="font-bold" style="color: var(--text);">Riwayat Non-Ritasi</h3>
                    <p class="text-sm text-slate-500">Lihat data non-ritasi sebelumnya</p>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm text-slate-600 font-medium">
                <span>Lihat Riwayat</span>
                <span class="material-symbols-outlined text-lg ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </div>
        </a>
    </div>
</div>
@endsection
