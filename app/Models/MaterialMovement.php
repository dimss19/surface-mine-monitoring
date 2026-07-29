<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialMovement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'stok_sebelum' => 'decimal:2',
        'stok_sesudah' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
