<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranPo extends Model
{
    protected $table = 'pengeluaran_po';

    protected $fillable = [
        'po_masuk_id',
        'item',
        'qty',
        'price',
        'amount',
        'bukti_gambar',
    ];

    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class);
    }
}
