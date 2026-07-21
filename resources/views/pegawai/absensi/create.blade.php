@extends('layouts.app')

@section('title', '- Form Absensi')

@section('content')
<style data-purpose="custom-layout">
    body {
      background-color: #111416 !important;
      background-image: none !important;
    }
    .form-card {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>

<main class="flex-grow container mx-auto px-4 py-4 max-w-7xl">
    <div class="mb-8" data-purpose="page-hero">
        <span class="text-red-500 text-xs font-bold uppercase tracking-widest">Form Input</span>
        <h1 class="text-3xl md:text-4xl font-extrabold mt-2 mb-3 text-white">
            Civil Mining <span class="text-red-600">Surface</span>
        </h1>
        <p class="text-gray-400 text-base">Input data HM unit, Ritasi, dan Pekerjaan General</p>
    </div>

    <div class="flex items-center justify-between gap-3 flex-wrap mb-6"
         x-data="{ online: navigator.onLine, outbox: window.outboxCount || 0 }"
         x-init="window.addEventListener('online', () => online = true); window.addEventListener('offline', () => online = false); window.addEventListener('outbox:changed', e => outbox = e.detail)">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border"
             :class="online ? 'bg-green-500/10 text-green-400 border-green-500/30' : 'bg-amber-500/10 text-amber-400 border-amber-500/30'">
            <span class="w-2 h-2 rounded-full" :class="online ? 'bg-green-400 animate-pulse' : 'bg-amber-400'"></span>
            <span x-text="online ? 'Online' : 'Offline'"></span>
        </div>
        <div x-show="outbox > 0" x-cloak
             class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/30">
            <span class="material-symbols-outlined" style="font-size: 16px;">cloud_upload</span>
            <span><span x-text="outbox"></span> item antrian offline</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <section class="bg-white rounded-2xl p-8 md:p-12 text-zinc-800 form-card lg:col-span-10 lg:col-start-2" data-purpose="main-form-container">
            <div class="bg-red-50 border border-red-100 rounded-lg p-4 mb-10 flex gap-4">
                <div class="bg-red-500 rounded-full h-5 w-5 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="text-white text-xs font-bold">i</span>
                </div>
                <p class="text-sm text-zinc-600 leading-relaxed">Absensi tercatat atas nama <strong>{{ auth()->user()->name }}</strong>. Jika offline, data disimpan lokal & dikirim otomatis saat online.</p>
            </div>

            <div class="mb-8">
                <span class="text-red-600 text-sm font-semibold">* Required</span>
            </div>

            <form id="absensiForm" action="{{ route('pegawai.absensi.store') }}" method="POST"
                  data-offline-form data-sync-tag="absensi-sync"
                  class="space-y-10"
                  x-data="{
                      loading: false,
                      hm_awal: '',
                      hm_akhir: '',
                      get errorHm() {
                          if(this.hm_awal === '' || this.hm_akhir === '') return false;
                          return parseFloat(this.hm_akhir) <= parseFloat(this.hm_awal);
                      },
                      validateForm(e) {
                          if (this.errorHm) {
                              e.preventDefault();
                              alert('HM Akhir harus lebih besar dari HM Awal');
                          } else {
                              this.loading = true;
                          }
                      }
                  }"
                  @submit="validateForm($event)"
            >
                @csrf
                <input type="hidden" name="pegawai_id" value="{{ auth()->user()->pegawai_id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 font-bold text-zinc-900">
                            <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">1</span>
                            Tanggal <span class="text-red-600 ml-1">*</span>
                        </label>
                        <input name="tanggal" class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 p-3 text-gray-700" type="date" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-3 font-bold text-zinc-900">
                            <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">2</span>
                            Shift <span class="text-red-600 ml-1">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input class="text-red-600 focus:ring-red-500" name="shift" type="radio" value="siang" required {{ old('shift') == 'siang' ? 'checked' : '' }}>
                                <span class="text-zinc-700">Siang</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input class="text-red-600 focus:ring-red-500" name="shift" type="radio" value="malam" required {{ old('shift') == 'malam' ? 'checked' : '' }}>
                                <span class="text-zinc-700">Malam</span>
                            </label>
                        </div>
                        @error('shift')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center gap-3 font-bold text-zinc-900">
                        <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">3</span>
                        Type Unit / Pekerjaan <span class="text-red-600 ml-1">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="text-red-600 focus:ring-red-500" name="tipe_pekerjaan" type="radio" value="unit_non_ritasi" required {{ old('tipe_pekerjaan') == 'unit_non_ritasi' ? 'checked' : '' }}>
                            <span class="text-zinc-700">Unit Non Ritasi</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="text-red-600 focus:ring-red-500" name="tipe_pekerjaan" type="radio" value="unit_ritasi" required {{ old('tipe_pekerjaan') == 'unit_ritasi' ? 'checked' : '' }}>
                            <span class="text-zinc-700">Unit Ritasi</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="text-red-600 focus:ring-red-500" name="tipe_pekerjaan" type="radio" value="pekerjaan_general" required {{ old('tipe_pekerjaan') == 'pekerjaan_general' ? 'checked' : '' }}>
                            <span class="text-zinc-700">Pekerjaan General</span>
                        </label>
                    </div>
                    @error('tipe_pekerjaan')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 font-bold text-zinc-900">
                            <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">4</span>
                            Area Lokasi <span class="text-red-600 ml-1">*</span>
                        </label>
                        <select name="area_id" required class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 p-3 text-gray-500 bg-white">
                            <option value="" disabled {{ old('area_id') ? '' : 'selected' }}>Pilih area lokasi</option>
                            @foreach($areaOptions as $val => $text)
                                <option value="{{ $val }}" {{ old('area_id') == $val ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                        @error('area_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-3 font-bold text-zinc-900">
                            <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">5</span>
                            Alat yang Digunakan <span class="text-red-600 ml-1">*</span>
                        </label>
                        <select name="alat_id" required class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 p-3 text-gray-500 bg-white">
                            <option value="" disabled {{ old('alat_id') ? '' : 'selected' }}>Pilih alat berat</option>
                            @foreach($alatOptions as $val => $text)
                                <option value="{{ $val }}" {{ old('alat_id') == $val ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                        @error('alat_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 font-bold text-zinc-900">
                            <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">6</span>
                            HM Awal <span class="text-red-600 ml-1">*</span>
                        </label>
                        <input name="hm_awal" x-model="hm_awal" type="number" step="0.01" min="0" required class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 p-3 text-gray-700" placeholder="Contoh: 100.23">
                        @error('hm_awal')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-3 font-bold text-zinc-900">
                            <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">7</span>
                            HM Akhir <span class="text-red-600 ml-1">*</span>
                        </label>
                        <input name="hm_akhir" x-model="hm_akhir" type="number" step="0.01" min="0" required class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 p-3 text-gray-700" :class="errorHm ? 'border-red-500' : ''" placeholder="Contoh: 100.28">
                        <p x-show="errorHm" x-cloak class="mt-2 text-sm text-red-500">HM Akhir harus lebih besar dari HM Awal!</p>
                        @error('hm_akhir')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center gap-3 font-bold text-zinc-900">
                        <span class="bg-red-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs">8</span>
                        Deskripsi Pekerjaan
                    </label>
                    <textarea name="deskripsi_pekerjaan" class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 p-3 text-gray-700" placeholder="Tuliskan catatan tambahan jika ada..." rows="4">{{ old('deskripsi_pekerjaan') }}</textarea>
                    @error('deskripsi_pekerjaan')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="pt-6 border-t border-zinc-100 flex flex-col items-start gap-4">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-10 rounded-xl transition-all flex items-center gap-3 shadow-lg shadow-red-500/20 active:scale-95"
                        x-bind:class="{ 'opacity-70 cursor-not-allowed': loading || errorHm }"
                        x-bind:disabled="loading || errorHm"
                    >
                        <span x-show="!loading" class="flex items-center gap-3">
                            <svg class="h-5 w-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Submit
                        </span>
                        <span x-show="loading" x-cloak class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Submitting...
                        </span>
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection
