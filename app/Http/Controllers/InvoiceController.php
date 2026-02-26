<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pemasukan;

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

        'items.*.gambar_trip'       => 'nullable|image|max:2048',
        'items.*.gambar_trip1'      => 'nullable|image|max:2048',
        'items.*.gambar_transfer'   => 'nullable|image|max:2048',
        'items.*.gambar_transfer1'  => 'nullable|image|max:2048',
        'items.*.gambar_transfer2'  => 'nullable|image|max:2048',
    ]);

    DB::transaction(function () use ($request) {

        $invoice = Invoice::firstOrCreate(
            ['mitra_id' => $request->mitra_id],
            ['status' => 'belum_lunas', 'total' => 0]
        );

        foreach ($request->items as $index => $row) {

            $cicilan = $row['cicilan'] ?? 0;
            $tagihan = $row['tagihan'] ?? 0;
            $amount  = $tagihan - $cicilan;

            $data = [
                'invoice_id'       => $invoice->id,
                'no_invoices'      => $row['no_invoices'] ?? null,
                'tanggal_invoices' => $row['tanggal_invoices'] ?? null,
                'tanggal_tf'       => $row['tanggal_tf'] ?? null,
                'item'             => $row['item'],
                'cicilan'          => $cicilan,
                'tagihan'          => $tagihan,
                'amount'           => $amount,
            ];

            // ====== HANDLE FILE UPLOAD ======
            foreach ([
                'gambar_trip',
                'gambar_trip1',
                'gambar_transfer',
                'gambar_transfer1',
                'gambar_transfer2'
            ] as $field) {

                if ($request->hasFile("items.$index.$field")) {
                    $data[$field] = $request->file("items.$index.$field")
                        ->store(
                            str_contains($field, 'trip')
                                ? 'invoice/trip'
                                : 'invoice/transfer',
                            'public'
                        );
                }
            }

            InvoiceItem::create($data);
        }

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

        $existingIds = $invoice->items()->pluck('id')->toArray();
        $formIds = collect($request->items ?? [])
            ->pluck('id')
            ->filter()
            ->toArray();

        $deleted = array_diff($existingIds, $formIds);

        /* ================= DELETE ITEM YANG DIHAPUS ================= */
        foreach ($deleted as $id) {

            $item = InvoiceItem::find($id);

            if ($item) {

                Storage::disk('public')->delete(array_filter([
                    $item->gambar_trip,
                    $item->gambar_trip1,
                    $item->gambar_transfer,
                    $item->gambar_transfer1,
                    $item->gambar_transfer2,
                ]));

                $item->delete();
            }
        }

        /* ================= UPDATE & CREATE ================= */
        foreach ($request->items ?? [] as $index => $row) {

            $cicilan = $row['cicilan'] ?? 0;
            $tagihan = $row['tagihan'] ?? 0;
            $amount  = $tagihan - $cicilan;

            /* ===== DATA DASAR ===== */
            $data = [
                'no_invoices'      => $row['no_invoices'] ?? null,
                'tanggal_invoices' => $row['tanggal_invoices'] ?? null,
                'tanggal_tf'       => $row['tanggal_tf'] ?? null,
                'item'             => $row['item'] ?? null,
                'cicilan'          => $cicilan,
                'tagihan'          => $tagihan,
                'amount'           => $amount,
            ];

            /* ===== JIKA UPDATE ===== */
            if (!empty($row['id'])) {

                $item = InvoiceItem::findOrFail($row['id']);

                /* ===== HANDLE HAPUS FILE LAMA ===== */
                foreach ([
                    'gambar_trip',
                    'gambar_trip1',
                    'gambar_transfer',
                    'gambar_transfer1',
                    'gambar_transfer2'
                ] as $field) {

                    // Jika checkbox hapus dikirim
                    if (!empty($row["hapus_$field"])) {

                        if ($item->$field) {
                            Storage::disk('public')->delete($item->$field);
                            $item->$field = null;
                        }
                    }

                    // Jika upload file baru
                    if ($request->hasFile("items.$index.$field")) {

                        if ($item->$field) {
                            Storage::disk('public')->delete($item->$field);
                        }

                        $data[$field] = $request
                            ->file("items.$index.$field")
                            ->store(
                                str_contains($field, 'trip')
                                    ? 'invoice/trip'
                                    : 'invoice/transfer',
                                'public'
                            );
                    }
                }

                $item->update($data);
            }

            /* ===== JIKA CREATE BARU ===== */
            else {

                foreach ([
                    'gambar_trip',
                    'gambar_trip1',
                    'gambar_transfer',
                    'gambar_transfer1',
                    'gambar_transfer2'
                ] as $field) {

                    if ($request->hasFile("items.$index.$field")) {

                        $data[$field] = $request
                            ->file("items.$index.$field")
                            ->store(
                                str_contains($field, 'trip')
                                    ? 'invoice/trip'
                                    : 'invoice/transfer',
                                'public'
                            );
                    }
                }

                $data['invoice_id'] = $invoice->id;

                InvoiceItem::create($data);
            }
        }

        /* ================= UPDATE TOTAL ================= */
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
           Storage::disk('public')->delete(array_filter([
    $item->gambar_trip,
    $item->gambar_trip1,
    $item->gambar_transfer,
    $item->gambar_transfer1,
    $item->gambar_transfer2,
]));
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

    /* ================= SORT ITEM BERDASARKAN TANGGAL ================= */
    $sortedItems = $invoice->items
        ->sortByDesc(function ($item) {
            return $item->tanggal_invoices ?? $item->created_at;
        })
        ->values();

    /* ================= ITEM TERAKHIR UNTUK HEADER ================= */
    $lastItem = $sortedItems->first();

    $invoiceNumber = $lastItem?->no_invoices ?? '-';

    $invoiceDate = $lastItem && $lastItem->tanggal_invoices
        ? \Carbon\Carbon::parse($lastItem->tanggal_invoices)
        : \Carbon\Carbon::parse($invoice->created_at);

    $grandTotal = $invoice->items->sum('amount');


    /* ================================================================
       AMBIL BUKTI TRANSFER DARI TABEL PEMASUKAN (BERDASARKAN MITRA)
       ================================================================ */

    $latestTransfers = Pemasukan::where('mitra_id', $invoice->mitra_id)
        ->where(function ($q) {
            $q->whereNotNull('gambar')
              ->orWhereNotNull('gambar1');
        })
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->get()
        ->flatMap(function ($p) {
            return array_filter([
                $p->gambar ? 'pemasukan/' . $p->gambar : null,
                $p->gambar1 ? 'pemasukan/' . $p->gambar1 : null,
            ]);
        })
        ->take(2) // maksimal 2 bukti terakhir
        ->values()
        ->toArray();


    /* ================================================================
       AMBIL 1 BUKTI TRIP TERAKHIR DARI INVOICE ITEMS
       ================================================================ */

    $latestTrip = null;

    foreach ($sortedItems as $item) {

        if ($item->gambar_trip1) {
            $latestTrip = $item->gambar_trip1;
            break;
        }

        if ($item->gambar_trip) {
            $latestTrip = $item->gambar_trip;
            break;
        }
    }


    /* ================= LOAD PDF ================= */

    $pdf = Pdf::loadView('admin_transport.invoice.print', [
        'invoice'         => $invoice,
        'items'           => $invoice->items,
        'lastItem'        => $lastItem,
        'invoiceNumber'   => $invoiceNumber,
        'invoiceDate'     => $invoiceDate,
        'grandTotal'      => $grandTotal,
        'latestTransfers' => $latestTransfers,
        'latestTrip'      => $latestTrip,
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('invoice-' . $invoice->id . '.pdf');
}
}
