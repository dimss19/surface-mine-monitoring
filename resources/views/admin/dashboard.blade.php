@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Monitoring Dashboard</h1>
    <p class="text-slate-500">Live operational telemetry and site metrics.</p>
</div>

{{-- Action buttons --}}
<div class="flex gap-3 mb-6">
    <a href="{{ route('admin.export') }}" class="btn-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <span class="material-symbols-outlined text-lg">sync</span>
        Sync Data
    </button>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <x-kpi-card title="Total Ritasi" value="{{ number_format($metrics['total_ritasi'] ?? 1284) }}" icon="local_shipping" color="blue" />
    <x-kpi-card title="Total Unit Aktif" value="{{ $metrics['unit_aktif'] ?? '42 / 50' }}" icon="precision_manufacturing" color="orange" />
    <x-kpi-card title="Total Jam Kerja" value="{{ $metrics['jam_kerja'] ?? '342h' }}" icon="schedule" color="green" />
    <x-kpi-card title="Pekerjaan General" value="{{ $metrics['general_tasks'] ?? '18 Tasks' }}" icon="assignment" color="purple" />
</div>

{{-- Daily Breakdown Activity --}}
<div class="card p-6 mb-6">
    <h2 class="text-lg font-heading font-bold mb-4">Daily Breakdown Activity</h2>
    <div class="grid grid-cols-3 gap-6">
        {{-- Bar Chart --}}
        <div>
            <div class="bg-slate-100 rounded-lg p-4 mb-4 text-center">
                <span class="text-sm text-slate-500">Daily All Hauling</span>
                <p class="text-2xl font-bold text-[var(--accent)]">4,278</p>
            </div>
            <canvas id="dailyChart" height="200"></canvas>
        </div>

        {{-- Day Shift --}}
        <div>
            <h3 class="text-center font-semibold mb-4">Day Shift</h3>
            <div class="space-y-3">
                @foreach([
                    ['name' => 'EX022 Long Arm', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX024 PC320', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX025 PC320', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX027 PC340', 'hours' => 7.5, 'type' => 'active'],
                    ['name' => 'EX028 PC320', 'hours' => 7.0, 'type' => 'active'],
                    ['name' => 'EX029 SY215', 'hours' => 8.0, 'type' => 'partial'],
                    ['name' => 'EX032 SY215', 'hours' => 8.0, 'type' => 'partial'],
                    ['name' => 'EX033 SY215', 'hours' => 6.0, 'type' => 'active'],
                    ['name' => 'EX034 SY215', 'hours' => 4.0, 'type' => 'partial'],
                ] as $unit)
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-600 w-24 truncate" title="{{ $unit['name'] }}">{{ $unit['name'] }}</span>
                        <div class="flex-1 bg-slate-100 rounded-full h-4 overflow-hidden">
                            <div class="h-4 rounded-full {{ $unit['type'] === 'active' ? 'bg-red-400' : 'bg-green-400' }}"
                                 style="width: {{ ($unit['hours'] / 12) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs text-slate-400 w-24"></span>
                    <div class="flex-1 flex justify-between text-[10px] text-slate-400">
                        <span>0.0</span><span>4.0</span><span>8.0</span><span>12.0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Night Shift --}}
        <div>
            <h3 class="text-center font-semibold mb-4">Night Shift</h3>
            <div class="space-y-3">
                @foreach([
                    ['name' => 'EX022 Long Arm', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX024 PC320', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX025 PC320', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX027 PC340', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX028 PC320', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX029 SY215', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX032 SY215', 'hours' => 8.0, 'type' => 'active'],
                    ['name' => 'EX033 SY215', 'hours' => 7.0, 'type' => 'active'],
                    ['name' => 'EX034 SY215', 'hours' => 8.0, 'type' => 'active'],
                ] as $unit)
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-600 w-24 truncate" title="{{ $unit['name'] }}">{{ $unit['name'] }}</span>
                        <div class="flex-1 bg-slate-100 rounded-full h-4 overflow-hidden">
                            <div class="h-4 rounded-full {{ $unit['type'] === 'active' ? 'bg-red-400' : 'bg-green-400' }}"
                                 style="width: {{ ($unit['hours'] / 12) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs text-slate-400 w-24"></span>
                    <div class="flex-1 flex justify-between text-[10px] text-slate-400">
                        <span>0.0</span><span>4.0</span><span>8.0</span><span>12.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik All Hauling (WTD) --}}
