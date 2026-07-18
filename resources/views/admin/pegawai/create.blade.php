@extends('layouts.dashboard')

@section('title', '- Tambah Pegawai')
@section('page_title', 'Tambah Pegawai Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center text-sm font-bold text-primary hover:text-primary-fixed transition-colors">
        <span class="material-symbols-outlined text-lg mr-1">arrow_back</span>
        Kembali ke Daftar Pegawai
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl border border-outline-variant max-w-2xl">
    <form action="{{ route('admin.pegawai.store') }}" method="POST" class="p-6 md:p-8">
        @csrf

        <div class="space-y-6">
            <x-form-input
                name="nama"
                type="text"
                label="Nama Lengkap Pegawai"
                :required="true"
            />
        </div>

        <div class="mt-8 pt-6 border-t border-outline-variant flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg mr-1.5">save</span>
                Simpan Data Pegawai
            </button>
        </div>
    </form>
</div>
@endsection
