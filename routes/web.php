<?php

use App\Http\Controllers\GitHookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
