<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TwoFactorCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'code', 'client_ip', 'expires_at'
    ];

    public function isExpired()
    {
        return Carbon::now()->greaterThanOrEqualTo($this->expires_at);
    }
}
