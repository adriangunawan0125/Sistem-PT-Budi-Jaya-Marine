<?php

namespace App\Http\Controllers\Admintransport;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Models\Unit;
use Illuminate\Http\Request;

class KelolaMitraController extends Controller
{
    /**
     * GET
     * Menampilkan daftar mitra
     */
    public function getIndex(Request $request)
{
    $query = Mitra::with('unit');

    // 🔍 Search nama mitra
    if ($request->filled('search')) {
        $query->where('nama_mitra', 'like', '%' . $request->search . '%');
    }

    $mitras = $query->paginate(5)->withQueryString();

    return view('admin_transport.kelola_mitra.index', compact('mitras'));
}


    /**
     * GET
     * Menampilkan form tambah mitra
     */
    public function getCreate()
{
    // ambil unit yang TERSEDIA dan BELUM punya mitra
    $units = Unit::where('status', 'tersedia')
        ->whereDoesntHave('mitra')
        ->get();

    return view('admin_transport.kelola_mitra.create', compact('units'));
}

    /**
     * POST
     * Menyimpan data mitra
     */
    public function postStore(Request $request)
{
    $request->validate([
        'nama_mitra' => 'required',
        'unit_id'    => 'required|exists:units,id',
        'alamat'     => 'required',
        'no_hp'      => 'required'
    ]);

    // simpan mitra
    $mitra = Mitra::create([
        'nama_mitra' => $request->nama_mitra,
        'unit_id'    => $request->unit_id,
        'alamat'     => $request->alamat,
        'no_hp'      => $request->no_hp
    ]);

    // 🔥 UPDATE STATUS UNIT
    Unit::where('id', $request->unit_id)
        ->update(['status' => 'disewakan']);

    return redirect('/admin-transport/mitra')
        ->with('success', 'Mitra berhasil ditambahkan');
}


    /**
     * GET
     * Menampilkan form edit mitra
     */
    public function getEdit($id)
    {
        $mitra = Mitra::findOrFail($id);
        $units = Unit::all();

        return view('admin_transport.kelola_mitra.edit', compact('mitra', 'units'));
    }

    /**
     * PUT
     * Mengupdate data mitra
     */
    public function putUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_mitra' => 'required',
            'unit_id'    => 'required|exists:units,id',
            'alamat'     => 'required',
            'no_hp'      => 'required'
        ]);

        $mitra = Mitra::findOrFail($id);
        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
            'unit_id'    => $request->unit_id,
            'alamat'     => $request->alamat,
            'no_hp'      => $request->no_hp
        ]);

        return redirect('/admin-transport/mitra')
            ->with('success', 'Mitra berhasil diperbarui');
    }

    /**
     * DELETE
     * Menghapus data mitra
     */
    public function deleteDestroy($id)
{
    $mitra = Mitra::findOrFail($id);

    // kembalikan status unit
    $mitra->unit->update([
        'status' => 'tersedia'
    ]);

    $mitra->delete();

    return redirect('/admin-transport/mitra')
        ->with('success', 'Mitra berhasil dihapus');
}

}
