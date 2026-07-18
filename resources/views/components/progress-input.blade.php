@props(['persenName' => 'progress_persen', 'statusName' => 'progress_status', 'persenValue' => 0, 'statusValue' => 'belum_mulai'])

<div class="bg-surface-container rounded-xl p-6 border border-outline-variant">
    <h4 class="text-sm font-bold text-on-surface mb-4 flex items-center">
        <span class="material-symbols-outlined text-lg mr-1.5 text-primary">progress_activity</span>
        Progress Pekerjaan
    </h4>

    <div class="mb-4" x-data="{ persen: {{ old($persenName, $persenValue) }} }">
        <label for="{{ $persenName }}" class="block text-sm font-medium text-on-surface mb-1">
            Persentase Selesai: <span x-text="persen + '%'" class="text-primary font-bold"></span>
        </label>
        <input
            type="range"
            name="{{ $persenName }}"
            id="{{ $persenName }}"
            min="0" max="100"
            x-model="persen"
            class="w-full h-2 bg-surface-variant rounded-lg appearance-none cursor-pointer accent-primary"
        >
        @error($persenName)
            <p class="mt-1.5 text-sm text-error flex items-center">
                <span class="material-symbols-outlined text-base mr-1">error</span>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="{{ $statusName }}" class="block text-sm font-medium text-on-surface mb-1">
            Status Kategori <span class="text-error">*</span>
        </label>
        <select
            name="{{ $statusName }}"
            id="{{ $statusName }}"
            required
            class="block w-full rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-4 py-2.5 transition-colors"
        >
            <option value="belum_mulai" {{ old($statusName, $statusValue) == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
            <option value="proses" {{ old($statusName, $statusValue) == 'proses' ? 'selected' : '' }}>Proses</option>
            <option value="selesai" {{ old($statusName, $statusValue) == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        @error($statusName)
            <p class="mt-1.5 text-sm text-error flex items-center">
                <span class="material-symbols-outlined text-base mr-1">error</span>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
