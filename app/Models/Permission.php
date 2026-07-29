<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function isAllowedFor(string $role): bool
    {
        return $this->rolePermissions()
            ->where('role', $role)
            ->where('allowed', true)
            ->exists();
    }
}
