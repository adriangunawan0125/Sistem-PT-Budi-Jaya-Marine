<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoSupplier;
use App\Models\PoSupplierItem;
use App\Models\PoSupplierTerm;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PoSupplierController extends Controller
{
    /* ================= INDEX ================= */
   public function index(Request $request)
{
    $query = PoSupplier::with('poMasuk');

    // ================= SEARCH =================
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('no_po_internal', 'like', '%' . $request->search . '%')
              ->orWhere('nama_perusahaan', 'like', '%' . $request->search . '%')
              ->orWhereHas('poMasuk', function ($sub) use ($request) {
                  $sub->where('no_po_klien', 'like', '%' . $request->search . '%');
              });
        });
    }

    // ================= FILTER BULAN =================
    if ($request->month) {
        $query->whereMonth('tanggal_po', $request->month);
    }

    // ================= FILTER TAHUN =================
    if ($request->year) {
        $query->whereYear('tanggal_po', $request->year);
    }

    $poSuppliers = $query->latest()->paginate(15)->withQueryString();

    return view('admin_marine.po_supplier.index', compact('poSuppliers'));
}


    /* ================= CREATE ================= */
    public function create($poMasukId)
    {
        $poMasuk = PoMasuk::findOrFail($poMasukId);

        return view('admin_marine.po_supplier.create', compact('poMasuk'));
    }

    /* ================= SHOW ================= */
    public function show(PoSupplier $poSupplier)
    {
        $poSupplier->load([
            'items',
            'terms',
            'poMasuk'
        ]);

        return view('admin_marine.po_supplier.show', compact('poSupplier'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_masuk_id'     => 'required|exists:po_masuk,id',
                'nama_perusahaan' => 'required',
                'tanggal_po'      => 'required|date',
                'no_po_internal'  => 'required',
                'terms.*'         => 'nullable|string'
            ]);

            $poSupplier = PoSupplier::create([
                'po_masuk_id'     => $request->po_masuk_id,
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat'          => $request->alamat,
                'tanggal_po'      => $request->tanggal_po,
                'no_po_internal'  => $request->no_po_internal,
                'discount_type'   => $request->discount_type,
                'discount_value'  => $request->discount_value ?? 0,
                'status'          => 'draft',
            ]);

            /* ---------- SAVE ITEMS ---------- */
            if ($request->items) {
                foreach ($request->items as $item) {

                    if (empty($item['item'])) continue;

                    $poSupplier->items()->create([
                        'item'       => $item['item'],
                        'price_beli' => (float) $item['price_beli'],
                        'qty'        => (float) $item['qty'],
                        'unit'       => $item['unit'] ?? null,
                    ]);
                }
            }

            /* ---------- SAVE TERMS ---------- */
            if ($request->terms) {
                foreach ($request->terms as $term) {
                    if (!empty($term)) {
                        $poSupplier->terms()->create([
                            'description' => $term
                        ]);
                    }
                }
            }

            /* ---------- RECALCULATE ---------- */
            $poSupplier->recalculateTotals();

            /* ---------- UPDATE MARGIN ---------- */
            $this->updateMargin($poSupplier->poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $poSupplier->po_masuk_id)
                ->with('success','PO Supplier berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= EDIT ================= */
    public function edit(PoSupplier $poSupplier)
    {
        $poSupplier->load([
            'items',
            'terms'
        ]);

        return view('admin_marine.po_supplier.edit', compact('poSupplier'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, PoSupplier $poSupplier)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'terms.*' => 'nullable|string'
            ]);

            $poSupplier->update([
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat'          => $request->alamat,
                'tanggal_po'      => $request->tanggal_po,
                'no_po_internal'  => $request->no_po_internal,
                'discount_type'   => $request->discount_type,
                'discount_value'  => $request->discount_value ?? 0,
            ]);

            /* ---------- DELETE OLD ITEMS ---------- */
            $poSupplier->items()->delete();

            /* ---------- SAVE NEW ITEMS ---------- */
            if ($request->items) {
                foreach ($request->items as $item) {

                    if (empty($item['item'])) continue;

                    $poSupplier->items()->create([
                        'item'       => $item['item'],
                        'price_beli' => (float) $item['price_beli'],
                        'qty'        => (float) $item['qty'],
                        'unit'       => $item['unit'] ?? null,
                    ]);
                }
            }

            /* ---------- DELETE OLD TERMS ---------- */
            $poSupplier->terms()->delete();

            /* ---------- SAVE NEW TERMS ---------- */
            if ($request->terms) {
                foreach ($request->terms as $term) {
                    if (!empty($term)) {
                        $poSupplier->terms()->create([
                            'description' => $term
                        ]);
                    }
                }
            }

            /* ---------- RECALCULATE ---------- */
            $poSupplier->recalculateTotals();

            /* ---------- UPDATE MARGIN ---------- */
            $this->updateMargin($poSupplier->poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $poSupplier->po_masuk_id)
                ->with('success','PO Supplier berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= DESTROY ================= */
    public function destroy(PoSupplier $poSupplier)
    {
        DB::beginTransaction();

        try {

            $poMasuk = $poSupplier->poMasuk;

            $poSupplier->items()->delete();
            $poSupplier->terms()->delete();
            $poSupplier->delete();

            $this->updateMargin($poMasuk);

            DB::commit();

             return redirect()
                ->route('po-masuk.show', $poSupplier->po_masuk_id)
                ->with('success','PO Supplier berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= UPDATE MARGIN ================= */
    private function updateMargin($poMasuk)
    {
        $totalBeli = $poMasuk->poSuppliers()->sum('grand_total');
        $margin    = $poMasuk->total_jual - $totalBeli;

        $marginStatus = 'break_even';
        if ($margin > 0) $marginStatus = 'profit';
        if ($margin < 0) $marginStatus = 'loss';

        $poMasuk->update([
            'margin'        => $margin,
            'margin_status' => $marginStatus,
        ]);
    }

    /* ================= PRINT ================= */
    public function print(PoSupplier $poSupplier)
    {
        $poSupplier->load([
            'items',
            'terms',
            'poMasuk'
        ]);

        $poDate = $poSupplier->tanggal_po
            ? Carbon::parse($poSupplier->tanggal_po)
            : Carbon::parse($poSupplier->created_at);

        $grandTotal = $poSupplier->grand_total ?? $poSupplier->total_beli;

        $pdf = Pdf::loadView('admin_marine.po_supplier.print', [
            'poSupplier' => $poSupplier,
            'poDate'     => $poDate,
            'grandTotal' => $grandTotal,
        ])->setPaper('A4','portrait');

        return $pdf->stream('po-supplier-'.$poSupplier->id.'.pdf');
    }
    /* ================= APPROVE ================= */
public function updateStatus(Request $request, PoSupplier $poSupplier)
{
    $request->validate([
        'status' => 'required|in:draft,approved,cancelled'
    ]);

    DB::beginTransaction();

    try {

        $poSupplier->update([
            'status' => $request->status
        ]);

        $poMasuk = $poSupplier->poMasuk;

        // ================= LOGIC SYNC KE PO MASUK =================

        if ($request->status === 'approved') {

            // Jika ada minimal 1 approved → PO Masuk jadi processing
            $poMasuk->update(['status' => 'processing']);

        } elseif ($request->status === 'cancelled') {

            // Cek apakah masih ada supplier approved
            $hasApproved = $poMasuk->poSuppliers()
                ->where('status','approved')
                ->exists();

            if (!$hasApproved) {
                $poMasuk->update(['status' => 'approved']);
            }
        }

        DB::commit();

        return back()->with('success','Status PO Supplier berhasil diupdate.');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error',$e->getMessage());
    }
}
}
