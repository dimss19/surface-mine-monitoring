@props([
    'units' => [],
    'latestStatus' => [],
    'showSupervisor' => false,
    'showUnit' => true,
    'spvs' => [],
])
<h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
    <span class="material-symbols-outlined text-[var(--primary)]">description</span>
    Data Dasar
</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div>
        <label class="form-label">Shift <span class="text-red-500">*</span></label>
        <select name="shift" class="form-input" required>
            <option value="">Contoh: Siang</option>
            <option value="siang" {{ old('shift') == 'siang' ? 'selected' : '' }}>Siang</option>
            <option value="malam" {{ old('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
        </select>
    </div>
    <div>
        <label class="form-label">Tanggal <span class="text-red-500">*</span></label>
        <input type="date" name="tanggal" class="form-input" value="{{ old('tanggal', date('Y-m-d')) }}" required>
    </div>
    @if($showUnit)
    <div>
        <label class="form-label">Nomor Unit (Dump Truck) <span class="text-red-500">*</span></label>
        <select name="unit_id" id="unitSelect" class="form-input" required>
            <option value="">Contoh: DT-1042</option>
            @foreach($units as $id => $kode)
                @php
                    $status = $latestStatus[$id] ?? 'ready';
                    $inMaintenance = in_array($status, ['breakdown', 'servis']);
                @endphp
                <option value="{{ $id }}" 
                        data-status="{{ $status }}"
                        {{ $inMaintenance ? 'disabled style=color:#dc2626;font-weight:bold;' : '' }}
                        {{ old('unit_id') == $id ? 'selected' : '' }}>
                    {{ $kode }} {{ $inMaintenance ? '(Sedang Maintenance - Tidak Bisa Digunakan)' : '' }}
                </option>
            @endforeach
        </select>
        <p id="unitStatusHint" class="mt-1.5 text-sm font-semibold text-slate-500"></p>
    </div>
    @endif
    @if($showSupervisor)
        <div>
            <label class="form-label">Supervisor <span class="text-red-500">*</span></label>
            <select name="supervisor_id" class="form-input" required>
                <option value="">Pilih Supervisor</option>
                @foreach($spvs as $spv)
                    <option value="{{ $spv->id }}" {{ old('supervisor_id') == $spv->id ? 'selected' : '' }}>
                        {{ $spv->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1.5 text-xs sm:text-sm text-slate-500">Pilih supervisor operasional shift ini.</p>
        </div>
        <div>
            <label class="form-label">Senior SPV</label>
            <select name="senior_spv_id" class="form-input">
                <option value="">Pilih Senior SPV (Opsional)</option>
                @foreach($spvs as $spv)
                    <option value="{{ $spv->id }}" {{ old('senior_spv_id') == $spv->id ? 'selected' : '' }}>
                        {{ $spv->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1.5 text-xs sm:text-sm text-slate-500">Pilih senior supervisor shift ini (bila ada).</p>
        </div>
    @endif
</div>
