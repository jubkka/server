<?php

namespace App\Http\Requests;

use App\Http\Resources\PermissionResource;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:permissions|max:255',
            'cipher' => 'required|unique:permissions|max:255',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function toDTO()
    {
        return new PermissionResource([
            'name' => $this->input('name'),
            'cipher' => $this->input('cipher'),
            'description' => $this->input('description'),
        ]);
    }
}
