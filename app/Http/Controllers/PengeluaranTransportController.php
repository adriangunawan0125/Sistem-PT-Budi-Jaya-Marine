<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranTransport;
use App\Models\PengeluaranTransportItem;
use App\Models\Unit;

class PengeluaranTransportController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        $transport = PengeluaranTransport::with('unit','items')
            ->whereYear('tanggal', substr($bulan,0,4))
            ->whereMonth('tanggal', substr($bulan,5,2))
            ->orderBy('tanggal','asc')->get();
        $total_all = $transport->sum('total_amount');

        return view('pengeluaran_transport.index', compact('transport','total_all','bulan'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('pengeluaran_transport.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'keterangan.*' => 'required|string',
            'nominal.*' => 'required|numeric',
        ]);

        $total = array_sum($request->nominal);

        $transport = PengeluaranTransport::create([
            'unit_id' => $request->unit_id,
            'tanggal' => $request->tanggal,
            'total_amount' => $total
        ]);

        foreach($request->keterangan as $i => $keterangan){
            PengeluaranTransportItem::create([
                'transport_id' => $transport->id,
                'keterangan' => $keterangan,
                'nominal' => $request->nominal[$i]
            ]);
        }

        return redirect()->route('pengeluaran_transport.index')->with('success','Pengeluaran transport berhasil ditambahkan.');
    }

    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        $transport = PengeluaranTransport::with('unit','items')
            ->whereYear('tanggal', substr($bulan,0,4))
            ->whereMonth('tanggal', substr($bulan,5,2))
            ->orderBy('tanggal','asc')->get();
        $total_all = $transport->sum('total_amount');

        return view('pengeluaran_transport.laporan', compact('transport','total_all','bulan'));
    }

    // Form edit
public function edit(PengeluaranTransport $pengeluaran_transport)
{
    $units = Unit::all();
    $pengeluaran_transport->load('items'); // ambil semua item
    return view('pengeluaran_transport.edit', compact('pengeluaran_transport','units'));
}

// Update
public function update(Request $request, PengeluaranTransport $pengeluaran_transport)
{
    $request->validate([
        'unit_id' => 'required|exists:units,id',
        'tanggal' => 'required|date',
        'keterangan.*' => 'required|string',
        'nominal.*' => 'required|numeric',
    ]);

    $total = array_sum($request->nominal);

    // Update header
    $pengeluaran_transport->update([
        'unit_id' => $request->unit_id,
        'tanggal' => $request->tanggal,
        'total_amount' => $total
    ]);

    // Hapus item lama
    $pengeluaran_transport->items()->delete();

    // Simpan item baru
    foreach($request->keterangan as $i => $keterangan){
        PengeluaranTransportItem::create([
            'transport_id' => $pengeluaran_transport->id,
            'keterangan' => $keterangan,
            'nominal' => $request->nominal[$i]
        ]);
    }

    return redirect()->route('pengeluaran_transport.index')->with('success','Pengeluaran transport berhasil diupdate.');
}

// Hapus
public function destroy(PengeluaranTransport $pengeluaran_transport)
{
    $pengeluaran_transport->delete();
    return redirect()->route('pengeluaran_transport.index')->with('success','Pengeluaran transport berhasil dihapus.');
}

}
