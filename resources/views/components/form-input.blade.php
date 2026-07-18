@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'required' => false, 'value' => ''])

<div class="relative group">
    <label for="{{ $name }}" class="block text-sm font-bold text-on-surface mb-1.5 transition-colors">
        {{ $label }} @if($required) <span class="text-error">*</span> @endif
    </label>
    <div class="relative">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full px-4 py-3 text-base bg-surface-container border border-outline-variant text-on-surface rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300 sm:text-sm shadow-sm placeholder-on-surface-variant/50']) }}
        >
    </div>
    @error($name)
        <p class="mt-1.5 text-sm text-error flex items-center">
            <span class="material-symbols-outlined text-base mr-1">error</span>
            {{ $message }}
        </p>
    @enderror
</div>
