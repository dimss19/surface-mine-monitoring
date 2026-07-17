@props(['persenName' => 'progress_persen', 'statusName' => 'progress_status', 'persenValue' => 0, 'statusValue' => 'belum_mulai'])

<div class="mb-4 bg-gray-50 p-4 rounded-md border border-gray-200">
    <h4 class="text-sm font-medium text-gray-700 mb-4">Progress Pekerjaan</h4>
    
    <div class="mb-4" x-data="{ persen: {{ old($persenName, $persenValue) }} }">
        <label for="{{ $persenName }}" class="block text-sm font-medium text-gray-700 mb-1">
            Persentase Selesai: <span x-text="persen + '%'" class="text-blue-600 font-bold"></span>
        </label>
        <input 
            type="range" 
            name="{{ $persenName }}" 
            id="{{ $persenName }}" 
            min="0" max="100" 
            x-model="persen"
            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
        >
        @error($persenName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $statusName }}" class="block text-sm font-medium text-gray-700 mb-1">
            Status Kategori <span class="text-red-500">*</span>
        </label>
        <select 
            name="{{ $statusName }}" 
            id="{{ $statusName }}" 
            required
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
        >
            <option value="belum_mulai" {{ old($statusName, $statusValue) == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
            <option value="proses" {{ old($statusName, $statusValue) == 'proses' ? 'selected' : '' }}>Proses</option>
            <option value="selesai" {{ old($statusName, $statusValue) == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        @error($statusName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>