<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LokasiController;
use App\Http\Controllers\API\UlasanController;
use App\Http\Controllers\API\StoryController;


Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout',[UserController::class, 'logout']);
    Route::post('add_lokasi', [LokasiController::class, 'store']);
    Route::get('lokasi',[LokasiController::class,'all']);
    Route::get('lokasi/{id}', [LokasiController::class, 'show']);
    Route::post('add_ulasan', [UlasanController::class, 'store']);
    Route::post('add_story', [StoryController::class, 'store']); // Tambahkan cerita
    Route::get('stories', [StoryController::class, 'index']);    // Daftar semua cerita
    Route::get('stories/{id}', [StoryController::class, 'show']); // Deta
   
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);


