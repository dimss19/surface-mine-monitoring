@extends('layouts.app', ['headerTitle' => 'Form Input Unit Ritasi'])

@section('title', 'Form Input Unit Ritasi')

@section('content')

@include('operator.partials.validation-errors')

@include('operator.partials.session-info', ['description' => 'Silakan isi data ritasi operasional harian. Pastikan durasi HM sesuai (6 - 11 Jam).'])

<form action="{{ route('pegawai.ritasi.store') }}" method="POST" data-offline-form data-sync-tag="ritasi-sync">
    @csrf
    
    <div class="card p-6">
        {{-- Data Dasar --}}
        @include('operator.partials.data-dasar', ['units' => $units, 'latestStatus' => $latestStatus])
        
        {{-- Hour Meter --}}
        @include('operator.partials.hour-meter')

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
        @include('operator.partials.fuel-consumption')
        
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
@include('operator.partials.unit-status-script', ['withHmCalculator' => true])
@endpush
@endsection
