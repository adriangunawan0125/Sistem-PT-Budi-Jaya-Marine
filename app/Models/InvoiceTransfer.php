<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTransfer extends Model
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
