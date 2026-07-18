<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $guarded = [];

    public function absensiPegawais()
    {
        return $this->hasMany(AbsensiPegawai::class);
    }
}
