<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimesheetItem extends Model
{
    protected $fillable = [
        'timesheet_id',
        'work_date',
        'day',
        'time_start',
        'time_end',
        'hours',
        'manpower',
        'kind_of_work',
    ];

    protected $casts = [
        'work_date' => 'date',
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
    ];

    /* ================= RELATION ================= */

    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class);
    }

    /* ================= AUTO CALCULATE HOURS ================= */

    public function calculateHours()
    {
        if ($this->time_start && $this->time_end) {

            $start = Carbon::parse($this->time_start);
            $end   = Carbon::parse($this->time_end);

            $diff = $start->floatDiffInHours($end);

            $this->hours = $diff;
        }
    }
}
