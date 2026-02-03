<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\MarineInvoice;
use App\Models\MarineInvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MarineInvoiceController extends Controller
{
    /**
     * List invoice
     */
    public function index()
    {
        $invoices = MarineInvoice::with('company')
            ->orderBy('invoice_date', 'desc')
            ->get();

        return view('admin_marine.marine_invoices.index', compact('invoices'));
    }

    /**
     * Form create invoice
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();

        return view('admin_marine.marine_invoices.create', compact('companies'));
    }

    /**
     * Simpan invoice + items
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id'        => 'required|exists:companies,id',
            'project' => 'required|string|max:255',
            'invoice_date'      => 'required|date',
            'period'            => 'required|date',
            'authorization_no'  => 'required|string',

            'description.*'     => 'required|string',
            'qty.*'             => 'required|integer|min:1',
            'price.*'           => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ hitung subtotal dari item
            $subtotal = 0;
            foreach ($request->qty as $i => $qty) {
                $subtotal += $qty * $request->price[$i];
            }

            // 2️⃣ simpan invoice
            $invoice = MarineInvoice::create([
                'company_id'       => $request->company_id,
                'project' => $request->project,
                'invoice_date'     => $request->invoice_date,
                'period'           => $request->period,
                'authorization_no' => $request->authorization_no,
                'vessel'           => $request->vessel,
                'po_no'            => $request->po_no,
                'manpower'         => $request->manpower,
                'subtotal'         => $subtotal,
                'dp'               => $request->dp ?? 0,
                'grand_total'      => $subtotal - ($request->dp ?? 0),
            ]);

            // 3️⃣ simpan item
            foreach ($request->description as $i => $desc) {
                MarineInvoiceItem::create([
                    'marine_invoice_id' => $invoice->id,
                    'description'       => $desc,
                    'qty'               => $request->qty[$i],
                    'unit'              => $request->unit[$i] ?? null,
                    'price'             => $request->price[$i],
                    'amount'            => $request->qty[$i] * $request->price[$i],
                ]);
            }
        });

        return redirect()
            ->route('marine-invoices.index')
            ->with('success', 'Invoice berhasil dibuat');
    }

    /**
     * Form edit invoice
     */
    public function edit(MarineInvoice $marineInvoice)
    {
        $marineInvoice->load('items');
        $companies = Company::orderBy('name')->get();

        return view(
            'admin_marine.marine_invoices.edit',
            compact('marineInvoice', 'companies')
        );
    }
public function show(MarineInvoice $marineInvoice)
{
    $marineInvoice->load('company', 'items');

    return view(
        'admin_marine.marine_invoices.show',
        compact('marineInvoice')
    );
}

    /**
     * Update invoice + items
     */
    public function update(Request $request, MarineInvoice $marineInvoice)
    {
        $request->validate([
            'company_id'        => 'required|exists:companies,id',
             'project' => 'required|string',

            'invoice_date'      => 'required|date',
            'period'            => 'required|date',
            'authorization_no'  => 'required|string',

            'description.*'     => 'required|string',
            'qty.*'             => 'required|integer|min:1',
            'price.*'           => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $marineInvoice) {

            // hapus item lama
            $marineInvoice->items()->delete();

            // hitung ulang subtotal
            $subtotal = 0;
            foreach ($request->qty as $i => $qty) {
                $subtotal += $qty * $request->price[$i];
            }

            // update invoice
            $marineInvoice->update([
                'company_id'       => $request->company_id,
                'project'          => $request->project, // ✅ ini penting
                'invoice_date'     => $request->invoice_date,
                'period'           => $request->period,
                'authorization_no' => $request->authorization_no,
                'vessel'           => $request->vessel,
                'po_no'            => $request->po_no,
                'manpower'         => $request->manpower,
                'subtotal'         => $subtotal,
                'dp'               => $request->dp ?? 0,
                'grand_total'      => $subtotal - ($request->dp ?? 0),
            ]);

            // simpan item baru
            foreach ($request->description as $i => $desc) {
                MarineInvoiceItem::create([
                    'marine_invoice_id' => $marineInvoice->id,
                    'description'       => $desc,
                    'qty'               => $request->qty[$i],
                    'unit'              => $request->unit[$i] ?? null,
                    'price'             => $request->price[$i],
                    'amount'            => $request->qty[$i] * $request->price[$i],
                ]);
            }
        });

        return redirect()
            ->route('marine-invoices.index')
            ->with('success', 'Invoice berhasil diupdate');
    }

    /**
     * Hapus invoice
     */
    public function destroy(MarineInvoice $marineInvoice)
    {
        $marineInvoice->delete();

        return redirect()
            ->route('marine-invoices.index')
            ->with('success', 'Invoice berhasil dihapus');
    }

    public function print($id)
{
    $invoice = MarineInvoice::with(['company', 'items'])
        ->findOrFail($id);

    $pdf = Pdf::loadView(
        'admin_marine.marine_invoices.print',
        compact('invoice')
    )
    ->setPaper('A4', 'portrait');

    // ⛔ stream = buka langsung di browser
    return $pdf->stream(
        'Invoice-'.$invoice->id.'.pdf'
    );
}

}
