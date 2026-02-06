<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranTransport;
use App\Models\PengeluaranTransportItem;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class PengeluaranTransportController extends Controller
{
    // Daftar pengeluaran transport
    public function index(Request $request)
{
    $bulan  = $request->bulan ?? date('Y-m');
    $search = $request->search;

    $transport = PengeluaranTransport::with(['unit','items'])
        ->whereYear('tanggal', substr($bulan, 0, 4))
        ->whereMonth('tanggal', substr($bulan, 5, 2))
        ->when($search, function ($query) use ($search) {
            $query->whereHas('items', function ($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('tanggal', 'asc')
        ->get();

    $total_all = $transport->sum('total_amount');

    return view(
        'admin_transport.pengeluaran_transport.index',
        compact('transport', 'bulan', 'total_all', 'search')
    );
}


    // Form tambah pengeluaran
    public function create()
    {
        $units = Unit::all();
        return view('admin_transport.pengeluaran_transport.create', compact('units'));
    }

    // Simpan pengeluaran
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'keterangan.*' => 'required|string',
            'nominal.*' => 'required|numeric',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $total = array_sum($request->nominal);

        $transport = PengeluaranTransport::create([
            'unit_id' => $request->unit_id,
            'tanggal' => $request->tanggal,
            'total_amount' => $total
        ]);

        foreach($request->keterangan as $i => $keterangan){
            $data_item = [
                'transport_id' => $transport->id,
                'keterangan' => $keterangan,
                'nominal' => $request->nominal[$i]
            ];

            if(isset($request->gambar[$i])){
                // Simpan gambar di folder pengeluaran_transport
                $data_item['gambar'] = $request->file('gambar')[$i]->store('pengeluaran_transport', 'public');
            }

            PengeluaranTransportItem::create($data_item);
        }

        return redirect()->route('pengeluaran_transport.index')
                         ->with('success','Pengeluaran transport berhasil ditambahkan.');
    }

    // Form laporan
    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        $transport = PengeluaranTransport::with('unit','items')
            ->whereYear('tanggal', substr($bulan,0,4))
            ->whereMonth('tanggal', substr($bulan,5,2))
            ->orderBy('tanggal','asc')
            ->get();

        $total_all = $transport->sum('total_amount');

        return view('admin_transport.pengeluaran_transport.laporan', compact('transport','total_all','bulan'));
    }

    // Form edit
    public function edit(PengeluaranTransport $pengeluaran_transport)
    {
        $units = Unit::all();
        $pengeluaran_transport->load('items'); // ambil semua item
        return view('admin_transport.pengeluaran_transport.edit', compact('pengeluaran_transport','units'));
    }

    // Update
    public function update(Request $request, PengeluaranTransport $pengeluaran_transport)
{
    if (!$request->has('nominal') || !is_array($request->nominal)) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['item' => 'Minimal harus ada 1 item pengeluaran']);
    }

    $request->validate([
        'unit_id' => 'required|exists:units,id',
        'tanggal' => 'required|date',
        'keterangan.*' => 'required|string',
        'nominal.*' => 'required|numeric|min:1',
        'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $total = collect($request->nominal)->sum();

    $pengeluaran_transport->update([
        'unit_id' => $request->unit_id,
        'tanggal' => $request->tanggal,
        'total_amount' => $total
    ]);

    // hapus gambar lama
    foreach ($pengeluaran_transport->items as $item) {
        if ($item->gambar) {
            Storage::disk('public')->delete($item->gambar);
        }
    }

    // hapus item lama
    $pengeluaran_transport->items()->delete();

    // simpan item baru
    foreach ($request->keterangan as $i => $keterangan) {
        $data_item = [
            'transport_id' => $pengeluaran_transport->id,
            'keterangan' => $keterangan,
            'nominal' => $request->nominal[$i],
        ];

        if (isset($request->gambar[$i])) {
            $data_item['gambar'] =
                $request->file('gambar')[$i]
                    ->store('pengeluaran_transport', 'public');
        }

        PengeluaranTransportItem::create($data_item);
    }

    return redirect()->route('pengeluaran_transport.index')
        ->with('success', 'Pengeluaran transport berhasil diupdate.');
}


    // Hapus pengeluaran transport
    public function destroy(PengeluaranTransport $pengeluaran_transport)
    {
        // Hapus gambar tiap item
        foreach($pengeluaran_transport->items as $item){
            if($item->gambar){
                Storage::disk('public')->delete($item->gambar);
            }
        }

        $pengeluaran_transport->delete();

        return redirect()->route('pengeluaran_transport.index')
                         ->with('success','Pengeluaran transport berhasil dihapus.');
    }

public function print(Request $request)
{
    $bulan = $request->bulan ?? date('Y-m');

    $transport = PengeluaranTransport::with('unit','items')
        ->whereYear('tanggal', substr($bulan, 0, 4))
        ->whereMonth('tanggal', substr($bulan, 5, 2))
        ->orderBy('tanggal', 'asc')
        ->get();

    $total_all = $transport->sum('total_amount');

    $pdf = Pdf::loadView(
        'admin_transport.pengeluaran_transport.print',
        compact('transport', 'total_all', 'bulan')
    )->setPaper('A4', 'portrait');

    return $pdf->stream(
        'laporan-pengeluaran-transport-'.$bulan.'.pdf'
    );
}

}
