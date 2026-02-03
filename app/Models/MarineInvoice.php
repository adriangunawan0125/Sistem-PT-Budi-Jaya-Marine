<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarineInvoice extends Model
{
    use HasFactory;

    protected $table = 'marine_invoices';

    protected $fillable = [
        'company_id',
        'project',
        'invoice_date',
        'period',
        'authorization_no',
        'vessel',
        'po_no',
        'manpower',
        'subtotal',
        'dp',
        'grand_total',
    ];

    /**
     * Relasi ke Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relasi ke item invoice
     */
    public function items()
    {
        return $this->hasMany(MarineInvoiceItem::class, 'marine_invoice_id');
    }
}
