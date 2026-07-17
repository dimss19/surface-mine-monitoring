@extends('layouts.app')

@section('title', '- Login')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-gray-800 px-6 py-8 text-center">
            <h2 class="text-2xl font-bold text-white">Portal Admin & SPV</h2>
            <p class="mt-2 text-gray-300 text-sm">Masuk untuk mengelola sistem Surface Mine.</p>
        </div>
        
        <form action="{{ route('login') }}" method="POST" class="p-6 sm:p-8" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            
            <x-form-input 
                name="email" 
                type="email" 
                label="Alamat Email" 
                placeholder="nama@perusahaan.com"
                :required="true" 
            />
            
            <x-form-input 
                name="password" 
                type="password" 
                label="Password" 
                :required="true" 
            />
            
            <div class="mt-8 flex items-center justify-end">
                <button type="submit" 
                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors duration-200"
                    x-bind:class="{ 'opacity-75 cursor-not-allowed': loading }"
                    x-bind:disabled="loading"
                >
                    <span x-show="!loading">Login</span>
                    <span x-show="loading" x-cloak class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
