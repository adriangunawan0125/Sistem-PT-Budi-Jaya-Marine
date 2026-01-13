<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use App\Models\Invoice;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mitra',
        'unit_id',
        'alamat',
        'no_hp'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Ganti hasOne jadi hasMany
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
