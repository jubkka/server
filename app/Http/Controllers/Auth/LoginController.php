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
use App\Services\AuthService;


class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Проверяем, соответствует ли пароль и существует ли пользователь
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Проверяем выбранный метод авторизации
        if ($user->two_factor_enabled) {
            // Переадресация на 2FA для подтверждения
            return response()->json(['message' => 'Two-factor authentication is enabled. Please verify your code.'], 200);
        }

        // Если авторизация по паролю
        return $this->authService->createTokenResponse($user);
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