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
            'name' => [
                'required',
                'string',
                'min:7',
                'max:32',
                'unique:users,name',
                'regex:/^[A-Z][a-zA-Z]*$/' // Начинается с большой буквы и состоит только из латинских букв
            ],
            'email' => 'required|email|unique:users,email|max:255',
            'birthday' => 'required|date|before_or_equal:today|after_or_equal:1900-01-01',
            'password' => [
                'required',
                'string',
                'min:8', // Минимальная длина 8 символов
                'confirmed',
                'regex:/[0-9]/', // Содержит хотя бы 1 цифру
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // Содержит хотя бы 1 специальный символ
                'regex:/[a-z]/', // Содержит хотя бы 1 символ в нижнем регистре
                'regex:/[A-Z]/', // Содержит хотя бы 1 символ в верхнем регистре
            ],
            'password_confirmation' => [
                'required',
                'string',
            ],
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
            'name.min' => 'Минимальная длина для имени 7 символов.',
            'name.max' => 'Максимальная длина для имени 32 символа.',
            'name.required' => 'Имя обязательно для заполнения.',
            'email.required' => 'Email обязателен для заполнения.',
            'email.unique' => 'Этот email уже зарегистрирован.',
            'password.required' => 'Пароль обязателен для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ];
    }
}
