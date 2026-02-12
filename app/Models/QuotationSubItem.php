<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationSubItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'name',
        'item_type',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'sub_item_id');
    }
}
