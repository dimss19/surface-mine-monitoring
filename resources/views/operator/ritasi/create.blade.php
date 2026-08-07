@extends('layouts.app', ['headerTitle' => 'Form Input Unit Ritasi'])

@section('title', 'Form Input Unit Ritasi')

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
                <label class="form-label">Nomor Unit (Dump Truck)</label>
                <select name="unit_id" id="unitSelect" class="form-input" required>
                    <option value="">Contoh: DT-1042</option>
                    @foreach($units as $id => $kode)
                        <option value="{{ $id }}" data-status="{{ $latestStatus[$id] ?? '' }}">{{ $kode }}</option>
                    @endforeach
                </select>
                <p id="unitStatusHint" class="mt-1 text-xs text-slate-500"></p>
            </div>
        </div>
        
        {{-- Hour Meter --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">timer</span>
            Hour Meter (HM)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
                <span class="text-xl font-bold" style="color: var(--text);" id="hmTotal">0.0 Jam</span>
            </div>
        </div>

        {{-- Produksi --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">scale</span>
            Produksi
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" step="0.01" min="0" class="form-input" value="0.00" placeholder="0.00">
            </div>
            <div>
                <label class="form-label">Satuan</label>
                <select name="quantity_unit" class="form-input">
                    <option value="ton">Ton (default)</option>
                    <option value="cbm">CBM</option>
                    <option value="m3">M3</option>
                </select>
            </div>
        </div>

        {{-- Fuel Consumption --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">local_gas_station</span>
            Konsumsi Fuel
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="form-label">Konsumsi Fuel (Liter)</label>
                <input type="number" 
                       name="fuel_consumption" 
                       step="0.01" 
                       min="0"
                       class="form-input" 
                       placeholder="0.00"
                       value="{{ old('fuel_consumption') }}">
            </div>
        </div>
        
        {{-- Detail Pekerjaan --}}
        <h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
            <span class="material-symbols-outlined text-blue-500">work</span>
            Detail Pekerjaan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="form-label">Jenis Material</label>
                <select name="material_id" class="form-input" required>
                    <option value="">Overburden (OB)</option>
                    @foreach($materials as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Area</label>
                <select name="area_id" class="form-input" required>
                    <option value="">Pilih Area</option>
                    @foreach($areas as $id => $nama)
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

    // Color units in maintenance (breakdown/servis) red in dropdown
    const unitSelect = document.getElementById('unitSelect');
    const statusHint = document.getElementById('unitStatusHint');
    if (unitSelect) {
        const statusLabels = {
            'breakdown': 'Breakdown (rusak)',
            'servis': 'Servis (perbaikan)',
            'ready': 'Ready (operasional)',
        };
        Array.from(unitSelect.options).forEach(function(opt) {
            const status = opt.dataset.status;
            if (status === 'breakdown' || status === 'servis') {
                opt.style.color = '#dc2626';
                opt.style.fontWeight = 'bold';
            }
        });
        unitSelect.addEventListener('change', function() {
            const status = this.options[this.selectedIndex]?.dataset.status || '';
            statusHint.textContent = status ? 'Status: ' + (statusLabels[status] || status) : '';
        });
    }
});
</script>
@endpush
@endsection
