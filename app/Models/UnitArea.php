<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitArea extends Model
{
    protected $table = 'unit_area';
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
