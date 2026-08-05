@extends('layouts.admin')

@section('title', 'Edit SPV')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Edit SPV</h1>
    <a href="{{ route('admin.spv.index') }}" class="btn-secondary">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.spv.update', $spv) }}">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-[var(--text)] mb-1">Nama Lengkap</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $spv->name) }}"
                       class="w-full px-4 py-2 border border-[var(--border)] rounded-lg focus:ring-2 focus:ring-[var(--accent)] focus:border-transparent"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-[var(--text)] mb-1">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username', $spv->username) }}"
                       class="w-full px-4 py-2 border border-[var(--border)] rounded-lg focus:ring-2 focus:ring-[var(--accent)] focus:border-transparent"
                       required>
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[var(--text)] mb-1">Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span></label>
                <input type="password"
                       id="password"
                       name="password"
                       class="w-full px-4 py-2 border border-[var(--border)] rounded-lg focus:ring-2 focus:ring-[var(--accent)] focus:border-transparent">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text)] mb-2">Area Tugas</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($areas as $area)
                        <label class="flex items-center gap-2 p-2 border border-[var(--border)] rounded-lg cursor-pointer hover:bg-slate-50">
                            <input type="checkbox"
                                   name="areas[]"
                                   value="{{ $area->id }}"
                                   {{ in_array($area->id, old('areas', $assignedAreas)) ? 'checked' : '' }}
                                   class="rounded border-slate-300 text-[var(--accent)] focus:ring-[var(--accent)]">
                            <span class="text-sm text-[var(--text)]">{{ $area->nama }}</span>
                        </label>
                    @endforeach
                </div>
                @error('areas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="p-6 border-t border-[var(--border)] flex justify-end gap-3">
            <a href="{{ route('admin.spv.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection