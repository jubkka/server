<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\TokensController;
use Illuminate\Support\Facades\Route;

//unauthorized
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

//authorized
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [UserController::class, 'me']);
    Route::post('change-password', [UserController::class, 'changePassword']);

    //tokens
    Route::get('tokens', [TokensController::class, 'tokens']);
    Route::post('refresh', [TokensController::class, 'refresh']);
    Route::delete('tokens/revoke', [TokensController::class, 'revokeAllTokens']);
});