<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JaminanMitra extends Model
{
    protected $fillable = [
        'mitra_id',
        'jaminan',
        'gambar_1',
        'gambar_2',
        'gambar_3',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
