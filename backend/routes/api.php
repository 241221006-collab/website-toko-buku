<?php

use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Seller\BookController as SellerBookController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Support\Facades\Route;

// ===== Publik =====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/sellers', [StoreController::class, 'index']);
Route::get('/sellers/{id}', [StoreController::class, 'show']);

// ===== Wajib login =====
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/seller/register', [SellerController::class, 'register']);

    // ----- Seller -----
    Route::middleware('role:seller')->prefix('seller')->group(function () {
        Route::get('/books', [SellerBookController::class, 'index']);
        Route::post('/books', [SellerBookController::class, 'store']);
        Route::put('/books/{id}', [SellerBookController::class, 'update']);
        Route::delete('/books/{id}', [SellerBookController::class, 'destroy']);
    });

    // ----- Admin -----
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/categories', [AdminCategoryController::class, 'index']);
        Route::post('/categories', [AdminCategoryController::class, 'store']);
        Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
        Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

        Route::get('/users', [AdminUserController::class, 'index']);
        Route::put('/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
    });
});