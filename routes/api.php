<?php

declare(strict_types = 1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'show'])->name('me.show');
    Route::put('/me', [UserController::class, 'update'])->name('me.update');
    Route::delete('/me', [UserController::class, 'destroy'])->name('me.destroy');

    Route::apiResource('recipes', RecipeController::class);
});
