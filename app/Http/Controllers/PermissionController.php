<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::all());
    }

    public function show($id)
    {
        return response()->json(Permission::findOrFail($id));
    }

    public function store(CreatePermissionRequest $request)
    {
        $permissionDTO = $request->toDTO();
        $permission = Permission::create([
            'name' => $permissionDTO->name,
            'cipher' => $permissionDTO->cipher,
            'description' => $permissionDTO->description,
        ]);
        return response()->json($permission, 201);
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permissionDTO = $request->toDTO();
        $permission->update([
            'name' => $permissionDTO->name,
            'cipher' => $permissionDTO->cipher,
            'description' => $permissionDTO->description,
        ]);
        return response()->json($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->forceDelete();
        return response()->json(['message' => 'Permission deleted']);
    }

    public function softDelete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['message' => 'Permission soft deleted']);
    }

    public function restore($id)
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();
        return response()->json(['message' => 'Permission restored']);
    }
}
