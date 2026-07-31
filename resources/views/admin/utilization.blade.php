@extends('layouts.admin')

@section('title', 'Utilization')
@section('page-title', 'Utilization')

@section('content')
<div class="mb-6 fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text); font-family: 'Plus Jakarta Sans', sans-serif;">Utilization</h1>
            <p class="text-slate-500">Pemanfaatan unit hari ini</p>
        </div>
        <div class="flex items-center gap-2">
            <select id="statusFilter" class="form-input w-auto" onchange="filterUnits()">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="standby">Standby</option>
                <option value="maintenance">Maintenance</option>
                <option value="breakdown">Breakdown</option>
            </select>
        </div>
    </div>
</div>

{{-- Summary Stats --}}
@php
    $running = $utilization->where('status', 'active')->count();
    $standby = $utilization->where('status', 'standby')->count();
    $maintenance = $utilization->where('status', 'maintenance')->count();
    $breakdown = $utilization->where('status', 'breakdown')->count();
    $totalHours = $utilization->sum('hours_today');
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 fade-in" style="animation-delay: 50ms;">
    <div class="stat-card border-l-4 border-green-500">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-green-500">play_circle</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Running</p>
            <p class="text-2xl font-bold text-green-600">{{ $running }}</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-amber-500">
        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-500">pause_circle</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Standby</p>
            <p class="text-2xl font-bold text-amber-600">{{ $standby }}</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-blue-500">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-500">build</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Maintenance</p>
            <p class="text-2xl font-bold text-blue-600">{{ $maintenance }}</p>
        </div>
    </div>
    <div class="stat-card border-l-4 border-red-500">
        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-red-500">error</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Breakdown</p>
            <p class="text-2xl font-bold text-red-600">{{ $breakdown }}</p>
        </div>
    </div>
</div>

{{-- Total Hours --}}
<div class="card mb-6 fade-in" style="animation-delay: 100ms;">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                <span class="material-symbols-outlined text-white text-2xl">schedule</span>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Jam Hari Ini</p>
                <p class="text-3xl font-bold" style="color: var(--text);">{{ number_format($totalHours, 1) }} <span class="text-lg font-normal text-slate-500">jam</span></p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm text-slate-500">Target</p>
            <p class="text-xl font-bold" style="color: var(--accent);">{{ $utilization->count() * 8 }} jam</p>
        </div>
    </div>
</div>

