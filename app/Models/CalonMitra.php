<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonMitra extends Model
{
    use HasFactory;

    protected $table = 'calonmitra';

    protected $fillable = [
        'nama',
        'no_handphone',
        'alamat',
        'jaminan',
        'gambar_1',
        'gambar_2',
        'gambar_3',
        'is_checked'
        
    ];
}
