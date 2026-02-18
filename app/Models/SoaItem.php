<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SoaItem extends Model
{
   protected $fillable = [
    'soa_id',
    'invoice_po_id',
    'job_details',   // ← tambahkan ini
    'acceptment_date',
    'remarks',
];


    protected $casts = [
        'acceptment_date' => 'date',
    ];

    /* ================= RELATION ================= */

    public function soa()
    {
        return $this->belongsTo(Soa::class);
    }

    public function invoice()
    {
        return $this->belongsTo(InvoicePo::class, 'invoice_po_id');
    }

    /*
    ================= DYNAMIC ATTRIBUTE =================
    */

    // Pending Payment (auto ambil dari invoice)
    public function getPendingPaymentAttribute()
    {
        return $this->invoice?->grand_total ?? 0;
    }

    // Days (selisih acceptment_date & statement_date)
    public function getDaysAttribute()
    {
        if (!$this->acceptment_date || !$this->soa?->statement_date) {
            return null;
        }

        return Carbon::parse($this->soa->statement_date)
            ->diffInDays($this->acceptment_date);
    }
}
