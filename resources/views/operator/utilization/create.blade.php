@extends('layouts.app')

@section('title', 'Form Input Utilization')
@section('page-title', 'Form Input Utilization')

@section('content')
<div class="bg-[var(--primary)] text-white rounded-lg p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-semibold">Sesi Aktif</p>
            <p class="text-sm text-slate-300">Catat kondisi unit: pilih Breakdown jika unit rusak, atau Servis jika unit telah ditangani.</p>
        </div>
    </div>
</div>

<form action="{{ route('pegawai.utilization.store') }}" method="POST" data-offline-form data-sync-tag="utilization-sync">
    @csrf

    <div class="card p-6">
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">build</span>
            Data Utilization
        </h2>
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Nomor Unit</label>
                <select name="unit_id" id="unit_id" class="form-input" required>
                    <option value="">Pilih Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" data-status="{{ $latestStatus->get($unit->id, '') }}">{{ $unit->kode }}</option>
                    @endforeach
                </select>
                <p id="statusHint" class="mt-2 text-sm text-slate-500"></p>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="form-label">Tipe</label>
                <div class="flex gap-4 mt-2">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipe" value="breakdown" required>
                        <span class="badge-breakdown px-3 py-1 rounded-full text-sm">Breakdown</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipe" value="servis" required>
                        <span class="badge-maintenance px-3 py-1 rounded-full text-sm">Servis</span>
                    </label>
                </div>
            </div>
        </div>

        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">description</span>
            Keterangan
        </h2>
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div>
                <label class="form-label">Deskripsi / Kerusakan</label>
                <textarea name="deskripsi" class="form-input" rows="3" placeholder="Contoh: Hidrolik bocor di lengan utama..."></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <button type="reset" class="btn-secondary">Reset</button>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Simpan Data Utilization
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('unit_id');
    const hint = document.getElementById('statusHint');
    const statuses = {
        'breakdown': 'Status saat ini: Breakdown (rusak)',
        'servis': 'Status saat ini: Servis (aktif)',
    };
    select.addEventListener('change', function() {
        const s = this.options[this.selectedIndex]?.dataset.status || '';
        hint.textContent = statuses[s] || '';
    });
});
</script>
@endpush
@endsection
