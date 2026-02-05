<?php

namespace App\Http\Controllers;

use App\Models\PoMasuk;
use App\Models\PoMasukItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoMasukController extends Controller
{
    public function index()
    {
        $poMasuk = PoMasuk::latest()->get();
        return view('admin_marine.po_masuk.index', compact('poMasuk'));
    }

    public function create()
    {
        return view('admin_marine.po_masuk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string',
            'alamat'          => 'required|string',
            'tanggal_po'      => 'required|date',
            'no_po_klien'     => 'required|string',
            'vessel'          => 'required|string',
            'items'           => 'required|array|min:1',
            'items.*.item'    => 'required|string',
            'items.*.qty'     => 'required|numeric|min:1',
            'items.*.unit'    => 'required|string',
            'items.*.price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $po = PoMasuk::create([
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat'          => $request->alamat,
                'tanggal_po'      => $request->tanggal_po,
                'no_po_klien'     => $request->no_po_klien,
                'vessel'          => $request->vessel,
                'status'          => 'draft',
            ]);

            $totalJual = 0;

            foreach ($request->items as $item) {
                $amount = $item['qty'] * $item['price'];
                $totalJual += $amount;

                PoMasukItem::create([
                    'po_masuk_id' => $po->id,
                    'item'        => $item['item'],
                    'qty'         => $item['qty'],
                    'unit'        => $item['unit'],
                    'price_jual'  => $item['price'],
                    'amount'      => $amount,
                ]);
            }

            $po->update([
                'total_jual' => $totalJual,
            ]);
        });

        return redirect()->route('po-masuk.index')
            ->with('success', 'PO Masuk berhasil disimpan');
    }

    public function show($id)
    {
        $po = PoMasuk::with('items')->findOrFail($id);
        return view('admin_marine.po_masuk.show', compact('po'));
    }

    public function approve($id)
    {
        PoMasuk::where('id', $id)->update(['status' => 'approved']);
        return back()->with('success', 'PO Masuk approved');
    }
}
