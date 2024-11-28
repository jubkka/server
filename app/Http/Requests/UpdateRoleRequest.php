<?php

namespace App\Http\Requests;

use App\Http\Resources\RoleResources;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь на выполнение этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
        // Проверка на авторизацию
        //return auth()->check();  // Пользователь должен быть авторизован
    }

    /**
     * Получить правила валидации для запроса.
     *
     * @return array
     */
    public function rules(): array
    {
        $roleId = $this->route('role');  // Получаем ID роли из маршрута

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($roleId), // Игнорируем уникальность для текущей роли
            ],
            'cipher' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($roleId), // Игнорируем уникальность для текущей роли
            ],
            'description' => 'nullable|string',
        ];
    }

    /**
     * Сообщения об ошибках для валидации.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Наименование роли обязательно.',
            'name.unique' => 'Роль с таким наименованием уже существует.',
            'cipher.required' => 'Шифр роли обязателен.',
            'cipher.unique' => 'Роль с таким шифром уже существует.',
        ];
    }

    /**
     * Возвращает экземпляр DTO для роли на основе валидированных данных.
     *
     * @return RoleDTO
     */
    public function toDTO(): RoleResources
    {
        return new RoleResources(
            $this->validated()['name'],
            $this->validated()['cipher'],
            $this->validated()['description'] ?? null
        );
    }
}