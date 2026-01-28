<?php

namespace App\Http\Controllers\Admintransport;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class KelolaUnitController extends Controller
{
    /**
     * GET
     * Menampilkan daftar unit (Search + Filter + Pagination)
     */
    public function getIndex(Request $request)
    {
        $query = Unit::query();

        // 🔍 Search nama unit
        if ($request->filled('search')) {
            $query->where('nama_unit', 'like', '%' . $request->search . '%');
        }

        // 🔽 Filter status (tersedia / disewakan)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $units = $query->orderBy('id', 'desc')
                       ->paginate(5)
                       ->appends($request->all());

        return view('admin_transport.kelola_unit.index', compact('units'));
    }

    /**
     * GET
     * Menampilkan form tambah unit
     */
    public function getCreate()
    {
        return view('admin_transport.kelola_unit.create');
    }

    /**
     * POST
     * Menyimpan data unit baru
     */
    public function postStore(Request $request)
    {
        $request->validate([
            'nama_unit'       => 'required|unique:units,nama_unit',
            'merek'           => 'required',
            'stnk_expired_at' => 'nullable|date|after_or_equal:today',
        ], [
            'nama_unit.unique' => 'Nomor unit / plat kendaraan ini sudah terdaftar!',
            'stnk_expired_at.after_or_equal' => 'Tanggal STNK minimal hari ini atau lebih.',
        ]);

        Unit::create([
            'nama_unit'       => $request->nama_unit,
            'merek'           => $request->merek,
            'status'          => 'tersedia', // default
            'stnk_expired_at' => $request->stnk_expired_at,
        ]);

        return redirect('/admin-transport/unit')
            ->with('success', 'Unit berhasil ditambahkan');
    }

    /**
     * GET
     * Menampilkan form edit unit
     */
    public function getEdit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('admin_transport.kelola_unit.edit', compact('unit'));
    }

    /**
     * PUT
     * Mengupdate data unit
     */
    public function putUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_unit'       => ['required', Rule::unique('units', 'nama_unit')->ignore($id)],
            'merek'           => 'required',
            'status'          => 'required|in:tersedia,disewakan',
            'stnk_expired_at' => 'nullable|date|after_or_equal:today',
        ], [
            'nama_unit.unique' => 'Nomor unit / plat kendaraan ini sudah digunakan unit lain!',
            'stnk_expired_at.after_or_equal' => 'Tanggal STNK minimal hari ini atau lebih.',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'nama_unit'       => $request->nama_unit,
            'merek'           => $request->merek,
            'status'          => $request->status,
            'stnk_expired_at' => $request->stnk_expired_at,
        ]);

        return redirect('/admin-transport/unit')
            ->with('success', 'Unit berhasil diperbarui');
    }

    /**
     * DELETE
     * Menghapus data unit
     */
    public function deleteDestroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect('/admin-transport/unit')
            ->with('success', 'Unit berhasil dihapus');
    }

    /**
     * Fungsi cek STNK akan habis dalam 7 hari
     * Bisa ditampilkan di dashboard atau JSON
     */
    public function checkStnkExpiring()
    {
        $today = Carbon::today();
        $weekAhead = $today->copy()->addDays(7);

        $unitsExpiring = Unit::whereBetween('stnk_expired_at', [$today, $weekAhead])
                              ->orderBy('stnk_expired_at', 'asc')
                              ->get();

        // contoh return JSON, bisa ditampilkan di dashboard
        return response()->json([
            'units_expiring' => $unitsExpiring->map(function($unit) {
                return [
                    'nama_unit' => $unit->nama_unit,
                    'merek' => $unit->merek,
                    'status' => $unit->status,
                    'stnk_expired_at' => $unit->stnk_expired_at,
                    'sisa_hari' => Carbon::today()->diffInDays($unit->stnk_expired_at),
                ];
            }),
        ]);
    }
}
