@extends('layouts.operator')

@section('title', 'Riwayat Ritasi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[var(--primary)]">Riwayat Ritasi</h1>
    <p class="text-slate-500">Data ritasi sebelumnya</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <form method="GET" class="flex items-center gap-3 mb-6">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-4 py-2 border border-slate-300 rounded-lg">
        <select name="shift" class="px-4 py-2 border border-slate-300 rounded-lg">
            <option value="">Semua Shift</option>
            <option value="siang" {{ request('shift') === 'siang' ? 'selected' : '' }}>Day</option>
            <option value="malam" {{ request('shift') === 'malam' ? 'selected' : '' }}>Night</option>
        </select>
        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg">Filter</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Shift</th>
                    <th class="px-4 py-3 text-left">Unit</th>
                    <th class="px-4 py-3 text-left">Material</th>
                    <th class="px-4 py-3 text-left">HM Awal</th>
                    <th class="px-4 py-3 text-left">HM Akhir</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Ritasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ritasis as $r)
                <tr>
                    <td class="px-4 py-3">{{ $r->tanggal }}</td>
                    <td class="px-4 py-3">{{ $r->shift === 'siang' ? 'Day' : 'Night' }}</td>
                    <td class="px-4 py-3">{{ $r->unit->kode ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $r->material->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ number_format($r->hm_awal, 1) }}</td>
                    <td class="px-4 py-3">{{ number_format($r->hm_akhir, 1) }}</td>
                    <td class="px-4 py-3">{{ number_format($r->hm_total, 1) }}</td>
                    <td class="px-4 py-3">{{ $r->jumlah_ritasi }}</td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $r->status_badge }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-8 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $ritasis->withQueryString()->links() }}
    </div>
</div>
@endsection
