@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold" style="color: var(--text); font-family: 'Plus Jakarta Sans', sans-serif;">Monitoring Dashboard</h1>
    <p class="text-slate-500">Live operational telemetry and site metrics.</p>
</div>

{{-- Info Panel --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="stat-card border-l-4 border-blue-500">
        <div>
            <p class="text-sm text-slate-500">Total Jam Running</p>
            <p class="text-2xl font-bold">{{ $totalHours ?? 0 }} jam</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-green-500">
        <div>
            <p class="text-sm text-slate-500">Pencapaian</p>
            <p class="text-2xl font-bold">{{ $pencapaian ?? 0 }}%</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-amber-500">
        <div>
            <p class="text-sm text-slate-500">Unit Running</p>
            <p class="text-2xl font-bold">{{ $runningUnits ?? 0 }} unit</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-slate-400">
        <div>
            <p class="text-sm text-slate-500">Unit Standby</p>
            <p class="text-2xl font-bold">{{ $standbyUnits ?? 0 }} unit</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-red-500">
        <div>
            <p class="text-sm text-slate-500">Unit BD</p>
            <p class="text-2xl font-bold">{{ $bdUnits ?? 0 }} unit</p>
        </div>
    </div>
</div>

{{-- Action buttons --}}
<div class="flex gap-3 mb-6 justify-end">
    <a href="{{ route('admin.laporan.index') }}" class="bg-white border border-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-slate-50 transition-colors">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
    <button class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <span class="material-symbols-outlined text-lg">sync</span>
        Sync Data
    </button>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card" style="border-left: 4px solid #3b82f6;">
        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-500 text-xl">local_shipping</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">Total Ritasi</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ number_format($metrics['total_ritasi'] ?? 0) }}</p>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #10b981;">
        <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-green-500 text-xl">precision_manufacturing</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">Total Unit Aktif</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $metrics['unit_aktif'] ?? '0 / 0' }}</p>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #f59e0b;">
        <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-500 text-xl">schedule</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">Total Jam Kerja</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $metrics['jam_kerja'] ?? '0h' }}</p>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
        <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-purple-500 text-xl">assignment</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">Pekerjaan General</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $metrics['general_tasks'] ?? '0 Tasks' }}</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-orange-500">
        <span class="material-symbols-outlined text-orange-500">local_gas_station</span>
        <div>
            <p class="text-sm text-slate-500">Total Fuel Hari Ini</p>
            <p class="text-2xl font-bold">{{ $totalFuel ?? 0 }} Liter</p>
        </div>
    </div>
</div>

