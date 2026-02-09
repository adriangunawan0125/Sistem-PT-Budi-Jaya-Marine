<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranTransportItem extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_transport_item';
    protected $fillable = ['transport_id', 'keterangan', 'nominal', 'gambar'];

    public function transport()
    {
        return $this->belongsTo(PengeluaranTransport::class, 'transport_id');
    }
    public function pengeluaran()
    {
        return $this->belongsTo(
            PengeluaranTransport::class,
            'transport_id'   // foreign key di tabel item
        );
    }
}
