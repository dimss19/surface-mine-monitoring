@extends('layouts.admin')

@section('title', 'Laporan Bulanan SPV')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[var(--primary)]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Laporan Bulanan</h1>
    <p class="text-slate-500">Data ritasi dan non-ritasi per bulan.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <div class="flex items-center gap-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm font-medium text-slate-600">Bulan:</label>
            <select name="month" class="px-4 py-2 border border-slate-300 rounded-lg">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <label class="text-sm font-medium text-slate-600">Tahun:</label>
            <input type="number" name="year" value="{{ $year }}" min="2020" max="2030" class="px-4 py-2 border border-slate-300 rounded-lg w-24">
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg">Filter</button>
        </form>
    </div>

    <h3 class="font-bold mb-3">Ritasi</h3>
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Shift</th>
                    <th class="px-4 py-3 text-left">Operator</th>
                    <th class="px-4 py-3 text-left">Unit</th>
                    <th class="px-4 py-3 text-left">Material</th>
                    <th class="px-4 py-3 text-left">Ritasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ritasis as $r)
                <tr>
                    <td class="px-4 py-3">{{ $r->tanggal }}</td>
                    <td class="px-4 py-3">{{ $r->shift === 'siang' ? 'Day' : 'Night' }}</td>
                    <td class="px-4 py-3">{{ $r->pegawai->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $r->unit->kode ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $r->material->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $r->jumlah_ritasi }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3 class="font-bold mb-3">Non-Ritasi</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Shift</th>
                    <th class="px-4 py-3 text-left">Operator</th>
                    <th class="px-4 py-3 text-left">Unit</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($nonRitasis as $nr)
                <tr>
                    <td class="px-4 py-3">{{ $nr->tanggal }}</td>
                    <td class="px-4 py-3">{{ $nr->shift === 'siang' ? 'Day' : 'Night' }}</td>
                    <td class="px-4 py-3">{{ $nr->pegawai->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $nr->unit->kode ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $nr->lokasi_pekerjaan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
