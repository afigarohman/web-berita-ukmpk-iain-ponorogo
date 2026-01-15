<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicPostController; 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', [PublicPostController::class, 'index']);

Route::get('/posts/{slug}', [PublicPostController::class, 'show']);