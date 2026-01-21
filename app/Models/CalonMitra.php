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
        
    ];
}
