<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserRoles($userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        $roles = $user->roles; // Возвращает коллекцию ролей

        return response()->json($roles);
    }
}
