<?php

namespace App\Http\Requests;

use App\Http\Resources\RoleResources;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
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
        return [
            'name' => 'required|string|unique:roles,name',  // Наименование роли обязательно и уникально
            'cipher' => 'required|string|unique:roles,cipher',  // Шифр роли обязателен и уникален
            'description' => 'nullable|string',  // Описание роли не обязательно
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
