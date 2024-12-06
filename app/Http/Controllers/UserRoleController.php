<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResources;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function softDelete($userId, $roleId)
    {
        // Находим пользователя и роль
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        // Мягко удаляем роль у пользователя
        if ($user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->updateExistingPivot($role->id, ['deleted_at' => now()]);
            return response()->json(['message' => 'Role soft deleted from user.'], 200);
        }

        return response()->json(['message' => 'Role not found on user.'], 404);
    }

    public function destroy($userId, $roleId)
    {
        // Находим пользователя и роль
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        // Удаляем роль у пользователя
        if ($user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->detach($role->id);
            return response()->json(['message' => 'Role permanently removed from user.'], 200);
        }

        return response()->json(['message' => 'Role not found on user.'], 404);
    }

    public function restore($userId, $roleId)
    {
        // Находим пользователя и роль
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        // Восстанавливаем мягко удаленную роль
        if ($user->roles()->where('role_id', $role->id)->whereNotNull('deleted_at')->exists()) {
            $user->roles()->updateExistingPivot($role->id, ['deleted_at' => null]);
            return response()->json(['message' => 'Role restored for user.'], 200);
        }

        return response()->json(['message' => 'Role not found or not soft deleted.'], 404);
    }


    /**
     * Получить список ролей пользователя.
     */
    public function getUserRoles($userId)
    {
        $user = User::with('roles.permissions')->findOrFail($userId);

        Log::info($user);

        // Формируем результат с ролями и разрешениями
        $rolesWithPermissions = $user->roles->map(function ($role) {
            return [
                'role' => new RoleResources($role),
                'permissions' => $role->permissions, // Все разрешения для этой роли
            ];
        });

        // Возвращаем результат в формате JSON
        return response()->json($rolesWithPermissions);
    }
}
