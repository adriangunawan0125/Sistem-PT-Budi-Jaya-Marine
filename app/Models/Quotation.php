<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
   protected $fillable = [
    'quote_no',
    'mitra_name',
    'vessel_name',
    'attention',
    'date',
    'project',
    'place',
    'discount_type',
    'discount_value',
    'discount_amount',
];



    public function subItems()
    {
        return $this->hasMany(QuotationSubItem::class);
    }

   public function termsConditions()
{
    return $this->hasMany(QuotationTermsCondition::class);
}
public function getSubtotalAttribute()
{
    return $this->subItems
        ->sum(fn($sub) => $sub->items->sum('total'));
}

public function getGrandTotalAttribute()
{
    return $this->subtotal - ($this->discount_amount ?? 0);
}

}
