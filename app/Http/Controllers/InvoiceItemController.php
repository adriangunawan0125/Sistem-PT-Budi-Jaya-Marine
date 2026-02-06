<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceItemController extends Controller
{
    // =====================================================
    // TAMBAH ITEM
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',

            'no_invoices' => 'nullable|string',
            'tanggal_invoices' => 'nullable|date',
            'tanggal_tf' => 'nullable|date',

            'item' => 'required|string',
            'tagihan' => 'required|numeric',
            'cicilan' => 'nullable|numeric',

            'gambar_trip' => 'nullable|image|max:2048',
            'gambar_transfer' => 'nullable|image|max:2048',
        ]);

        // upload gambar
        $gambarTrip = $request->file('gambar_trip')
            ? $request->file('gambar_trip')->store('invoice/items', 'public')
            : null;

        $gambarTransfer = $request->file('gambar_transfer')
            ? $request->file('gambar_transfer')->store('invoice/items', 'public')
            : null;

        // hitung amount
        $cicilan = $request->cicilan ?? 0;
        $tagihan = $request->tagihan;
        $amount = $tagihan - $cicilan;

        // simpan
        InvoiceItem::create([
            'invoice_id' => $request->invoice_id,
            'no_invoices' => $request->no_invoices,
            'tanggal_invoices' => $request->tanggal_invoices,
            'tanggal_tf' => $request->tanggal_tf,
            'item' => $request->item,
            'tagihan' => $tagihan,
            'cicilan' => $cicilan,
            'amount' => $amount,
            'gambar_trip' => $gambarTrip,
            'gambar_transfer' => $gambarTransfer,
        ]);

        $this->recalculateInvoice($request->invoice_id);

        return back()->with('success', 'Item ditambahkan');
    }

    // =====================================================
    // FORM EDIT
    // =====================================================
    public function edit($id)
    {
        $item = InvoiceItem::with('invoice')->findOrFail($id);
        return view('admin_transport.invoice.edit', compact('item'));
    }

    // =====================================================
    // UPDATE ITEM
    // =====================================================
    public function update(Request $request, $id)
    {
        $item = InvoiceItem::findOrFail($id);

        $request->validate([
            'item' => 'required|string',

            'no_invoices' => 'nullable|string',
            'tanggal_invoices' => 'nullable|date',
            'tanggal_tf' => 'nullable|date',

            'tagihan' => 'required|numeric',
            'cicilan' => 'nullable|numeric',

            'gambar_trip' => 'nullable|image|max:2048',
            'gambar_transfer' => 'nullable|image|max:2048',
        ]);

        $gambarTrip = $item->gambar_trip;
        $gambarTransfer = $item->gambar_transfer;

        // replace gambar trip
        if ($request->hasFile('gambar_trip')) {
            if ($item->gambar_trip) {
                Storage::disk('public')->delete($item->gambar_trip);
            }
            $gambarTrip = $request->file('gambar_trip')->store('invoice/items', 'public');
        }

        // replace gambar transfer
        if ($request->hasFile('gambar_transfer')) {
            if ($item->gambar_transfer) {
                Storage::disk('public')->delete($item->gambar_transfer);
            }
            $gambarTransfer = $request->file('gambar_transfer')->store('invoice/items', 'public');
        }

        $cicilan = $request->cicilan ?? 0;
        $tagihan = $request->tagihan;
        $amount = $tagihan - $cicilan;

        $item->update([
            'item' => $request->item,
            'no_invoices' => $request->no_invoices,
            'tanggal_invoices' => $request->tanggal_invoices,
            'tanggal_tf' => $request->tanggal_tf,
            'tagihan' => $tagihan,
            'cicilan' => $cicilan,
            'amount' => $amount,
            'gambar_trip' => $gambarTrip,
            'gambar_transfer' => $gambarTransfer,
        ]);

        $this->recalculateInvoice($item->invoice_id);

        return redirect()
            ->route('invoice.show', $item->invoice->mitra_id)
            ->with('success', 'Item diperbarui');
    }

    // =====================================================
    // HAPUS ITEM
    // =====================================================
    public function destroy($id)
    {
        $item = InvoiceItem::findOrFail($id);
        $invoiceId = $item->invoice_id;

        if ($item->gambar_trip) {
            Storage::disk('public')->delete($item->gambar_trip);
        }

        if ($item->gambar_transfer) {
            Storage::disk('public')->delete($item->gambar_transfer);
        }

        $item->delete();

        $this->recalculateInvoice($invoiceId);

        return back()->with('success', 'Item dihapus');
    }

    // =====================================================
    // RECALCULATE TOTAL
    // =====================================================
    private function recalculateInvoice($invoiceId)
    {
        $total = InvoiceItem::where('invoice_id', $invoiceId)->sum('amount');

        Invoice::where('id', $invoiceId)->update([
            'total' => $total,
            'status' => $total <= 0 ? 'lunas' : 'belum_lunas'
        ]);
    }
}
