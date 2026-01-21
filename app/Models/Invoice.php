<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'ex_mitra_id', // 🔥 WAJIB
        'tanggal',
        'status',
        'total'
    ];

    // =====================
    // RELATION
    // =====================

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    public function exMitra()
    {
        return $this->belongsTo(ExMitra::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transfers()
    {
        return $this->hasMany(InvoiceTransfer::class);
    }

    public function trips()
    {
        return $this->hasMany(InvoiceTrip::class);
    }
}
