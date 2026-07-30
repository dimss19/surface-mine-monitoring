@extends('layouts.admin')
@section('title', 'Utilization Unit')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[var(--primary)]">Utilization Unit</h1>
    <p class="text-slate-500">Status dan utilisi setiap unit hari ini</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Unit</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Area</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Jam Hari Ini</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Utilization</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Last Update</th>
            </tr>
        </thead>
        <tbody>
            @forelse($utilization as $item)
            <tr class="border-t hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">{{ $item['unit']->kode }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $item['unit']->area->nama ?? '-' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $item['status'] === 'active' ? 'bg-green-100 text-green-700' : 
                           ($item['status'] === 'breakdown' ? 'bg-red-100 text-red-700' : 
                           ($item['status'] === 'maintenance' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700')) }}">
                        {{ ucfirst($item['status']) }}
                    </span>
                </td>
                <td class="px-4 py-3">{{ $item['hours_today'] }}h / {{ $item['target'] }}h</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-24 bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full {{ $item['utilization_pct'] >= 75 ? 'bg-green-500' : ($item['utilization_pct'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                 style="width: {{ $item['utilization_pct'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium w-10">{{ $item['utilization_pct'] }}%</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-slate-500">
                    {{ $item['last_update'] ? $item['last_update']->diffForHumans() : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-slate-500">Tidak ada data unit</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection