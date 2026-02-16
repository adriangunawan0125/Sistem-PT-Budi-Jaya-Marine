<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoSupplierItem extends Model
{
    use HasFactory;

    protected $table = 'po_supplier_items';

    protected $fillable = [
        'po_supplier_id',
        'item',
        'price_beli',
        'qty',
        'unit',
        'amount',
    ];

    public function poSupplier()
    {
        return $this->belongsTo(PoSupplier::class, 'po_supplier_id');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO CALCULATE AMOUNT
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::saving(function ($item) {
            $item->amount = ($item->price_beli ?? 0) * ($item->qty ?? 0);
        });

        static::saved(function ($item) {
            $item->poSupplier?->recalculateTotals();
        });

        static::deleted(function ($item) {
            $item->poSupplier?->recalculateTotals();
        });
    }
}
