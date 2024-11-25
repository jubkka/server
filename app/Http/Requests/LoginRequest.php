<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Определяем, авторизован ли пользователь для выполнения этого запроса.
     *
     * @return bool
     */
    public function authorize()
    {
        // Разрешаем выполнение запроса для всех пользователей
        return true;
    }

    /**
     * Получить правила валидации, которые применяются к запросу.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * Получить настраиваемые сообщения об ошибках.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email обязателен для заполнения.',
            'email.exists' => 'Пользователь с таким email не найден.',
            'password.required' => 'Пароль обязателен для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
        ];
    }
}