<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOrderItem extends Model
{
    use HasFactory;

    protected $table = 'delivery_order_items';

    protected $fillable = [
        'delivery_order_id',
        'po_supplier_item_id',
        'item',
        'qty',
        'unit',
    ];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public function poSupplierItem()
    {
        return $this->belongsTo(PoSupplierItem::class, 'po_supplier_item_id');
    }
}
