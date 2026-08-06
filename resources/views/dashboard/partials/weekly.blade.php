@php
    $kpi = $kpi ?? [];
    $haulingByMaterial = $haulingByMaterial ?? [];
    $availability = $availability ?? [];
    $uoa = $uoa ?? [];
    $typeLabels = ['excavator' => 'Exc', 'dump_truck' => 'DT', 'bulldozer' => 'Dozer', 'loader' => 'LV', 'motor_grader' => 'MG'];
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Grafik All Hauling (WTD)</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $periodLabel ?? '' }}</p>
    </div>
    <a href="{{ route(auth()->user()->role . '.dashboard.export', array_merge(['tab' => 'weekly'], request()->except('tab'))) }}" class="btn-secondary inline-flex items-center gap-2 self-start">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Left: Bar chart --}}
    <div class="card p-4">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-sm text-slate-500">Weekly All Hauling</span>
            <span class="text-2xl font-bold text-[var(--primary)]">{{ number_format((float)($kpi['tonnage'] ?? 0), 0) }}</span>
        </div>
        <div class="relative" style="height: {{ max(200, count($haulingByMaterial) * 40 + 40) }}px;">
            <canvas id="weeklyMaterialChart"></canvas>
        </div>
    </div>

    {{-- Right: Availability + UoA gauges --}}
    <div class="space-y-6">
        {{-- Availability --}}
        <div class="card p-4">
            <h3 class="section-title mb-4">Availability</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($availability as $a)
                    @php $label = $typeLabels[$a['type']] ?? $a['type']; @endphp
                    <div class="text-center">
                        <canvas id="avail_{{ $a['type'] }}" width="100" height="60"></canvas>
                        <p class="text-xs font-bold text-[var(--primary)] mt-1">{{ $label }}</p>
                        <p class="text-xs text-slate-500">{{ $a['pct'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- UoA --}}
        <div class="card p-4">
            <h3 class="section-title mb-4">UoA</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($uoa as $u)
                    @php $label = $typeLabels[$u['type']] ?? $u['type']; @endphp
                    <div class="text-center">
                        <canvas id="uoa_{{ $u['type'] }}" width="100" height="60"></canvas>
                        <p class="text-xs font-bold text-[var(--primary)] mt-1">{{ $label }}</p>
                        <p class="text-xs text-slate-500">{{ $u['pct'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bar chart
    const matCtx = document.getElementById('weeklyMaterialChart');
    if (matCtx) {
        new Chart(matCtx, {
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

    // Semicircular gauge helper
    function drawGauge(canvasId, pct, color) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const w = canvas.width, h = canvas.height;
        const cx = w / 2, cy = h - 5;
        const r = Math.min(cx, cy) - 8;

        ctx.clearRect(0, 0, w, h);

        // Background arc
        ctx.beginPath();
        ctx.arc(cx, cy, r, Math.PI, 2 * Math.PI);
        ctx.lineWidth = 12;
        ctx.strokeStyle = '#e2e8f0';
        ctx.stroke();

        // Value arc
        const angle = Math.PI + (pct / 100) * Math.PI;
        ctx.beginPath();
        ctx.arc(cx, cy, r, Math.PI, angle);
        ctx.lineWidth = 12;
        ctx.strokeStyle = color;
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    // Draw availability gauges
    @foreach ($availability as $a)
        drawGauge('avail_{{ $a['type'] }}', {{ $a['pct'] }}, '#1e3a5f');
    @endforeach

    // Draw UoA gauges
    @foreach ($uoa as $u)
        drawGauge('uoa_{{ $u['type'] }}', {{ $u['pct'] }}, '#3b82f6');
    @endforeach
});
</script>
@endpush
