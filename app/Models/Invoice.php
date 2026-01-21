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

    public function refreshTotalAndStatus()
{
    $total = $this->items()->sum('amount');

    $this->update([
        'total' => $total,
        'status' => $total <= 0 ? 'lunas' : 'belum_lunas'
    ]);
}

}
