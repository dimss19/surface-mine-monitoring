@extends('layouts.app', ['headerTitle' => 'Form Input Utilization'])

@section('title', 'Form Input Utilization')

@section('content')

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm mb-6">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Nomor Unit</label>
                <select name="unit_id" id="unit_id" class="form-input" required>
                    <option value="">Pilih Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" data-status="{{ $latestStatus->get($unit->id, '') }}">{{ $unit->kode }}</option>
                    @endforeach
                </select>
                <p id="statusHint" class="mt-2 text sm text-slate-500"></p>
            </div>
            <div>
                <label class="form-label">Status</label>
                <div class="flex gap-4 mt-2">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="breakdown" required>
                        <span class="text-red-600 font-medium">Breakdown</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="servis" required>
                        <span class="text-amber-600 font-medium">Servis</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="ready" required>
                        <span class="text-green-600 font-medium">Ready</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Tanggal / Jam Mulai</label>
                <input type="datetime-local" name="started_at" class="form-input" id="startedAt">
            </div>
            <div>
                <label class="form-label">Tanggal / Jam Selesai</label>
                <input type="datetime-local" name="ended_at" class="form-input" id="endedAt">
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
        'servis': 'Status saat ini: Servis (perbaikan)',
        'ready': 'Status saat ini: Ready (operasional)',
    };
    select.addEventListener('change', function() {
        const s = this.options[this.selectedIndex]?.dataset.status || '';
        hint.textContent = statuses[s] || '';
    });
    document.getElementById('startedAt').value = new Date().toISOString().slice(0,16);
});
</script>
@endpush
@endsection
