@extends('layouts.admin')

@section('title', 'Laporan Pemantauan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Mining Oprationals Civil Departement</h1>
</div>

<div class="mb-4">
    <form method="POST" action="{{ route('spv.laporan.export', 'excel') }}">
        @csrf
        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">download</span>
            Export to Excel
        </button>
    </form>
</div>

<div class="card p-4 mb-6">
    <form method="GET" action="{{ route('spv.laporan.index') }}" class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-slate-400">calendar_today</span>
            <input type="date" name="tanggal_start" value="{{ request('tanggal_start') }}" class="form-input w-40">
            <span class="text-slate-400">-</span>
            <input type="date" name="tanggal_end" value="{{ request('tanggal_end') }}" class="form-input w-40">
        </div>
        <select name="shift" class="form-input w-40">
            <option value="">All Shifts</option>
            <option value="siang" {{ request('shift') === 'siang' ? 'selected' : '' }}>Day</option>
            <option value="malam" {{ request('shift') === 'malam' ? 'selected' : '' }}>Night</option>
        </select>
        <select name="tipe" class="form-input w-44">
            <option value="">All Unit Types</option>
            <option value="ritasi" {{ request('tipe') === 'ritasi' ? 'selected' : '' }}>Ritasi</option>
            <option value="non_ritasi" {{ request('tipe') === 'non_ritasi' ? 'selected' : '' }}>Non-Ritasi</option>
        </select>
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <span class="material-symbols-outlined text-lg">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Operator or Unit ID..." class="pl-10 pr-4 py-2 border rounded-lg w-full text-sm">
        </div>
        <button type="submit" class="btn-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">filter_alt</span>
            Filter
        </button>
        @if(request()->has('tanggal_start') || request()->has('shift') || request()->has('search'))
            <a href="{{ route('spv.laporan.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

@php
    $merged = $ritasis->getCollection()->merge($nonRitasis->getCollection());
    if (request('tanggal_start') && request('tanggal_end')) {
        $start = \Carbon\Carbon::parse(request('tanggal_start'))->startOfDay();
        $end = \Carbon\Carbon::parse(request('tanggal_end'))->endOfDay();
        $merged = $merged->filter(fn($item) => $item->tanggal->between($start, $end));
    } elseif (request('tanggal_start')) {
        $merged = $merged->filter(fn($item) => $item->tanggal->toDateString() === request('tanggal_start'));
    }
    if (request('shift')) {
        $merged = $merged->filter(fn($item) => $item->shift === request('shift'));
    }
    if (request('tipe')) {
        if (request('tipe') === 'ritasi') {
            $merged = $merged->filter(fn($item) => $item instanceof \App\Models\Ritasi);
        } else {
            $merged = $merged->filter(fn($item) => $item instanceof \App\Models\NonRitasi);
        }
    }
    if (request('search')) {
        $search = request('search');
        $merged = $merged->filter(function($item) use ($search) {
            return str_contains(strtolower($item->pegawai->nama ?? ''), strtolower($search))
                || str_contains(strtolower($item->unit->kode ?? ''), strtolower($search));
        });
    }
    $merged = $merged->sortByDesc('tanggal')->values();
    $perPage = 10;
    $currentPage = request('page', 1);
    $paginated = $merged->forPage($currentPage, $perPage);
    $total = $merged->count();
    $lastPage = ceil($total / $perPage);
@endphp

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TANGGAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">SHIFT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">NAMA OPERATOR</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">UNIT ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TIPE</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">HM AWAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">HM AKHIR</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TOTAL / RIT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">LOKASI</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">ACTION</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($paginated as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">{{ $item->tanggal->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->shift_label }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->pegawai->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-mono">{{ $item->unit->kode ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                @if($item instanceof \App\Models\Ritasi)
                                    <span class="material-symbols-outlined text-sm">local_shipping</span>
                                    <span class="text-sm">Ritasi</span>
                                @else
                                    <span class="material-symbols-outlined text-sm">construction</span>
                                    <span class="text-sm">Non-Ritasi</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ number_format($item->hm_awal, 1) }}</td>
                        <td class="px-4 py-3 text-sm">{{ number_format($item->hm_akhir, 1) }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ number_format($item->hm_total, 1) }}
                            @if($item instanceof \App\Models\Ritasi)
                                / {{ $item->jumlah_ritasi }}
                            @else
                                / -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $item->area->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <button class="text-blue-600 hover:text-blue-700">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-slate-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t flex items-center justify-between">
        <p class="text-sm text-slate-500">
            Showing {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, $total) }} of {{ $total }} entries
        </p>
        <div class="flex gap-1">
            @if($currentPage > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="px-3 py-1 border rounded text-sm hover:bg-slate-50">Previous</a>
            @endif
            @for($i = 1; $i <= $lastPage; $i++)
                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                   class="px-3 py-1 border rounded text-sm {{ $i == $currentPage ? 'bg-slate-800 text-white' : 'hover:bg-slate-50' }}">
                    {{ $i }}
                </a>
            @endfor
            @if($currentPage < $lastPage)
                <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="px-3 py-1 border rounded text-sm hover:bg-slate-50">Next</a>
            @endif
        </div>
    </div>
</div>
@endsection
