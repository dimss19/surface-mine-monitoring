@extends('layouts.dashboard')

@section('title', '- Profil')
@section('page_title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto space-y-8" x-data="{ passwordForm: false }">

    {{-- Foto Profil --}}
    <div class="bg-surface-container-high rounded-2xl border border-outline-variant p-6 sm:p-8">
        <h3 class="text-lg font-bold text-on-surface mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">photo_camera</span>
            Foto Profil
        </h3>
        <div class="flex items-center gap-6">
            <div class="shrink-0">
                @php
                    $photoUrl = auth()->user()->profile_photo
                        ? Storage::url(auth()->user()->profile_photo)
                        : null;
                @endphp
                <div class="w-20 h-20 rounded-full bg-surface-variant flex items-center justify-center overflow-hidden border-2 border-outline-variant">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-on-surface-variant text-4xl">person</span>
                    @endif
                </div>
            </div>
            <div class="space-y-2">
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" x-data="{ uploading: false }" @submit="uploading = true">
                    @csrf
                    <label class="inline-flex items-center gap-2 bg-primary text-on-primary font-bold py-2 px-5 rounded-xl cursor-pointer hover:opacity-90 transition-all text-sm">
                        <span class="material-symbols-outlined" style="font-size: 18px;">upload</span>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="this.form.submit()">
                        Upload Foto
                    </label>
                </form>
                @if(auth()->user()->profile_photo)
                    <form action="{{ route('profile.photo.remove') }}" method="POST" onsubmit="return confirm('Hapus foto profil?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-error hover:text-error/80 transition-colors">Hapus foto</button>
                    </form>
                @endif
                <p class="text-xs text-on-surface-variant">Format: JPEG/PNG, maks 2MB</p>
            </div>
        </div>
    </div>

    {{-- Data Profil --}}
    <div class="bg-surface-container-high rounded-2xl border border-outline-variant p-6 sm:p-8">
        <h3 class="text-lg font-bold text-on-surface mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">badge</span>
            Data Profil
        </h3>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Nama Lengkap</label>
                <input name="name" value="{{ old('name', auth()->user()->name) }}" required
                       class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface placeholder:text-on-surface-variant/50">
                @error('name')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Username</label>
                <input name="username" value="{{ old('username', auth()->user()->username) }}"
                       class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface placeholder:text-on-surface-variant/50"
                       placeholder="username untuk login">
                @error('username')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Email</label>
                <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required
                       class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface placeholder:text-on-surface-variant/50">
                @error('email')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Role</label>
                <input value="{{ ucfirst(auth()->user()->role) }}" disabled
                       class="w-full bg-surface-variant/50 border border-outline rounded-lg p-3 text-on-surface-variant cursor-not-allowed">
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary font-bold py-2.5 px-6 rounded-xl hover:opacity-90 active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="bg-surface-container-high rounded-2xl border border-outline-variant p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">lock</span>
                Kata Sandi
            </h3>
            <button @click="passwordForm = !passwordForm" class="text-sm font-semibold text-primary hover:underline">
                <span x-show="!passwordForm">Ubah Password</span>
                <span x-show="passwordForm" x-cloak>Batal</span>
            </button>
        </div>

        <form x-show="passwordForm" x-cloak action="{{ route('profile.password') }}" method="POST" class="space-y-5" x-data="{ showCurrent: false, showNew: false, showConfirm: false }" @submit="passwordForm = false">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Password Saat Ini</label>
                <div class="relative">
                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" required
                           class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface">
                    <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 hover:text-primary">
                        <span class="material-symbols-outlined" x-text="showCurrent ? 'visibility_off' : 'visibility'" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
                @error('current_password')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Password Baru</label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" name="password" required minlength="8"
                           class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface">
                    <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 hover:text-primary">
                        <span class="material-symbols-outlined" x-text="showNew ? 'visibility_off' : 'visibility'" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
                @error('password')<p class="mt-1 text-xs text-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1.5">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required minlength="8"
                           class="w-full bg-surface border border-outline rounded-lg focus:border-primary focus:ring-1 focus:ring-primary p-3 text-on-surface">
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 hover:text-primary">
                        <span class="material-symbols-outlined" x-text="showConfirm ? 'visibility_off' : 'visibility'" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
            </div>

            <div>
                <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary font-bold py-2.5 px-6 rounded-xl hover:opacity-90 active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined" style="font-size: 18px;">lock_reset</span>
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
