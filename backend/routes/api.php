<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SellerController;
use Illuminate\Support\Facades\Route;

// Publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Wajib login
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/seller/register', [SellerController::class, 'register']);

    // Khusus seller (diisi di Tahap 5 dan 6)
    Route::middleware('role:seller')->prefix('seller')->group(function () {
        //
    });

    // Khusus admin (diisi di Tahap 5 dan 6)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        //
    });
});