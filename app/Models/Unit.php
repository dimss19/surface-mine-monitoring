<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'kapasitas' => 'decimal:2',
        'fuel_consumption_rate' => 'decimal:2',
        'tahun' => 'integer',
        'is_active' => 'boolean',
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
    ];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'unit_area')->withTimestamps();
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_unit')->withPivot('consumption_rate');
    }

    public function ritasis()
    {
        return $this->hasMany(Ritasi::class);
    }

    public function nonRitasis()
    {
        return $this->hasMany(NonRitasi::class);
    }

    public function fuelLogs()
    {
        return $this->hasMany(UnitFuelLog::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'badge-active',
            'maintenance' => 'badge-maintenance',
            'breakdown' => 'badge-breakdown',
            'standby' => 'badge-standby',
            default => 'badge-standby',
        };
    }
}
