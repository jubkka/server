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
    public function __construct(string $name, string $cipher, ?string $description = null)
    {
        $this->name = $name;
        $this->cipher = $cipher;
        $this->description = $description;
    }

    /**
     * Преобразование объекта в массив.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
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