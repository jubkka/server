<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\TokensController;


//// ROLE POLICY

//Role
Route::middleware('auth:sanctum')->group(function () {
    Route::get('ref/policy/role', [RoleController::class, 'index']); // Список ролей
    Route::get('ref/policy/role/{id}', [RoleController::class, 'show']); // Получить конкретную роль
    Route::post('ref/policy/role', [RoleController::class, 'store']); // Создать новую роль
    Route::put('ref/policy/role/{id}', [RoleController::class, 'update']); // Обновить роль
    Route::delete('ref/policy/role/{id}/soft', [RoleController::class, 'softDelete']); // Мягко удалить роль
    Route::post('ref/policy/role/{id}/restore', [RoleController::class, 'restore']); // Восстановить роль
});
Route::delete('ref/policy/role/{id}', [RoleController::class, 'destroy']); // Жесткое удаление

//Permission
Route::middleware('auth:sanctum')->group(function () {
    Route::get('ref/policy/permission', [PermissionController::class, 'index']); // Получение списка разрешений
    Route::get('ref/policy/permission/{id}', [PermissionController::class, 'show']); // Получить конкретное разрешение 
    Route::post('ref/policy/permission', [PermissionController::class, 'store']); // Создать новое разрешение
    Route::put('ref/policy/permission/{id}', [PermissionController::class, 'update']); // Обновить разрешение
    Route::delete('ref/policy/permission/{id}/soft', [PermissionController::class, 'softDelete']); // Мягко удалить разрешение
    Route::post('ref/policy/permission/{id}/restore', [PermissionController::class, 'restore']); // Восстановить разрешение
});
Route::delete('ref/policy/permission/{id}', [PermissionController::class, 'destroy']); // Жестко удалить разрешение

//UserRole
Route::middleware('auth:sanctum')->group(function () {
    Route::get('ref/user', [UserController::class, 'getUsers']); //Список пользователей
    Route::get('ref/user/{userId}/role', [UserRoleController::class, 'getUserRoles']); //Получение всех ролей пользователя
    Route::post('ref/user/{userId}/role', [UserRoleController::class, 'assignRole']);  // Назначить роль
    Route::delete('ref/user/{userId}/role/{roleId}', [UserRoleController::class, 'destroy']);  // Удалить жестко роль
    Route::delete('ref/user/{userId}/role/{roleId}/soft', [UserRoleController::class, 'softDelete']);  // Удалить мягко роль ! 
    Route::post('ref/user/{userId}/role/{roleId}/restore', [UserRoleController::class, 'restore']);  // Восстановить роли пользователя !
});

//RolePermission
Route::middleware('auth:sanctum')->group(function () {
    Route::post('roles/{roleId}/permissions', [RolePermissionController::class, 'assignPermission']);  // Назначить разрешение
    Route::delete('roles/{roleId}/permissions/{permissionId}', [RolePermissionController::class, 'removePermission']);  // Удалить разрешение
    Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'getRolePermissions']);  // Получить разрешения роли
});

//// ACTIONS users

//Route::get('users', [UserController::class, 'users'])->middleware('permission:get-list-user');;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [UserController::class, 'read']);
    Route::post('update', [UserController::class, 'update']);
});


//// LOGING and REGISTER users

//unauthorized
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

//authorized
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('change-password', [UserController::class, 'changePassword']);

    //tokens
    Route::get('tokens', [TokensController::class, 'tokens']);
    Route::post('refresh', [TokensController::class, 'refresh']);
    Route::delete('tokens/revoke', [TokensController::class, 'revokeAllTokens']);
});
