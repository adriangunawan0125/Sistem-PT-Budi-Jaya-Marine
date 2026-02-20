<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;

class PoMasukController extends Controller
{
    /* ================= INDEX ================= */
  public function index(Request $request)
{
    $query = PoMasuk::query();

    //  SEARCH
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('no_po_klien', 'like', "%{$search}%")
              ->orWhere('mitra_marine', 'like', "%{$search}%")
              ->orWhere('vessel', 'like', "%{$search}%");
        });
    }
    // FILTER BULAN
    if ($request->filled('month')) {
        $query->whereMonth('tanggal_po', $request->month);
    }
    //  FILTER TAHUN
    if ($request->filled('year')) {
        $query->whereYear('tanggal_po', $request->year);
    }
    $poMasuk = $query->with(['poSuppliers','pengeluaran'])
        ->latest()
        ->paginate(10);
    return view('admin_marine.po_masuk.index', compact('poMasuk'));
}

    /* ================= CREATE ================= */
    public function create()
    {
        return view('admin_marine.po_masuk.create');
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'mitra_marine' => 'required|string|max:255',
                'vessel'       => 'required|string|max:255',
                'tanggal_po'   => 'required|date',
                'no_po_klien'  => 'required|string|max:255',
                'type'         => 'required|in:sparepart,manpower',
            ]);

            $poMasuk = PoMasuk::create([
                'mitra_marine' => $request->mitra_marine,
                'vessel'       => $request->vessel,
                'alamat'       => $request->alamat,
                'tanggal_po'   => $request->tanggal_po,
                'no_po_klien'  => $request->no_po_klien,
                'type'         => $request->type,
                'status'       => 'draft',
                'total_jual'   => 0,
            ]);

            $totalJual = 0;

            if ($request->items) {

                foreach ($request->items as $item) {
                    if (empty($item['item'])) continue;
                    $price  = (float) $item['price_jual'];
                    $qty    = (float) $item['qty'];
                    $amount = $price * $qty;
                    $totalJual += $amount;
                    $poMasuk->items()->create([
                        'item'       => $item['item'],
                        'price_jual' => $price,
                        'qty'        => $qty,
                        'unit'       => $item['unit'] ?? null,
                        'amount'     => $amount,
                    ]);
                }
            }

            $poMasuk->update([
                'total_jual'    => $totalJual,
                'margin'        => 0,
                'margin_status' => 'break_even',
            ]);
            DB::commit();
            return redirect()
                ->route('po-masuk.index')
                ->with('success','PO Masuk berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= SHOW ================= */
  public function show(PoMasuk $poMasuk)
{
    $poMasuk->load([
        'items',
        'poSuppliers',
        'deliveryOrders',
        'pengeluaran',
        'invoicePos'
    ]);
    return view('admin_marine.po_masuk.show', compact('poMasuk'));
}

    /* ================= EDIT ================= */
    public function edit(PoMasuk $poMasuk)
    {
        $poMasuk->load('items');

        return view('admin_marine.po_masuk.edit', compact('poMasuk'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, PoMasuk $poMasuk)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'mitra_marine' => 'required|string|max:255',
                'vessel'       => 'required|string|max:255',
                'tanggal_po'   => 'required|date',
                'no_po_klien'  => 'required|string|max:255',
                'type'         => 'required|in:sparepart,manpower',
            ]);

            $poMasuk->update([
                'mitra_marine' => $request->mitra_marine,
                'vessel'       => $request->vessel,
                'alamat'       => $request->alamat,
                'tanggal_po'   => $request->tanggal_po,
                'no_po_klien'  => $request->no_po_klien,
                'type'         => $request->type,
            ]);

            // Hapus item lama
            $poMasuk->items()->delete();
            $totalJual = 0;
            if ($request->items) {
                foreach ($request->items as $item) {
                    if (empty($item['item'])) continue;
                    $price  = (float) $item['price_jual'];
                    $qty    = (float) $item['qty'];
                    $amount = $price * $qty;
                    $totalJual += $amount;
                    $poMasuk->items()->create([
                        'item'       => $item['item'],
                        'price_jual' => $price,
                        'qty'        => $qty,
                        'unit'       => $item['unit'] ?? null,
                        'amount'     => $amount,
                    ]);
                }
            }
            // Hitung margin
            $totalBeli = $poMasuk->poSuppliers()->sum('total_beli');
            $margin    = $totalJual - $totalBeli;

            $marginStatus = 'break_even';
            if ($margin > 0) $marginStatus = 'profit';
            if ($margin < 0) $marginStatus = 'loss';

            $poMasuk->update([
                'total_jual'    => $totalJual,
                'margin'        => $margin,
                'margin_status' => $marginStatus,
            ]);
            DB::commit();
            return redirect()
                ->route('po-masuk.index')
                ->with('success','PO Masuk berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /* ================= DESTROY ================= */
    public function destroy(PoMasuk $poMasuk)
    {
        DB::beginTransaction();
        try {
            if ($poMasuk->poSuppliers()->count() > 0) {
                return back()->with('error','Tidak bisa hapus karena sudah ada PO Supplier.');
            }
            if ($poMasuk->deliveryOrders()->count() > 0) {
                return back()->with('error','Tidak bisa hapus karena sudah ada Delivery Order.');
            }
            $poMasuk->items()->delete();
            $poMasuk->delete();
            DB::commit();
            return redirect()
                ->route('po-masuk.index')
                ->with('success','PO Masuk berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }
public function updateStatus(Request $request, PoMasuk $poMasuk)
{
    $request->validate([
        'status' => 'required|in:draft,approved,processing,delivered,closed',
    ]);

    $poMasuk->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status PO berhasil diupdate.');
}

}
