@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $periodLabel ?? '' }}</p>
    </div>
    <div class="text-right">
        <button type="button" onclick="location.href='{{ route(auth()->user()->role . '.dashboard.export') }}?{{ http_build_query(request()->collect()->except('tab')->all()) }}'"
                class="btn-secondary flex items-center gap-2">
            <span class="material-symbols-outlined">download</span> Export
        </button>
    </div>
</div>

@php
    $tabs = [
        'daily'   => ['label' => 'Harian',   'icon' => 'calendar_today'],
        'weekly'  => ['label' => 'Mingguan',  'icon' => 'calendar_view_week'],
        'monthly' => ['label' => 'Bulanan',  'icon' => 'calendar_month'],
    ];
    $q = request()->collect()->except('tab')->all();
@endphp

<div class="mb-6 border-b border-slate-200">
    <nav class="-mb-px flex gap-6 overflow-x-auto">
        @foreach ($tabs as $key => $t)
            @php $active = ($tab === $key); @endphp
            <a href="{{ request()->fullUrlWithQuery(array_merge($q, ['tab' => $key])) }}"
               class="py-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2 whitespace-nowrap
                      {{ $active ? 'border-[var(--primary)] text-[var(--primary)]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                <span class="material-symbols-outlined text-base">{{ $t['icon'] }}</span>
                {{ $t['label'] }}
            </a>
        @endforeach
    </nav>
</div>

<div>
    @include('dashboard.partials.' . ($tab ?? 'daily'))
</div>
@endsection
