<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PemantauanLapangan extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    public function spv()
    {
        return $this->belongsTo(User::class, 'spv_id');
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
