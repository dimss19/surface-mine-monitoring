@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Monitoring Dashboard</h1>
    <p class="text-slate-500">Live operational telemetry and site metrics.</p>
</div>

<div class="flex gap-3 mb-6">
    <a href="{{ route('spv.laporan.index') }}" class="btn-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">sync</span>
        Sync Data
    </button>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <x-kpi-card title="Total Ritasi" value="{{ number_format($metrics['total_ritasi'] ?? 1284) }}" icon="local_shipping" color="blue" />
    <x-kpi-card title="Total Unit Aktif" value="{{ $metrics['unit_aktif'] ?? '42 / 50' }}" icon="precision_manufacturing" color="orange" />
    <x-kpi-card title="Total Jam Kerja" value="{{ $metrics['jam_kerja'] ?? '342h' }}" icon="schedule" color="green" />
    <x-kpi-card title="Pekerjaan General" value="{{ $metrics['general_tasks'] ?? '18 Tasks' }}" icon="assignment" color="purple" />
</div>

<div class="card p-6 mb-6">
    <h2 class="text-lg font-heading font-bold mb-4">Daily Breakdown Activity</h2>
    <div class="grid grid-cols-3 gap-6">
        <div>
            <div class="bg-slate-100 rounded-lg p-4 mb-4">
                <span class="text-sm text-slate-500">Daily All Hauling</span>
                <p class="text-2xl font-bold text-[var(--accent)]">4,278</p>
            </div>
            <canvas id="dailyChart" height="200"></canvas>
        </div>

        <div>
            <h3 class="text-center font-semibold mb-4">Day Shift</h3>
            <div class="space-y-3" id="dayShiftBars"></div>
        </div>

        <div>
            <h3 class="text-center font-semibold mb-4">Night Shift</h3>
            <div class="space-y-3" id="nightShiftBars"></div>
        </div>
    </div>
</div>

<div class="card p-6 mb-6">
    <h2 class="text-lg font-heading font-bold mb-4">Grafik All Hauling (WTD)</h2>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <div class="bg-slate-100 rounded-lg p-4 mb-4">
                <span class="text-sm text-slate-500">Weekly All Hauling</span>
                <p class="text-2xl font-bold text-[var(--accent)]">8,568</p>
            </div>
            <canvas id="weeklyChart" height="200"></canvas>
        </div>

        <div>
            <div class="mb-6">
                <h3 class="font-semibold mb-4">Availability</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['Exc' => '45.5%', 'Sany' => '33.3%', 'ADT' => '66.7%', 'Dozer' => '31.3%'] as $name => $value)
                        <div class="text-center">
                            <canvas id="avail-{{ $name }}" width="80" height="80"></canvas>
                            <p class="text-xs mt-1">{{ $name }}</p>
                            <p class="text-sm font-bold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-4">UoA</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['Exc' => '49.1%', 'Sany' => '41.8%', 'ADT' => '74.4%', 'Dozer' => '38.2%'] as $name => $value)
                        <div class="text-center">
                            <canvas id="uoa-{{ $name }}" width="80" height="80"></canvas>
                            <p class="text-xs mt-1">{{ $name }}</p>
                            <p class="text-sm font-bold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card p-6">
    <h2 class="text-lg font-heading font-bold mb-4">Monthly - MTD Report</h2>
    <div class="bg-slate-100 rounded-lg p-4 mb-4">
        <span class="text-sm text-slate-500">All Materials Hauling</span>
        <p class="text-2xl font-bold text-[var(--accent)]">75,709</p>
    </div>
    <canvas id="monthlyChart" height="150"></canvas>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: ['Tuff Paste', 'KCN', 'CakeDST', 'Tuff Off', 'Batu Pica', 'Mining Tuff', 'Pasir Hitam', 'Mud', 'Lumpur', 'Waste'],
            datasets: [{
                data: [1227, 700, 260, 175, 120, 90, 40, 26, 0, 0],
                backgroundColor: '#0f172a',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: ['1-May', '4-May', '7-May', '10-May', '13-May', '16-May', '19-May', '22-May', '25-May', '28-May', '31-May'],
            datasets: [
                { type: 'bar', label: 'Ore', data: [40, 35, 45, 50, 42, 48, 55, 52, 58, 54, 60], backgroundColor: '#0f172a' },
                { type: 'bar', label: 'Others', data: [30, 28, 32, 35, 30, 34, 38, 36, 40, 38, 42], backgroundColor: '#93c5fd' },
                { type: 'line', label: 'Cumulative', data: [70, 63, 77, 85, 72, 82, 93, 88, 98, 92, 102], borderColor: '#f59e0b', fill: false }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
