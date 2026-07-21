<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpvController;
use App\Http\Controllers\AdminSpvController;
use App\Http\Controllers\AdminAlatController;
use App\Http\Controllers\AdminPegawaiController;
use App\Http\Controllers\SpvPemantauanController;

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]));

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/absensi', [PegawaiController::class, 'createAbsensi'])->name('absensi.create');
        Route::post('/absensi', [PegawaiController::class, 'storeAbsensi'])->name('absensi.store');
    });

    Route::middleware('role:spv')->prefix('spv')->name('spv.')->group(function () {
        Route::get('/dashboard', [SpvController::class, 'index'])->name('dashboard');
        Route::resource('pemantauan', SpvPemantauanController::class);
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
        Route::resource('spv', AdminSpvController::class);
        Route::resource('alat', AdminAlatController::class);
        Route::resource('pegawai', AdminPegawaiController::class);
    });
});

Route::get('/absensi', fn () => redirect()->route('pegawai.absensi.create'));
