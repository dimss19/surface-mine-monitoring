<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpvController;
use App\Http\Controllers\AdminSpvController;
use App\Http\Controllers\AdminAlatController;
use App\Http\Controllers\AdminPegawaiController;
use App\Http\Controllers\SpvPemantauanController;

Route::get('/', [AbsensiController::class, 'create'])->name('home');
Route::get('/absensi', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // SPV Routes
    Route::middleware('role:spv')->prefix('spv')->name('spv.')->group(function () {
        Route::get('/dashboard', [SpvController::class, 'index'])->name('dashboard');
        Route::resource('pemantauan', SpvPemantauanController::class);
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
        Route::resource('spv', AdminSpvController::class);
        Route::resource('alat', AdminAlatController::class);
        Route::resource('pegawai', AdminPegawaiController::class);
    });
});
