@extends('layouts.dashboard')

@section('title', '- Kelola SPV')
@section('page_title', 'Kelola Data Supervisor')

@section('content')
<section class="space-y-2">
    <h2 class="text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Daftar Supervisor</h2>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Daftar semua Supervisor (SPV) dan area tugasnya.
    </p>
</section>

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.spv.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">
        <span class="material-symbols-outlined text-lg mr-1.5">person_add</span>
        Tambah SPV
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant">
    <x-data-table :headers="['Nama SPV', 'Email', 'Area Ditugaskan']" :actions="true" :pagination="$spvs->links()">
        @forelse($spvs as $spv)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-xs mr-3 shadow-sm">
                            {{ strtoupper(substr($spv->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-bold text-on-surface">{{ $spv->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-on-surface-variant">
                    {{ $spv->email }}
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex flex-wrap gap-1">
                        @forelse($spv->areas as $area)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-primary-container/30 text-primary">
                                {{ $area->nama }}
                            </span>
                        @empty
                            <span class="text-xs text-on-surface-variant italic">Belum ada area</span>
                        @endforelse
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.spv.edit', $spv->id) }}" class="inline-flex items-center text-primary hover:text-primary-fixed bg-primary-container/20 hover:bg-primary-container/40 px-3 py-1.5 rounded-lg transition-colors mr-2">
                        <span class="material-symbols-outlined text-base mr-1">edit</span>
                        Edit
                    </a>
                    <form action="{{ route('admin.spv.destroy', $spv->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SPV ini? Data laporan terkait mungkin akan terpengaruh.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center text-error hover:bg-error-container/20 px-3 py-1.5 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-base mr-1">delete</span>
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">person_off</span>
                    <p class="text-sm text-on-surface-variant">Belum ada data SPV.</p>
                </td>
            </tr>
        @endforelse
    </x-data-table>
</div>
@endsection
