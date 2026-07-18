@props(['name' => 'foto[]', 'label' => 'Upload Foto', 'required' => false, 'multiple' => true])

<div class="mb-4" x-data="{ files: [] }">
    <label class="block text-sm font-bold text-on-surface mb-1">
        {{ $label }} @if($required) <span class="text-error">*</span> @endif
    </label>
    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-outline-variant border-dashed rounded-xl relative hover:bg-surface-container/50 cursor-pointer transition-colors bg-surface-container">
        <div class="space-y-1 text-center">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant">add_photo_alternate</span>
            <div class="flex text-sm text-on-surface-variant justify-center">
                <label for="{{ $name }}" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-fixed focus-within:outline-none focus-within:ring-2 focus-within:ring-primary">
                    <span>Pilih file</span>
                    <input id="{{ $name }}" name="{{ $name }}" type="file" class="sr-only"
                        {{ $multiple ? 'multiple' : '' }}
                        accept="image/jpeg, image/png"
                        {{ $required ? 'required' : '' }}
                        x-on:change="files = Object.values($event.target.files).map(file => URL.createObjectURL(file))"
                    >
                </label>
                <p class="pl-1">atau drag and drop</p>
            </div>
            <p class="text-xs text-on-surface-variant">
                PNG, JPG hingga 5MB
            </p>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-4" x-show="files.length > 0" x-cloak>
        <template x-for="url in files" :key="url">
            <div class="relative">
                <img :src="url" class="h-24 w-full object-cover rounded-xl border border-outline-variant">
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
