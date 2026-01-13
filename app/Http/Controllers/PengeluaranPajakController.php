<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranPajak;
use App\Models\Unit;

class PengeluaranPajakController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $pajak = PengeluaranPajak::with('unit')
            ->whereYear('tanggal', substr($bulan,0,4))
            ->whereMonth('tanggal', substr($bulan,5,2))
            ->orderBy('tanggal','asc')
            ->get();

        $total = $pajak->sum('nominal');

        return view('pengeluaran_pajak.index', compact('pajak','total','bulan'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('pengeluaran_pajak.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        PengeluaranPajak::create($request->all());

        return redirect()->route('pengeluaran_pajak.index')->with('success','Pengeluaran pajak berhasil ditambahkan.');
    }

    public function edit(PengeluaranPajak $pengeluaranPajak)
    {
        $units = Unit::all();
        return view('pengeluaran_pajak.edit', compact('pengeluaranPajak','units'));
    }

    public function update(Request $request, PengeluaranPajak $pengeluaranPajak)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        $pengeluaranPajak->update($request->all());

        return redirect()->route('pengeluaran_pajak.index')->with('success','Pengeluaran pajak berhasil diupdate.');
    }

    public function destroy(PengeluaranPajak $pengeluaranPajak)
    {
        $pengeluaranPajak->delete();
        return redirect()->route('pengeluaran_pajak.index')->with('success','Pengeluaran pajak berhasil dihapus.');
    }

   public function laporan(Request $request)
{
    // Ambil bulan dari query string, default bulan ini
    $bulan = $request->bulan ?? date('Y-m');

    // Ambil data pengeluaran pajak per bulan
    $pajak = PengeluaranPajak::with('unit')
        ->whereYear('tanggal', substr($bulan, 0, 4))
        ->whereMonth('tanggal', substr($bulan, 5, 2))
        ->orderBy('tanggal','asc')
        ->get();

    // Hitung total nominal
    $total = $pajak->sum('nominal');

    return view('pengeluaran_pajak.laporan', compact('pajak', 'total', 'bulan'));
}



}
