<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'mitra_id',
        'vessel_id',
        'attention',
        'quote_no',
        'date',
        'project',
        'place',
    ];

    public function mitra()
    {
        return $this->belongsTo(MitraMarine::class, 'mitra_id');
    }

    public function vessel()
    {
        return $this->belongsTo(VesselMitra::class, 'vessel_id');
    }

    public function subItems()
    {
        return $this->hasMany(QuotationSubItem::class);
    }

   public function termsConditions()
{
    return $this->hasMany(QuotationTermsCondition::class);
}

}
