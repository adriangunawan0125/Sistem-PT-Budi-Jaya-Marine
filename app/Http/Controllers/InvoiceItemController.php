<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceItemController extends Controller
{
    /**
     * TAMBAH ITEM
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'item' => 'required|string',
            'tagihan' => 'required|numeric',
            'cicilan' => 'nullable|numeric',
            'gambar_trip' => 'nullable|image|max:2048',
            'gambar_transfer' => 'nullable|image|max:2048',
        ]);

        $gambarTrip = $request->file('gambar_trip')
            ? $request->file('gambar_trip')->store('invoice/items', 'public')
            : null;

        $gambarTransfer = $request->file('gambar_transfer')
            ? $request->file('gambar_transfer')->store('invoice/items', 'public')
            : null;

        $amount = $request->tagihan - ($request->cicilan ?? 0);

        InvoiceItem::create([
            'invoice_id' => $request->invoice_id,
            'item' => $request->item,
            'tagihan' => $request->tagihan,
            'cicilan' => $request->cicilan ?? 0,
            'amount' => $amount,
            'gambar_trip' => $gambarTrip,
            'gambar_transfer' => $gambarTransfer,
        ]);

        // 🔥 UPDATE TOTAL INVOICE
        $this->recalculateInvoice($request->invoice_id);

        return back()->with('success', 'Item ditambahkan');
    }

    /**
     * FORM EDIT ITEM
     */
    public function edit($id)
    {
        $item = InvoiceItem::with('invoice')->findOrFail($id);
        return view('admin_transport.invoice.edit', compact('item'));
    }

    /**
     * UPDATE ITEM
     */
    public function update(Request $request, $id)
    {
        $item = InvoiceItem::findOrFail($id);

        $request->validate([
            'item' => 'required|string',
            'tanggal' => 'nullable|date',
            'tagihan' => 'required|numeric',
            'cicilan' => 'nullable|numeric',
            'gambar_trip' => 'nullable|image|max:2048',
            'gambar_transfer' => 'nullable|image|max:2048',
        ]);

        $gambarTrip = $item->gambar_trip;
        $gambarTransfer = $item->gambar_transfer;

        if ($request->hasFile('gambar_trip')) {
            if ($item->gambar_trip) {
                Storage::disk('public')->delete($item->gambar_trip);
            }
            $gambarTrip = $request->file('gambar_trip')->store('invoice/items', 'public');
        }

        if ($request->hasFile('gambar_transfer')) {
            if ($item->gambar_transfer) {
                Storage::disk('public')->delete($item->gambar_transfer);
            }
            $gambarTransfer = $request->file('gambar_transfer')->store('invoice/items', 'public');
        }

        $amount = $request->tagihan - ($request->cicilan ?? 0);

        $item->update([
            'item' => $request->item,
            'tanggal' => $request->tanggal,
            'tagihan' => $request->tagihan,
            'cicilan' => $request->cicilan ?? 0,
            'amount' => $amount,
            'gambar_trip' => $gambarTrip,
            'gambar_transfer' => $gambarTransfer,
        ]);

        // 🔥 UPDATE TOTAL
        $this->recalculateInvoice($item->invoice_id);

        return redirect()
            ->route('invoice.show', $item->invoice->mitra_id)
            ->with('success', 'Item diperbarui');
    }

    /**
     * HAPUS ITEM
     */
    public function destroy($id)
    {
        $item = InvoiceItem::findOrFail($id);
        $invoiceId = $item->invoice_id;

        if ($item->gambar_trip) Storage::disk('public')->delete($item->gambar_trip);
        if ($item->gambar_transfer) Storage::disk('public')->delete($item->gambar_transfer);

        $item->delete();

        // 🔥 UPDATE TOTAL
        $this->recalculateInvoice($invoiceId);

        return back()->with('success', 'Item dihapus');
    }

    /**
     * HITUNG ULANG TOTAL INVOICE
     */
   private function recalculateInvoice($invoiceId)
{
    $total = InvoiceItem::where('invoice_id', $invoiceId)->sum('amount');

    Invoice::where('id', $invoiceId)->update([
        'total' => $total,
        'status' => $total <= 0 ? 'lunas' : 'belum_lunas'
    ]);
}

}
