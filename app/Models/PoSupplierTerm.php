<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoSupplierTerm extends Model
{
    protected $table = 'po_supplier_terms';

    protected $fillable = [
        'po_supplier_id',
        'description',
    ];

    public function poSupplier()
    {
        return $this->belongsTo(PoSupplier::class);
    }
}
