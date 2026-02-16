<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengeluaranPo;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;

class PengeluaranPoController extends Controller
{
    /* ================= INDEX ================= */
   public function index(Request $request)
{
    $query = PengeluaranPo::with('poMasuk')->latest();

    if ($request->search) {
        $query->where('item', 'like', '%' . $request->search . '%')
              ->orWhereHas('poMasuk', function($q) use ($request){
                  $q->where('no_po_klien', 'like', '%' . $request->search . '%');
              });
    }

    $pengeluaran = $query->get();

    return view('admin_marine.pengeluaran_po.index',
        compact('pengeluaran'));
}


    /* ================= CREATE ================= */
    public function create(PoMasuk $poMasuk)
    {
        return view('admin_marine.pengeluaran_po.create', compact('poMasuk'));
    }

    /* ================= SHOW ================= */
    public function show(PengeluaranPo $pengeluaranPo)
    {
        $pengeluaranPo->load('poMasuk');

        return view('admin_marine.pengeluaran_po.show', compact('pengeluaranPo'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_masuk_id' => 'required|exists:po_masuk,id',
                'item'        => 'required|string',
                'qty'         => 'required|numeric|min:0',
                'price'       => 'required|numeric|min:0',
            ]);

            $amount = $request->qty * $request->price;

            $pengeluaran = PengeluaranPo::create([
                'po_masuk_id' => $request->po_masuk_id,
                'item'        => $request->item,
                'qty'         => $request->qty,
                'price'       => $request->price,
                'amount'      => $amount,
            ]);

            $this->updateMargin($pengeluaran->poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $pengeluaran->po_masuk_id)
                ->with('success', 'Pengeluaran berhasil ditambahkan');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= EDIT ================= */
    public function edit(PengeluaranPo $pengeluaranPo)
    {
        return view('admin_marine.pengeluaran_po.edit', compact('pengeluaranPo'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, PengeluaranPo $pengeluaranPo)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'item'  => 'required|string',
                'qty'   => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
            ]);

            $amount = $request->qty * $request->price;

            $pengeluaranPo->update([
                'item'   => $request->item,
                'qty'    => $request->qty,
                'price'  => $request->price,
                'amount' => $amount,
            ]);

            $this->updateMargin($pengeluaranPo->poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $pengeluaranPo->po_masuk_id)
                ->with('success', 'Pengeluaran berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= DESTROY ================= */
    public function destroy(PengeluaranPo $pengeluaranPo)
    {
        DB::beginTransaction();

        try {

            $poMasuk = $pengeluaranPo->poMasuk;

            $pengeluaranPo->delete();

            $this->updateMargin($poMasuk);

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $poMasuk->id)
                ->with('success', 'Pengeluaran berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= UPDATE MARGIN ================= */
    private function updateMargin($poMasuk)
    {
        $totalBeli        = $poMasuk->poSuppliers()->sum('grand_total');
        $totalPengeluaran = $poMasuk->pengeluaran()->sum('amount');

        $margin = $poMasuk->total_jual - $totalBeli - $totalPengeluaran;

        $marginStatus = 'break_even';
        if ($margin > 0) $marginStatus = 'profit';
        if ($margin < 0) $marginStatus = 'loss';

        $poMasuk->update([
            'margin'        => $margin,
            'margin_status' => $marginStatus,
        ]);
    }
}
