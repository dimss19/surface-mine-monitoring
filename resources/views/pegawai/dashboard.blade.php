@extends('layouts.app')

@section('title', '- Dashboard Pegawai')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 sm:py-12 space-y-8" x-data="{ online: navigator.onLine, outbox: window.outboxCount || 0 }" x-init="window.addEventListener('online', () => online = true); window.addEventListener('offline', () => online = false); window.addEventListener('outbox:changed', e => outbox = e.detail)">

    <div class="space-y-2">
        <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-[#ffb4a9]">Dashboard Pegawai</p>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#e1e2e6] tracking-tight">Halo, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-[#e7bdb7]">Isi absensi harian Anda. Data tersimpan otomatis saat online kembali.</p>
    </div>

    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border" x-show="true">
            <span class="w-2 h-2 rounded-full bg-green-400"></span>
            <span>Online</span>
        </div>
    </div>

    <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <a href="{{ route('pegawai.rekapan.create') }}"
           class="group bg-[#e2231a] rounded-2xl p-6 flex flex-col justify-between min-h-[160px] relative overflow-hidden transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-red-900/40">
            <div class="absolute top-0 right-0 p-6 opacity-15 group-hover:opacity-25 transition-opacity">
                <span class="material-symbols-outlined text-white" style="font-size: 96px;">edit_note</span>
            </div>
            <div class="relative">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-white" style="font-size: 28px;">edit_note</span>
                </div>
                <h3 class="text-xl font-bold text-white">Isi Absensi</h3>
                <p class="text-white/80 text-sm mt-1">Input HM unit & pekerjaan harian</p>
            </div>
            <span class="material-symbols-outlined text-white/70 group-hover:translate-x-1 transition-transform relative">arrow_forward</span>
        </a>

        <a href="{{ route('pegawai.riwayat') }}"
           class="group bg-[#1d2023] rounded-2xl p-6 flex flex-col justify-between min-h-[160px] border border-[#5d3f3b]">
            <div>
                <div class="w-12 h-12 bg-[#272a2d] rounded-xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-[#ffb4a9]" style="font-size: 28px;">history</span>
                </div>
                <h3 class="text-xl font-bold text-[#e1e2e6]">Riwayat</h3>
                <p class="text-[#e7bdb7] text-sm mt-1">Lihat absensi yang sudah terkirim</p>
            </div>
            <span class="inline-flex items-center gap-2 text-xs font-bold text-[#ae8882] uppercase tracking-wider mt-4">
                <span class="material-symbols-outlined" style="font-size: 14px;">lock</span>
                Segera Hadir
            </span>
        </a>

    </section>

    <div class="pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 text-sm font-bold text-[#ffb4ab] hover:text-white hover:bg-[#93000a]/40 px-5 py-2.5 rounded-xl transition-colors border border-[#93000a]/40">
                <span class="material-symbols-outlined" style="font-size: 20px;">logout</span>
                Logout
            </button>
        </form>
    </div>

</div>
@endsection
