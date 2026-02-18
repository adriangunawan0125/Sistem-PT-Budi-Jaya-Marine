<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingReport extends Model
{
    protected $fillable = [
        'po_masuk_id',
        'project',
        'place',
        'periode',
    ];

    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class);
    }

    public function items()
    {
        return $this->hasMany(WorkingReportItem::class);
    }
}
