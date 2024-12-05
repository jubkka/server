<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegisterResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Получить информацию об авторизованном пользователе.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

    public function getUsers() 
    {
        if (Gate::denies('permission-check', 'read-user')) {
            return response()->json([
                'error' => 'Access Denied',
                "message" => "User does not have the required permission: get-list-users"
            ], 403);
        }

        $users = User::all();

        return response()->json([
            'users' => $users 
        ]);
    }

    public function destroyUser($id) 
    {
        $user = User::withTrashed()->find($id);

        $user->forceDelete();

        return response()->json([
            "message" => "destroyed $user->name",
        ]);
    }

    public function deleteUser($id) 
    {
        $user = User::find($id);

        $user->delete();

        return response()->json([
            "message" => "deleted $user->name",
        ]);
    }

    public function read()
    {
        if (Gate::denies('permission-check', 'read-user')) {
            return response()->json([
                "error" => "Access Denied",
                "message" => "User does not have the required permission: create-user"
            ], 403);
        }

        $user = Auth::user();

        // Возвращаем ресурс для авторизованного пользователя
        return new UserResource($user);
    }

    public function update(Request $request) 
    {
        if (Gate::denies('permission-check', 'read-user')) {
            return response()->json([
                "error" => "Access Denied",
                "message" => "User does not have the required permission: update-user"
            ], 403);
        }

        $user = User::find($request->id);

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('birthday')) {
            $user->birthday = $request->input('birthday');
        }

        $user->save();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'birthday' => $user->birthday,
        ]);
    }
}