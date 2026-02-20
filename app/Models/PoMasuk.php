<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PemasukanMarine;

class PoMasuk extends Model
{
    use HasFactory;

    protected $table = 'po_masuk';

  protected $fillable = [
    'mitra_marine',
    'vessel',
    'alamat',
    'tanggal_po',
    'no_po_klien',
    'status',
    'total_jual',
    'margin',
    'margin_status',
];

    /* ================= RELATION ================= */
    public function items()
    {
        return $this->hasMany(PoMasukItem::class, 'po_masuk_id');
    }

    public function poSuppliers()
    {
        return $this->hasMany(PoSupplier::class, 'po_masuk_id');
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'po_masuk_id');
    }
    /* ================= CALCULATION ================= */
    public function totalBeli()
    {
        return $this->poSuppliers()->sum('total_beli');
    }
    public function hitungMargin()
    {
        return $this->total_jual - $this->totalBeli();
    }
    public function pengeluaran()
    {
        return $this->hasMany(PengeluaranPo::class);
    }
  public function invoicePos()
{
    return $this->hasMany(\App\Models\InvoicePo::class, 'po_masuk_id');
}
public function timesheets()
{
    return $this->hasMany(\App\Models\Timesheet::class);
}
public function workingReports()
{
    return $this->hasMany(\App\Models\WorkingReport::class);
}

public function pemasukanMarine()
{
    return $this->hasMany(PemasukanMarine::class);
}

}
