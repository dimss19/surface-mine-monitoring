<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpvController;
use App\Http\Controllers\AdminUnitController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminAreaController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\PegawaiRitasiController;
use App\Http\Controllers\PegawaiNonRitasiController;
use App\Http\Controllers\PegawaiGeneralController;
use App\Http\Controllers\SpvLaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminLaporanController;

// Public
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]));

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
        Route::delete('/photo', [ProfileController::class, 'removePhoto'])->name('photo.remove');
    });

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
        
        // Legacy
        Route::get('rekapan/create', [PegawaiController::class, 'createRekapan'])->name('rekapan.create');
        Route::post('rekapan', [PegawaiController::class, 'storeRekapan'])->name('rekapan.store');
        Route::get('riwayat', [PegawaiController::class, 'riwayat'])->name('riwayat');
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
        
        Route::post('material', [AdminMaterialController::class, 'store'])->name('material.store');
        Route::put('material/{material}', [AdminMaterialController::class, 'update'])->name('material.update');
        Route::delete('material/{material}', [AdminMaterialController::class, 'destroy'])->name('material.destroy');
        
        Route::post('area', [AdminAreaController::class, 'store'])->name('area.store');
        Route::put('area/{area}', [AdminAreaController::class, 'update'])->name('area.update');
        Route::delete('area/{area}', [AdminAreaController::class, 'destroy'])->name('area.destroy');
        
        // Hak Akses
        Route::get('hak-akses', [AdminPermissionController::class, 'index'])->name('hak-akses.index');
        Route::put('hak-akses/{role}', [AdminPermissionController::class, 'update'])->name('hak-akses.update');
        
        // Laporan
        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan/export', [AdminLaporanController::class, 'export'])->name('laporan.export');
        
        // Export
        Route::get('export', [AdminController::class, 'export'])->name('export');
    });
});

// Legacy redirects
Route::get('/absensi', fn () => redirect()->route('pegawai.ritasi.create'));
Route::get('/rekapan', fn () => redirect()->route('pegawai.ritasi.create'));