@extends('layouts.dashboard')

@section('title', '- Riwayat Rekapan Operator')
@section('page_title', 'Riwayat Rekapan')

@section('content')
<div x-data="{ online: navigator.onLine }"
     x-init="window.addEventListener('online', () => online = true); window.addEventListener('offline', () => online = false)">
    <div class="flex items-center justify-between gap-3 flex-wrap mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-on-surface tracking-tight">Riwayat Rekapan Operator</h2>
            <p class="text-sm text-on-surface-variant mt-1">Riwayat rekapan operator atas nama <strong class="text-primary">{{ auth()->user()->name }}</strong>.</p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border"
             :class="online ? 'bg-green-500/10 text-green-400 border-green-500/30' : 'bg-amber-500/10 text-amber-400 border-amber-500/30'">
            <span class="w-2 h-2 rounded-full" :class="online ? 'bg-green-400 animate-pulse' : 'bg-amber-400'"></span>
            <span x-text="online ? 'Online' : 'Offline'"></span>
        </div>
    </div>

    <div class="bg-surface-container-high rounded-2xl border border-outline-variant overflow-hidden">
        @if($rekapans->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface">
                            <th class="text-left p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">Tanggal</th>
                            <th class="text-left p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">Shift</th>
                            <th class="text-left p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">Area</th>
                            <th class="text-left p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">Alat</th>
                            <th class="text-left p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">Tipe Pekerjaan</th>
                            <th class="text-center p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">HM Awal</th>
                            <th class="text-center p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">HM Akhir</th>
                            <th class="text-center p-4 font-bold text-on-surface-variant text-xs uppercase tracking-wider">HM Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50">
                        @foreach($rekapans as $rekapan)
                        <tr class="hover:bg-surface-variant/30 transition-colors">
                            <td class="p-4 text-on-surface font-medium whitespace-nowrap">{{ \Carbon\Carbon::parse($rekapan->tanggal)->format('d/m/Y') }}</td>
                            <td class="p-4 text-on-surface capitalize">{{ $rekapan->shift }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $rekapan->area?->nama ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $rekapan->alat?->nama ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ str_replace('_', ' ', ucfirst($rekapan->tipe_pekerjaan)) }}</td>
                            <td class="p-4 text-on-surface text-center font-mono">{{ number_format($rekapan->hm_awal, 2) }}</td>
                            <td class="p-4 text-on-surface text-center font-mono">{{ number_format($rekapan->hm_akhir, 2) }}</td>
                            <td class="p-4 text-on-surface text-center font-mono font-bold">{{ number_format($rekapan->hm_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-outline-variant">
                {{ $rekapans->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <span class="material-symbols-outlined text-on-surface-variant/30" style="font-size: 64px;">history</span>
                <h3 class="text-lg font-bold text-on-surface mt-4">Belum Ada Rekapan</h3>
                <p class="text-sm text-on-surface-variant mt-1">Anda belum pernah mengisi rekapan operator.</p>
                <a href="{{ route('pegawai.rekapan.create') }}" class="mt-6 inline-flex items-center gap-2 bg-primary text-on-primary font-bold py-2.5 px-6 rounded-xl transition-all hover:opacity-90 active:scale-95">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                    Isi Rekapan Baru
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
