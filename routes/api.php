<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);