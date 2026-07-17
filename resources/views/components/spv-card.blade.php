@props(['spv', 'areaCount' => 0, 'latestReport' => null])

<div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 ease-in-out">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dt class="text-lg font-semibold text-gray-900 truncate">
                    {{ $spv->name }}
                </dt>
                <dd>
                    <div class="text-sm text-gray-500">
                        {{ $spv->email }}
                    </div>
                </dd>
            </div>
        </div>
        
        <div class="mt-4 border-t border-gray-100 pt-4">
            <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                <div class="sm:col-span-1">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Area Aktif</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $areaCount }} Lokasi</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Laporan Terakhir</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($latestReport)
                            {{ \Carbon\Carbon::parse($latestReport->tanggal)->format('d M Y') }} ({{ ucfirst($latestReport->shift) }})
                        @else
                            <span class="text-gray-400 italic">Belum ada</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="bg-gray-50 px-4 py-4 sm:px-6">
        <div class="text-sm">
            <a href="{{ route('admin.spv.show', $spv->id) }}" class="font-medium text-blue-600 hover:text-blue-500 flex items-center">
                Lihat Detail SPV
                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>