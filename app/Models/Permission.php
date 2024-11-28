<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'cipher'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_and_permissions');
    }
}
