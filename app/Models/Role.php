<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array
     */
    protected $fillable = [
        'name',        // Наименование роли
        'cipher',      // Шифр роли
        'description', // Описание
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в дату.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',  // Поддержка мягкого удаления
    ];

    /**
     * Связь "многие ко многим" с моделью User.
     * Пользователи, которые имеют эту роль.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_and_roles');
    }

    /**
     * Связь "многие ко многим" с моделью Permission.
     * Разрешения, которые имеют эта роль.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_and_permissions');
    }
}