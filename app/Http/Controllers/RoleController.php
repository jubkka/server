<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Получаем все роли

        return response()->json($roles);
    }

    // Получение конкретной роли
    public function show($id)
    {
        $role = Role::findOrFail($id); // Ищем роль по ID
        return response()->json($role);
    }

    // Создание новой роли
    public function store(CreateRoleRequest $request)
    {
        $roleDTO = $request->toDTO(); // Получаем DTO из запроса

        // Создаем роль
        $role = Role::create([
            'name' => $roleDTO->name,
            'cipher' => $roleDTO->cipher,
            'description' => $roleDTO->description,
        ]);

        return response()->json($role, 201);
    }

    // Обновление роли
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id); // Ищем роль по ID

        $roleDTO = $request->toDTO(); // Получаем DTO из запроса

        // Обновляем роль
        $role->update([
            'name' => $roleDTO->name,
            'cipher' => $roleDTO->cipher,
            'description' => $roleDTO->description,
        ]);

        return response()->json($role);
    }

    // Жесткое удаление роли
    public function destroy($id) 
    {
        $role = Role::findOrFail($id); // Ищем роль по ID
        $role->forceDelete(); // Жестко удаляем роль

        return response()->json(['message' => 'Роль успешно удалена']);
    }

    // Мягкое удаление роли
    public function softDelete($id)
    {
        $role = Role::findOrFail($id); // Ищем роль по ID
        $role->delete(); // Мягко удаляем роль

        return response()->json(['message' => 'Роль успешно удалена (мягко)']);
    }

    // Восстановление мягко удаленной роли
    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id); // Ищем роль, включая мягко удаленные
        $role->restore(); // Восстанавливаем роль

        return response()->json(['message' => 'Роль успешно восстановлена']);
    }
}
