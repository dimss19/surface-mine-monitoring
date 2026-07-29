<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
