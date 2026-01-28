<?php

namespace App\Models;
use App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_unit',
        'merek',
        'status',
        'stnk_expired_at'
    ];

    protected $casts = [
    'stnk_expired_at' => 'datetime',
];


    public function mitra()
{
    return $this->hasOne(Mitra::class);
}

}
