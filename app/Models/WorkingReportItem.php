<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingReportItem extends Model
{
    protected $fillable = [
        'working_report_id',
        'work_date',
        'detail',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function workingReport()
    {
        return $this->belongsTo(WorkingReport::class);
    }

    public function images()
    {
        return $this->hasMany(WorkingReportImage::class);
    }
}
