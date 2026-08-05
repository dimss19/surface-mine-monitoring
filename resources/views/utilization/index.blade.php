@extends('layouts.app')

@section('title', 'Utilization')

@section('content')
@php $role = Auth::user()->role; @endphp

<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Utilization Unit</h1>
</div>

<div class="card p-4 mb-6">
    <form method="GET" action="{{ route("$role.utilization.index") }}" class="flex items-center gap-4 flex-wrap">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-slate-400">calendar_today</span>
            <input type="date" name="tanggal_start" value="{{ request('tanggal_start') }}" class="form-input w-40">
            <span class="text-slate-400">-</span>
            <input type="date" name="tanggal_end" value="{{ request('tanggal_end') }}" class="form-input w-40">
        </div>
        <button type="submit" class="btn-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">filter_alt</span>
            Filter
        </button>
        @if(request()->filled('tanggal_start'))
            <a href="{{ route("$role.utilization.index") }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

@php
    $breakdownCount = $latestStatus->filter(fn ($t) => $t === 'breakdown')->count();
    $servisCount = $latestStatus->count() - $breakdownCount;
@endphp

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="card p-4 flex items-center gap-3">
        <span class="badge-breakdown material-symbols-outlined rounded-full p-2">report</span>
        <div>
            <p class="text-2xl font-bold">{{ $breakdownCount }}</p>
            <p class="text-sm text-slate-500">Unit Breakdown</p>
        </div>
    </div>
    <div class="card p-4 flex items-center gap-3">
        <span class="badge-active material-symbols-outlined rounded-full p-2">check_circle</span>
        <div>
            <p class="text-2xl font-bold">{{ $servisCount }}</p>
            <p class="text-sm text-slate-500">Unit Servis</p>
        </div>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TANGGAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">UNIT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TIPE</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">DESKRIPSI</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">PENGISI</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($utilizations as $u)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">{{ $u->tanggal->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm font-mono">{{ $u->unit->kode ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($u->tipe === 'breakdown')
                                <span class="badge-breakdown px-3 py-1 rounded-full text-sm">Breakdown</span>
                            @else
                                <span class="badge-maintenance px-3 py-1 rounded-full text-sm">Servis</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $u->deskripsi ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $u->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">
        {{ $utilizations->links() }}
    </div>
</div>
@endsection
