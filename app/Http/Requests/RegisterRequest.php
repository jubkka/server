<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
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
            'name.required' => 'Имя обязательно для заполнения.',
            'email.required' => 'Email обязателен для заполнения.',
            'email.unique' => 'Этот email уже зарегистрирован.',
            'password.required' => 'Пароль обязателен для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ];
    }
}
