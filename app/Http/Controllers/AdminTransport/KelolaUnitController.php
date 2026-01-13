<?php

namespace App\Http\Controllers\Admintransport;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

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

        // 📄 Pagination
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
            'nama_unit' => 'required',
            'merek'     => 'required',
            'status'    => 'required'
        ]);

    Unit::create([
    'nama_unit' => $request->nama_unit,
    'merek'     => $request->merek,
    'status'    => 'tersedia' // 🔥 DEFAULT
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
            'nama_unit' => 'required',
            'merek'     => 'required',
            'status'    => 'required|in:tersedia,disewakan'
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'nama_unit' => $request->nama_unit,
            'merek'     => $request->merek,
            'status'    => $request->status
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
}
