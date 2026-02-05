<?php

namespace App\Http\Controllers;

use App\Models\PoMasuk;
use App\Models\PoMasukItem;
use App\Models\PoSupplier;
use App\Models\PoSupplierItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoSupplierController extends Controller
{
    public function index()
    {
        $poSuppliers = PoSupplier::with('poMasuk')->latest()->get();
        return view('admin_marine.po_supplier.index', compact('poSuppliers'));
    }

    public function create($poMasukId)
    {
        $poMasuk = PoMasuk::with('items')->findOrFail($poMasukId);
        return view('admin_marine.po_supplier.create', compact('poMasuk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_masuk_id'     => 'required|exists:po_masuk,id',
            'nama_perusahaan' => 'required|string',
            'tanggal_po'      => 'required|date',
            'no_po_internal'  => 'required|string',
            'items'           => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $poSupplier = PoSupplier::create([
                'po_masuk_id'     => $request->po_masuk_id,
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat'          => $request->alamat,
                'tanggal_po'      => $request->tanggal_po,
                'no_po_internal'  => $request->no_po_internal,
                'status'          => 'approved',
            ]);

            $totalBeli = 0;

            foreach ($request->items as $item) {

                // checkbox tidak dicentang
                if (!isset($item['po_masuk_item_id'])) {
                    continue;
                }

                $poMasukItem = PoMasukItem::findOrFail($item['po_masuk_item_id']);

                if (empty($item['qty']) || empty($item['price_beli'])) {
                    continue;
                }

                // total qty yg sudah dibeli sebelumnya
                $qtyTerbeli = PoSupplierItem::where('item', $poMasukItem->item)
                    ->whereHas('poSupplier', function ($q) use ($request) {
                        $q->where('po_masuk_id', $request->po_masuk_id);
                    })
                    ->sum('qty');

                $sisa = $poMasukItem->qty - $qtyTerbeli;

                if ($item['qty'] > $sisa) {
                    throw new \Exception(
                        "Qty {$poMasukItem->item} melebihi sisa ({$sisa})"
                    );
                }

                $amount = $item['qty'] * $item['price_beli'];
                $totalBeli += $amount;

                PoSupplierItem::create([
                    'po_supplier_id' => $poSupplier->id,
                    'item'           => $poMasukItem->item,
                    'qty'            => $item['qty'],
                    'unit'           => $poMasukItem->unit,
                    'price_beli'     => $item['price_beli'],
                    'amount'         => $amount,
                ]);
            }

            $poSupplier->update([
                'total_beli' => $totalBeli,
            ]);

            PoMasuk::where('id', $request->po_masuk_id)
                ->update(['status' => 'processing']);
        });

        return redirect()
            ->route('po-supplier.index')
            ->with('success', 'PO Supplier berhasil dibuat');
    }

    public function show($id)
    {
        $poSupplier = PoSupplier::with(['items','poMasuk'])->findOrFail($id);
        return view('admin_marine.po_supplier.show', compact('poSupplier'));
    }
}
