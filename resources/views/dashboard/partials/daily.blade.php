@php
    $kpi = $kpi ?? [];
    $pies = $pies ?? [];
    $haulingByMaterial = $haulingByMaterial ?? [];
    $timeline = $timeline ?? [];
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Dashboard Harian</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $periodLabel ?? '' }}</p>
    </div>
    <a href="{{ route(auth()->user()->role . '.dashboard.export', ['date' => request('date')]) }}" class="btn-secondary inline-flex items-center gap-2 self-start">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
</div>

<div class="card p-6 mb-6">
    <div class="text-center">
        <p class="text-sm text-slate-500 uppercase tracking-wide">Total Tonnage</p>
        <p class="text-5xl font-bold text-[var(--primary)] mt-2">{{ number_format((float)($kpi['tonnage'] ?? 0), 0) }}<span class="text-2xl font-normal ml-2">ton</span></p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100">
        <div class="text-center">
            <p class="text-sm text-slate-500">Fuel</p>
            <p class="text-xl font-bold text-slate-700">{{ number_format((float)($kpi['fuel'] ?? 0), 1) }} L</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-slate-500">Active</p>
            <p class="text-xl font-bold text-green-600">{{ (int)($kpi['active_units'] ?? 0) }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-slate-500">Maintenance</p>
            <p class="text-xl font-bold text-red-600">{{ (int)($kpi['maintenance_units'] ?? 0) }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-slate-500">PA / UA</p>
            <p class="text-xl font-bold text-[var(--primary)]">{{ number_format((float)($kpi['pa'] ?? 0), 1) }}%</p>
        </div>
    </div>
</div>

<div class="card p-4 mb-6">
    <h2 class="section-title mb-3">Hauling by Material</h2>
    <div class="relative" style="height: {{ max(200, count($haulingByMaterial) * 40 + 40) }}px;">
        <canvas id="materialChart"></canvas>
    </div>
</div>

<div class="card p-4 mb-6">
    <h2 class="section-title mb-3">Timeline</h2>
    <div class="space-y-2">
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
                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-3 rounded-full flex"
                         style="background:linear-gradient(to right, #ef4438 0%, #ef4438 {{ $pRed }}%, #1e3a5f {{ $pRed }}%, #1e3a5f {{ $pAct }}%, #e2e8f0 {{ $pAct }}%, #e2e8f0 100%)">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const materialCtx = document.getElementById('materialChart');
    if (materialCtx) {
        new Chart(materialCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($haulingByMaterial)) !!},
                datasets: [{
                    label: 'Tonnage',
                    data: {!! json_encode(array_values($haulingByMaterial)) !!},
                    backgroundColor: '#1e3a5f',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#e2e8f0' }, ticks: { color: '#475569' } },
                    y: { grid: { display: false }, ticks: { color: '#1e3a5f', font: { weight: 'bold' } } }
                }
            }
        });
    }
});
</script>
@endpush
