<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleCollectionResorces extends JsonResource
{
    /**
     * Массив объектов RoleResources.
     *
     * @var \App\DTOs\RoleResources[]
     */
    public array $roles;

    /**
     * Конструктор коллекции ролей.
     *
     * @param \App\DTOs\RoleDTO[] $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Преобразовать коллекцию в массив.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return array_map(function (RoleResources $role) {
            return $role->toArray();
        }, $this->roles);
    }

    /**
     * Создать DTO коллекцию из массива данных.
     *
     * @param array $data
     * @return RoleCollectionResources
     */
    public static function fromArray(array $data): self
    {
        $roles = array_map(function ($roleData) {
            return new RoleResources(
                $roleData['name'],
                $roleData['cipher'],
                $roleData['description'] ?? null
            );
        }, $data);

        return new self($roles);
    }
}