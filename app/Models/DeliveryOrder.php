<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOrder extends Model
{
    use HasFactory;

    protected $table = 'delivery_orders';

    protected $fillable = [
        'po_masuk_id',
        'tanggal_do',
        'no_do',
        'status',
    ];

    public function poMasuk()
{
    return $this->belongsTo(PoMasuk::class);
}

public function items()
{
    return $this->hasMany(DeliveryOrderItem::class);
}

}
