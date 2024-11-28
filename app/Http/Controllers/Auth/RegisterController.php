<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
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
            'birthday' => $validated['birthday'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'user' => new RegisterResource($user),
            'token' => $token
        ]);
    }
}