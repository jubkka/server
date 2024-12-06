<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChangeLogDTO extends ResourceCollection
{
    public $entityType;
    public $entityId;
    public $beforeChange;
    public $afterChange;
    public $operation_type;
    public $createdBy;
    public $createdAt;

    public function __construct($entityType, $entityId, $beforeChange, $afterChange, $operation_type, $createdBy, $createdAt)
    {
        $this->entityType = $entityType;
        $this->entityId = $entityId;
        $this->beforeChange = $beforeChange;
        $this->afterChange = $afterChange;
        $this->operation_type = $operation_type;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }
}
