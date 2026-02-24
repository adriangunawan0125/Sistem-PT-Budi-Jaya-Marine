<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mitra;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'mitra_id',
        'kategori',
        'deskripsi',
        'nominal',
        'gambar',
        'gambar1'
    ];

    /* ================= RELATION ================= */

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
