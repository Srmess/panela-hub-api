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

    Route::put('recipes/{recipe}/like', [RecipeController::class, 'like'])->name('recipe.like');
    Route::put('recipes/{recipe}/dislike', [RecipeController::class, 'dislike'])->name('recipe.dislike');
    Route::put('recipes/{recipe}/remove-like', [RecipeController::class, 'removeLike'])->name('recipe.removeLike');
});
