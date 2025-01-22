<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Middleware\IsAdmin; // Pastikan middleware Anda diimpor

Route::get('/', function () {
    return view('welcome');
});

// Middleware group untuk otentikasi dan role admin
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    IsAdmin::class, // Tambahkan middleware IsAdmin di sini
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lokasi/create', [DashboardController::class, 'create'])->name('lokasi.create');
    Route::post('/lokasi/store', [DashboardController::class, 'store'])->name('lokasi.store');

    Route::get('/lokasi/{id}/edit', [DashboardController::class, 'edit'])->name('lokasi.edit');

    Route::put('/lokasi/{id}', [DashboardController::class, 'update'])->name('lokasi.update');
    Route::delete('/lokasi/{id}', [DashboardController::class, 'destroy'])->name('lokasi.destroy');



});
