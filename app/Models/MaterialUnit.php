<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialUnit extends Model
{
    protected $table = 'material_unit';
    protected $guarded = [];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
