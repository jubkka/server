<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('info/php', [InfoController::class,'php'])->name('info.php');
Route::get('info/user', [InfoController::class,'user'])->name('info.user');
Route::get('info/database', [InfoController::class,'database'])->name('info.database');
