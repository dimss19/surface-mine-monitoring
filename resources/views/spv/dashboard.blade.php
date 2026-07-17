@extends('layouts.dashboard')

@section('title', '- SPV Dashboard')
@section('page_title', 'Dashboard Supervisor')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900">Selamat datang di Panel SPV</h3>
        <p class="mt-1 text-sm text-gray-500">Anda dapat memantau area yang ditugaskan kepada Anda dan membuat laporan harian.</p>
        
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Summary cards placeholder -->
            <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Area Ditugaskan</dt>
                                <dd class="text-3xl font-semibold text-gray-900">--</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-indigo-50 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Laporan Terkirim (Bulan ini)</dt>
                                <dd class="text-3xl font-semibold text-gray-900">--</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border-2 border-dashed border-gray-300 overflow-hidden shadow-sm rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                <a href="/spv/pemantauan/create" class="p-5 w-full h-full flex flex-col items-center justify-center text-blue-600">
                    <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-medium">Buat Laporan Baru</span>
                </a>
            </div>
        </div>
    </div>
@endsection
