<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this->resource['user']->id,
                'name' => $this->resource['user']->name,
                'email' => $this->resource['user']->email,
                'created_at' => $this->resource['user']->created_at,
                'updated_at' => $this->resource['user']->updated_at,
            ],
            'token' => $this->resource['token'], // Включаем токен в ответ
            'refresh_token' => $this->resource['refresh_token'], // Включаем токен в ответ
        ];
    }
}