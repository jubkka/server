<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function assignPermission(Request $request, $roleId)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($request->permission_id);

        // Проверяем, есть ли уже такое разрешение у роли
        if ($role->permissions->contains($permission)) {
            return response()->json(['message' => 'The role already has this permission'], 400);
        }

        $role->permissions()->attach($permission);

        return response()->json(['message' => 'Permission assigned to role successfully']);
    }

    /**
     * Удалить разрешение у роли.
     */
    public function removePermission($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);

        if (!$role->permissions->contains($permissionId)) {
            return response()->json(['message' => 'The role does not have this permission'], 404);
        }

        $role->permissions()->detach($permissionId);

        return response()->json(['message' => 'Permission removed from role successfully']);
    }

    /**
     * Получить список разрешений роли.
     */
    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = $role->permissions;

        return response()->json($permissions);
    }
}
