@extends('layouts.dashboard')

@section('title', '- Tambah SPV')
@section('page_title', 'Tambah Supervisor Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.spv.index') }}" class="inline-flex items-center text-sm font-bold text-primary hover:text-primary-fixed transition-colors">
        <span class="material-symbols-outlined text-lg mr-1">arrow_back</span>
        Kembali ke Daftar SPV
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl border border-outline-variant max-w-2xl">
    <form action="{{ route('admin.spv.store') }}" method="POST" class="p-6 md:p-8">
        @csrf

        <div class="space-y-6">
            <x-form-input
                name="name"
                type="text"
                label="Nama Lengkap"
                :required="true"
            />

            <x-form-input
                name="email"
                type="email"
                label="Alamat Email"
                :required="true"
            />

            <x-form-input
                name="password"
                type="password"
                label="Password Baru"
                :required="true"
            />

            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Pilih Area Tugas SPV <span class="text-error">*</span></label>
                <div class="bg-surface-container rounded-xl p-4 max-h-60 overflow-y-auto space-y-2 border border-outline-variant">
                    @forelse($areas as $area)
                        <div class="flex items-center">
                            <input id="area_{{ $area->id }}" name="areas[]" value="{{ $area->id }}" type="checkbox" class="h-4 w-4 rounded border-outline-variant bg-surface-container text-primary focus:ring-primary" {{ (is_array(old('areas')) && in_array($area->id, old('areas'))) ? 'checked' : '' }}>
                            <label for="area_{{ $area->id }}" class="ml-2 block text-sm text-on-surface">
                                {{ $area->nama }}
                            </label>
                        </div>
                    @empty
                        <p class="text-sm text-on-surface-variant">Data area belum tersedia. Silakan tambahkan area terlebih dahulu.</p>
                    @endforelse
                </div>
                @error('areas')
                    <p class="mt-1.5 text-sm text-error flex items-center">
                        <span class="material-symbols-outlined text-base mr-1">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-outline-variant flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-primary-container text-on-primary-container rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg mr-1.5">save</span>
                Simpan Data SPV
            </button>
        </div>
    </form>
</div>
@endsection
