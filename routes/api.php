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
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'me']);
Route::post('/change-password', [UserController::class, 'changePassword'])->middleware('auth:sanctum');

//tokens
Route::post('refresh', [TokensController::class, 'refresh']);
Route::middleware('auth:sanctum')->get('/tokens', [TokensController::class, 'tokens']);
Route::middleware('auth:sanctum')->delete('/tokens/revoke', [TokensController::class, 'revokeAllTokens']);