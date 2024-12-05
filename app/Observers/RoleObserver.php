<?php

namespace App\Observers;

use App\Models\ChangeLog;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleObserver
{
    public function updated(Role $role)
    {
        DB::transaction(function () use ($role) {
            // Получаем старые и новые данные
            $before = $role->getOriginal();  // Старые атрибуты модели
            $after = $role->getAttributes(); // Новые атрибуты модели

            // Определяем измененные атрибуты
            $changed = array_diff_assoc($after, $before);

            if (!empty($changed)) {
                ChangeLog::create([
                    'entity_type' => 'Role',          // Тип сущности (User)
                    'entity_id' => $role->id,         // ID измененной сущности
                    'before_change' => json_encode(array_intersect_key($before, $changed)),  // Старые данные
                    'after_change' => json_encode($changed),  // Новые данные
                    'created_by' => Auth::id() ?? 1,       // ID текущего пользователя (Auth::id() может быть null для автоматических операций)
                    'operation_type' => 'update'
                ]);
            }
        });
    }

    public function created(Role $role)
    {
        DB::transaction(function () use ($role) {
            ChangeLog::create([
                'entity_type' => 'Role',
                'entity_id' => $role->id,
                'before_change' => null, 
                'after_change' => json_encode($role->getAttributes()),
                'created_by' => null, 
                'operation_type' => 'create' // операция создания 
            ]);
        });
    }

    public function deleted(Role $role)
    {
        DB::transaction(function () use ($role) {
            $after = $role->getAttributes();
            $role->deleted_at = null;
            $before = $role->getAttributes();

            $changed = array_diff_assoc($after, $before);
        
            ChangeLog::create([
                'entity_type' => 'Role',
                'entity_id' => $role->id,
                'before_change' => json_encode(array_intersect_key($before, $changed)),
                'after_change' => json_encode($changed),
                'created_by' => null, 
                'operation_type' => 'soft_delete' // операция удаления 
            ]);
        });   
    }

}