{{-- Target Bar --}}
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <h2 class="section-title mb-4">Target Harian Material</h2>
    <div class="space-y-4">
        @foreach($targets ?? [] as $item)
        <div class="flex items-center gap-4">
            <span class="w-20 font-bold text-sm">{{ $item['material'] }}</span>
            <div class="flex-1 bg-gray-200 rounded-full h-6 overflow-hidden">
                <div class="h-6 rounded-full flex items-center justify-center text-xs text-white font-medium
                    {{ $item['percentage'] >= 100 ? 'bg-green-500' : ($item['percentage'] >= 75 ? 'bg-yellow-500' : 'bg-red-500') }}"
                    style="width: {{ min(100, $item['percentage']) }}%">
                    {{ $item['actual'] }}/{{ $item['target'] }}
                </div>
            </div>
            <span class="w-16 text-right text-sm font-bold">{{ $item['percentage'] }}%</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Daily Breakdown Activity --}}
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <h2 class="section-title mb-4">Daily Breakdown Activity</h2>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Bar Chart Placeholder --}}
        <div class="bg-slate-50 rounded-lg p-4">
            <div class="bg-white rounded-lg px-4 py-2 mb-4 inline-block">
                <p class="text-sm font-semibold">Daily All Hauling</p>
                <p class="text-2xl font-bold text-amber-500">{{ number_format($metrics['daily_hauling'] ?? 0) }}</p>
            </div>
            <canvas id="dailyChart" height="200"></canvas>
        </div>
        {{-- Day Shift --}}
        <div>
            <h3 class="font-bold text-center mb-4">Day Shift</h3>
            <div class="space-y-2">
                @php $shifts = $metrics['day_shift'] ?? []; @endphp
                @forelse($shifts as $shift)
                    <div class="flex items-center gap-2">
                        <span class="text-xs w-24 truncate">{{ $shift['unit'] ?? '-' }}</span>
                        <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-red-400 rounded-full" style="width: {{ $shift['percent'] ?? 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center text-sm py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
        {{-- Night Shift --}}
        <div>
            <h3 class="font-bold text-center mb-4">Night Shift</h3>
            <div class="space-y-2">
                @php $shifts = $metrics['night_shift'] ?? []; @endphp
                @forelse($shifts as $shift)
                    <div class="flex items-center gap-2">
                        <span class="text-xs w-24 truncate">{{ $shift['unit'] ?? '-' }}</span>
                        <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-400 rounded-full" style="width: {{ $shift['percent'] ?? 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center text-sm py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Jam Values per Unit --}}
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <h2 class="section-title mb-4">Jam Kerja Unit ({{ $currentShift === 'siang' ? 'Day Shift' : 'Night Shift' }})</h2>
    <div class="space-y-3">
        @foreach($unitHours ?? [] as $item)
        <div class="flex items-center gap-2">
            <span class="w-24 text-sm font-medium">{{ $item['unit'] }}</span>
            <div class="flex-1 flex h-8 rounded overflow-hidden">
                <div class="bg-green-500 flex items-center justify-center text-xs text-white font-medium"
                     style="width: {{ ($item['actual'] / $item['target']) * 100 }}%">
                    {{ $item['actual'] }}h
                </div>
                @if($item['remaining'] > 0)
                <div class="bg-red-500 flex items-center justify-center text-xs text-white font-medium"
                     style="width: {{ ($item['remaining'] / $item['target']) * 100 }}%">
                    {{ $item['remaining'] }}h
                </div>
                @endif
            </div>
            <span class="w-12 text-right text-sm font-bold">{{ $item['target'] }}h</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Grafik All Hauling --}}
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <h2 class="section-title mb-4">Grafik All Hauling (WTD)</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Material Breakdown --}}
        <div class="bg-slate-50 rounded-lg p-4">
            <div class="bg-white rounded-lg px-4 py-2 mb-4 inline-block">
                <p class="text-sm font-semibold">Weekly All Hauling</p>
                <p class="text-2xl font-bold text-amber-500">{{ number_format($metrics['weekly_hauling'] ?? 0) }}</p>
            </div>
            <div class="space-y-2">
                @php $materials = $metrics['material_breakdown'] ?? []; @endphp
                @forelse($materials as $mat)
                    <div class="flex items-center gap-3">
                        <span class="text-sm w-32">{{ $mat['name'] ?? '-' }}</span>
                        <div class="w-4 h-4 bg-slate-700 rounded"></div>
                        <span class="text-sm font-semibold">{{ $mat['total'] ?? 0 }}</span>
                    </div>
                @empty
                    <p class="text-slate-400 text-center text-sm py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
        {{-- Availability & UoA --}}
        <div class="space-y-6">
            <div>
                <h3 class="font-bold mb-3">Availability</h3>
                <div class="grid grid-cols-4 gap-4">
                    @php $avail = $metrics['availability'] ?? []; @endphp
                    @foreach(['Exc', 'Sany', 'ADT', 'Dozer'] as $type)
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-full border-4 border-blue-200 border-t-blue-500 flex items-center justify-center" style="transform: rotate({{ $avail[$type]['deg'] ?? 0 }}deg);">
                            </div>
                            <p class="text-sm font-semibold mt-2">{{ $type }}</p>
                            <p class="text-xs text-slate-500">{{ $avail[$type]['percent'] ?? '0%' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h3 class="font-bold mb-3">UoA</h3>
                <div class="grid grid-cols-4 gap-4">
                    @php $uoa = $metrics['uoa'] ?? []; @endphp
                    @foreach(['Exc', 'Sany', 'ADT', 'Dozer'] as $type)
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-full border-4 border-purple-200 border-t-purple-500 flex items-center justify-center" style="transform: rotate({{ $uoa[$type]['deg'] ?? 0 }}deg);">
                            </div>
                            <p class="text-sm font-semibold mt-2">{{ $type }}</p>
                            <p class="text-xs text-slate-500">{{ $uoa[$type]['percent'] ?? '0%' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly MTD Report --}}
<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h2 class="section-title mb-4">Monthly - MTD Report</h2>
    <div class="bg-slate-50 rounded-lg p-4">
        <div class="bg-white rounded-lg px-4 py-2 mb-4 inline-block">
            <p class="text-sm font-semibold">All Materials Hauling</p>
            <p class="text-2xl font-bold text-amber-500">{{ number_format($metrics['monthly_hauling'] ?? 0) }}</p>
        </div>
        <canvas id="monthlyChart" height="150"></canvas>
    </div>
    <div class="flex items-center justify-center gap-6 mt-4">
        <div class="flex items-center gap-2"><div class="w-4 h-4 bg-blue-600 rounded"></div><span class="text-sm">Ore</span></div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 bg-blue-200 rounded"></div><span class="text-sm">Others</span></div>
        <div class="flex items-center gap-2"><div class="w-4 h-1 bg-blue-500 rounded"></div><span class="text-sm">Cumulative</span></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Chart
    const dailyCtx = document.getElementById('dailyChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($metrics['daily_labels'] ?? ['Tuff', 'Paste', 'KCN', 'Cake', 'Ore', 'Batu', 'Mining', 'Pasir', 'Mud', 'Waste']) !!},
                datasets: [{
                    data: {!! json_encode($metrics['daily_data'] ?? [1227, 700, 260, 175, 120, 90, 40, 26, 0, 0]) !!},
                    backgroundColor: '#1e3a5f',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($metrics['monthly_labels'] ?? []) !!},
                datasets: [
                    {
                        type: 'bar',
                        label: 'Ore',
                        data: {!! json_encode($metrics['monthly_ore'] ?? []) !!},
                        backgroundColor: '#1e3a5f',
                        borderRadius: 4
                    },
                    {
                        type: 'bar',
                        label: 'Others',
                        data: {!! json_encode($metrics['monthly_others'] ?? []) !!},
                        backgroundColor: '#93c5fd',
                        borderRadius: 4
                    },
                    {
                        type: 'line',
                        label: 'Cumulative',
                        data: {!! json_encode($metrics['monthly_cumulative'] ?? []) !!},
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    }
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
    }
</script>
@endpush
@endsection
