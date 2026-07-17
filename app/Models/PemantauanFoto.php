<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemantauanFoto extends Model
{
    protected $guarded = [];

    public function pemantauanLapangan()
    {
        return $this->belongsTo(PemantauanLapangan::class);
    }
