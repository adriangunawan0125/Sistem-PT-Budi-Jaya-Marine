<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoMasukItem extends Model
{
    use HasFactory;

    protected $table = 'po_masuk_items';

    protected $fillable = [
        'po_masuk_id',
        'item',
        'price_jual',
        'qty',
        'unit',
        'amount',
    ];

    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class, 'po_masuk_id');
    }
}
