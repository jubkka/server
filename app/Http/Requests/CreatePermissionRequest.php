<?php

namespace App\Http\Requests;

use App\Http\Resources\PermissionResource;
use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $permissionId = $this->route('id');
        return [
            'name' => 'required|unique:permissions,name,' . $permissionId . '|max:255',
            'cipher' => 'required|unique:permissions,cipher,' . $permissionId . '|max:255',
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
