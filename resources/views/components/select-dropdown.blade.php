@props(['label', 'name', 'options' => [], 'required' => false, 'value' => '', 'placeholder' => 'Pilih salah satu'])

<div class="relative group">
    <label for="{{ $name }}" class="block text-sm font-bold text-on-surface mb-1.5 transition-colors">
        {{ $label }} @if($required) <span class="text-error">*</span> @endif
    </label>
    <div class="relative">
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'appearance-none block w-full px-4 py-3 text-base bg-surface-container border border-outline-variant text-on-surface rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300 sm:text-sm cursor-pointer shadow-sm']) }}
        >
            <option value="" disabled {{ old($name, $value) == '' ? 'selected' : '' }}>{{ $placeholder }}</option>
            @foreach($options as $val => $text)
                <option value="{{ $val }}" {{ old($name, $value) == $val ? 'selected' : '' }} class="bg-surface-container-high text-on-surface">
                    {{ $text }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-on-surface-variant">
            <span class="material-symbols-outlined">unfold_more</span>
        </div>
    </div>
    @error($name)
        <p class="mt-1.5 text-sm text-error flex items-center">
            <span class="material-symbols-outlined text-base mr-1">error</span>
            {{ $message }}
        </p>
    @enderror
</div>
