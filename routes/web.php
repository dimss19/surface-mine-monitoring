<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpvController;

Route::get('/', [AbsensiController::class, 'create'])->name('home');
Route::get('/absensi', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // SPV Routes
    Route::get('/spv/dashboard', [SpvController::class, 'index'])->name('spv.dashboard');

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