<div class="card p-6 mb-6">
    <h2 class="text-lg font-heading font-bold mb-4">Grafik All Hauling (WTD)</h2>
    <div class="grid grid-cols-2 gap-6">
        {{-- Weekly Chart --}}
        <div>
            <div class="bg-slate-100 rounded-lg p-4 mb-4">
                <span class="text-sm text-slate-500">Weekly All Hauling</span>
                <p class="text-2xl font-bold text-[var(--accent)]">8,568</p>
            </div>
            <canvas id="weeklyChart" height="220"></canvas>
        </div>

        {{-- Availability & UoA --}}
        <div>
            {{-- Availability --}}
            <div class="mb-6">
                <h3 class="font-semibold mb-4">Availability</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['Exc' => '45.5%', 'Sany' => '33.3%', 'ADT' => '66.7%', 'Dozer' => '31.3%'] as $name => $value)
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto relative">
                                <canvas id="avail-{{ $name }}" width="80" height="80"></canvas>
                                <span class="absolute inset-0 flex items-center justify-center text-xs font-bold">{{ $value }}</span>
                            </div>
                            <p class="text-xs mt-1 text-slate-600">{{ $name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- UoA --}}
            <div>
                <h3 class="font-semibold mb-4">UoA</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['Exc' => '49.1%', 'Sany' => '41.8%', 'ADT' => '74.4%', 'Dozer' => '38.2%'] as $name => $value)
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto relative">
                                <canvas id="uoa-{{ $name }}" width="80" height="80"></canvas>
                                <span class="absolute inset-0 flex items-center justify-center text-xs font-bold">{{ $value }}</span>
                            </div>
                            <p class="text-xs mt-1 text-slate-600">{{ $name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly MTD Report --}}
<div class="card p-6">
    <h2 class="text-lg font-heading font-bold mb-4">Monthly - MTD Report</h2>
    <div class="bg-slate-100 rounded-lg p-4 mb-4">
        <span class="text-sm text-slate-500">All Materials Hauling</span>
        <p class="text-2xl font-bold text-[var(--accent)]">75,709</p>
    </div>
    <canvas id="monthlyChart" height="150"></canvas>
    <div class="flex justify-center gap-6 mt-4">
        <div class="flex items-center gap-2 text-sm">
            <span class="w-3 h-3 rounded bg-[#0f172a] inline-block"></span> Ore
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="w-3 h-3 rounded bg-[#93c5fd] inline-block"></span> Others
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="w-8 h-0.5 bg-[#f59e0b] inline-block"></span> Cumulative
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accentColor = '#f59e0b';
    const primaryColor = '#0f172a';
    const blueColor = '#3b82f6';
    const lightBlueColor = '#93c5fd';
    const greenColor = '#22c55e';

    // Daily Chart
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: ['Tuff Paste', 'KCN', 'CakeDST', 'Tuff Off', 'Batu Pica', 'Mining Tuff', 'Pasir Hitam', 'Mud', 'Lumpur', 'Waste'],
            datasets: [{
                data: [1227, 700, 260, 175, 120, 90, 40, 26, 0, 0],
                backgroundColor: primaryColor,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { font: { size: 10 } } },
                x: { ticks: { font: { size: 9 }, maxRotation: 45 } }
            }
        }
    });

    // Weekly Chart
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: ['Waste', 'Mud - Lumpur', 'Pasir Hitam', 'Mining Tuff', 'Batu Pica (5/15)', 'Tuff Off', 'CakeDST'],
            datasets: [{
                data: [26, 40, 90, 120, 175, 260, 700],
                backgroundColor: primaryColor,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { font: { size: 10 } } },
                y: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // Monthly Chart
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: ['1-May', '4-May', '7-May', '10-May', '13-May', '16-May', '19-May', '22-May', '25-May', '28-May', '31-May'],
            datasets: [
                { type: 'bar', label: 'Ore', data: [40, 35, 45, 50, 42, 48, 55, 52, 58, 54, 60], backgroundColor: primaryColor, borderRadius: 2 },
                { type: 'bar', label: 'Others', data: [30, 28, 32, 35, 30, 34, 38, 36, 40, 38, 42], backgroundColor: lightBlueColor, borderRadius: 2 },
                { type: 'line', label: 'Cumulative', data: [70, 63, 77, 85, 72, 82, 93, 88, 98, 92, 102], borderColor: accentColor, fill: false, tension: 0.3, pointRadius: 3 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, stacked: true },
                x: { stacked: true }
            }
        }
    });

    // Donut charts for Availability
    function createDonut(canvasId, value, color) {
        const numValue = parseFloat(value);
        new Chart(document.getElementById(canvasId), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [numValue, 100 - numValue],
                    backgroundColor: [color, '#e2e8f0'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
            }
        });
    }

    // Availability donuts
    createDonut('avail-Exc', '45.5', blueColor);
    createDonut('avail-Sany', '33.3', blueColor);
    createDonut('avail-ADT', '66.7', blueColor);
    createDonut('avail-Dozer', '31.3', blueColor);

    // UoA donuts
    createDonut('uoa-Exc', '49.1', greenColor);
    createDonut('uoa-Sany', '41.8', greenColor);
    createDonut('uoa-ADT', '74.4', greenColor);
    createDonut('uoa-Dozer', '38.2', greenColor);
});
</script>
@endpush
