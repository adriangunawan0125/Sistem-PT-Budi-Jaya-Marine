<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoicePo;
use App\Models\InvoicePoItem;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoicePoController extends Controller
{

    /* ================= INDEX ================= */
    public function index()
    {
        $invoices = InvoicePo::with('poMasuk')
            ->latest()
            ->get();

        return view('admin_marine.invoice_po.index', compact('invoices'));
    }

    /* ================= CREATE ================= */
    public function create($poMasukId)
    {
        $poMasuk = PoMasuk::findOrFail($poMasukId);

        return view('admin_marine.invoice_po.create', compact('poMasuk'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_masuk_id'     => 'required|exists:po_masuk,id',
                'no_invoice'      => 'required',
                'tanggal_invoice' => 'required|date',
                'periode'         => 'nullable|string',
            ]);

            $invoice = InvoicePo::create([
                'po_masuk_id'     => $request->po_masuk_id,
                'no_invoice'      => $request->no_invoice,
                'tanggal_invoice' => $request->tanggal_invoice,
                'authorization_no'=> $request->authorization_no,
                'periode'         => $request->periode,
                'manpower'        => $request->manpower,
                'discount_type'   => $request->discount_type,
                'discount_value'  => $request->discount_value ?? 0,
                'status'          => 'draft',
            ]);

            /* ---------- SAVE ITEMS ---------- */
            if ($request->items) {
                foreach ($request->items as $item) {

                    if (empty($item['description'])) continue;

                    $invoice->items()->create([
                        'description' => $item['description'],
                        'qty'         => (float) $item['qty'],
                        'unit'        => $item['unit'],
                        'price'       => (float) $item['price'],
                    ]);
                }
            }

            $invoice->recalculateTotals();

            DB::commit();

           return redirect()
    ->route('po-masuk.show', $invoice->po_masuk_id)
    ->with('success','Invoice berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= SHOW ================= */
    public function show(InvoicePo $invoicePo)
    {
        $invoicePo->load('items','poMasuk');

        return view('admin_marine.invoice_po.show',
            compact('invoicePo'));
    }

    /* ================= EDIT ================= */
    public function edit(InvoicePo $invoicePo)
    {
        $invoicePo->load('items','poMasuk');

        return view('admin_marine.invoice_po.edit',
            compact('invoicePo'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, InvoicePo $invoicePo)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'no_invoice'      => 'required',
                'tanggal_invoice' => 'required|date',
            ]);

            $invoicePo->update([
                'no_invoice'      => $request->no_invoice,
                'tanggal_invoice' => $request->tanggal_invoice,
                'authorization_no'=> $request->authorization_no,
                'periode'         => $request->periode,
                'manpower'        => $request->manpower,
                'discount_type'   => $request->discount_type,
                'discount_value'  => $request->discount_value ?? 0,
            ]);

            /* ---------- DELETE OLD ITEMS ---------- */
            $invoicePo->items()->delete();

            /* ---------- SAVE NEW ITEMS ---------- */
            if ($request->items) {
                foreach ($request->items as $item) {

                    if (empty($item['description'])) continue;

                    $invoicePo->items()->create([
                        'description' => $item['description'],
                        'qty'         => (float) $item['qty'],
                        'unit'        => $item['unit'],
                        'price'       => (float) $item['price'],
                    ]);
                }
            }

            $invoicePo->recalculateTotals();

            DB::commit();

            return redirect()
                ->route('invoice-po.show', $invoicePo->id)
                ->with('success','Invoice berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= DESTROY ================= */
    public function destroy(InvoicePo $invoicePo)
    {
        DB::beginTransaction();

        try {

            $invoicePo->items()->delete();
            $invoicePo->delete();

            DB::commit();

            return redirect()
                ->route('invoice-po.index')
                ->with('success','Invoice berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= PRINT ================= */
    public function print(InvoicePo $invoicePo)
    {
        $invoicePo->load('items','poMasuk');

        $invoiceDate = Carbon::parse($invoicePo->tanggal_invoice);

        $pdf = Pdf::loadView(
            'admin_marine.invoice_po.print',
            [
                'invoice'     => $invoicePo,
                'invoiceDate' => $invoiceDate,
            ]
        )->setPaper('A4','portrait');

        return $pdf->stream('invoice-'.$invoicePo->id.'.pdf');
    }

    public function updateStatus(Request $request, InvoicePo $invoicePo)
{
    $request->validate([
        'status' => 'required|in:draft,issued,paid,cancelled'
    ]);

    $invoicePo->update([
        'status' => $request->status
    ]);

    return back()->with('success','Status berhasil diupdate');
}


}
