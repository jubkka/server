<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TokensController extends Controller
{
    public function tokens(Request $request)
    {
        // Получаем текущего авторизованного пользователя
        $user = Auth::user();

        // Получаем токены авторизованного пользователя
        $tokens = $user->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'token' => $token->token
            ];
        });

        // Возвращаем список токенов
        return response()->json([
            'tokens' => $tokens
        ], 200);
    }

    public function revokeAllTokens(Request $request)
    {
        // Получаем текущего авторизованного пользователя
        $user = Auth::user();

        // Отзываем все токены пользователя
        $user->tokens()->delete();
        RefreshToken::where('user_id', $user->id)->delete();

        // Возвращаем сообщение об успешном отзыве токенов
        return response()->json([
            'message' => 'All tokens have been successfully revoked.'
        ], 200);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        // Проверяем, существует ли этот refresh token
        $storedToken = RefreshToken::where('refresh_token', $refreshToken)->first();
        
        if (!$storedToken) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        if (Carbon::parse($storedToken->expires_at)->isPast()) {
            return response()->json(['message' => 'Refresh token has expired.'], 400);
        }

        // Получаем пользователя
        $user = $storedToken->user;

        // Удаляем старый refresh token (опционально)
        $storedToken->delete();

        // Создаем новый access token
        $accessToken = $user->createToken('auth_token')->plainTextToken;

        // Генерируем новый refresh token
        $newRefreshToken = Str::random(64);
        RefreshToken::create([
            'user_id' => $user->id,
            'refresh_token' => $newRefreshToken,
            'expires_at' => Carbon::now()->addDays(30)
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken
        ]);
    }
}
