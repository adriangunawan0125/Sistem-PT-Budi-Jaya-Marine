<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingReportImage extends Model
{
    protected $fillable = [
        'working_report_item_id',
        'image_path',
    ];

    public function item()
    {
        return $this->belongsTo(WorkingReportItem::class, 'working_report_item_id');
    }
}
