<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPegawai extends Model
{
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
