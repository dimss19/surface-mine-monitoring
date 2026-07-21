@extends('layouts.dashboard')

@section('title', '- Buat Laporan Pemantauan')
@section('page_title', 'Buat Laporan Pemantauan Lapangan')

@section('content')
<section class="space-y-2 mb-6">
    <h2 class="text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Form Pemantauan Lapangan</h2>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Laporkan kondisi alat, kendala, dan foto area.
    </p>
</section>

<div class="flex items-center justify-between gap-3 flex-wrap mb-4"
     x-data="{ online: navigator.onLine, outbox: window.outboxCount || 0 }"
     x-init="window.addEventListener('online', () => online = true); window.addEventListener('offline', () => online = false); window.addEventListener('outbox:changed', e => outbox = e.detail)">
    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border"
         :class="online ? 'bg-green-500/10 text-green-400 border-green-500/30' : 'bg-amber-500/10 text-amber-400 border-amber-500/30'">
        <span class="w-2 h-2 rounded-full" :class="online ? 'bg-green-400 animate-pulse' : 'bg-amber-400'"></span>
        <span x-text="online ? 'Online' : 'Offline'"></span>
    </div>
    <div x-show="outbox > 0" x-cloak class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-[#e2231a]/10 text-[#ffb4a9] border border-[#e2231a]/30">
        <span class="material-symbols-outlined" style="font-size: 16px;">cloud_upload</span>
        <span><span x-text="outbox"></span> item antrian offline</span>
    </div>
</div>

<div class="bg-surface-container-high rounded-2xl border border-outline-variant max-w-4xl">
    <form action="{{ route('spv.pemantauan.store') }}" method="POST" enctype="multipart/form-data" data-offline-form data-sync-tag="pemantauan-sync" class="p-6 md:p-8" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-input
                name="tanggal"
                type="date"
                label="Tanggal"
                :value="date('Y-m-d')"
                :required="true"
            />

            <x-select-dropdown
                name="shift"
                label="Shift Kerja"
                :options="['siang' => 'Shift Siang', 'malam' => 'Shift Malam']"
                :required="true"
            />

            <x-select-dropdown
                name="area_id"
                label="Area Lokasi"
                :options="$areas"
                :required="true"
            />

            <x-select-dropdown
                name="alat_id"
                label="Alat Utama di Area"
                :options="$alats"
                :required="true"
            />
        </div>

        <div class="mt-6">
            <label for="deskripsi" class="block text-sm font-bold text-on-surface mb-1.5">Deskripsi Kondisi Lapangan <span class="text-error">*</span></label>
            <textarea id="deskripsi" name="deskripsi" rows="3" required class="w-full rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-4 py-2.5 transition-colors">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="mt-1.5 text-sm text-error flex items-center">
                    <span class="material-symbols-outlined text-base mr-1">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mt-4">
            <label for="kendala" class="block text-sm font-bold text-on-surface mb-1.5">Kendala / Masalah (Opsional)</label>
            <textarea id="kendala" name="kendala" rows="2" class="w-full rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-4 py-2.5 transition-colors">{{ old('kendala') }}</textarea>
        </div>

        <div class="mt-6">
            <x-progress-input />
        </div>

        <div class="mt-6">
            <x-photo-uploader name="foto[]" label="Upload Foto Lapangan (Wajib)" :required="true" :multiple="true" />
        </div>

        <div class="mt-8 pt-6 border-t border-outline-variant flex justify-end gap-3">
            <a href="{{ route('spv.pemantauan.index') }}" class="inline-flex items-center px-5 py-2.5 border border-outline-variant text-on-surface rounded-xl text-sm font-bold hover:bg-surface-variant transition-all">Batal</a>
            <button type="submit"
                class="inline-flex items-center px-6 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm"
                x-bind:class="{ 'opacity-75 cursor-not-allowed': loading }"
                x-bind:disabled="loading"
            >
                <span x-show="!loading" class="flex items-center">
                    <span class="material-symbols-outlined text-lg mr-1.5">save</span>
                    Simpan Laporan
                </span>
                <span x-show="loading" x-cloak class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-on-primary-container" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
@endsection
