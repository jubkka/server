<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChangeLogCollectionDTO extends JsonResource
{
    public $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function toArray($request)
    {
        return [
            'data' => $this->logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'operation_type' => $log->operation_type,
                    'created_at' => $log->created_at,
                    'created_by' => $log->created_by,
                    'changed_attributes' => $log->getChangedAttributes(),
                ];
            }),
        ];
    }
}
