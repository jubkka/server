<?php

namespace App\Services;

use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthService
{
    /**
     * Создание токенов доступа и refresh-токенов
     */
    public function createTokenResponse($user)
    {
        // Удаляем все предыдущие токены пользователя
        $user->tokens()->delete();

        // Создаем новый токен доступа
        $token = $user->createToken('auth_token')->plainTextToken;

        // Генерируем новый refresh token
        RefreshToken::where('user_id', $user->id)->delete();
        $refreshToken = Str::random(64);

        RefreshToken::create([
            'user_id' => $user->id,
            'refresh_token' => $refreshToken,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        $cookie = cookie('refresh_token', $refreshToken, 60 * 24 * 30, null, null, true, true, false, 'Strict');

        return response()->json([
            'user' => $user,
            'token' => $token,
            'refresh_token' => $refreshToken
        ])->cookie($cookie);
    }
}