@php
    $kpi = $kpi ?? [];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="stat-card border-l-4 border-amber-500">
        <span class="material-symbols-outlined text-amber-600">local_flame</span>
        <div class="min-w-0">
            <p class="text-sm text-slate-500">Fuel Consumption</p>
            <p class="text-2xl font-bold">{{ number_format((float)($kpi['fuel'] ?? 0), 2) }}</p>
            <p class="text-xs text-slate-400">Liter</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-[var(--primary)]">
        <span class="material-symbols-outlined text-[var(--primary)]">scale</span>
        <div class="min-w-0">
            <p class="text-sm text-slate-500">Tonnage</p>
            <p class="text-2xl font-bold">{{ number_format((float)($kpi['tonnage'] ?? 0), 2) }}</p>
            <p class="text-xs text-slate-400">ton</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-green-500">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <div class="min-w-0">
            <p class="text-sm text-slate-500">Active Units</p>
            <p class="text-2xl font-bold">{{ (int)($kpi['active_units'] ?? 0) }}</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-red-500">
        <span class="material-symbols-outlined text-red-600">build</span>
        <div class="min-w-0">
            <p class="text-sm text-slate-500">Maintenance Units</p>
            <p class="text-2xl font-bold">{{ (int)($kpi['maintenance_units'] ?? 0) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-[var(--primary)]">{{ number_format((float)($kpi['pa'] ?? 0), 2) }}%</p>
        <p class="text-xs text-slate-500">PA</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-[var(--primary)]">{{ number_format((float)($kpi['ua'] ?? 0), 2) }}%</p>
        <p class="text-xs text-slate-500">UA</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-slate-500">SH {{ (int)($kpi['sh'] ?? 0) }} · WH {{ number_format((float)($kpi['wh'] ?? 0), 2) }} · BD {{ number_format((float)($kpi['bd'] ?? 0), 2) }}</p>
        <p class="text-xs text-slate-500">Hours</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ number_format((float)($pies['day'] ?? 0), 2) }}</p>
        <p class="text-xs text-slate-500">Day Tonnage</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ number_format((float)($pies['night'] ?? 0), 2) }}</p>
        <p class="text-xs text-slate-500">Night Tonnage</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-[var(--primary)]">{{ number_format((float)($pies['combined'] ?? 0), 2) }}</p>
        <p class="text-xs text-slate-500">Combined Tonnage</p>
    </div>
</div>

<div class="card p-4 mb-6">
    <h2 class="section-title mb-3">Timeline</h2>
    <div class="space-y-3">
        @forelse ($timeline as $t)
            @php
                $t   = is_array($t) ? $t : $t->toArray();
                $red   = (float)($t['red'] ?? 0);
                $green = (float)($t['green'] ?? 0);
                $white = (float)($t['white'] ?? 0);
                $total = ($red + $green + $white) ?: 1.0;
                $pRed   = ($red / $total) * 100;
                $pAct   = (($red + $green) / $total) * 100;
            @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-medium">{{ $t['kode'] ?? ('Unit ' . ($t['unit_id'] ?? '-')) }}</span>
                    <span class="text-slate-500">
                        {{ number_format($red, 1) }}h maint · {{ number_format($green, 1) }}h work · {{ number_format($white, 1) }}h standby
                    </span>
                </div>
                <div class="h-4 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-4 rounded-full flex"
                         style="background:linear-gradient(to right, #ef4438 0%, #ef4438 {{ $pRed }}%, #38bdf8 {{ $pRed }}%, #38bdf8 {{ $pAct }}%, #e2e8f0 {{ $pAct }}%, #e2e8f0 100%)">
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500">Belum ada data unit.</p>
        @endforelse
    </div>
</div>

<div class="card overflow-hidden mb-6">
    <div class="p-4 border-b flex items-center justify-between">
        <h2 class="section-title">Hauling Records</h2>
        <span class="text-xs text-slate-500">{{ $hauling->total() }} record{{ $hauling->total() != 1 ? 's' : '' }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">TANGGAL</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">SHIFT</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">UNIT</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">MATERIAL</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">HM</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">TON</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">FUEL</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($hauling as $r)
                    <tr>
                        <td class="px-4 py-2 text-sm">{{ $r->tanggal?->format('d M Y') }}</td>
                        <td class="px-4 py-2 text-sm">{{ $r->shift === 'siang' ? 'Day' : 'Night' }}</td>
                        <td class="px-4 py-2 text-sm font-mono">{{ $r->unit->kode ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm">{{ $r->material->nama ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-right">{{ number_format((float)($r->hm_total ?? 0), 1) }}</td>
                        <td class="px-4 py-2 text-sm text-right">{{ number_format((float)($r->quantity_tonnes ?? 0), 2) }}</td>
                        <td class="px-4 py-2 text-sm text-right">{{ number_format((float)($r->fuel_consumption ?? 0), 1) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-slate-500">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $hauling->links() }}</div>
</div>
