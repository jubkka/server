<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

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
}