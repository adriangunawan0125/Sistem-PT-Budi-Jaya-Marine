<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
   protected $fillable = [
    'mitra_name',
    'vessel_name',
    'attention',
    'quote_no',
    'date',
    'project',
    'place',
];


    public function subItems()
    {
        return $this->hasMany(QuotationSubItem::class);
    }

   public function termsConditions()
{
    return $this->hasMany(QuotationTermsCondition::class);
}

}
