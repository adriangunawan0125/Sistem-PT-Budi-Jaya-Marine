<?php

namespace App\Http\Controllers;

use App\Models\ExMitra;
use Illuminate\Http\Request;

class ExMitraController extends Controller
{
    /**
     * TAMPIL LIST EX MITRA
     */
    public function index(Request $request)
{
    $exMitra = ExMitra::when($request->search, function ($query) use ($request) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    })->latest()->get();

    return view('ex-mitra.index', compact('exMitra'));
}


    /**
     * FORM TAMBAH EX MITRA
     */
    public function create()
    {
        return view('ex-mitra.create');
    }

    /**
     * SIMPAN DATA EX MITRA
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'jaminan'       => 'nullable|string|max:255',
            'no_handphone'  => 'required|string|max:20',
            'keterangan'    => 'nullable|string',
        ]);

        ExMitra::create([
            'nama'         => $request->nama,
            'jaminan'      => $request->jaminan,
            'no_handphone' => $request->no_handphone,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()
            ->route('ex-mitra.index')
            ->with('success', 'Ex Mitra berhasil ditambahkan');
    }

    /**
     * FORM EDIT EX MITRA
     */
    public function edit(ExMitra $exMitra)
    {
        return view('ex-mitra.edit', compact('exMitra'));
    }

    /**
     * UPDATE DATA EX MITRA
     */
    public function update(Request $request, ExMitra $exMitra)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'jaminan'       => 'nullable|string|max:255',
            'no_handphone'  => 'required|string|max:20',
            'keterangan'    => 'nullable|string',
        ]);

        $exMitra->update([
            'nama'         => $request->nama,
            'jaminan'      => $request->jaminan,
            'no_handphone' => $request->no_handphone,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()
            ->route('ex-mitra.index')
            ->with('success', 'Ex Mitra berhasil diperbarui');
    }

    /**
     * HAPUS EX MITRA
     */
    public function destroy(ExMitra $exMitra)
    {
        $exMitra->delete();

        return redirect()
            ->route('ex-mitra.index')
            ->with('success', 'Ex Mitra berhasil dihapus');
    }
}
