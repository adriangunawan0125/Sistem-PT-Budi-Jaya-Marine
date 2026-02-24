<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranPajak extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_pajak';
    protected $fillable = ['unit_id', 'tanggal', 'deskripsi', 'nominal', 'gambar', 'gambar1'];

 
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
