<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return new LoginResource([
                'user' => $user,
                'token' => $token,
            ]); 
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
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