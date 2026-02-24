<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'no_invoices',
        'tanggal_invoices',
        'item',
        'tanggal_tf',
        'cicilan',
        'tagihan',
        'amount',

        // ====== GAMBAR TRIP (2) ======
        'gambar_trip',
        'gambar_trip1',

        // ====== GAMBAR TRANSFER (3) ======
        'gambar_transfer',
        'gambar_transfer1',
        'gambar_transfer2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
