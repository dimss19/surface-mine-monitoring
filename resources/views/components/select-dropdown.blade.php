@props(['label', 'name', 'options' => [], 'required' => false, 'value' => '', 'placeholder' => 'Pilih salah satu'])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    <select 
        name="{{ $name }}" 
        id="{{ $name }}" 
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md']) }}
    >
        <option value="" disabled {{ old($name, $value) == '' ? 'selected' : '' }}>{{ $placeholder }}</option>
        @foreach($options as $val => $text)
            <option value="{{ $val }}" {{ old($name, $value) == $val ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>