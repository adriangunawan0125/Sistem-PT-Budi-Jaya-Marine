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
    $tanggal = $request->tanggal ?? date('Y-m-d');

    $pemasukan = Pemasukan::whereDate('tanggal', $tanggal)
        ->orderBy('tanggal', 'desc')
        ->get();

    $total = $pemasukan->sum('nominal');

    return view('pemasukan.index', compact('pemasukan', 'tanggal', 'total'));
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
            'tanggal'   => 'required|date',
            'deskripsi' => 'required',
            'nominal'   => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['tanggal','deskripsi','nominal']);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/pemasukan', $namaFile);

            $data['gambar'] = $namaFile; // SIMPAN NAMA FILE SAJA
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

        if ($request->hasFile('gambar')) {
            // hapus gambar lama
            if ($pemasukan->gambar) {
                Storage::disk('public')->delete('pemasukan/'.$pemasukan->gambar);
            }

            $file = $request->file('gambar');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/pemasukan', $namaFile);

            $pemasukan->gambar = $namaFile;
        }

        $pemasukan->update([
            'tanggal'   => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'nominal'   => $request->nominal,
        ]);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Pemasukan berhasil diperbarui');
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);

        if ($pemasukan->gambar) {
            Storage::disk('public')->delete('pemasukan/'.$pemasukan->gambar);
        }

        $pemasukan->delete();

        return back()->with('success', 'Pemasukan berhasil dihapus');
    }

    /* ================= LAPORAN HARIAN ================= */
public function laporanHarian(Request $request)
{
    $tanggal = $request->tanggal ?? date('Y-m-d');

    $pemasukan = Pemasukan::whereDate('tanggal', $tanggal)
        ->orderBy('tanggal', 'desc')
        ->get();

    $total = $pemasukan->sum('nominal');

    return view('pemasukan.laporan-harian', compact(
        'pemasukan',
        'tanggal',
        'total'
    ));
}

/* ================= LAPORAN BULANAN ================= */
public function laporanBulanan(Request $request)
{
    $bulan = $request->bulan ?? date('Y-m');

    $tahun = substr($bulan, 0, 4);
    $bulanAngka = substr($bulan, 5, 2);

    $pemasukan = Pemasukan::whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulanAngka)
        ->orderBy('tanggal', 'asc')
        ->get();

    $total = $pemasukan->sum('nominal');

    return view('pemasukan.laporan-bulanan', compact(
        'pemasukan',
        'bulan',
        'total'
    ));
}



}
