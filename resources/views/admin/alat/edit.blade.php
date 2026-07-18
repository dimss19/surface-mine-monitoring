@extends('layouts.dashboard')

@section('title', '- Edit Alat')
@section('page_title', 'Edit Master Data Alat')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.alat.index') }}" class="inline-flex items-center text-sm font-bold text-primary hover:text-primary-fixed transition-colors">
        <span class="material-symbols-outlined text-lg mr-1">arrow_back</span>
        Kembali ke Daftar Alat
    </a>
</div>

<div class="bg-surface-container-high rounded-2xl border border-outline-variant max-w-2xl">
    <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" class="p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <x-form-input
                name="nama"
                type="text"
                label="Nama Alat / Model"
                :value="$alat->nama"
                :required="true"
            />

            <div>
                <label for="jenis" class="block text-sm font-bold text-on-surface mb-1.5">Jenis Alat <span class="text-error">*</span></label>
                <select id="jenis" name="jenis" required class="block w-full rounded-xl border-outline-variant bg-surface-container text-on-surface shadow-sm focus:border-primary focus:ring-primary sm:text-sm px-4 py-2.5 transition-colors">
                    <option value="">Pilih Jenis</option>
                    <option value="excavator" {{ (old('jenis') ?? $alat->jenis) == 'excavator' ? 'selected' : '' }}>Excavator</option>
                    <option value="dump_truck" {{ (old('jenis') ?? $alat->jenis) == 'dump_truck' ? 'selected' : '' }}>Dump Truck</option>
                    <option value="dozer" {{ (old('jenis') ?? $alat->jenis) == 'dozer' ? 'selected' : '' }}>Dozer</option>
                    <option value="grader" {{ (old('jenis') ?? $alat->jenis) == 'grader' ? 'selected' : '' }}>Grader</option>
                    <option value="loader" {{ (old('jenis') ?? $alat->jenis) == 'loader' ? 'selected' : '' }}>Loader</option>
                    <option value="lainnya" {{ (old('jenis') ?? $alat->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis')
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
                Update Data Alat
            </button>
        </div>
    </form>
</div>
@endsection
