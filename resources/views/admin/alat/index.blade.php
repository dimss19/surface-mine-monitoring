@extends('layouts.dashboard')

@section('title', '- Kelola Alat')
@section('page_title', 'Kelola Master Data Alat')

@section('content')
<section class="space-y-2">
    <h2 class="text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Daftar Alat</h2>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Daftar semua alat yang digunakan dalam operasional tambang.
    </p>
</section>

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.alat.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">
        <span class="material-symbols-outlined text-lg mr-1.5">add</span>
        Tambah Alat
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant max-w-4xl">
    <x-data-table :headers="['Nama Alat', 'Jenis Alat']" :actions="true" :pagination="$alats->links()">
        @forelse($alats as $alat)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-lg bg-surface-container flex items-center justify-center text-primary mr-3">
                            <span class="material-symbols-outlined text-lg">construction</span>
                        </div>
                        <span class="text-sm font-bold text-on-surface">{{ $alat->nama }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-surface-container text-on-surface-variant capitalize border border-outline-variant">
                        {{ $alat->jenis }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.alat.edit', $alat->id) }}" class="inline-flex items-center text-primary hover:text-primary-fixed bg-primary-container/20 hover:bg-primary-container/40 px-3 py-1.5 rounded-lg transition-colors mr-2">
                        <span class="material-symbols-outlined text-base mr-1">edit</span>
                        Edit
                    </a>
                    <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Alat ini?')">
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
                <td colspan="3" class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">hardware</span>
                    <p class="text-sm text-on-surface-variant">Belum ada data Alat.</p>
                </td>
            </tr>
        @endforelse
    </x-data-table>
</div>
@endsection
