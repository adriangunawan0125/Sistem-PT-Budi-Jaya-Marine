<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemasukanMarine extends Model
{
    protected $table = 'pemasukan_marine';

    protected $fillable = [
        'po_masuk_id',
        'tanggal',
        'nama_pengirim',
        'metode',
        'keterangan',
        'bukti',
        'nominal', 
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Relasi ke PO Masuk
    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class);
    }
}