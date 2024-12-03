<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'before_change',
        'after_change',
        'created_by',
        'operation_type'
    ];

    protected $casts = [
        'before_change' => 'array',  // Или 'json', в зависимости от формата данных
        'after_change' => 'array',   // Сериализация значений
    ];

    // Связь с пользователем, который создал запись
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
