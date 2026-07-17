<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $guarded = [];

    public function absensiPegawais()
    {
        return $this->hasMany(AbsensiPegawai::class);
    }

    public function pemantauanLapangans()
    {
        return $this->hasMany(PemantauanLapangan::class);
    }
