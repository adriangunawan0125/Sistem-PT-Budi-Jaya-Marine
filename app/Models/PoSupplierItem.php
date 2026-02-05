<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoSupplierItem extends Model
{
    use HasFactory;

    protected $table = 'po_supplier_items';

    protected $fillable = [
        'po_supplier_id',
        'item',
        'price_beli',
        'qty',
        'unit',
        'amount',
    ];

    public function poSupplier()
    {
        return $this->belongsTo(PoSupplier::class, 'po_supplier_id');
    }
    public function deliveryItems()
{
    return $this->hasMany(DeliveryOrderItem::class, 'po_supplier_item_id');
}

}
