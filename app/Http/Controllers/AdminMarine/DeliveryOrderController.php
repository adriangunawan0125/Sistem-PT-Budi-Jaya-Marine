<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{

    /* ================= INDEX ================= */
    public function index($poMasukId)
    {
        $poMasuk = PoMasuk::findOrFail($poMasukId);

        $deliveryOrders = DeliveryOrder::where('po_masuk_id', $poMasukId)
            ->latest()
            ->get();

        return view('admin_marine.delivery_order.index',
            compact('poMasuk','deliveryOrders'));
    }


    /* ================= CREATE ================= */
    public function create($poMasukId)
    {
        $poMasuk = PoMasuk::with('items')
            ->findOrFail($poMasukId);

        return view('admin_marine.delivery_order.create',
            compact('poMasuk'));
    }


    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_masuk_id' => 'required|exists:po_masuk,id',
                'tanggal_do'  => 'required|date',
                'no_do'       => 'required',
            ]);

            $deliveryOrder = DeliveryOrder::create([
                'po_masuk_id' => $request->po_masuk_id,
                'tanggal_do'  => $request->tanggal_do,
                'no_do'       => $request->no_do,
                'status'      => 'draft',
            ]);

            // Save Items
            if ($request->items) {

                foreach ($request->items as $item) {

                    if (empty($item['item'])) continue;

                    $deliveryOrder->items()->create([
                        'item' => $item['item'],
                        'qty'  => (float) $item['qty'],
                        'unit' => $item['unit'],
                    ]);
                }
            }

            // Update status otomatis jika sudah terkirim semua
            $this->updatePoStatus($deliveryOrder->poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $deliveryOrder->po_masuk_id)
                ->with('success','Delivery Order berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    /* ================= SHOW ================= */
    public function show(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items','poMasuk');

        return view('admin_marine.delivery_order.show',
            compact('deliveryOrder'));
    }


    /* ================= EDIT ================= */
    public function edit(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items');

        return view('admin_marine.delivery_order.edit',
            compact('deliveryOrder'));
    }


    /* ================= UPDATE ================= */
    public function update(Request $request, DeliveryOrder $deliveryOrder)
    {
        DB::beginTransaction();

        try {

            $deliveryOrder->update([
                'tanggal_do' => $request->tanggal_do,
                'no_do'      => $request->no_do,
                'status'     => $request->status ?? 'draft',
            ]);

            // Hapus item lama
            $deliveryOrder->items()->delete();

            // Save ulang item
            if ($request->items) {

                foreach ($request->items as $item) {

                    if (empty($item['item'])) continue;

                    $deliveryOrder->items()->create([
                        'item' => $item['item'],
                        'qty'  => (float) $item['qty'],
                        'unit' => $item['unit'],
                    ]);
                }
            }

            $this->updatePoStatus($deliveryOrder->poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $deliveryOrder->po_masuk_id)
                ->with('success','Delivery Order berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    /* ================= DESTROY ================= */
    public function destroy(DeliveryOrder $deliveryOrder)
    {
        DB::beginTransaction();

        try {

            $poMasuk = $deliveryOrder->poMasuk;

            $deliveryOrder->items()->delete();
            $deliveryOrder->delete();

            $this->updatePoStatus($poMasuk);

            DB::commit();

            return back()->with('success','Delivery Order berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    /* ================= UPDATE PO STATUS ================= */
    private function updatePoStatus($poMasuk)
    {
        // total qty PO
        $totalPoQty = $poMasuk->items()->sum('qty');

        // total qty terkirim
        $totalDelivered = DeliveryOrderItem::whereHas('deliveryOrder', function($q) use ($poMasuk){
            $q->where('po_masuk_id', $poMasuk->id);
        })->sum('qty');

        if ($totalDelivered >= $totalPoQty && $totalPoQty > 0) {
            $poMasuk->update(['status' => 'closed']);
        } else {
            $poMasuk->update(['status' => 'processing']);
        }
    }

    /* ================= PRINT ================= */
public function print(DeliveryOrder $deliveryOrder)
{
    $deliveryOrder->load('items','poMasuk');

    $doDate = $deliveryOrder->tanggal_do
        ? \Carbon\Carbon::parse($deliveryOrder->tanggal_do)
        : \Carbon\Carbon::parse($deliveryOrder->created_at);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'admin_marine.delivery_order.print',
        [
            'deliveryOrder' => $deliveryOrder,
            'doDate'        => $doDate,
        ]
    )->setPaper('A4','portrait');

    return $pdf->stream('delivery-order-'.$deliveryOrder->id.'.pdf');
}

public function markDelivered(DeliveryOrder $deliveryOrder)
{
    DB::beginTransaction();

    try {

        $deliveryOrder->update([
            'status' => 'delivered'
        ]);

        $this->updatePoStatus($deliveryOrder->poMasuk);

        DB::commit();

        return back()->with('success','Delivery Order berhasil di-deliver');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error',$e->getMessage());
    }
}


}
