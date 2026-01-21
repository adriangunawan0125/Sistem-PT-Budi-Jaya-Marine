<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranInternal extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_internal';
    protected $fillable = ['tanggal', 'deskripsi', 'nominal', 'gambar'];
}
