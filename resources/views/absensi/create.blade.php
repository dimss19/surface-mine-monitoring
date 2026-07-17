@extends('layouts.app')

@section('title', '- Form Absensi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-blue-600 px-6 py-8 text-center">
            <h2 class="text-2xl font-bold text-white">Form Absensi Pegawai</h2>
            <p class="mt-2 text-blue-100 text-sm">Silakan isi data kehadiran Anda hari ini.</p>
        </div>
        
        <form action="{{ route('absensi.store') }}" method="POST" class="p-6 sm:p-8" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            
            <x-select-dropdown 
                name="pegawai_id" 
                label="Nama Pegawai" 
                :options="$pegawaiOptions" 
                :required="true"
            />
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-select-dropdown 
                    name="shift" 
                    label="Shift Kerja" 
                    :options="['siang' => 'Shift Siang', 'malam' => 'Shift Malam']" 
                    :required="true"
                />
                
                <x-form-input 
                    name="tanggal" 
                    type="date" 
                    label="Tanggal" 
                    :value="date('Y-m-d')" 
                    :required="true" 
                />
            </div>
            
            <x-select-dropdown 
                name="area_id" 
                label="Area Lokasi" 
                :options="$areaOptions" 
                :required="true"
            />
            
            <x-select-dropdown 
                name="alat_id" 
                label="Alat yang Digunakan" 
                :options="$alatOptions" 
                :required="true"
            />
            
            <div class="mt-8 flex items-center justify-end">
                <button type="submit" 
                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    x-bind:class="{ 'opacity-75 cursor-not-allowed': loading }"
                    x-bind:disabled="loading"
                >
                    <span x-show="!loading">Kirim Absensi</span>
                    <span x-show="loading" x-cloak class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
