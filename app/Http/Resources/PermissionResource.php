<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public $name;
    public $cipher;
    public $description;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->cipher = $data['cipher'];
        $this->description = $data['description'];
    }
}
