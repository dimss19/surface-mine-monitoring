@extends('layouts.dashboard')

@section('title', '- Admin Dashboard')
@section('page_title', 'Dashboard Administrator')

@section('content')
    <section class="space-y-2">
        <h2 class="text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Halo, Admin!</h2>
        <p class="text-base text-on-surface-variant max-w-2xl">
            Pantau aktivitas operasional, kelola akun SPV, dan lihat laporan pemantauan secara real-time.
        </p>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-surface-container-high rounded-xl p-6 border border-outline-variant flex flex-col justify-between group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Total SPV</span>
                    <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-3xl">supervisor_account</span>
                    </div>
                </div>
                <div class="mt-8">
                    <span class="text-6xl font-bold text-on-surface leading-none">{{ $metrics['total_spv'] }}</span>
                </div>
            </div>

            <div class="bg-surface-container-high rounded-xl p-6 border border-outline-variant flex flex-col justify-between group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Total Area Kerja</span>
                    <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-3xl">public</span>
                    </div>
                </div>
                <div class="mt-8">
                    <span class="text-6xl font-bold text-on-surface leading-none">{{ $metrics['total_area'] }}</span>
                </div>
            </div>

            <div class="bg-surface-container-high rounded-xl p-6 border border-outline-variant flex flex-col justify-between group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Laporan Hari Ini</span>
                    <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-3xl">description</span>
                    </div>
                </div>
                <div class="mt-8">
                    <span class="text-6xl font-bold text-on-surface leading-none">{{ $metrics['laporan_hari_ini'] }}</span>
                </div>
            </div>

            <div class="md:col-span-1 bg-surface-container rounded-xl p-6 border-l-4 border-primary flex items-start space-x-4 shadow-lg">
                <span class="material-symbols-outlined text-primary-container text-4xl mt-1">info</span>
                <div class="space-y-1">
                    <h4 class="text-lg font-bold text-on-surface">Informasi Sistem</h4>
                    <p class="text-sm text-on-surface-variant">Data dashboard diperbarui secara real-time. Pantau kinerja SPV dan progres laporan harian.</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 h-full">
            <a href="{{ route('admin.spv.index') }}" class="w-full h-full bg-primary-container rounded-xl p-8 text-left flex flex-col justify-center items-center group relative overflow-hidden transition-all duration-300 hover:shadow-[0_0_40px_rgba(226,35,26,0.3)] hover:-translate-y-1 block">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                    <span class="material-symbols-outlined text-[120px] select-none">group_add</span>
                </div>
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-white text-5xl font-bold">add</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Kelola SPV</h3>
                <p class="text-white/80 text-center text-base max-w-xs">
                    Tambah, edit, atau nonaktifkan akun supervisor untuk menjaga integritas data operasional.
                </p>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-white/10" style="clip-path: polygon(100% 0, 0 100%, 100% 100%);"></div>
            </a>
        </div>
    </div>

    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-on-surface">Daftar Supervisor (SPV)</h2>
            <a href="{{ route('admin.spv.index') }}" class="text-sm font-bold text-primary hover:text-primary-fixed transition-colors">Lihat Semua &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($spvList as $spv)
                <x-spv-card
                    :spv="$spv"
                    :areaCount="$spv->areas_count"
                    :latestReport="$spv->pemantauanLapangans->first()"
                />
            @endforeach

            @if($spvList->isEmpty())
                <div class="col-span-full py-12 bg-surface-container-high border border-dashed border-outline-variant rounded-xl text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant">person_off</span>
                    <h3 class="mt-2 text-sm font-bold text-on-surface">Belum Ada SPV</h3>
                    <p class="mt-1 text-sm text-on-surface-variant">Silakan tambahkan akun SPV baru.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant">
        <div class="p-6 md:p-8 border-b border-outline-variant">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-on-surface">Semua Laporan Pemantauan</h3>
                    <p class="mt-1 text-sm text-on-surface-variant">Riwayat laporan dari semua SPV dan area kerja.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="block w-full sm:w-auto rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-4 py-2.5 transition-colors">

                        <select name="spv_id" class="block w-full sm:w-auto rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-4 py-2.5 transition-colors cursor-pointer">
                            <option value="">Semua SPV</option>
                            @foreach($spvs as $id => $name)
                                <option value="{{ $id }}" {{ request('spv_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="w-full sm:w-auto bg-primary-container text-on-primary-container px-5 py-2.5 rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg mr-1">filter_alt</span>
                            Filter
                        </button>
                    </form>

                    <a href="{{ route('admin.export', request()->all()) }}" target="_blank" class="w-full sm:w-auto bg-[#1b5e20] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#2e7d32] transition-all shadow-sm hover:shadow-md flex items-center justify-center whitespace-nowrap">
                        <span class="material-symbols-outlined text-lg mr-1">download</span>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        <x-data-table :headers="['SPV', 'Tanggal & Shift', 'Area', 'Progress', 'Status']" :actions="true" :pagination="$pemantauans->links()">
            @forelse($pemantauans as $item)
                <tr class="hover:bg-surface-variant/50 transition-colors group/row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-xs mr-3 shadow-sm">
                                {{ strtoupper(substr($item->spv->name ?? '?', 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-on-surface">{{ $item->spv->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-on-surface">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                        <div class="text-xs text-on-surface-variant mt-0.5 inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container font-medium">Shift {{ ucfirst($item->shift) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-on-surface flex items-center">
                            <span class="material-symbols-outlined text-base text-on-surface-variant mr-1.5">location_on</span>
                            {{ $item->area->nama ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-bold text-on-surface w-10">{{ $item->progress_persen }}%</span>
                            <div class="w-24 bg-surface-container rounded-full h-2 ml-2 shadow-inner overflow-hidden">
                                <div class="h-2 rounded-full {{ $item->progress_persen == 100 ? 'bg-[#2e7d32]' : 'bg-primary' }} transition-all duration-1000" style="width: {{ $item->progress_persen }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->progress_status == 'selesai')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-[#1b5e20]/30 text-[#66bb6a] border border-[#2e7d32]">Selesai</span>
                        @elseif($item->progress_status == 'proses')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-[#e65100]/30 text-[#ffb74d] border border-[#e65100]">Proses</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-surface-container text-on-surface-variant border border-outline-variant">Belum Mulai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" onclick="alert('Detail Laporan SPV')" class="inline-flex items-center text-primary hover:text-primary-fixed bg-primary-container/20 hover:bg-primary-container/40 px-3 py-1.5 rounded-lg transition-colors">
                            Detail
                            <span class="material-symbols-outlined text-base ml-1 opacity-0 group-hover/row:opacity-100 transition-opacity">chevron_right</span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">description</span>
                        <h3 class="text-sm font-bold text-on-surface">Belum ada laporan</h3>
                        <p class="mt-1 text-sm text-on-surface-variant">Belum ada laporan pemantauan lapangan dari SPV yang sesuai dengan filter.</p>
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </div>
@endsection
