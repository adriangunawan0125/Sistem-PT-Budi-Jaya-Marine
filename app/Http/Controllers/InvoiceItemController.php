<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    // ===============================
    // TAMBAH BARIS INVOICE (HARIAN)
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required',
            'tanggal'    => 'required|date',
            'item'       => 'required',
            'tagihan'    => 'required|numeric',
        ]);

        InvoiceItem::create([
            'invoice_id' => $request->invoice_id,
            'tanggal'    => $request->tanggal,
            'item'       => $request->item,
            'keterangan' => $request->keterangan,
            'tagihan'    => $request->tagihan,
            'cicilan'    => $request->cicilan ?? 0,
            'status'     => ($request->cicilan >= $request->tagihan)
                ? 'lunas'
                : 'belum_bayar',
        ]);

        return back()->with('success', 'Invoice berhasil ditambahkan');
    }
     public function destroy($id)
    {
        InvoiceItem::findOrFail($id)->delete();
        return back()->with('success', 'Item dihapus');
    }
}
