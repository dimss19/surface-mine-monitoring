<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = [];

    public function spvs()
    {
        return $this->belongsToMany(User::class, 'area_spv', 'area_id', 'spv_id');
    }

    public function absensiPegawais()
    {
        return $this->hasMany(AbsensiPegawai::class);
    }

    public function pemantauanLapangans()
    {
        return $this->hasMany(PemantauanLapangan::class);
    }
}
