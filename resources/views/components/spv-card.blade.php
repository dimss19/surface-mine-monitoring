@props(['spv', 'areaCount' => 0, 'latestReport' => null])

<div class="bg-surface-container-high rounded-xl border border-outline-variant shadow-sm hover:shadow-xl transition-all duration-300 ease-out transform hover:-translate-y-1 group overflow-hidden">
    <div class="absolute top-0 inset-x-0 h-1 bg-primary opacity-75 group-hover:opacity-100 transition-opacity"></div>

    <div class="p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0 relative">
                <div class="relative bg-surface-container rounded-xl p-3 border border-outline-variant">
                    <span class="material-symbols-outlined text-primary">person</span>
                </div>
            </div>
            <div class="ml-4 w-0 flex-1">
                <h3 class="text-lg font-bold text-on-surface truncate group-hover:text-primary transition-colors">
                    {{ $spv->name }}
                </h3>
                <p class="text-sm text-on-surface-variant truncate mt-0.5">
                    {{ $spv->email }}
                </p>
            </div>
        </div>

        <div class="mt-6 pt-5 border-t border-outline-variant grid grid-cols-2 gap-4">
            <div>
                <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Area Aktif</dt>
                <dd class="flex items-center text-sm font-bold text-on-surface">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#2e7d32] mr-2 animate-pulse"></span>
                    {{ $areaCount }} Lokasi
                </dd>
            </div>
            <div>
                <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Laporan Terakhir</dt>
                <dd class="text-sm font-medium text-on-surface">
                    @if($latestReport)
                        {{ \Carbon\Carbon::parse($latestReport->tanggal)->format('d M Y') }}
                        <span class="text-xs text-on-surface-variant ml-1">({{ ucfirst($latestReport->shift) }})</span>
                    @else
                        <span class="text-on-surface-variant italic">Belum ada</span>
                    @endif
                </dd>
            </div>
        </div>
    </div>

    <div class="bg-surface-container px-6 py-4 border-t border-outline-variant">
        <a href="{{ route('admin.spv.show', $spv->id) }}" class="inline-flex items-center text-sm font-bold text-primary hover:text-primary-fixed transition-colors group/link">
            Lihat Detail SPV
            <span class="material-symbols-outlined text-base ml-1.5 transform group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
        </a>
    </div>
</div>
