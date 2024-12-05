<?php

namespace App\Observers;

use App\Models\ChangeLog;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission)
    {
        DB::transaction(function () use ($permission) {
            ChangeLog::create([
                'entity_type' => 'Permission',
                'entity_id' => $permission->id,
                'before_change' => null, 
                'after_change' => json_encode($permission->getAttributes()),
                'created_by' => null, 
                'operation_type' => 'create' // операция создания 
            ]);
        });
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        DB::transaction(function () use ($permission) {
            // Получаем старые и новые данные
             $before = $permission->getOriginal();  // Старые атрибуты модели
             $after = $permission->getAttributes(); // Новые атрибуты модели
 
             // Определяем измененные атрибуты
             $changed = array_diff_assoc($after, $before);
 
             if (!empty($changed)) {
                 ChangeLog::create([
                     'entity_type' => 'Permission',          // Тип сущности (User)
                     'entity_id' => $permission->id,         // ID измененной сущности
                     'before_change' => json_encode(array_intersect_key($before, $changed)),  // Старые данные
                     'after_change' => json_encode($changed),  // Новые данные
                     'created_by' => Auth::id() ?? 1,       // ID текущего пользователя (Auth::id() может быть null для автоматических операций)
                     'operation_type' => 'update'
                 ]);
             } 
         });
    }

    public function deleted(Permission $permission)
    {        
        DB::transaction(function () use ($permission) {
            $after = $permission->getAttributes();
            $permission->deleted_at = null;
            $before = $permission->getAttributes();

            $changed = array_diff_assoc($after, $before);
        
            ChangeLog::create([
                'entity_type' => 'Permission',
                'entity_id' => $permission->id,
                'before_change' => json_encode(array_intersect_key($before, $changed)),
                'after_change' => json_encode($changed),
                'created_by' => null, 
                'operation_type' => 'soft_delete' // операция удаления 
            ]);
        });   
    }
}
