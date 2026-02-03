<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarineInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'marine_invoice_items';

    protected $fillable = [
        'marine_invoice_id',
        'description',
        'qty',
        'unit',
        'price',
        'amount',
    ];

    /**
     * Relasi ke invoice
     */
    public function invoice()
    {
        return $this->belongsTo(MarineInvoice::class, 'marine_invoice_id');
    }
}
