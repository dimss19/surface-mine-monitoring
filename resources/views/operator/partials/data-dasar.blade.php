@props(['units', 'latestStatus', 'showSupervisor' => false])
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
    @if($showSupervisor)
        <div>
            <label class="form-label">Supervisor</label>
            <input type="text" name="supervisor" class="form-input" placeholder="Nama Supervisor" value="{{ old('supervisor') }}">
        </div>
        <div>
            <label class="form-label">Senior SPV</label>
            <input type="text" name="senior_spv" class="form-input" placeholder="Nama Sr. SPV" value="{{ old('senior_spv') }}">
        </div>
    @endif
</div>
