<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use App\Models\Invoice;
use App\Models\JaminanMitra;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mitra',
        'unit_id',
        'alamat',
        'no_hp',
        'kontrak_mulai',
        'kontrak_berakhir',
        'status',
    ];

    protected $casts = [
        'kontrak_mulai' => 'date',
        'kontrak_berakhir' => 'date',
    ];

    /* ======================
     |  RELATION
     |====================== */

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function jaminan()
    {
        return $this->hasOne(JaminanMitra::class);
    }

    /* ======================
     |  QUERY SCOPE
     |====================== */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeBerakhir($query)
    {
        return $query->where('status', 'berakhir');
    }

    /* ======================
     |  HELPER
     |====================== */

    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    public function isBerakhir()
    {
        return $this->status === 'berakhir';
    }
}
