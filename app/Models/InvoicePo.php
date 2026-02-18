<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePo extends Model
{
    protected $table = 'invoice_po';

    protected $fillable = [
        'po_masuk_id',
        'no_invoice',
        'tanggal_invoice',
        'authorization_no',
        'periode',
        'manpower',
        'discount_type',
        'discount_value',
        'discount_amount',
        'subtotal',
        'grand_total',
        'status',
    ];

    /* ================= RELATION ================= */


    public function items()
    {
        return $this->hasMany(InvoicePoItem::class);
    }

    /* ================= RECALCULATE ================= */

    public function recalculateTotals()
    {
        // Hitung subtotal
        $subtotal = $this->items()->sum('amount');

        $discountAmount = 0;

        if ($this->discount_type && $this->discount_value) {

            if ($this->discount_type === 'percent') {
                $discountAmount = ($subtotal * $this->discount_value) / 100;
            }

            if ($this->discount_type === 'nominal') {
                $discountAmount = $this->discount_value;
            }
        }

        $grandTotal = $subtotal - $discountAmount;

        $this->update([
            'subtotal'        => $subtotal,
            'discount_amount' => $discountAmount,
            'grand_total'     => $grandTotal,
        ]);
    }
    public function poMasuk()
{
    return $this->belongsTo(\App\Models\PoMasuk::class, 'po_masuk_id');
}

public function soaItems()
{
    return $this->hasMany(SoaItem::class, 'invoice_po_id');
}


}
