<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'sub_item_id',
        'item',
        'price',
        'qty',
        'unit',
        'day',
        'hour',
        'total',
    ];

    public function subItem()
    {
        return $this->belongsTo(QuotationSubItem::class, 'sub_item_id');
    }
}
