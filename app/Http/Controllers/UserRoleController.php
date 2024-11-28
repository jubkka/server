<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->role_id);

        // Проверяем, есть ли уже такая роль у пользователя
        if ($user->roles->contains($role)) {
            return response()->json(['message' => 'The user already has this role'], 400);
        }

        $user->roles()->attach($role);

        return response()->json(['message' => 'Role assigned to user successfully']);
    }

    /**
     * Удалить роль у пользователя.
     */
    public function removeRole($userId, $roleId)
    {
        $user = User::findOrFail($userId);

        if (!$user->roles->contains($roleId)) {
            return response()->json(['message' => 'The user does not have this role'], 404);
        }

        $user->roles()->detach($roleId);

        return response()->json(['message' => 'Role removed from user successfully']);
    }

    /**
     * Получить список ролей пользователя.
     */
    public function getUserRoles($userId)
    {
        $user = User::findOrFail($userId);
        $roles = $user->roles;

        return response()->json($roles);
    }
}
