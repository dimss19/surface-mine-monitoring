{{-- resources/views/operator/ritasi/create.blade.php --}}
@extends('layouts.operator')

@section('title', 'Form Input Unit Ritasi')
@section('page-title', 'Form Input Unit Ritasi')

@section('content')
{{-- Session Info --}}
<div class="bg-[var(--primary)] text-white rounded-lg p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-semibold">Sesi Aktif</p>
            <p class="text-sm text-slate-300">Silakan isi data ritasi operasional harian. Pastikan durasi HM sesuai (6 - 11 Jam).</p>
        </div>
    </div>
</div>

<form action="{{ route('pegawai.ritasi.store') }}" method="POST" data-offline-form data-sync-tag="ritasi-sync">
    @csrf
    
    <div class="card p-6">
        {{-- Data Dasar --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">description</span>
            Data Dasar
        </h2>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="form-label">Shift</label>
                <select name="shift" class="form-input" required>
                    <option value="">Pilih Shift</option>
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
                    <option value="">Pilih Unit</option>
                    @foreach($units as $id => $kode)
                        <option value="{{ $id }}">{{ $kode }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Hour Meter --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">timer</span>
            Hour Meter (HM)
        </h2>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="form-label">HM Awal</label>
                <input type="number" name="hm_awal" class="form-input" step="0.1" min="0" value="0.0" required id="hmAwal">
            </div>
            <div>
                <label class="form-label">HM Akhir</label>
                <input type="number" name="hm_akhir" class="form-input" step="0.1" min="0" value="0.0" required id="hmAkhir">
            </div>
            <div class="bg-slate-100 rounded-lg p-4 flex items-center justify-between">
                <span class="text-sm font-medium">Total Durasi HM:</span>
                <span class="text-xl font-bold text-[var(--primary)]" id="hmTotal">0.0 Jam</span>
            </div>
        </div>
        
        {{-- Detail Pekerjaan --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">work</span>
            Detail Pekerjaan
        </h2>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="form-label">Jenis Material</label>
                <select name="material_id" class="form-input" required>
                    <option value="">Pilih Material</option>
                    @foreach($materials as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Jumlah Ritasi (Trip)</label>
                <input type="number" name="jumlah_ritasi" class="form-input" min="0" value="0" required>
            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hmAwal = document.getElementById('hmAwal');
    const hmAkhir = document.getElementById('hmAkhir');
    const hmTotal = document.getElementById('hmTotal');
    
    function updateTotal() {
        const awal = parseFloat(hmAwal.value) || 0;
        const akhir = parseFloat(hmAkhir.value) || 0;
        const total = akhir - awal;
        hmTotal.textContent = total.toFixed(1) + ' Jam';
    }
    
    hmAwal.addEventListener('input', updateTotal);
    hmAkhir.addEventListener('input', updateTotal);
});
</script>
@endpush
@endsection