<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Получить информацию об авторизованном пользователе.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        // Получаем текущего авторизованного пользователя
        $user = $request->user();

        // Возвращаем ресурс для авторизованного пользователя
        return new UserResource($user);
    }

    public function changePassword(Request $request)
    {
        // Валидируем входные данные
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Проверяем текущий пароль
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 401);
        }

        // Обновляем пароль
        $user->password = $request->new_password;
        $user->save();

        // Отзываем все токены после смены пароля
        $user->tokens()->delete();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}