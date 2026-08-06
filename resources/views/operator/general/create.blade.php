@extends('layouts.app')

@section('title', 'Form Pekerjaan General')

@section('content')
<h1 class="text-2xl font-heading font-bold text-[var(--primary)] mb-4">Form Pekerjaan General</h1>

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm mb-6">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Session Info --}}
<div class="bg-[var(--primary)] text-white rounded-lg p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-semibold">Sesi Aktif</p>
            <p class="text-sm text-slate-300">Silakan isi data pekerjaan general harian. Pastikan durasi Jam Kerja sesuai (6 - 11 Jam).</p>
        </div>
    </div>
</div>

<form action="{{ route('pegawai.general.store') }}" method="POST" data-offline-form data-sync-tag="general-sync">
    @csrf
    
    <div class="card p-6">
        {{-- Data Dasar --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">description</span>
            Data Dasar
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Shift</label>
                <select name="shift" class="form-input" required>
                    <option value="">Contoh: Siang</option>
                    <option value="siang">Siang</option>
                    <option value="malam">Malam</option>
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="form-label">Nama Operator</label>
                <input type="text" class="form-input bg-slate-50" value="{{ $pegawai->nama ?? Auth::user()->name }}" readonly>
            </div>
            <div>
                <label class="form-label">Nomor Unit (Dump Truck)</label>
                <select name="unit_id" class="form-input" required>
                    <option value="">Contoh: DT-1042</option>
                    @foreach($units as $id => $kode)
                        <option value="{{ $id }}">{{ $kode }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Supervisor</label>
                <input type="text" name="supervisor" class="form-input" placeholder="Nama Supervisor">
            </div>
            <div>
                <label class="form-label">Senior SPV</label>
                <input type="text" name="senior_spv" class="form-input" placeholder="Nama Sr. SPV">
            </div>
        </div>
        
        {{-- Jam Kerja --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">schedule</span>
            Jam Kerja
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Jam Mulai <span class="text-red-500">*</span></label>
                <input type="time" name="jam_mulai" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Jam Akhir <span class="text-red-500">*</span></label>
                <input type="time" name="jam_selesai" class="form-input" required>
            </div>
            <div class="col-span-2">
                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg">
                    <span class="material-symbols-outlined text-slate-600">timer</span>
                    <div class="flex-1">
                        <p class="font-medium" style="color: var(--text);">Status Overtime</p>
                        <p class="text-sm text-slate-500">Aktifkan jika pekerjaan melewati batas jam reguler</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_overtime" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>
            </div>
        </div>
        
        {{-- Detail Pekerjaan --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">work</span>
            Detail Pekerjaan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="form-label">Lokasi Pekerjaan (Pit / Disposal)</label>
                <input type="text" name="lokasi_pekerjaan" class="form-input" placeholder="Contoh: Pit 1 North">
            </div>
            <div class="col-span-2">
                <label class="form-label">Deskripsi Pekerjaan / Kendala (Opsional)</label>
                <textarea name="deskripsi_pekerjaan" class="form-input" rows="3" placeholder="Tambahkan catatan khusus bila ada kendala operasional..."></textarea>
            </div>
        </div>
        
        <input type="hidden" name="area_id" value="{{ $areas[array_key_first($areas)] ?? 1 }}">
        
        {{-- Buttons --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button type="reset" class="btn-secondary">Reset</button>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Simpan Data Ritasi
            </button>
        </div>
    </div>
</form>
@endsection
