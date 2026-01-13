<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranTransport extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_transport';
    protected $fillable = ['unit_id','tanggal','total_amount'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function items()
    {
        return $this->hasMany(PengeluaranTransportItem::class, 'transport_id');
    }
}
