@extends('layouts.app', ['headerTitle' => 'Master Data'])

@section('title', 'Master Data')

@section('content')

{{-- Tabs --}}
<div class="border-b border-[var(--border)] mb-6">
    <nav class="flex gap-8">
        @php
            $tabs = [
                'user' => 'User',
                'area' => 'Area',
                'unit' => 'Unit',
                'material' => 'Material',
                'target' => 'Target Harian',
            ];
        @endphp
        @foreach($tabs as $key => $label)
            <a href="?tab={{ $key }}"
               class="pb-3 px-1 text-sm font-medium border-b-2 transition-colors
                      {{ ($activeTab ?? 'user') === $key ? 'border-[var(--primary)] text-[var(--primary)]' : 'border-transparent text-[var(--text-muted)] hover:text-[var(--text)]' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>
</div>

{{-- Tab Content --}}
@if(($activeTab ?? 'user') === 'user')
    @include('admin.master-data.partials.user-table')
@elseif($activeTab === 'area')
    @include('admin.master-data.partials.area-table')
@elseif($activeTab === 'unit')
    @include('admin.master-data.partials.unit-table')
@elseif($activeTab === 'material')
    @include('admin.master-data.partials.material-table')
@elseif($activeTab === 'target')
    @include('admin.master-data.partials.target-table')
@endif
@endsection
