@extends('layouts.public')

@section('title', 'Login - Surface Mine')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#0f172a]">
    <div class="w-full max-w-md px-6">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/company-logo.png') }}" alt="Company Logo" class="w-32 h-32 mx-auto mb-4">
        </div>
        
        {{-- Title --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-heading font-bold text-white mb-2">SURFACE MINE OPERATIONALS</h1>
            <p class="text-slate-300 text-lg">Welcome To Civil Departement</p>
        </div>
        
        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            
            {{-- Username --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Username</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <span class="material-symbols-outlined">person</span>
                    </span>
                    <input type="text" 
                           name="login" 
                           value="{{ old('login') }}"
                           placeholder="Enter username"
                           class="w-full pl-10 pr-4 py-3 bg-white border-0 rounded-lg text-gray-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500"
                           required>
                </div>
                @error('login')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Password / ID Pekerja --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">ID Pekerja</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <span class="material-symbols-outlined">badge</span>
                    </span>
                    <input type="password" 
                           name="password" 
                           placeholder="Enter ID Pekerja"
                           class="w-full pl-10 pr-4 py-3 bg-white border-0 rounded-lg text-gray-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500"
                           required>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Error message --}}
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            {{-- Button --}}
            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                Masuk
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>
    </div>
</div>
@endsection
