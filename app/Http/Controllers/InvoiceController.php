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
                'total_amount' => $totalAmount,
                'total_amount_rp' => 'Rp ' . number_format($totalAmount, 0, ',', '.')
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

    public function items($id)
    {
        $invoice = Invoice::with(['mitra','items'])->findOrFail($id);
        return view('admin_transport.invoice.items', compact('invoice'));
    }

    // SIMPAN INVOICE BARU
    public function store(Request $request)
    {
        $request->validate([
            'mitra_id' => 'nullable|exists:mitras,id',
            'ex_mitra_id' => 'nullable|exists:ex_mitras,id',
            'items.*.item' => 'required|string|max:255',
            'items.*.no_invoices' => 'nullable|string|max:100',
            'items.*.tanggal_invoices' => 'nullable|date',
            'items.*.tanggal_tf' => 'nullable|date',
            'items.*.cicilan' => 'nullable|numeric',
            'items.*.tagihan' => 'nullable|numeric',
            'items.*.gambar_trip' => 'nullable|image|max:2048',
            'items.*.gambar_transfer' => 'nullable|image|max:2048',
        ]);

        $invoice = Invoice::create([
            'mitra_id' => $request->mitra_id,
            'ex_mitra_id' => $request->ex_mitra_id,
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
                'invoice_id'       => $invoice->id,
                'no_invoices'      => $item['no_invoices'] ?? null,
                'tanggal_invoices' => $item['tanggal_invoices'] ?? null,
                'tanggal_tf'       => $item['tanggal_tf'] ?? null,
                'item'             => $item['item'],
                'cicilan'          => $cicilan,
                'tagihan'          => $tagihan,
                'amount'           => $amount,
                'gambar_trip'      => $gambarTrip,
                'gambar_transfer'  => $gambarTransfer
            ]);

            $total += $amount;
        }

        $invoice->update(['total' => $total]);

        return redirect()
            ->route('invoice.show', $invoice->mitra_id ?? $invoice->ex_mitra_id)
            ->with('success', 'Invoice berhasil dibuat');
    }

    // DETAIL INVOICE (PER MITRA)
    public function show($mitra_id)
    {
        $mitra = Mitra::with(['unit', 'invoices.items'])
            ->findOrFail($mitra_id);

        $invoices = $mitra->invoices->map(function($inv){
            $inv->total_rp = 'Rp ' . number_format($inv->total, 0, ',', '.');

            foreach ($inv->items as $item) {
                $item->tagihan_rp = 'Rp ' . number_format($item->tagihan, 0, ',', '.');
                $item->cicilan_rp = 'Rp ' . number_format($item->cicilan, 0, ',', '.');
                $item->amount_rp  = 'Rp ' . number_format($item->amount, 0, ',', '.');
            }

            return $inv;
        });

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

    // =========================
    // ✅ EDIT PER MITRA (FIX)
    // =========================
    public function edit(Invoice $invoice)
    {
        $mitra = Mitra::with([
            'unit',
            'invoices.items'
        ])->findOrFail($invoice->mitra_id);

        // SEMUA ITEM DARI SEMUA INVOICE MILIK MITRA
        $items = $mitra->invoices->flatMap->items;

        return view('admin_transport.invoice.edit', compact(
            'mitra',
            'items'
        ));
    }

    // =========================
    // UPDATE SEMUA ITEM MITRA
    // =========================
  public function update(Request $request, Invoice $invoice)
{
    DB::transaction(function () use ($request, $invoice) {

        $mitra = Mitra::with('invoices.items')
            ->findOrFail($invoice->mitra_id);

        // =========================
        // 1️⃣ AMBIL SEMUA ITEM ID DI DB (MITRA)
        // =========================
        $dbItemIds = $mitra->invoices
            ->flatMap->items
            ->pluck('id')
            ->toArray();

        // =========================
        // 2️⃣ AMBIL ITEM ID DARI FORM
        // =========================
        $formItemIds = collect($request->items)
            ->pluck('id')
            ->filter()
            ->map(fn($id) => (int)$id)
            ->toArray();

        // =========================
        // 3️⃣ ITEM YANG DIHAPUS (ADA DI DB, TAPI GA ADA DI FORM)
        // =========================
        $deletedIds = array_diff($dbItemIds, $formItemIds);

        foreach ($deletedIds as $id) {
            $item = InvoiceItem::find($id);
            if ($item) {
                if ($item->gambar_trip) {
                    Storage::disk('public')->delete($item->gambar_trip);
                }
                if ($item->gambar_transfer) {
                    Storage::disk('public')->delete($item->gambar_transfer);
                }
                $item->delete();
            }
        }

        // =========================
        // 4️⃣ UPDATE / CREATE ITEM
        // =========================
        foreach ($request->items as $row) {

            $cicilan = $row['cicilan'] ?? 0;
            $tagihan = $row['tagihan'] ?? 0;
            $amount  = $tagihan - $cicilan;

            // UPDATE
            if (!empty($row['id'])) {

                $item = InvoiceItem::findOrFail($row['id']);

                if (!empty($row['gambar_trip'])) {
                    if ($item->gambar_trip) {
                        Storage::disk('public')->delete($item->gambar_trip);
                    }
                    $item->gambar_trip = $row['gambar_trip']
                        ->store('invoice/items','public');
                }

                if (!empty($row['gambar_transfer'])) {
                    if ($item->gambar_transfer) {
                        Storage::disk('public')->delete($item->gambar_transfer);
                    }
                    $item->gambar_transfer = $row['gambar_transfer']
                        ->store('invoice/items','public');
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
            // CREATE
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
                        ? $row['gambar_trip']->store('invoice/items','public')
                        : null,
                    'gambar_transfer'  => !empty($row['gambar_transfer'])
                        ? $row['gambar_transfer']->store('invoice/items','public')
                        : null,
                ]);
            }
        }

        // =========================
        // 5️⃣ UPDATE TOTAL INVOICE
        // =========================
        foreach ($mitra->invoices as $inv) {
            $total = $inv->items()->sum('amount');
            $inv->update([
                'total'  => $total,
                'status' => $total <= 0 ? 'lunas' : 'belum_lunas'
            ]);
        }
    });

    return redirect()
        ->route('invoice.show', $invoice->mitra_id)
        ->with('success','Invoice berhasil diperbarui');
}


    // PRINT PDF
    public function print($id)
    {
        $invoice = Invoice::with(['mitra','mitra.unit'])->findOrFail($id);

        $invoices = Invoice::with('items')
            ->where('mitra_id', $invoice->mitra_id)
            ->get();

        $items = $invoices->flatMap->items;

        $lastItem = $items->sortByDesc('created_at')->first();
        $invoiceNumber = $lastItem->no_invoices ?? '-';

        $grandTotal = (float) $items->sum('amount');

        $pdf = Pdf::loadView('admin_transport.invoice.print', [
            'invoice'       => $invoice,
            'items'         => $items,
            'invoiceNumber' => $invoiceNumber,
            'grandTotal'    => $grandTotal,
            'lastItem'      => $lastItem
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('invoice-'.$invoice->id.'.pdf');
    }
}
