<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpvController;
use App\Http\Controllers\AdminUnitController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminAreaController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\AdminTargetController;
use App\Http\Controllers\AdminSpvController;
use App\Http\Controllers\AdminPegawaiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PegawaiRitasiController;
use App\Http\Controllers\PegawaiNonRitasiController;
use App\Http\Controllers\PegawaiGeneralController;
use App\Http\Controllers\SpvLaporanController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Legacy redirects
Route::get('/absensi', fn () => redirect()->route('pegawai.ritasi.create'));
Route::get('/rekapan', fn () => redirect()->route('pegawai.ritasi.create'));

Route::middleware('auth')->group(function () {
    // CSRF token refresh for offline sync (see resources/js/offline-sync.js)
    Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]))->name('csrf-token');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pegawai (Operator)
    Route::middleware('role:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'dashboard'])->name('dashboard');
        
        // Ritasi
        Route::get('ritasi/create', [PegawaiRitasiController::class, 'create'])->name('ritasi.create');
        Route::post('ritasi', [PegawaiRitasiController::class, 'store'])->name('ritasi.store');
        Route::get('ritasi/riwayat', [PegawaiRitasiController::class, 'riwayat'])->name('ritasi.riwayat');
        
        // Non-Ritasi
        Route::get('non-ritasi/create', [PegawaiNonRitasiController::class, 'create'])->name('non-ritasi.create');
        Route::post('non-ritasi', [PegawaiNonRitasiController::class, 'store'])->name('non-ritasi.store');
        Route::get('non-ritasi/riwayat', [PegawaiNonRitasiController::class, 'riwayat'])->name('non-ritasi.riwayat');
        
        // General
        Route::get('general/create', [PegawaiGeneralController::class, 'create'])->name('general.create');
        Route::post('general', [PegawaiGeneralController::class, 'store'])->name('general.store');
    });

    // SPV
    Route::middleware('role:spv')->prefix('spv')->name('spv.')->group(function () {
        Route::get('/dashboard', [SpvController::class, 'dashboard'])->name('dashboard');
        
        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [SpvLaporanController::class, 'index'])->name('index');
            Route::get('harian', [SpvLaporanController::class, 'harian'])->name('harian');
            Route::get('mingguan', [SpvLaporanController::class, 'mingguan'])->name('mingguan');
            Route::get('bulanan', [SpvLaporanController::class, 'bulanan'])->name('bulanan');
            Route::post('export/{type}', [SpvLaporanController::class, 'export'])->name('export');
        });

    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Master Data
        Route::get('master-data', [AdminUnitController::class, 'index'])->name('master-data.index');
        Route::post('unit', [AdminUnitController::class, 'store'])->name('unit.store');
        Route::put('unit/{unit}', [AdminUnitController::class, 'update'])->name('unit.update');
        Route::delete('unit/{unit}', [AdminUnitController::class, 'destroy'])->name('unit.destroy');
        Route::get('unit/{unit}/edit', [AdminUnitController::class, 'edit'])->name('unit.edit');
        
        Route::post('material', [AdminMaterialController::class, 'store'])->name('material.store');
        Route::put('material/{material}', [AdminMaterialController::class, 'update'])->name('material.update');
        Route::delete('material/{material}', [AdminMaterialController::class, 'destroy'])->name('material.destroy');
        Route::get('material/{material}/edit', [AdminMaterialController::class, 'edit'])->name('material.edit');
        
        Route::post('area', [AdminAreaController::class, 'store'])->name('area.store');
        Route::put('area/{area}', [AdminAreaController::class, 'update'])->name('area.update');
        Route::delete('area/{area}', [AdminAreaController::class, 'destroy'])->name('area.destroy');
        Route::get('area/{area}/edit', [AdminAreaController::class, 'edit'])->name('area.edit');
        
        // Target Harian
        Route::post('target', [AdminTargetController::class, 'store'])->name('target.store');
        Route::delete('target/{target}', [AdminTargetController::class, 'destroy'])->name('target.destroy');
        
        // Hak Akses
        Route::get('hak-akses', [AdminPermissionController::class, 'index'])->name('hak-akses.index');
        Route::put('hak-akses/{role}', [AdminPermissionController::class, 'update'])->name('hak-akses.update');
        
        // Laporan
        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan/export', [AdminLaporanController::class, 'export'])->name('laporan.export');
        
        // SPV Management
        Route::get('spv', [AdminSpvController::class, 'index'])->name('spv.index');
        Route::get('spv/create', [AdminSpvController::class, 'create'])->name('spv.create');
        Route::post('spv', [AdminSpvController::class, 'store'])->name('spv.store');
        Route::get('spv/{spv}/edit', [AdminSpvController::class, 'edit'])->name('spv.edit');
        Route::put('spv/{spv}', [AdminSpvController::class, 'update'])->name('spv.update');
        Route::delete('spv/{spv}', [AdminSpvController::class, 'destroy'])->name('spv.destroy');
        
        // Pegawai Management
        Route::get('pegawai', [AdminPegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('pegawai/create', [AdminPegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('pegawai', [AdminPegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('pegawai/{pegawai}/edit', [AdminPegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('pegawai/{pegawai}', [AdminPegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('pegawai/{pegawai}', [AdminPegawaiController::class, 'destroy'])->name('pegawai.destroy');
    });
});

require __DIR__.'/auth.php';
