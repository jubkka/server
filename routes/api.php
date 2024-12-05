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
use App\Http\Controllers\ChangeLogController;

//// ROLE POLICY
Route::prefix('/ref')->middleware('auth:sanctum')->group(function () {

    Route::prefix('/policy')->group(function () {
        Route::prefix('/permission')->group(function () {
            Route::get('/', [PermissionController::class, 'index']); // Получение списка разрешений
            Route::post('/', [PermissionController::class, 'store']); // Создать новое разрешение
            
            Route::prefix('/{id}')->group(function () {
                //Route::get('/story', [ChangeLogController::class, 'index']);

                Route::get('/', [PermissionController::class, 'show']); // Получить конкретное разрешение 
                Route::put('/', [PermissionController::class, 'update']); // Обновить разрешение
                Route::delete('/', [PermissionController::class, 'destroy']); // Жестко удалить разрешение
                Route::delete('/soft', [PermissionController::class, 'softDelete']); // Мягко удалить разрешение
                Route::post('/restore', [PermissionController::class, 'restore']); // Восстановить разрешение
            });
            
        });
    
        //Role
        Route::prefix('/role')->group(function () {
            Route::get('/', [RoleController::class, 'index']); // Список ролей
            Route::post('/', [RoleController::class, 'store']); // Создать новую роль
            
            Route::prefix('/{id}')->group(function () {
                //Route::get('/story', [ChangeLogController::class, 'index']);

                Route::get('/', [RoleController::class, 'show']); // Получить конкретную роль
                Route::put('/', [RoleController::class, 'update']); // Обновить роль
                Route::delete('/', [RoleController::class, 'destroy']); // Жесткое удаление
                Route::delete('/soft', [RoleController::class, 'softDelete']); // Мягко удалить роль
                Route::post('/restore', [RoleController::class, 'restore']); // Восстановить роль
                Route::get('/permissions', [RolePermissionController::class, 'getRolePermissions']);  // Получить разрешения роли
                Route::post('/permissions', [RolePermissionController::class, 'assignPermission']);  // Назначить разрешение
                Route::delete('/permissions/{permissionId}', [RolePermissionController::class, 'removePermission']);  // Удалить разрешение
            });
        });
    });

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::delete('/destroy/{id}', [UserController::class, 'destroyUser']);
        Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);

        // Группа маршрутов для работы с ролями пользователей
        Route::prefix('{userId}/role')->group(function () {

            Route::get('/', [UserRoleController::class, 'getUserRoles']); // Получение всех ролей пользователя
            Route::post('/', [UserRoleController::class, 'assignRole']); // Назначить роль пользователю
            Route::delete('{roleId}', [UserRoleController::class, 'destroy']); // Удалить роль жестко
            Route::delete('{roleId}/soft}', [UserRoleController::class, 'softDelete']);
            Route::post('{roleId}/restore}', [UserRoleController::class], 'restore');
        });
    });

    

});



Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('ref/{entityType}/{entityId}/story')->group(function () {
        Route::get('/', [ChangeLogController::class, 'getEntityChangeLog']);
        Route::put('/restore/{logId}', [ChangeLogController::class, 'restoreEntityState']);
    })->where(['entityType' => 'user|role|permission', 'entityId' => '[0-9]+']);
    
});

//// ACTIONS users

//Route::get('users', [UserController::class, 'users'])->middleware('permission:get-list-user');;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [UserController::class, 'read']);
    Route::post('update/{id}', [UserController::class, 'update']);
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