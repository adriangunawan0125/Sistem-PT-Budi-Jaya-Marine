<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoMasuk extends Model
{
    use HasFactory;

    protected $table = 'po_masuk';

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'tanggal_po',
        'no_po_klien',
        'vessel',
        'total_jual',
        'status',
        'margin',
        'margin_status',
    ];

    // PO Masuk punya banyak item
    public function items()
    {
        return $this->hasMany(PoMasukItem::class, 'po_masuk_id');
    }

    // PO Masuk punya banyak PO Supplier
    public function poSuppliers()
    {
        return $this->hasMany(PoSupplier::class, 'po_masuk_id');
    }

    // PO Masuk punya satu / banyak DO
    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'po_masuk_id');
    }

    // Hitung total beli dari semua PO supplier
    public function totalBeli()
    {
        return $this->poSuppliers()->sum('total_beli');
    }

    // Hitung margin
    public function hitungMargin()
    {
        return $this->total_jual - $this->totalBeli();
    }
}
