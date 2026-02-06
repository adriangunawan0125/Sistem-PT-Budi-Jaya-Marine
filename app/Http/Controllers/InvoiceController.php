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

            // ===== INI DATA YANG SEBELUMNYA TIDAK TERSIMPAN =====
            'no_invoices'      => $item['no_invoices'] ?? null,
            'tanggal_invoices' => $item['tanggal_invoices'] ?? null,
            'tanggal_tf'       => $item['tanggal_tf'] ?? null,

            // ===== DATA UTAMA =====
            'item'             => $item['item'],
            'cicilan'          => $cicilan,
            'tagihan'          => $tagihan,
            'amount'           => $amount,
            'gambar_trip'      => $gambarTrip,
            'gambar_transfer'  => $gambarTransfer
        ]);

        $total += $amount;
    }

    $invoice->update([
        'total' => $total
    ]);

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

    // PRINT PDF
    public function print($id)
{
    $invoice = Invoice::with([
        'mitra',
        'mitra.unit'
    ])->findOrFail($id);

    // semua invoice milik mitra
    $invoices = Invoice::with('items')
        ->where('mitra_id', $invoice->mitra_id)
        ->get();

    // semua item
    $items = $invoices->flatMap->items;

    // ===== ITEM TERBARU (UPLOAD TERAKHIR) =====
    $lastItem = $items
        ->sortByDesc('created_at')   // paling akurat
        ->first();

    // nomor invoice dari row terbaru
    $invoiceNumber = $lastItem->no_invoices ?? '-';

    // ===== GRAND TOTAL =====
    $grandTotal = (float) $items->sum(function ($item) {
        return (float) $item->amount;
    });

    $pdf = Pdf::loadView('admin_transport.invoice.print', [
        'invoice'       => $invoice,
        'items'         => $items,
        'invoiceNumber' => $invoiceNumber,
        'grandTotal'    => $grandTotal,
        'lastItem'      => $lastItem
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('invoice-'.$invoice->id.'.pdf');
}

private function generateNoInvoice($rawNumber)
{
    // bulan & tahun sekarang
    $bulan = now()->month;
    $tahun = now()->year;

    // romawi bulan
    $romawi = [
        1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
        7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
    ];

    // ambil angka saja lalu jadi 3 digit
    $angka = (int) preg_replace('/\D/', '', $rawNumber);
    $no = str_pad($angka, 3, '0', STR_PAD_LEFT);

    return $no.'/BJM/'.$romawi[$bulan].'/'.$tahun;
}


}
