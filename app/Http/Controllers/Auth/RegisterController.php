<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RegisterResource;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Создаем нового пользователя
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Аутентифицируем пользователя
        Auth::login($user);

        return new RegisterResource($user);
    }
}