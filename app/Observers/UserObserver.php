<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ChangeLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function updated(User $user)
    {
        DB::transaction(function () use ($user) {
           // Получаем старые и новые данные
            $before = $user->getOriginal();  // Старые атрибуты модели
            $after = $user->getAttributes(); // Новые атрибуты модели

            // Определяем измененные атрибуты
            $changed = array_diff_assoc($after, $before);

            if (!empty($changed)) {
                ChangeLog::create([
                    'entity_type' => 'User',          // Тип сущности (User)
                    'entity_id' => $user->id,         // ID измененной сущности
                    'before_change' => json_encode(array_intersect_key($before, $changed)),  // Старые данные
                    'after_change' => json_encode($changed),  // Новые данные
                    'created_by' => Auth::id() ?? 0,       // ID текущего пользователя (Auth::id() может быть null для автоматических операций)
                    'operation_type' => 'update'
                ]);
            } 
        });
    }

    public function created(User $user)
    {
        DB::transaction(function () use ($user) {
            ChangeLog::create([
                'entity_type' => 'User',
                'entity_id' => $user->id,
                'before_change' => null, 
                'after_change' => json_encode($user->getAttributes()),
                'created_by' => null, 
                'operation_type' => 'create', // операция создания 
            ]);
        });
    }

    public function deleted(User $user)
    {
        DB::transaction(function () use ($user) {
            $before = $user->getOriginal();  // Старые атрибуты модели

            // Определяем измененные атрибуты
            //$changed = array_diff_assoc($user, $before);

            ChangeLog::create([
                'entity_type' => 'User',
                'entity_id' => $user->id,
                'before_change' => json_encode($before),
                'after_change' => json_encode($user),
                'created_by' => null, 
                'operation_type' => 'soft_delete' // операция удаления 
            ]);
        });        
    }

    public function forceDeleting(User $user)
    {
        DB::transaction(function () use ($user) {
            ChangeLog::create([
                'entity_type' => 'User',
                'entity_id' => $user->id,
                'before_change' => json_encode($user->getAttributes()),
                'after_change' => null,
                'created_by' => null, 
                'operation_type' => 'force_delete' // операция удаления 
            ]);
        });        
    }
}