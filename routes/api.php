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
    Route::get('ref/policy/role', [RoleController::class, 'index']);
    Route::get('ref/policy/role/{id}', [RoleController::class, 'show']);
    Route::post('ref/policy/role', [RoleController::class, 'store']);
    Route::put('ref/policy/role/{id}', [RoleController::class, 'update']);
    Route::delete('ref/policy/role/{id}/soft', [RoleController::class, 'softDelete']);
    Route::post('ref/policy/role/{id}/restore', [RoleController::class, 'restore']);
});
Route::delete('ref/policy/role/{id}', [RoleController::class, 'destroy']);

//Permission
Route::middleware('auth:sanctum')->group(function () {
    Route::get('ref/policy/permission', [PermissionController::class, 'index']);
    Route::get('ref/policy/permission/{id}', [PermissionController::class, 'show']);
    Route::post('ref/policy/permission', [PermissionController::class, 'store']);
    Route::put('ref/policy/permission/{id}', [PermissionController::class, 'update']);
    Route::delete('ref/policy/permission/{id}/soft', [PermissionController::class, 'softDelete']);
    Route::post('ref/policy/permission/{id}/restore', [PermissionController::class, 'restore']);
});
Route::delete('ref/policy/permission/{id}', [PermissionController::class, 'destroy']);

//UserRole
Route::middleware('auth:sanctum')->group(function () {
    Route::post('users/{userId}/roles', [UserRoleController::class, 'assignRole']);  // Назначить роль
    Route::delete('users/{userId}/roles/{roleId}', [UserRoleController::class, 'removeRole']);  // Удалить роль
    Route::get('users/{userId}/roles', [UserRoleController::class, 'getUserRoles']);  // Получить роли пользователя
});

//RolePermission
Route::middleware('auth:sanctum')->group(function () {
    Route::post('roles/{roleId}/permissions', [RolePermissionController::class, 'assignPermission']);  // Назначить разрешение
    Route::delete('roles/{roleId}/permissions/{permissionId}', [RolePermissionController::class, 'removePermission']);  // Удалить разрешение
    Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'getRolePermissions']);  // Получить разрешения роли
});

//// LOGING and REGISTER users

//unauthorized
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

//authorized
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [UserController::class, 'me']);
    Route::post('change-password', [UserController::class, 'changePassword']);

    //tokens
    Route::get('tokens', [TokensController::class, 'tokens']);
    Route::post('refresh', [TokensController::class, 'refresh']);
    Route::delete('tokens/revoke', [TokensController::class, 'revokeAllTokens']);
});
