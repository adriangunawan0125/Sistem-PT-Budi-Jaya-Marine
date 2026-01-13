<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTrip extends Model
{
    protected $fillable = [
        'invoice_id',
        'gambar'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
