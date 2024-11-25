<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Валидация данных и аутентификация
        $validated = $request->validated();

        $credentials = ['email' => $validated['email'], 'password' => $validated['password']];

        if (Auth::attempt($credentials)) {
            Log::info('Authentication successful for user: ' . $validated['email']);
        } else {
            Log::error('Authentication failed for user: ' . $validated['email']);
        }

        Log::info('Credentials: ', $credentials);
        Log::info('Attempting to authenticate user: ' . $validated['email']);
        Log::info('Password: ' . $validated['password']); 

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            //$token = $user->createToken('auth_token')->plainTextToken;

            return new LoginResource([
                'user' => $user,
                //'token' => $token,
            ]); 
        }

        return response()->json(['message' => 'Invalid credentials'], 401); // Если не прошли аутентификацию
    }

    public function logout(Request $request)
    {
        // Отзываем текущий токен
        $request->user()->currentAccessToken()->delete();

        // Возвращаем успешный ответ
        return response()->json([
            'message' => 'Вы успешно вышли из системы.'
        ], 200);
    }
}