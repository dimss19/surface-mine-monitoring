@props(['name' => 'foto[]', 'label' => 'Upload Foto', 'required' => false, 'multiple' => true])

<div class="mb-4" x-data="{ files: [] }">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative hover:bg-gray-50 cursor-pointer">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600 justify-center">
                <label for="{{ $name }}" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
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
            <p class="text-xs text-gray-500">
                PNG, JPG hingga 5MB
            </p>
        </div>
    </div>
    
    <!-- Image Previews -->
    <div class="mt-4 grid grid-cols-3 gap-4" x-show="files.length > 0">
        <template x-for="url in files" :key="url">
            <div class="relative">
                <img :src="url" class="h-24 w-full object-cover rounded-md border border-gray-200">
            </div>
        </template>
    </div>
    
    @error(str_replace('[]', '', $name))
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>