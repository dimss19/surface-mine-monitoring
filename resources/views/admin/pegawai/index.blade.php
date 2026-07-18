@extends('layouts.dashboard')

@section('title', '- Kelola Pegawai')
@section('page_title', 'Kelola Data Pegawai')

@section('content')
<section class="space-y-2">
    <h2 class="text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Daftar Pegawai</h2>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Daftar semua pegawai yang terdaftar dalam sistem.
    </p>
</section>

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.pegawai.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">
        <span class="material-symbols-outlined text-lg mr-1.5">person_add</span>
        Tambah Pegawai
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant max-w-2xl">
    <x-data-table :headers="['Nama Pegawai']" :actions="true" :pagination="$pegawais->links()">
        @forelse($pegawais as $pegawai)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-xs mr-3 shadow-sm">
                            {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                        </div>
                        <span class="text-sm font-bold text-on-surface">{{ $pegawai->nama }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="inline-flex items-center text-primary hover:text-primary-fixed bg-primary-container/20 hover:bg-primary-container/40 px-3 py-1.5 rounded-lg transition-colors mr-2">
                        <span class="material-symbols-outlined text-base mr-1">edit</span>
                        Edit
                    </a>
                    <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')">
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
                <td colspan="2" class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">badge</span>
                    <p class="text-sm text-on-surface-variant">Belum ada data pegawai.</p>
                </td>
            </tr>
        @endforelse
    </x-data-table>
</div>
@endsection
