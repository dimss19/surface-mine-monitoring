@props(['title', 'value', 'icon', 'color' => 'blue'])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'purple' => 'bg-purple-50 text-purple-600',
        default => 'bg-blue-50 text-blue-600',
    };
@endphp

<div class="card p-4">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg {{ $colorClasses }} flex items-center justify-center">
            <span class="material-symbols-outlined">{{ $icon }}</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-[var(--primary)]">{{ $value }}</p>
        </div>
    </div>
</div>
