<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soa;
use App\Models\InvoicePo;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SoaController extends Controller
{
    public function index()
    {
        $soas = Soa::withCount('items')
            ->latest()
            ->get();

        return view('admin_marine.soa.index', compact('soas'));
    }

    public function create()
    {
        $invoiceList = InvoicePo::with('poMasuk')
            ->whereIn('status', ['issued', 'paid'])
            ->orderByDesc('tanggal_invoice')
            ->get();

        return view('admin_marine.soa.create', compact('invoiceList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'debtor' => 'required|string',
            'statement_date' => 'required|date',
            'termin' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.invoice_po_id' => 'required|exists:invoice_po,id',
        ]);

        DB::beginTransaction();

        try {

            $soa = Soa::create([
                'debtor' => $request->debtor,
                'address' => $request->address,
                'statement_date' => $request->statement_date,
                'termin' => $request->termin,
            ]);

            foreach ($request->items as $item) {

                if (!empty($item['invoice_po_id'])) {

                    $soa->items()->create([
                        'invoice_po_id'   => $item['invoice_po_id'],
                        'job_details'     => $item['job_details'] ?? null,
                        'acceptment_date' => $item['acceptment_date'] ?? null,
                        'remarks'         => $item['remarks'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('soa.show', $soa->id)
                ->with('success', 'SOA berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Soa $soa)
    {
        $soa->load('items.invoice.poMasuk');
        return view('admin_marine.soa.show', compact('soa'));
    }

    public function edit(Soa $soa)
    {
        $soa->load('items.invoice.poMasuk');

        $invoiceList = InvoicePo::with('poMasuk')
            ->whereIn('status', ['issued', 'paid'])
            ->orderByDesc('tanggal_invoice')
            ->get();

        return view('admin_marine.soa.edit', compact('soa', 'invoiceList'));
    }

    public function update(Request $request, Soa $soa)
    {
        $request->validate([
            'debtor' => 'required|string',
            'statement_date' => 'required|date',
            'termin' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.invoice_po_id' => 'required|exists:invoice_po,id',
        ]);

        DB::beginTransaction();

        try {

            $soa->update([
                'debtor' => $request->debtor,
                'address' => $request->address,
                'statement_date' => $request->statement_date,
                'termin' => $request->termin,
            ]);

            // hapus item lama
            $soa->items()->delete();

            foreach ($request->items as $item) {

                if (!empty($item['invoice_po_id'])) {

                    $soa->items()->create([
                        'invoice_po_id'   => $item['invoice_po_id'],
                        'job_details'     => $item['job_details'] ?? null,
                        'acceptment_date' => $item['acceptment_date'] ?? null,
                        'remarks'         => $item['remarks'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('soa.show', $soa->id)
                ->with('success', 'SOA berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Soa $soa)
    {
        DB::transaction(function () use ($soa) {
            $soa->items()->delete();
            $soa->delete();
        });

        return redirect()
            ->route('soa.index')
            ->with('success', 'SOA berhasil dihapus');
    }

    public function print(Soa $soa)
    {
        $soa->load('items.invoice.poMasuk');

        $pdf = Pdf::loadView(
            'admin_marine.soa.print',
            compact('soa')
        )->setPaper('A4', 'portrait');

        return $pdf->stream('SOA-' . $soa->id . '.pdf');
    }
}
