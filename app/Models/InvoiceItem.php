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
        'gambar_trip',
        'gambar_transfer'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
