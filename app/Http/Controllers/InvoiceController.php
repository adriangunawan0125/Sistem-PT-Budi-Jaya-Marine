<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
  use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // LIST INVOICE
    public function index(Request $request)
    {
        $query = Mitra::with(['invoices.items']);

        if ($request->filled('search')) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        $mitras = $query->paginate(10)->withQueryString();

        $data = $mitras->getCollection()->map(function ($mitra) {
            $totalAmount = $mitra->invoices->sum(function ($invoice) {
                return $invoice->items->sum('amount');
            });

            return (object)[
                'mitra' => $mitra,
                'total_amount' => $totalAmount
            ];
        });

        $mitras->setCollection($data);

        return view('admin_transport.invoice.index', ['data' => $mitras]);
    }

    // FORM TAMBAH INVOICE
    public function create(Request $request)
    {
        if ($request->filled('mitra_id')) {
            $mitra = Mitra::findOrFail($request->mitra_id);
            return view('admin_transport.invoice.create', compact('mitra'));
        }

        $mitras = Mitra::all();
        return view('admin_transport.invoice.create', compact('mitras'));
    }

    // SIMPAN INVOICE BARU
    public function store(Request $request)
    {
        $request->validate([
            'mitra_id' => 'required',
            'items.*.tanggal' => 'nullable',
            'items.*.item' => 'required',
            'items.*.cicilan' => 'nullable|numeric',
            'items.*.tagihan' => 'nullable|numeric',
            'items.*.gambar_trip' => 'nullable|image|max:2048',
            'items.*.gambar_transfer' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'mitra_id' => $request->mitra_id,
               
                'status' => 'belum_lunas',
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $cicilan = $item['cicilan'] ?? 0;
                $tagihan = $item['tagihan'] ?? 0;
                $amount  = $tagihan - $cicilan;

                $gambarTrip = isset($item['gambar_trip'])
                    ? $item['gambar_trip']->store('invoice/items','public')
                    : null;

                $gambarTransfer = isset($item['gambar_transfer'])
                    ? $item['gambar_transfer']->store('invoice/items','public')
                    : null;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item' => $item['item'],
                    'cicilan' => $cicilan,
                    'tagihan' => $tagihan,
                    'amount' => $amount,
                    'gambar_trip' => $gambarTrip,
                    'gambar_transfer' => $gambarTransfer
                ]);

                $total += $amount;
            }

            $invoice->update(['total' => $total]);

            DB::commit();
            return redirect()->route('invoice.index')
                ->with('success','Invoice berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    // DETAIL INVOICE (PER MITRA)
    public function show($mitra_id)
    {
        $mitra = Mitra::with(['unit', 'invoices.items'])
            ->findOrFail($mitra_id);

        $invoices = $mitra->invoices;

        return view('admin_transport.invoice.show', compact('mitra', 'invoices'));
    }

    // HAPUS INVOICE
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        foreach ($invoice->items as $item) {
            if ($item->gambar_trip) {
                Storage::disk('public')->delete($item->gambar_trip);
            }
            if ($item->gambar_transfer) {
                Storage::disk('public')->delete($item->gambar_transfer);
            }
            $item->delete();
        }

        $invoice->delete();

        return back()->with('success','Invoice dihapus');
    }

    // TANDAI LUNAS
    public function markLunas($id)
    {
        Invoice::where('id', $id)
            ->update(['status' => 'lunas']);

        return back()->with('success','Invoice ditandai lunas');
    }

public function print($id)
{
    // ambil invoice yang diklik
    $invoice = Invoice::with([
        'mitra',
        'mitra.unit'
    ])->findOrFail($id);

    // ambil SEMUA invoice milik mitra ini + items
    $invoices = Invoice::with('items')
        ->where('mitra_id', $invoice->mitra_id)
        ->get();

    // gabungkan semua items jadi 1 collection
    $items = $invoices->flatMap->items;

    // HITUNG GRAND TOTAL DI SINI (setelah $items tersedia)
    $grandTotal = $items->sum('amount');

    // invoice number (contoh)
    $invoiceNumber = str_pad($items->count(), 3, '0', STR_PAD_LEFT)
        . '/BJM/'
        . now()->format('m/Y');

    $pdf = Pdf::loadView('invoice.print', [
        'invoice' => $invoice,
        'items' => $items,
        'invoiceNumber' => $invoiceNumber,
        'grandTotal' => $grandTotal
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('invoice-'.$invoice->id.'.pdf');
}


}
