<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoSupplier extends Model
{
    use HasFactory;

    protected $table = 'po_supplier';

    protected $fillable = [
        'po_masuk_id',
        'nama_perusahaan',
        'alamat',
        'tanggal_po',
        'no_po_internal',
        'total_beli',
        'discount_type',
        'discount_value',
        'discount_amount',
        'grand_total',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class, 'po_masuk_id');
    }

    public function items()
    {
        return $this->hasMany(PoSupplierItem::class, 'po_supplier_id');
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    // Hitung total dari semua item
    public function calculateTotalBeli()
    {
        return $this->items()->sum('amount');
    }

    // Hitung discount nominal
    public function calculateDiscountAmount($total)
    {
        if (!$this->discount_type || !$this->discount_value) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            return ($total * $this->discount_value) / 100;
        }

        return $this->discount_value;
    }

    // Hitung grand total
    public function calculateGrandTotal($total, $discount)
    {
        $grand = $total - $discount;

        // Jangan sampai minus
        return $grand < 0 ? 0 : $grand;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO RECALCULATE
    |--------------------------------------------------------------------------
    */

    public function recalculateTotals()
    {
        $total = $this->calculateTotalBeli();
        $discount = $this->calculateDiscountAmount($total);
        $grand = $this->calculateGrandTotal($total, $discount);

        $this->update([
            'total_beli'     => $total,
            'discount_amount'=> $discount,
            'grand_total'    => $grand,
        ]);
    }
    public function terms()
{
    return $this->hasMany(PoSupplierTerm::class);
}

}
