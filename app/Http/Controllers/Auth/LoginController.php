<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Проверяем, существует ли пользователь
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Проверяем, соответствует ли пароль
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        // Ограничиваем количество токенов
        $maxTokens = env('MAX_ACTIVE_TOKENS', 3);
        
        if ($user->tokens()->count() >= $maxTokens) {
            // Удаляем самый старый токен, если их больше, чем максимальное количество
            $user->tokens()->oldest()->first()->delete();
        }

        // Генерируем новый токен
        $token = $user->createToken('auth_token')->plainTextToken;

        $refreshToken = Str::random(64); // Случайный токен
        RefreshToken::create([
            'user_id' => $user->id,
            'refresh_token' => $refreshToken
        ]);

        // Возвращаем успешный ответ с данными пользователя и токеном
        return new LoginResource([
            'user' => $user,
            'token' => $token,
            'refresh_token' => $refreshToken
        ]);
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