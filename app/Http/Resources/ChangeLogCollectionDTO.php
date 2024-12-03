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
}
