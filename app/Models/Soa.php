<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soa extends Model
{
    protected $fillable = [
        'debtor',
        'address',
        'statement_date',
        'termin',
    ];

    protected $casts = [
        'statement_date' => 'date',
    ];

    /* ================= RELATION ================= */

    public function items()
    {
        return $this->hasMany(SoaItem::class);
    }
}
