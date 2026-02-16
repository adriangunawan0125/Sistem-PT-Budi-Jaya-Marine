<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'po_masuk_id',
        'project',
        'manpower',
        'status',
        'total_hours',
    ];

    /* ================= RELATION ================= */

    public function poMasuk()
    {
        return $this->belongsTo(PoMasuk::class);
    }

    public function items()
    {
        return $this->hasMany(TimesheetItem::class);
    }

    /* ================= RECALCULATE TOTAL HOURS ================= */

    public function recalculateTotalHours()
    {
        $total = $this->items()->sum('hours');

        $this->update([
            'total_hours' => $total
        ]);
    }
}
