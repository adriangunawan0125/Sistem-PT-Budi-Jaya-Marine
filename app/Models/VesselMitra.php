<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VesselMitra extends Model
{
    protected $fillable = [
        'mitra_marine_id',
        'nama_vessel'
    ];

    public function mitra()
    {
        return $this->belongsTo(MitraMarine::class);
    }
}
