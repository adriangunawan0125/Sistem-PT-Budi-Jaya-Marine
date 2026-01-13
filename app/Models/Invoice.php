<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Mitra;
class Invoice extends Model
{
    protected $fillable = [
        'mitra_id',
        'tanggal',
        'status',
        'total'
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
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
    public function unit()
{
    return $this->belongsTo(Unit::class);
}

}
