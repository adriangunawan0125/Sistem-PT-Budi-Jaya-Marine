<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\PoMasuk;
use App\Models\PoSupplierItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index(PoMasuk $poMasuk)
    {
        $deliveryOrders = $poMasuk
            ->deliveryOrders()
            ->latest()
            ->get();

        return view(
            'admin_marine.delivery_order.index',
            compact('poMasuk', 'deliveryOrders')
        );
    }

    public function create(PoMasuk $poMasuk)
    {
        $poSuppliers = $poMasuk
            ->poSuppliers()
            ->with('items.deliveryItems')
            ->get();

        return view(
            'admin_marine.delivery_order.create',
            compact('poMasuk', 'poSuppliers')
        );
    }

    public function show($id)
{
    $deliveryOrder = DeliveryOrder::with([
        'items',
        'poMasuk',
        'items.poSupplierItem'
    ])->findOrFail($id);

    return view(
        'admin_marine.delivery_order.show',
        compact('deliveryOrder')
    );
}

public function deliver($id)
{
    DB::transaction(function () use ($id) {

        $deliveryOrder = DeliveryOrder::with('poMasuk')->findOrFail($id);

        // 1. update status DO
        $deliveryOrder->update([
            'status' => 'delivered'
        ]);

        $po = $deliveryOrder->poMasuk;

        /**
         * 2. cek apakah SEMUA item PO Supplier sudah terkirim
         */
        $semuaTerkirim = true;

        foreach ($po->poSuppliers as $supplier) {
            foreach ($supplier->items as $item) {

                $qtyTerkirim = $item->deliveryItems()->sum('qty');

                if ($qtyTerkirim < $item->qty) {
                    $semuaTerkirim = false;
                    break 2;
                }
            }
        }

        /**
         * 3. kalau semua sudah terkirim → CLOSE PO + hitung margin
         */
        if ($semuaTerkirim) {

            $totalBeli = $po->poSuppliers()->sum('total_beli');
            $margin = $po->total_jual - $totalBeli;

            $po->update([
                'status'        => 'closed',
                'margin'        => $margin,
                'margin_status' => $margin > 0
                    ? 'profit'
                    : ($margin < 0 ? 'loss' : 'break_even'),
            ]);
        }
    });

    return back()->with('success', 'Delivery Order berhasil delivered');
}


    public function store(Request $request)
    {
        $request->validate([
            'po_masuk_id' => 'required|exists:po_masuk,id',
            'tanggal_do'  => 'required|date',
            'no_do'       => 'required|string|max:100',
            'items'       => 'required|array|min:1',
            'items.*.po_supplier_item_id' => 'required|exists:po_supplier_items,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $do = DeliveryOrder::create([
                    'po_masuk_id' => $request->po_masuk_id,
                    'tanggal_do'  => $request->tanggal_do,
                    'no_do'       => $request->no_do,
                    'status'      => 'draft',
                ]);

                foreach ($request->items as $item) {

                    $supplierItem = PoSupplierItem::with('deliveryItems')
                        ->lockForUpdate()
                        ->findOrFail($item['po_supplier_item_id']);

                    $qtyTerkirim = $supplierItem
                        ->deliveryItems
                        ->sum('qty');

                    $sisa = $supplierItem->qty - $qtyTerkirim;

                    if ($item['qty'] > $sisa) {
                        throw new \Exception(
                            "Qty kirim untuk item {$supplierItem->item} melebihi sisa ({$sisa})"
                        );
                    }

                    DeliveryOrderItem::create([
                        'delivery_order_id'   => $do->id,
                        'po_supplier_item_id' => $supplierItem->id,
                        'item'                => $supplierItem->item,
                        'qty'                 => $item['qty'],
                        'unit'                => $supplierItem->unit,
                    ]);
                }
            });

            return redirect()
                ->route('delivery-order.index', $request->po_masuk_id)
                ->with('success', 'Delivery Order berhasil dibuat');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    
}
