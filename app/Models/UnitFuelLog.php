<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitFuelLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fuel_amount' => 'decimal:2',
        'odometer' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
