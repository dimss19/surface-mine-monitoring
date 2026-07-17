@extends('layouts.dashboard')

@section('title', '- Admin Dashboard')
@section('page_title', 'Dashboard Administrator')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900">Selamat datang di Panel Admin</h3>
        <p class="mt-1 text-sm text-gray-500">Gunakan menu di sebelah kiri untuk mengelola master data SPV dan Alat, serta melihat laporan pemantauan.</p>
        
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Summary cards placeholder -->
            <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total SPV</dt>
                                <dd class="text-3xl font-semibold text-gray-900">--</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Area</dt>
                                <dd class="text-3xl font-semibold text-gray-900">--</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Laporan Hari Ini</dt>
                                <dd class="text-3xl font-semibold text-gray-900">--</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
