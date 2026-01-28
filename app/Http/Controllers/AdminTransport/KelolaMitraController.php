<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KelolaMitraController extends Controller
{
    /**
     * MITRA AKTIF
     */
    public function getIndex(Request $request)
    {
        $query = Mitra::with('unit')->aktif();

        if ($request->filled('search')) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        $mitras = $query->paginate(5)->withQueryString();
        return view('admin_transport.kelola_mitra.index', compact('mitras'));
    }

    /**
     * MITRA BERAKHIR
     */
    public function getBerakhir(Request $request)
    {
        $query = Mitra::with('unit')->berakhir();

        if ($request->filled('search')) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        $mitras = $query->paginate(5)->withQueryString();
        return view('admin_transport.kelola_mitra.berakhir', compact('mitras'));
    }

    /**
     * CREATE
     */
    public function getCreate()
    {
        $units = Unit::where('status', 'tersedia')
            ->whereDoesntHave('mitra')
            ->get();

        return view('admin_transport.kelola_mitra.create', compact('units'));
    }

    /**
     * STORE (KONTRAK BOLEH NULL)
     */
    public function postStore(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required',
            'unit_id' => 'nullable|exists:units,id',
            'alamat' => 'required',
            'no_hp' => 'required',
            'kontrak_mulai' => 'nullable|date',
            'kontrak_berakhir' => 'nullable|date|after_or_equal:kontrak_mulai',
        ]);

        Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'unit_id' => $request->unit_id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'kontrak_mulai' => $request->kontrak_mulai ?: null,
            'kontrak_berakhir' => $request->kontrak_berakhir ?: null,
            'status' => 'aktif',
        ]);

        Unit::where('id', $request->unit_id)
            ->update(['status' => 'disewakan']);

        return redirect('/admin-transport/mitra')
            ->with('success', 'Mitra berhasil ditambahkan');
    }

    /**
     * EDIT
     */
    public function getEdit($id)
    {
        $mitra = Mitra::findOrFail($id);

        $units = Unit::where('status', 'tersedia')
            ->orWhere('id', $mitra->unit_id)
            ->get();

        return view('admin_transport.kelola_mitra.edit', compact('mitra', 'units'));
    }

    /**
     * UPDATE (AKTIVASI / EDIT)
     */
    public function putUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_mitra' => 'required',
            'unit_id' => 'nullable|exists:units,id',
            'alamat' => 'required',
            'no_hp' => 'required',
            'kontrak_mulai' => 'nullable|date',
            'kontrak_berakhir' => 'nullable|date|after_or_equal:kontrak_mulai',
        ]);

        $mitra = Mitra::findOrFail($id);

        $unitLama = $mitra->unit_id;
        $unitBaru = $request->unit_id;

        if ($unitLama && $unitLama != $unitBaru) {
            Unit::where('id', $unitLama)->update(['status' => 'tersedia']);
        }

        Unit::where('id', $unitBaru)->update(['status' => 'disewakan']);

        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
            'unit_id' => $unitBaru,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'kontrak_mulai' => $request->kontrak_mulai ?: null,
            'kontrak_berakhir' => $request->kontrak_berakhir ?: null,
            'status' => 'aktif',
        ]);

        return redirect('/admin-transport/mitra')
            ->with('success', 'Mitra berhasil diperbarui');
    }

    /**
     * DETAIL
     */
    public function show($id)
    {
        $mitra = Mitra::with('unit')->findOrFail($id);
        return view('admin_transport.kelola_mitra.show', compact('mitra'));
    }

    /**
     * AKHIRI KONTRAK
     */
    public function patchAkhiriKontrak($id)
    {
        $mitra = Mitra::findOrFail($id);

        if ($mitra->isBerakhir()) {
            return back()->with('warning', 'Kontrak mitra sudah berakhir.');
        }

        if ($mitra->unit_id) {
            Unit::where('id', $mitra->unit_id)
                ->update(['status' => 'tersedia']);
        }

        $mitra->update([
            'status' => 'berakhir',
            'unit_id' => null,
            'kontrak_berakhir' => Carbon::now()->toDateString(),
        ]);

        return back()->with('success', 'Kontrak mitra berhasil diakhiri.');
    }

    /**
     * AKTIFKAN (ARAHKAN KE EDIT)
     */
    public function patchAktifkan($id)
    {
        return redirect('/admin-transport/mitra/' . $id . '/edit')
            ->with('warning', 'Pilih unit dan kontrak baru untuk mengaktifkan mitra');
    }

    /**
     * DELETE
     */
    public function destroy(Mitra $mitra)
    {
        if ($mitra->unit) {
            return back()->with('warning', 'Mitra masih terhubung dengan unit');
        }

        $mitra->delete();

        return back()->with('success', 'Mitra berhasil dihapus');
    }
}
