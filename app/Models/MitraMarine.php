<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraMarine extends Model
{
    protected $fillable = [
        'nama_mitra',
        'address',
        'telp'
    ];

    public function vessels()
    {
        return $this->hasMany(VesselMitra::class);
    }
}
