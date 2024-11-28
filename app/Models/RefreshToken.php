<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'refresh_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
