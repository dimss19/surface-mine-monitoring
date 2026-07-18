@extends('layouts.dashboard')

@section('title', '- Laporan Pemantauan')
@section('page_title', 'Laporan Pemantauan Anda')

@section('content')
<section class="space-y-2">
    <h2 class="text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Daftar Laporan Pemantauan</h2>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Pantau dan kelola laporan pemantauan lapangan Anda.
    </p>
</section>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <form method="GET" action="{{ route('spv.pemantauan.index') }}" class="flex flex-wrap items-end gap-3">
        <div>
            <label for="tanggal" class="block text-xs font-bold text-on-surface-variant mb-1">Filter Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="block rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-3 py-2">
        </div>
        <div>
            <label for="area_id" class="block text-xs font-bold text-on-surface-variant mb-1">Filter Area</label>
            <select name="area_id" class="block rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-3 py-2">
                <option value="">Semua Area</option>
                @foreach($areas as $id => $nama)
                    <option value="{{ $id }}" {{ request('area_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg mr-1">filter_alt</span>
                Filter
            </button>
            @if(request()->has('tanggal') || request()->has('area_id'))
                <a href="{{ route('spv.pemantauan.index') }}" class="inline-flex items-center px-4 py-2 border border-outline-variant text-on-surface rounded-xl text-sm font-bold hover:bg-surface-variant transition-all">
                    Reset
                </a>
            @endif
        </div>
    </form>
    <a href="{{ route('spv.pemantauan.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm shrink-0">
        <span class="material-symbols-outlined text-lg mr-1.5">add</span>
        Buat Laporan Baru
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant">
    <x-data-table :headers="['Tanggal & Shift', 'Area', 'Alat', 'Progress', 'Status']" :actions="true" :pagination="$pemantauans->links()">
        @forelse($pemantauans as $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                    <div class="text-xs text-on-surface-variant mt-0.5 inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container font-medium">Shift {{ ucfirst($item->shift) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-on-surface">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-base text-on-surface-variant mr-1.5">location_on</span>
                        {{ $item->area->nama ?? '-' }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-on-surface">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-base text-on-surface-variant mr-1.5">construction</span>
                        {{ $item->alat->nama ?? '-' }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <span class="text-sm font-bold text-on-surface w-10">{{ $item->progress_persen }}%</span>
                        <div class="w-24 bg-surface-container rounded-full h-2 ml-2 shadow-inner overflow-hidden">
                            <div class="h-2 rounded-full {{ $item->progress_persen == 100 ? 'bg-[#2e7d32]' : 'bg-primary' }} transition-all duration-1000" style="width: {{ $item->progress_persen }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->progress_status == 'selesai')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-[#1b5e20]/30 text-[#66bb6a] border border-[#2e7d32]">Selesai</span>
                    @elseif($item->progress_status == 'proses')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-[#e65100]/30 text-[#ffb74d] border border-[#e65100]">Proses</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-surface-container text-on-surface-variant border border-outline-variant">Belum Mulai</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('spv.pemantauan.show', $item->id) }}" class="inline-flex items-center text-primary hover:text-primary-fixed bg-primary-container/20 hover:bg-primary-container/40 px-3 py-1.5 rounded-lg transition-colors">
                        Detail
                        <span class="material-symbols-outlined text-base ml-1">chevron_right</span>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">description</span>
                    <p class="text-sm text-on-surface-variant">Belum ada data laporan pemantauan.</p>
                </td>
            </tr>
        @endforelse
    </x-data-table>
</div>
@endsection