{{-- Unit Cards --}}
<div class="mb-4 fade-in" style="animation-delay: 150ms;">
    <h2 class="section-title mb-4">Daftar Unit</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 fade-in" style="animation-delay: 200ms;" id="unitGrid">
    @forelse($currentPageItems as $item)
        @php
            $unit = $item['unit'];
            $statusColors = [
                'active' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'dot' => 'bg-green-500'],
                'standby' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                'maintenance' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
                'breakdown' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'dot' => 'bg-red-500'],
            ];
            $colors = $statusColors[$unit->status] ?? $statusColors['active'];
            $utilPct = $item['utilization_pct'];
            $barColor = $utilPct >= 75 ? 'bg-green-500' : ($utilPct >= 50 ? 'bg-amber-500' : 'bg-red-500');
        @endphp
        
        <div class="card p-0 overflow-hidden unit-card" data-status="{{ $unit->status }}">
            {{-- Header --}}
            <div class="p-4 border-b border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $colors['dot'] }}"></span>
                        <span class="badge {{ $colors['bg'] }} {{ $colors['text'] }}">{{ ucfirst($unit->status) }}</span>
                    </div>
                    <button onclick="openModal('{{ $unit->id }}', '{{ $unit->nama }}', '{{ $unit->status }}', '{{ $unit->keterangan }}')" 
                            class="p-1.5 rounded-lg hover:bg-slate-100 transition-colors" title="Ubah Status">
                        <span class="material-symbols-outlined text-slate-400 text-lg">edit</span>
                    </button>
                </div>
                <h3 class="font-bold text-lg" style="color: var(--text);">{{ $unit->nama }}</h3>
                <p class="text-xs text-slate-500">{{ $unit->kode }} • {{ $unit->tipe }}</p>
            </div>
            
            {{-- Body --}}
            <div class="p-4">
                {{-- Area --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-slate-400 text-sm">location_on</span>
                    <span class="text-sm text-slate-600">{{ $unit->areas->pluck('nama')->join(', ') ?: '-' }}</span>
                </div>
                
                {{-- Hours --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-slate-500">Jam Kerja</span>
                    <span class="font-bold" style="color: var(--text);">{{ number_format($item['hours_today'], 1) }} / {{ $item['target'] }} jam</span>
                </div>
                
                {{-- Utilization Bar --}}
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mb-3">
                    <div class="h-full {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $utilPct }}%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium" style="color: var(--accent);">{{ $utilPct }}%</span>
                    @if($item['last_update'])
                        <span class="text-xs text-slate-400">{{ $item['last_update']->diffForHumans() }}</span>
                    @endif
                </div>
                
                {{-- Keterangan --}}
                @if($unit->keterangan)
                    <div class="mt-3 p-2 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500">{{ $unit->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="card p-12 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-3">construction</span>
                <p class="text-slate-500">Belum ada unit</p>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($totalPages > 1)
<div class="flex items-center justify-center gap-2 mt-6 fade-in" style="animation-delay: 250ms;">
    @if($currentPage > 1)
        <a href="{{ route('admin.utilization.index', ['page' => $currentPage - 1, 'status' => $statusFilter]) }}" 
           class="px-3 py-2 rounded-lg border border-slate-200 text-sm font-medium hover:bg-slate-50 transition-colors">
            <span class="material-symbols-outlined text-lg">chevron_left</span>
        </a>
    @endif
    
    @for($i = 1; $i <= $totalPages; $i++)
        <a href="{{ route('admin.utilization.index', ['page' => $i, 'status' => $statusFilter]) }}" 
           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ $i === $currentPage ? 'text-white' : 'border border-slate-200 hover:bg-slate-50' }}"
           @if($i === $currentPage) style="background: var(--primary);" @endif>
            {{ $i }}
        </a>
    @endfor
    
    @if($currentPage < $totalPages)
        <a href="{{ route('admin.utilization.index', ['page' => $currentPage + 1, 'status' => $statusFilter]) }}" 
           class="px-3 py-2 rounded-lg border border-slate-200 text-sm font-medium hover:bg-slate-50 transition-colors">
            <span class="material-symbols-outlined text-lg">chevron_right</span>
        </a>
    @endif
</div>
@endif

{{-- Modal Edit Status --}}
<div id="statusModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold" style="color: var(--text);">Ubah Status Unit</h3>
                <button onclick="closeModal()" class="p-1 rounded-lg hover:bg-slate-100">
                    <span class="material-symbols-outlined text-slate-400">close</span>
                </button>
            </div>
            
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="unitId" name="unit_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text);">Unit</label>
                    <input type="text" id="unitName" class="form-input bg-slate-50" readonly>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text);">Status</label>
                    <select name="status" id="statusSelect" class="form-input" required>
                        <option value="active">Aktif (Running)</option>
                        <option value="standby">Standby</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="breakdown">Breakdown (Rusak)</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text);">Keterangan</label>
                    <textarea name="keterangan" id="keteranganInput" class="form-input" rows="3" placeholder="Catatan opsional..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn-primary flex-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterUnits() {
    const filter = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.unit-card');
    
    cards.forEach(card => {
        if (filter === 'all' || card.dataset.status === filter) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function openModal(id, name, status, keterangan) {
    document.getElementById('unitId').value = id;
    document.getElementById('unitName').value = name;
    document.getElementById('statusSelect').value = status;
    document.getElementById('keteranganInput').value = keterangan || '';
    document.getElementById('statusForm').action = `/admin/utilization/${id}`;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endpush
@endsection
