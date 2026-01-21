<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemasukanController extends Controller
{
    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $pemasukan = Pemasukan::whereMonth('tanggal', substr($bulan, 5, 2))
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->orderBy('tanggal', 'desc')
            ->get();

        $total = $pemasukan->sum('nominal');

        return view('pemasukan.index', compact('pemasukan', 'bulan', 'total'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        return view('pemasukan.create');
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required',
            'nominal' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pemasukan', 'public');
        }

        Pemasukan::create($data);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Pemasukan berhasil ditambahkan');
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        return view('pemasukan.edit', compact('pemasukan'));
    }

    /* ================= UPDATE ================= */
public function update(Request $request, $id)
{
    $request->validate([
        'tanggal'   => 'required|date',
        'deskripsi' => 'required',
        'nominal'   => 'required|numeric',
        'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $pemasukan = Pemasukan::findOrFail($id);

    // Jika ada file gambar baru
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama jika ada
        if ($pemasukan->gambar) {
            $oldFile = storage_path('app/public/pemasukan/' . $pemasukan->gambar);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Simpan file baru
        $file = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/pemasukan', $namaFile);

        $pemasukan->gambar = $namaFile; // update kolom gambar
    }

    // Update field lain
    $pemasukan->tanggal   = $request->tanggal;
    $pemasukan->deskripsi = $request->deskripsi;
    $pemasukan->nominal   = $request->nominal;

    $pemasukan->save();

    return redirect()->route('pemasukan.index')
                     ->with('success', 'Pemasukan berhasil diperbarui');
}



    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);

        if ($pemasukan->gambar) {
            Storage::disk('public')->delete($pemasukan->gambar);
        }

        $pemasukan->delete();

        return back()->with('success', 'Pemasukan berhasil dihapus');
    }

    /* ================= LAPORAN PER HARI ================= */
    public function laporanHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $pemasukan = Pemasukan::whereDate('tanggal', $tanggal)->get();
        $total = $pemasukan->sum('nominal');

        return view('pemasukan.laporan-harian', compact('pemasukan', 'tanggal', 'total'));
    }
}
