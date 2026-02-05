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
        'status',
    ];

    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class, 'po_masuk_id');
    }

    public function items()
    {
        return $this->hasMany(PoSupplierItem::class, 'po_supplier_id');
    }

    // ✅ INI YANG KURANG
    public function totalBeli()
    {
        return $this->items()->sum('amount');
    }
}

