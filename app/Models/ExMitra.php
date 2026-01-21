<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExMitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jaminan',
        'no_handphone',
        'keterangan',
    ];
}
