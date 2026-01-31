<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- IMPORT CONTROLLER (SESUAI STRUKTUR FOLDER KAMU) ---
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicPostController; // Kita pakai ini untuk Berita
use App\Http\Controllers\Api\CommentController;    // Kita pakai ini untuk Komentar

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIC (BISA DIAKSES TANPA LOGIN)
// ==========================================

// --- AUTHENTICATION ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- BERITA (NEWS) ---
// Menggunakan PublicPostController yang ada di folder Api
Route::get('/posts', [PublicPostController::class, 'index']);
Route::get('/posts/{slug}', [PublicPostController::class, 'show']);

// --- KOMENTAR (BACA SAJA) ---
// Menggunakan CommentController yang ada di folder Api
Route::get('/posts/{id}/comments', [CommentController::class, 'index']);


// ==========================================
// 2. ROUTE PRIVATE (HARUS LOGIN / PAKAI TOKEN)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Cek User yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Kirim Komentar (Write Comment)
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);

});