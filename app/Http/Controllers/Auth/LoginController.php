<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Проверяем, соответствует ли пароль и существует ли пользователь
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        RefreshToken::where('user_id', $user->id)->delete();

        $refreshToken = Str::random(64); // Случайный токен
        RefreshToken::create([
            'user_id' => $user->id,
            'refresh_token' => $refreshToken,
            'expires_at' => Carbon::now()->addDays(30)
        ]);

        $cookie = cookie('refresh_token', $refreshToken, 60 * 24 * 30, null, null, true, true, false, 'Strict');

        // Возвращаем успешный ответ с данными пользователя и токеном
        return response()->json([
            'user' => new LoginResource($user),
            'token' => $token,
            'refresh_token' => $refreshToken
        ])->cookie($cookie);
    }

    public function logout(Request $request)
    {
        // Отзываем текущий токен
        $user = $request->user();

        $user->currentAccessToken()->delete();
        RefreshToken::where('user_id', $user->id)->delete();

        // Возвращаем успешный ответ
        return response()->json([
            'message' => 'Вы успешно вышли из системы.'
        ], 200);
    }
}