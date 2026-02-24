<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranPajak;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranPajakController extends Controller
{
    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        $bulan  = $request->bulan ?? date('Y-m');
        $search = $request->search;

        $pajak = PengeluaranPajak::with('unit')
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->when($search, function ($query) use ($search) {
                $query->where('deskripsi', 'like', '%' . $search . '%');
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $pajak->sum('nominal');

        return view(
            'admin_transport.pengeluaran_pajak.index',
            compact('pajak', 'total', 'bulan', 'search')
        );
    }

    /* ================= SHOW ================= */
    public function show($id)
    {
        $pajak = PengeluaranPajak::with('unit')->findOrFail($id);

        return view(
            'admin_transport.pengeluaran_pajak.show',
            compact('pajak')
        );
    }

    /* ================= CREATE ================= */
    public function create()
    {
        $units = Unit::all();
        return view('admin_transport.pengeluaran_pajak.create', compact('units'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id'   => 'required|exists:units,id',
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'nominal'   => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gambar1'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only([
            'unit_id',
            'tanggal',
            'deskripsi',
            'nominal'
        ]);

        // Simpan Nota
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')
                ->store('pengeluaran_pajak', 'public');
        }

        // Simpan Bukti TF
        if ($request->hasFile('gambar1')) {
            $data['gambar1'] = $request->file('gambar1')
                ->store('pengeluaran_pajak', 'public');
        }

        PengeluaranPajak::create($data);

        return redirect()
            ->route('pengeluaran_pajak.index')
            ->with('success', 'Pengeluaran pajak berhasil ditambahkan.');
    }

    /* ================= EDIT ================= */
    public function edit(PengeluaranPajak $pengeluaranPajak)
    {
        $units = Unit::all();
        return view(
            'admin_transport.pengeluaran_pajak.edit',
            compact('pengeluaranPajak', 'units')
        );
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, PengeluaranPajak $pengeluaranPajak)
    {
        $request->validate([
            'unit_id'   => 'required|exists:units,id',
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'nominal'   => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gambar1'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only([
            'unit_id',
            'tanggal',
            'deskripsi',
            'nominal'
        ]);

        // Update Nota
        if ($request->hasFile('gambar')) {

            if ($pengeluaranPajak->gambar) {
                Storage::disk('public')->delete($pengeluaranPajak->gambar);
            }

            $data['gambar'] = $request->file('gambar')
                ->store('pengeluaran_pajak', 'public');
        }

        // Update Bukti TF
        if ($request->hasFile('gambar1')) {

            if ($pengeluaranPajak->gambar1) {
                Storage::disk('public')->delete($pengeluaranPajak->gambar1);
            }

            $data['gambar1'] = $request->file('gambar1')
                ->store('pengeluaran_pajak', 'public');
        }

        $pengeluaranPajak->update($data);

        return redirect()
            ->route('pengeluaran_pajak.index')
            ->with('success', 'Pengeluaran pajak berhasil diupdate.');
    }

    /* ================= DESTROY ================= */
    public function destroy(PengeluaranPajak $pengeluaranPajak)
    {
        // Hapus Nota
        if ($pengeluaranPajak->gambar) {
            Storage::disk('public')->delete($pengeluaranPajak->gambar);
        }

        // Hapus Bukti TF
        if ($pengeluaranPajak->gambar1) {
            Storage::disk('public')->delete($pengeluaranPajak->gambar1);
        }

        $pengeluaranPajak->delete();

        return redirect()->back()
            ->with('success', 'Pengeluaran pajak berhasil dihapus.');
    }

    /* ================= LAPORAN ================= */
    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $pajak = PengeluaranPajak::with('unit')
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $pajak->sum('nominal');

        return view(
            'admin_transport.pengeluaran_pajak.laporan',
            compact('pajak', 'total', 'bulan')
        );
    }

    /* ================= PRINT ================= */
    public function print(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $pajak = PengeluaranPajak::with('unit')
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $pajak->sum('nominal');

        $pdf = Pdf::loadView(
            'admin_transport.pengeluaran_pajak.print',
            compact('pajak', 'total', 'bulan')
        )->setPaper('A4', 'portrait');

        return $pdf->stream(
            'laporan-pajak-' . $bulan . '.pdf'
        );
    }
}