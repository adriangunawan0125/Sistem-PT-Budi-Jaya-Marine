<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use App\Models\MitraMarine;
use App\Models\VesselMitra;
use Illuminate\Http\Request;
use DB;


class MitraMarineController extends Controller
{
 public function index(Request $request)
{
    $query = MitraMarine::query();

    if ($request->search) {
        $query->where('nama_mitra', 'like', '%' . $request->search . '%');
    }

    $data = $query->latest()->paginate(10);

    return view('admin_marine.mitra_marine.index', compact('data'));
}


    public function create()
    {
        return view('admin_marine.mitra_marine.create');
    }

  public function store(Request $request)
{
    DB::beginTransaction();
    try {

        // SIMPAN MITRA
        $mitra = MitraMarine::create([
            'nama_mitra' => $request->nama_mitra,
            'address'    => $request->address,
            'telp'       => $request->telp,
        ]);

        // SIMPAN VESSEL
        if ($request->vessel) {
            foreach ($request->vessel as $vessel) {
                if ($vessel) {
                    VesselMitra::create([
                        'mitra_marine_id' => $mitra->id,
                        'nama_vessel'     => $vessel
                    ]);
                }
            }
        }

        DB::commit();
        return redirect()->route('mitra-marine.index')
            ->with('success','Mitra berhasil ditambahkan');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error','Gagal menyimpan data');
    }
}


    public function show($id)
    {
        $mitra = MitraMarine::with('vessels')->findOrFail($id);
        return view('admin_marine.mitra_marine.show', compact('mitra'));
    }

    public function edit($id)
    {
        $mitra = MitraMarine::with('vessels')->findOrFail($id);
        return view('admin_marine.mitra_marine.edit', compact('mitra'));
    }

    public function update(Request $request, $id)
{
    DB::beginTransaction();
    try {

        $mitra = MitraMarine::findOrFail($id);

        // UPDATE MITRA
        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
            'address'    => $request->address,
            'telp'       => $request->telp,
        ]);

        /* =========================
           UPDATE VESSEL LAMA
        ========================= */
        if ($request->vessel_existing) {
            foreach ($request->vessel_existing as $vessel_id => $nama) {
                VesselMitra::where('id',$vessel_id)
                    ->update(['nama_vessel'=>$nama]);
            }
        }

        /* =========================
           HAPUS VESSEL
        ========================= */
        if ($request->deleted_vessel) {
            $ids = explode(',', $request->deleted_vessel);
            VesselMitra::whereIn('id',$ids)->delete();
        }

        /* =========================
           TAMBAH VESSEL BARU
        ========================= */
        if ($request->vessel_new) {
            foreach ($request->vessel_new as $nama) {
                if ($nama) {
                    VesselMitra::create([
                        'mitra_marine_id' => $mitra->id,
                        'nama_vessel' => $nama
                    ]);
                }
            }
        }

        DB::commit();
        return redirect()->route('mitra-marine.index')
            ->with('success','Mitra berhasil diupdate');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error','Update gagal');
    }
}


    public function destroy($id)
    {
        MitraMarine::destroy($id);
        return back()->with('success','Mitra dihapus');
    }
}
