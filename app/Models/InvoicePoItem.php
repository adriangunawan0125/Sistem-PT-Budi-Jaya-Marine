<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePoItem extends Model
{
    protected $table = 'invoice_po_items';

    protected $fillable = [
        'invoice_po_id',
        'description',
        'qty',
        'unit',
        'price',
        'amount',
    ];

    /* ================= RELATION ================= */

    public function invoicePo()
    {
        return $this->belongsTo(InvoicePo::class);
    }

    /* ================= AUTO AMOUNT ================= */

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->amount = $item->qty * $item->price;
        });
    }
}
