@extends('layouts.dashboard')

@section('title', '- Form Rekapan Operator')
@section('page_title', 'Rekapan Operator')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-surface-container-high rounded-2xl p-6 sm:p-10 border border-outline-variant">
        <div class="bg-primary-container/20 border border-primary-container/40 rounded-xl p-4 mb-8 flex gap-3 items-start">
            <span class="material-symbols-outlined text-primary shrink-0" style="font-variation-settings: 'FILL' 1;">info</span>
            <p class="text-sm text-on-surface leading-relaxed">Rekapan atas nama <strong class="text-primary">{{ auth()->user()->name }}</strong>. Jika offline, data disimpan lokal & dikirim otomatis saat online.</p>
        </div>

        <form id="rekapanForm" action="{{ route('pegawai.rekapan.store') }}" method="POST"
              data-offline-form data-sync-tag="rekapan-sync"
              class="space-y-8"
              x-data="{
                  loading: false,
                  hm_awal_jam: '',
                  hm_awal_menit: '',
                  hm_akhir_jam: '',
                  hm_akhir_menit: '',
                  get hm_awal() {
                      const jam = parseFloat(this.hm_awal_jam) || 0;
                      const menit = parseFloat(this.hm_awal_menit) || 0;
                      return jam + menit / 60;
                  },
                  get hm_akhir() {
                      const jam = parseFloat(this.hm_akhir_jam) || 0;
                      const menit = parseFloat(this.hm_akhir_menit) || 0;
                      return jam + menit / 60;
                  },
                  get errorHm() {
                      if(this.hm_awal_jam === '' || this.hm_akhir_jam === '') return false;
                      return this.hm_akhir <= this.hm_awal;
                  },
                  validateForm(e) {
                      if (this.errorHm) {
                          e.preventDefault();
                          e.stopImmediatePropagation();
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">1</span>
                        Tanggal <span class="text-error ml-0.5">*</span>
                    </label>
                    <input name="tanggal" type="date" value="{{ old('tanggal', date('Y-m-d')) }}" required
                           class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface placeholder:text-on-surface-variant/50">
                    @error('tanggal')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">2</span>
                        Shift <span class="text-error ml-0.5">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input class="text-primary focus:ring-primary" name="shift" type="radio" value="siang" required {{ old('shift') == 'siang' ? 'checked' : '' }}>
                            <span class="text-sm text-on-surface">Siang</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input class="text-primary focus:ring-primary" name="shift" type="radio" value="malam" required {{ old('shift') == 'malam' ? 'checked' : '' }}>
                            <span class="text-sm text-on-surface">Malam</span>
                        </label>
                    </div>
                    @error('shift')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                    <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">3</span>
                    Type Unit / Pekerjaan <span class="text-error ml-0.5">*</span>
                </label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="text-primary focus:ring-primary" name="tipe_pekerjaan" type="radio" value="unit_non_ritasi" required {{ old('tipe_pekerjaan') == 'unit_non_ritasi' ? 'checked' : '' }}>
                        <span class="text-sm text-on-surface">Unit Non Ritasi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="text-primary focus:ring-primary" name="tipe_pekerjaan" type="radio" value="unit_ritasi" required {{ old('tipe_pekerjaan') == 'unit_ritasi' ? 'checked' : '' }}>
                        <span class="text-sm text-on-surface">Unit Ritasi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="text-primary focus:ring-primary" name="tipe_pekerjaan" type="radio" value="pekerjaan_general" required {{ old('tipe_pekerjaan') == 'pekerjaan_general' ? 'checked' : '' }}>
                        <span class="text-sm text-on-surface">Pekerjaan General</span>
                    </label>
                </div>
                @error('tipe_pekerjaan')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">4</span>
                        Area Lokasi <span class="text-error ml-0.5">*</span>
                    </label>
                    <select name="area_id" required class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface">
                        <option value="" disabled {{ old('area_id') ? '' : 'selected' }}>Pilih area lokasi</option>
                        @foreach($areaOptions as $val => $text)
                            <option value="{{ $val }}" {{ old('area_id') == $val ? 'selected' : '' }}>{{ $text }}</option>
                        @endforeach
                    </select>
                    @error('area_id')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">5</span>
                        Alat yang Digunakan <span class="text-error ml-0.5">*</span>
                    </label>
                    <select name="alat_id" required class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface">
                        <option value="" disabled {{ old('alat_id') ? '' : 'selected' }}>Pilih alat berat</option>
                        @foreach($alatOptions as $val => $text)
                            <option value="{{ $val }}" {{ old('alat_id') == $val ? 'selected' : '' }}>{{ $text }}</option>
                        @endforeach
                    </select>
                    @error('alat_id')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">6</span>
                        HM Awal <span class="text-error ml-0.5">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input x-model="hm_awal_jam" type="number" min="0" max="23" required
                               x-on:input="if (parseFloat($event.target.value) > 23) $event.target.value = 23"
                               class="w-2/3 bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface text-center text-lg"
                               placeholder="Jam">
                        <span class="text-2xl font-bold text-on-surface-variant">:</span>
                        <input x-model="hm_awal_menit" type="number" min="0" max="59"
                               x-on:input="if (parseFloat($event.target.value) > 59) $event.target.value = 59"
                               class="w-1/3 bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface text-center text-lg"
                               placeholder="Menit">
                        <input type="hidden" name="hm_awal" x-bind:value="hm_awal.toFixed(2)">
                    </div>
                    @error('hm_awal')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">7</span>
                        HM Akhir <span class="text-error ml-0.5">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input x-model="hm_akhir_jam" type="number" min="0" max="23" required
                               x-on:input="if (parseFloat($event.target.value) > 23) $event.target.value = 23"
                               class="w-2/3 bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface text-center text-lg"
                               :class="errorHm ? 'border-error' : ''"
                               placeholder="Jam">
                        <span class="text-2xl font-bold text-on-surface-variant">:</span>
                        <input x-model="hm_akhir_menit" type="number" min="0" max="59"
                               x-on:input="if (parseFloat($event.target.value) > 59) $event.target.value = 59"
                               class="w-1/3 bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface text-center text-lg"
                               placeholder="Menit">
                        <input type="hidden" name="hm_akhir" x-bind:value="hm_akhir.toFixed(2)">
                    </div>
                    <p x-show="errorHm" x-cloak class="mt-1 text-xs text-error">HM Akhir harus lebih besar dari HM Awal!</p>
                    @error('hm_akhir')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                    <span class="bg-primary text-white w-5 h-5 rounded flex items-center justify-center text-[11px] font-bold shrink-0">8</span>
                    Deskripsi Pekerjaan
                </label>
                <textarea name="deskripsi_pekerjaan" class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface placeholder:text-on-surface-variant/50" placeholder="Tuliskan catatan tambahan jika ada..." rows="4">{{ old('deskripsi_pekerjaan') }}</textarea>
                @error('deskripsi_pekerjaan')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 border-t border-outline-variant flex items-center gap-4">
                <button type="submit"
                    class="bg-primary text-on-primary font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-3 shadow-lg shadow-primary/30 hover:opacity-90 active:scale-95"
                    x-bind:class="{ 'opacity-70 cursor-not-allowed': loading || errorHm }"
                    x-bind:disabled="loading || errorHm"
                >
                    <span x-show="!loading" class="flex items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">send</span>
                        Submit
                    </span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Submitting...
                    </span>
                </button>
                <a href="{{ route('pegawai.index') }}" class="text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
