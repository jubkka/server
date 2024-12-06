<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResources extends JsonResource
{
    public string $name;
    public string $cipher;
    public ?string $description;
    
    /**
     * Конструктор класса.
     *
     * @param string $name
     * @param string $cipher
     * @param string|null $description
     */
    public function __construct($resource, $additional = null)
    {
        parent::__construct($resource);
        
        // Если у вас есть дополнительные данные, которые нужно передавать
        $this->name = $resource->name;
        $this->description = $resource->description;
        $this->cipher = $resource->cipher;
    }

    /**
     * Преобразование объекта в массив.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cipher' => $this->cipher,
            'description' => $this->description,
        ];
    }

    /**
     * Создание DTO объекта из массива данных.
     *
     * @param array $data
     * @return RoleDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['cipher'],
            $data['description'] ?? null
        );
    }
}