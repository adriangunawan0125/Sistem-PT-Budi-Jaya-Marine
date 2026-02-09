<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * LIST MITRA + TOTAL TAGIHAN
     */
    public function index(Request $request)
{
    $query = Mitra::with(['invoice.items']);

    if ($request->filled('search')) {
        $query->where('nama_mitra', 'like', '%' . $request->search . '%');
    }

    $mitras = $query->paginate(10)->withQueryString();

    $data = $mitras->getCollection()->map(function ($mitra) {

        $total = $mitra->invoice?->items?->sum('amount') ?? 0;

        return (object)[
            'mitra'        => $mitra,
            'total_amount' => $total,
            'total_rp'     => 'Rp ' . number_format($total, 0, ',', '.'),
        ];
    });

    $mitras->setCollection($data);

    return view('admin_transport.invoice.index', [
        'data' => $mitras
    ]);
}


    /**
     * FORM TAMBAH ITEM
     */
    public function create(Request $request)
    {
        if ($request->filled('mitra_id')) {
            $mitra = Mitra::findOrFail($request->mitra_id);
            return view('admin_transport.invoice.create', compact('mitra'));
        }

        $mitras = Mitra::all();
        return view('admin_transport.invoice.create', compact('mitras'));
    }

    /**
     * SIMPAN ITEM (AUTO CREATE INVOICE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'mitra_id' => 'required|exists:mitras,id',
            'items.*.item' => 'required|string|max:255',
            'items.*.no_invoices' => 'nullable|string|max:100',
            'items.*.tanggal_invoices' => 'nullable|date',
            'items.*.tanggal_tf' => 'nullable|date',
            'items.*.cicilan' => 'nullable|numeric',
            'items.*.tagihan' => 'nullable|numeric',
            'items.*.gambar_trip' => 'nullable|image|max:2048',
            'items.*.gambar_transfer' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ AMBIL / BUAT INVOICE SEKALI
            $invoice = Invoice::firstOrCreate(
                ['mitra_id' => $request->mitra_id],
                ['status' => 'belum_lunas', 'total' => 0]
            );

            foreach ($request->items as $row) {

                $cicilan = $row['cicilan'] ?? 0;
                $tagihan = $row['tagihan'] ?? 0;
                $amount  = $tagihan - $cicilan;

                $gambarTrip = !empty($row['gambar_trip'])
                    ? $row['gambar_trip']->store('invoice/items', 'public')
                    : null;

                $gambarTransfer = !empty($row['gambar_transfer'])
                    ? $row['gambar_transfer']->store('invoice/items', 'public')
                    : null;

                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'no_invoices'      => $row['no_invoices'] ?? null,
                    'tanggal_invoices' => $row['tanggal_invoices'] ?? null,
                    'tanggal_tf'       => $row['tanggal_tf'] ?? null,
                    'item'             => $row['item'],
                    'cicilan'          => $cicilan,
                    'tagihan'          => $tagihan,
                    'amount'           => $amount,
                    'gambar_trip'      => $gambarTrip,
                    'gambar_transfer'  => $gambarTransfer,
                ]);
            }

            // 2️⃣ UPDATE TOTAL INVOICE
            $total = $invoice->items()->sum('amount');

            $invoice->update([
                'total'  => $total,
                'status' => $total <= 0 ? 'lunas' : 'belum_lunas'
            ]);
        });

        return redirect()
            ->route('invoice.show', $request->mitra_id)
            ->with('success', 'Item invoice berhasil ditambahkan');
    }

    /**
     * DETAIL INVOICE (PER MITRA)
     */
    public function show($mitra_id)
    {
        $mitra = Mitra::with(['unit', 'invoice.items'])
            ->findOrFail($mitra_id);

        $invoice = $mitra->invoice;

        if ($invoice) {
            $invoice->total_rp = 'Rp ' . number_format($invoice->total, 0, ',', '.');

            foreach ($invoice->items as $item) {
                $item->tagihan_rp = 'Rp ' . number_format($item->tagihan, 0, ',', '.');
                $item->cicilan_rp = 'Rp ' . number_format($item->cicilan, 0, ',', '.');
                $item->amount_rp  = 'Rp ' . number_format($item->amount, 0, ',', '.');
            }
        }

        return view('admin_transport.invoice.show', compact('mitra', 'invoice'));
    }

     //EDIT SEMUA ITEM
    public function edit(Invoice $invoice)
    {
        $invoice->load(['mitra.unit', 'items']);
        return view('admin_transport.invoice.edit', compact('invoice'));
    }

    // UPDATE ITEM
    public function update(Request $request, Invoice $invoice)
{
    DB::transaction(function () use ($request, $invoice) {

        // ID ITEM DI DATABASE
        $existingIds = $invoice->items()->pluck('id')->toArray();

        // ID ITEM DARI FORM
        $formIds = collect($request->items)
            ->pluck('id')
            ->filter()
            ->toArray();

        // =========================
        // HAPUS ITEM YANG DIHAPUS DI FORM
        // =========================
        $deleted = array_diff($existingIds, $formIds);

        foreach ($deleted as $id) {
            $item = InvoiceItem::find($id);
            if ($item) {

                // FIX: JANGAN DELETE NULL
                $files = array_filter([
                    $item->gambar_trip,
                    $item->gambar_transfer
                ]);

                if (!empty($files)) {
                    Storage::disk('public')->delete($files);
                }

                $item->delete();
            }
        }

        // =========================
        // UPDATE & TAMBAH ITEM
        // =========================
        foreach ($request->items as $row) {

            $cicilan = $row['cicilan'] ?? 0;
            $tagihan = $row['tagihan'] ?? 0;
            $amount  = $tagihan - $cicilan;

            // ================= UPDATE ITEM =================
            if (!empty($row['id'])) {

                $item = InvoiceItem::findOrFail($row['id']);

                if (!empty($row['gambar_trip'])) {
                    if ($item->gambar_trip) {
                        Storage::disk('public')->delete($item->gambar_trip);
                    }
                    $item->gambar_trip = $row['gambar_trip']
                        ->store('invoice/items', 'public');
                }

                if (!empty($row['gambar_transfer'])) {
                    if ($item->gambar_transfer) {
                        Storage::disk('public')->delete($item->gambar_transfer);
                    }
                    $item->gambar_transfer = $row['gambar_transfer']
                        ->store('invoice/items', 'public');
                }

                $item->update([
                    'no_invoices'      => $row['no_invoices'] ?? null,
                    'tanggal_invoices' => $row['tanggal_invoices'] ?? null,
                    'tanggal_tf'       => $row['tanggal_tf'] ?? null,
                    'item'             => $row['item'],
                    'cicilan'          => $cicilan,
                    'tagihan'          => $tagihan,
                    'amount'           => $amount,
                ]);

            }
            // ================= TAMBAH ITEM =================
            else {

                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'no_invoices'      => $row['no_invoices'] ?? null,
                    'tanggal_invoices' => $row['tanggal_invoices'] ?? null,
                    'tanggal_tf'       => $row['tanggal_tf'] ?? null,
                    'item'             => $row['item'],
                    'cicilan'          => $cicilan,
                    'tagihan'          => $tagihan,
                    'amount'           => $amount,
                    'gambar_trip'      => !empty($row['gambar_trip'])
                        ? $row['gambar_trip']->store('invoice/items', 'public')
                        : null,
                    'gambar_transfer'  => !empty($row['gambar_transfer'])
                        ? $row['gambar_transfer']->store('invoice/items', 'public')
                        : null,
                ]);
            }
        }

        // =========================
        // UPDATE TOTAL & STATUS INVOICE
        // =========================
        $total = $invoice->items()->sum('amount');

        $invoice->update([
            'total'  => $total,
            'status' => $total <= 0 ? 'lunas' : 'belum_lunas'
        ]);
    });

    return redirect()
        ->route('invoice.show', $invoice->mitra_id)
        ->with('success', 'Invoice berhasil diperbarui');
}


    /**
     * HAPUS INVOICE + ITEM
     */
    public function destroy(Invoice $invoice)
    {
        foreach ($invoice->items as $item) {
            Storage::disk('public')->delete([$item->gambar_trip, $item->gambar_transfer]);
            $item->delete();
        }

        $invoice->delete();

        return back()->with('success', 'Invoice dihapus');
    }

    public function showItem($id)
{
    $item = InvoiceItem::with([
        'invoice.mitra',
    ])->findOrFail($id);

    return view('admin_transport.invoice.items', compact('item'));
}

     //PRINT PDF   
  public function print(Invoice $invoice)
{
    $invoice->load(['mitra.unit', 'items']);

    // ITEM TERAKHIR BERDASARKAN TANGGAL INVOICE
    $lastItem = $invoice->items
        ->whereNotNull('tanggal_invoices')
        ->sortByDesc('tanggal_invoices')
        ->first();

    $invoiceNumber = $lastItem?->no_invoices ?? '-';

    // DATE INVOICE AMBIL DARI TANGGAL_INVOICES TERAKHIR
    $invoiceDate = $lastItem && $lastItem->tanggal_invoices
        ? \Carbon\Carbon::parse($lastItem->tanggal_invoices)
        : \Carbon\Carbon::parse($invoice->created_at);

    $grandTotal = $invoice->items->sum('amount');

    $pdf = Pdf::loadView('admin_transport.invoice.print', [
        'invoice'       => $invoice,
        'items'         => $invoice->items,
        'lastItem'      => $lastItem,
        'invoiceNumber' => $invoiceNumber,
        'invoiceDate'   => $invoiceDate, // ⬅️ INI PENTING
        'grandTotal'    => $grandTotal,
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('invoice-' . $invoice->id . '.pdf');
}

}
