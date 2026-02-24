<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranInternal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PengeluaranInternalController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $bulan  = $request->bulan ?? date('Y-m');
        $search = $request->search;

        $pengeluaran = PengeluaranInternal::whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->when($search, function ($query) use ($search) {
                $query->where('deskripsi', 'like', '%' . $search . '%');
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $pengeluaran->sum('nominal');

        return view(
            'admin_transport.pengeluaran_internal.index',
            compact('pengeluaran', 'total', 'bulan', 'search')
        );
    }

    // ================= SHOW =================
    public function show($id)
    {
        $pengeluaran = PengeluaranInternal::findOrFail($id);
        return view('admin_transport.pengeluaran_internal.show', compact('pengeluaran'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin_transport.pengeluaran_internal.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'nominal'   => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gambar1'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['tanggal','deskripsi','nominal']);

        // Upload gambar pertama
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')
                ->store('pengeluaran', 'public');
        }

        // Upload gambar kedua
        if ($request->hasFile('gambar1')) {
            $data['gambar1'] = $request->file('gambar1')
                ->store('pengeluaran', 'public');
        }

        PengeluaranInternal::create($data);

        return redirect()->route('pengeluaran_internal.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // ================= EDIT =================
    public function edit(PengeluaranInternal $pengeluaranInternal)
    {
        return view(
            'admin_transport.pengeluaran_internal.edit',
            compact('pengeluaranInternal')
        );
    }

    // ================= UPDATE =================
    public function update(Request $request, PengeluaranInternal $pengeluaranInternal)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'nominal'   => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gambar1'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['tanggal','deskripsi','nominal']);

        // Update gambar pertama
        if ($request->hasFile('gambar')) {
            if ($pengeluaranInternal->gambar) {
                Storage::disk('public')
                    ->delete($pengeluaranInternal->gambar);
            }

            $data['gambar'] = $request->file('gambar')
                ->store('pengeluaran', 'public');
        }

        // Update gambar kedua
        if ($request->hasFile('gambar1')) {
            if ($pengeluaranInternal->gambar1) {
                Storage::disk('public')
                    ->delete($pengeluaranInternal->gambar1);
            }

            $data['gambar1'] = $request->file('gambar1')
                ->store('pengeluaran', 'public');
        }

        $pengeluaranInternal->update($data);

        return redirect()->route('pengeluaran_internal.index')
            ->with('success', 'Pengeluaran berhasil diupdate.');
    }

    // ================= DESTROY =================
    public function destroy(Request $request, PengeluaranInternal $pengeluaranInternal)
    {
        if ($pengeluaranInternal->gambar) {
            Storage::disk('public')
                ->delete($pengeluaranInternal->gambar);
        }

        if ($pengeluaranInternal->gambar1) {
            Storage::disk('public')
                ->delete($pengeluaranInternal->gambar1);
        }

        $pengeluaranInternal->delete();

        return redirect()->route('pengeluaran_internal.index', [
            'bulan'  => $request->bulan,
            'search' => $request->search
        ])->with('success', 'Pengeluaran berhasil dihapus.');
    }

    // ================= LAPORAN =================
    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $pengeluaran = PengeluaranInternal::whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $pengeluaran->sum('nominal');

        return view(
            'admin_transport.pengeluaran_internal.laporan',
            compact('pengeluaran', 'total', 'bulan')
        );
    }

    // ================= PDF =================
    public function pdf(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $pengeluaran = PengeluaranInternal::whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $pengeluaran->sum('nominal');

        $pdf = Pdf::loadView(
            'admin_transport.pengeluaran_internal.print',
            compact('pengeluaran', 'total', 'bulan')
        )->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-pengeluaran-'.$bulan.'.pdf');
    }
}