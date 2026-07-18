@props(['name' => 'foto[]', 'label' => 'Upload Foto', 'required' => false, 'multiple' => true])

<div class="mb-4" x-data="{ files: [] }">
    <label class="block text-sm font-bold text-on-surface mb-1">
        {{ $label }} @if($required) <span class="text-error">*</span> @endif
    </label>

    <div class="flex gap-3 mb-3">
        <label for="gallery-{{ $name }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-outline-variant rounded-xl bg-surface-container hover:bg-surface-variant cursor-pointer transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant">add_photo_alternate</span>
            <span class="text-sm font-medium text-on-surface-variant">Pilih dari Galeri</span>
        </label>
        <label for="camera-{{ $name }}" class="flex items-center justify-center gap-2 px-4 py-3 border-2 border-outline-variant rounded-xl bg-surface-container hover:bg-surface-variant cursor-pointer transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant">photo_camera</span>
            <span class="text-sm font-medium text-on-surface-variant">Kamera</span>
        </label>
    </div>

    <input id="gallery-{{ $name }}" name="{{ $name }}" type="file" class="hidden"
        {{ $multiple ? 'multiple' : '' }}
        accept="image/*"
        {{ $required ? 'required' : '' }}
        x-on:change="files = Object.values($event.target.files).map(file => URL.createObjectURL(file))"
    >

    <input id="camera-{{ $name }}" name="{{ $name }}" type="file" class="hidden"
        {{ $multiple ? 'multiple' : '' }}
        accept="image/*"
        capture="environment"
        x-on:change="files = Object.values($event.target.files).map(file => URL.createObjectURL(file))"
    >

    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-outline-variant border-dashed rounded-xl bg-surface-container transition-colors" x-show="files.length === 0" x-cloak>
        <div class="space-y-1 text-center">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant">add_photo_alternate</span>
            <p class="text-sm text-on-surface-variant">Klik "Pilih dari Galeri" untuk upload atau "Kamera" untuk memotret langsung</p>
            <p class="text-xs text-on-surface-variant">PNG, JPG hingga 5MB</p>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-4" x-show="files.length > 0" x-cloak>
        <template x-for="(url, index) in files" :key="url">
            <div class="relative group">
                <img :src="url" class="h-24 w-full object-cover rounded-xl border border-outline-variant">
                <button type="button" class="absolute -top-2 -right-2 w-6 h-6 bg-error text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-xs" x-on:click="files.splice(index, 1); $el.closest('[x-data]').querySelectorAll('input[type=file]').forEach(i => i.value = '')">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        </template>
    </div>

    @error(str_replace('[]', '', $name))
        <p class="mt-1.5 text-sm text-error flex items-center">
            <span class="material-symbols-outlined text-base mr-1">error</span>
            {{ $message }}
        </p>
    @enderror
</div>
