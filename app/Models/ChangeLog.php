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

    public function getChangedAttributes(): array
    {
        $before = is_array($this->before_change) ? $this->before_change : (json_decode($this->before_change, true) ?? []);
        $after = is_array($this->after_change) ? $this->after_change : (json_decode($this->after_change, true) ?? []);

        return $this->filterChangedAttributes($before, $after);
    }

    /**
     * Фильтрует изменившиеся атрибуты.
     *
     * @param array $before Массив данных до изменений.
     * @param array $after Массив данных после изменений.
     * @return array Измененные атрибуты.
     */
    private function filterChangedAttributes(array $before, array $after): array
    {
        $changedAttributes = [];

        foreach ($after as $key => $value) {
            if (!array_key_exists($key, $before) || $before[$key] !== $value) {
                $changedAttributes[$key] = [
                    'before' => $before[$key] ?? null,
                    'after' => $value,
                ];
            }
        }

        return $changedAttributes;
    }

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
