@php
    $kpi = $kpi ?? [];
    $dailyOreOthers = $dailyOreOthers ?? [];
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Monthly – MTD Report</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $periodLabel ?? '' }}</p>
    </div>
    <a href="{{ route(auth()->user()->role . '.dashboard.export', array_merge(['tab' => 'monthly'], request()->except('tab'))) }}" class="btn-secondary inline-flex items-center gap-2 self-start">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
</div>

<div class="card p-6 mb-6">
    <div class="flex items-center gap-3 mb-6">
        <span class="text-sm text-slate-500">All Material Hauling</span>
        <span class="text-3xl font-bold text-[var(--primary)]">{{ number_format((float)($kpi['tonnage'] ?? 0), 0) }}</span>
    </div>
    <div class="relative" style="height: 400px;">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart');
    if (!ctx) return;

    const labels = {!! json_encode(array_column($dailyOreOthers, 'date')) !!};
    const ore = {!! json_encode(array_column($dailyOreOthers, 'ore')) !!};
    const others = {!! json_encode(array_column($dailyOreOthers, 'others')) !!};
    const cumulative = {!! json_encode(array_column($dailyOreOthers, 'cumulative')) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Ore',
                    data: ore,
                    backgroundColor: '#1e3a5f',
                    borderRadius: 4,
                    yAxisID: 'y',
                    order: 2
                },
                {
                    label: 'Others',
                    data: others,
                    backgroundColor: '#93c5fd',
                    borderRadius: 4,
                    yAxisID: 'y',
                    order: 3
                },
                {
                    label: 'Cumulative',
                    data: cumulative,
                    type: 'line',
                    borderColor: '#1e3a5f',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: '#1e3a5f',
                    pointRadius: 4,
                    borderWidth: 2,
                    tension: 0.3,
                    yAxisID: 'y1',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#475569', usePointStyle: true, padding: 16 }
                }
            },
            scales: {
                x: {
                    grid: { color: '#e2e8f0' },
                    ticks: { color: '#475569' }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    grid: { color: '#e2e8f0' },
                    ticks: { color: '#475569' },
                    title: { display: true, text: 'Daily', color: '#475569' }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { color: '#1e3a5f' },
                    title: { display: true, text: 'Cumulative', color: '#1e3a5f' }
                }
            }
        }
    });
});
</script>
@endpush
