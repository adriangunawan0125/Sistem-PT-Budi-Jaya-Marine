<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item',
        'tanggal',
        'cicilan',
        'tagihan',
        'amount',
        'gambar_trip',      // baru
        'gambar_transfer'   // baru
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}

