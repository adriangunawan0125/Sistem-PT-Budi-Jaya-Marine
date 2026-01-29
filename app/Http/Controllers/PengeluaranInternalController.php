<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranInternal;

class PengeluaranInternalController extends Controller
{
    // Menampilkan daftar pengeluaran
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m'); // default bulan ini

        $pengeluaran = PengeluaranInternal::whereYear('tanggal', substr($bulan, 0, 4))
                        ->whereMonth('tanggal', substr($bulan, 5, 2))
                        ->orderBy('tanggal', 'asc')
                        ->get();

        $total = $pengeluaran->sum('nominal');

        return view('admin_transport.pengeluaran_internal.index', compact('pengeluaran', 'total', 'bulan'));
    }

    // Form tambah pengeluaran
    public function create()
    {
        return view('admin_transport.pengeluaran_internal.create');
    }

    // Simpan pengeluaran
    public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'deskripsi' => 'required|string',
        'nominal' => 'required|numeric',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
    ]);

    $data = $request->all();

    if ($request->hasFile('gambar')) {
        $data['gambar'] = $request->file('gambar')->store('pengeluaran', 'public');
    }

    PengeluaranInternal::create($data);

    return redirect()->route('pengeluaran_internal.index')
                     ->with('success', 'Pengeluaran berhasil ditambahkan.');
}


    // Form edit pengeluaran
    public function edit(PengeluaranInternal $pengeluaranInternal)
    {
        return view('admin_transport.pengeluaran_internal.edit', compact('pengeluaranInternal'));
    }

    // Update pengeluaran
    public function update(Request $request, PengeluaranInternal $pengeluaranInternal)
{
    $request->validate([
        'tanggal' => 'required|date',
        'deskripsi' => 'required|string',
        'nominal' => 'required|numeric',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('gambar')) {
        // hapus file lama kalau ada
        if ($pengeluaranInternal->gambar) {
            \Storage::disk('public')->delete($pengeluaranInternal->gambar);
        }
        $data['gambar'] = $request->file('gambar')->store('pengeluaran', 'public');
    }

    $pengeluaranInternal->update($data);

    return redirect()->route('pengeluaran_internal.index')
                     ->with('success', 'Pengeluaran berhasil diupdate.');
}


    // Hapus pengeluaran
    public function destroy(PengeluaranInternal $pengeluaranInternal)
{
    if ($pengeluaranInternal->gambar) {
        \Storage::disk('public')->delete($pengeluaranInternal->gambar);
    }

    $pengeluaranInternal->delete();

    return redirect()->route('pengeluaran_internal.index')
                     ->with('success', 'Pengeluaran berhasil dihapus.');
}

    public function laporan(Request $request)
{
    $bulan = $request->bulan ?? date('Y-m'); // default bulan ini

    $pengeluaran = PengeluaranInternal::whereYear('tanggal', substr($bulan, 0, 4))
                    ->whereMonth('tanggal', substr($bulan, 5, 2))
                    ->orderBy('tanggal', 'asc')
                    ->get();

    $total = $pengeluaran->sum('nominal');

    return view('admin_transport.pengeluaran_internal.laporan', compact('pengeluaran', 'total', 'bulan'));
}

}
