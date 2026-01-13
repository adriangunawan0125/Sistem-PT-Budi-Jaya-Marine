<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceTransfer;
use App\Models\InvoiceTrip;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class InvoiceController extends Controller
{
    /**
     * LIST INVOICE
     */
   public function index()
{
    $data = Mitra::with(['invoices.items'])
        ->get()
        ->map(function($mitra){
            $totalAmount = $mitra->invoices->sum(function($invoice){
                return $invoice->items->sum('amount'); // jumlah semua item per invoice
            });

            return (object)[
                'mitra' => $mitra,
                'total_amount' => $totalAmount
            ];
        });

    return view('invoice.index', compact('data'));
}



    /**
     * FORM TAMBAH INVOICE
     */
    public function create(Request $request)
{
    // kalau dari detail (mitra sudah ada)
    if ($request->filled('mitra_id')) {
        $mitra = Mitra::findOrFail($request->mitra_id);
        return view('invoice.create', compact('mitra'));
    }

    // kalau mitra baru (manual)
    $mitras = Mitra::all();
    return view('invoice.create', compact('mitras'));
}


    /**
     * SIMPAN INVOICE BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'mitra_id' => 'required',
            'tanggal' => 'required|date',
            'items.*.item' => 'required',
            'items.*.cicilan' => 'nullable|numeric',
            'items.*.tagihan' => 'nullable|numeric',
            'transfer.*' => 'image|max:2048',
            'trip.*' => 'image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'mitra_id' => $request->mitra_id,
                'tanggal' => $request->tanggal,
                'status' => 'belum_lunas',
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->items as $item) {
    $cicilan = $item['cicilan'] ?? 0;
    $tagihan = $item['tagihan'] ?? 0;
    $amount  = $tagihan - $cicilan;

    InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'item' => $item['item'],
        'cicilan' => $cicilan,
        'tagihan' => $tagihan,
        'amount' => $amount
    ]);

    $total += $amount;
}


            // upload bukti transfer
            if ($request->hasFile('transfer')) {
                foreach ($request->file('transfer') as $file) {
                    $path = $file->store('invoice/transfer','public');
                    InvoiceTransfer::create([
                        'invoice_id' => $invoice->id,
                        'gambar' => $path
                    ]);
                }
            }

            // upload bukti perjalanan
            if ($request->hasFile('trip')) {
                foreach ($request->file('trip') as $file) {
                    $path = $file->store('invoice/trip','public');
                    InvoiceTrip::create([
                        'invoice_id' => $invoice->id,
                        'gambar' => $path
                    ]);
                }
            }

            $invoice->update(['total' => $total]);

            DB::commit();
            return redirect()->route('invoice.index')->with('success','Invoice berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * DETAIL INVOICE
     */
    public function show($mitra_id)
{
    $invoices = Invoice::with('items','mitra')
        ->where('mitra_id',$mitra_id)
        ->get();

    return view('invoice.show', compact('invoices'));
}


    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $mitras = Mitra::all();

        return view('invoice.edit', compact('invoice','mitras'));
    }

    /**
     * UPDATE INVOICE
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::findOrFail($id);

            $invoice->update([
                'mitra_id' => $request->mitra_id,
                'tanggal' => $request->tanggal,
            ]);

            // hapus item lama
            InvoiceItem::where('invoice_id',$invoice->id)->delete();

            $total = 0;
            foreach ($request->items as $item) {
                $amount = ($item['tagihan'] ?? 0) - ($item['cicilan'] ?? 0);


                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item' => $item['item'],
                    'cicilan' => $item['cicilan'] ?? 0,
                    'tagihan' => $item['tagihan'] ?? 0,
                    'amount' => $amount
                ]);

                $total += $amount;
            }

            $invoice->update(['total' => $total]);

            DB::commit();
            return redirect()->route('invoice.show',$invoice->id)->with('success','Invoice diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error',$e->getMessage());
        }
    }

    /**
     * HAPUS INVOICE
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // hapus file fisik
        foreach ($invoice->transfers as $t) {
            Storage::disk('public')->delete($t->gambar);
        }
        foreach ($invoice->trips as $t) {
            Storage::disk('public')->delete($t->gambar);
        }

        $invoice->delete();

        return back()->with('success','Invoice dihapus');
    }

    /**
     * TANDAI LUNAS
     */
    public function markLunas($id)
    {
        Invoice::where('id',$id)->update(['status'=>'lunas']);
        return back()->with('success','Invoice ditandai lunas');
    }
}
