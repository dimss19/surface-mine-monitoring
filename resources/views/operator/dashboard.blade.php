{{-- resources/views/operator/dashboard.blade.php --}}
@extends('layouts.operator')

@section('title', 'Operator Dashboard')
@section('page-title', 'Dashboard Operator')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Dashboard Operator</h1>
    <p class="text-[var(--text-muted)]">Selamat datang, {{ Auth::user()->name }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Quick Access Cards --}}
    <a href="{{ route('pegawai.ritasi.create') }}" 
       class="card p-6 hover:shadow-md transition-shadow cursor-pointer">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600">local_shipping</span>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[var(--primary)]">Unit Ritasi</h3>
                <p class="text-sm text-[var(--text-muted)]">Input data ritasi harian</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('pegawai.non-ritasi.create') }}" 
       class="card p-6 hover:shadow-md transition-shadow cursor-pointer">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600">construction</span>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[var(--primary)]">Unit Non Ritasi</h3>
                <p class="text-sm text-[var(--text-muted)]">Input data non-ritasi</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('pegawai.general.create') }}" 
       class="card p-6 hover:shadow-md transition-shadow cursor-pointer">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600">engineering</span>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[var(--primary)]">Pekerjaan General</h3>
                <p class="text-sm text-[var(--text-muted)]">Input pekerjaan general</p>
            </div>
        </div>
    </a>
</div>
@endsection