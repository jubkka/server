<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
