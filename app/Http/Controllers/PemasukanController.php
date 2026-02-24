<?php
namespace App\Http\Controllers;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OwnerNotification;
use App\Models\Mitra;


class PemasukanController extends Controller
{
    /* ================= INDEX ================= */
public function index(Request $request)
{
    $tanggal     = $request->tanggal ?? date('Y-m-d');
    $nama        = $request->nama;
    $kategori    = $request->kategori;
    $tidakSetor  = $request->tidak_setor;

    /* ================= QUERY UTAMA ================= */
    $query = Pemasukan::with('mitra')
        ->whereDate('tanggal', $tanggal)
        ->whereHas('mitra', function ($q) {
            // 🔒 hanya mitra AKTIF
            $q->where('status', 'aktif');
        });

    /* ================= FILTER NAMA ================= */
    if ($nama) {
        $query->whereHas('mitra', function ($q) use ($nama) {
            $q->where('nama_mitra', 'like', "%{$nama}%");
        });
    }

    /* ================= FILTER KATEGORI ================= */
    if ($kategori) {
        $query->where('kategori', $kategori);
    }

    /* ================= FILTER TIDAK SETOR ================= */
    if ($tidakSetor) {

        $mitraTidakSetor = Mitra::where('status', 'aktif') // ⛔ exclude berakhir
            ->whereDoesntHave('pemasukans', function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal);
            })
            ->orderBy('nama_mitra')
            ->get();

        return view('admin_transport.pemasukan.index', [
            'pemasukan'   => collect(),
            'mitraKosong' => $mitraTidakSetor,
            'tanggal'     => $tanggal,
            'total'       => 0
        ]);
    }

    /* ================= DATA NORMAL ================= */
    $pemasukan = $query->orderBy('tanggal', 'desc')->get();
    $total     = $pemasukan->sum('nominal');

    return view('admin_transport.pemasukan.index', compact(
        'pemasukan',
        'tanggal',
        'total'
    ));
}

public function show($id)
{
    $pemasukan = Pemasukan::with('mitra')->findOrFail($id);

    return view('admin_transport.pemasukan.show', compact('pemasukan'));
}

    /* ================= CREATE ================= */
    public function create()
{
    $mitras = Mitra::aktif()->orderBy('nama_mitra')->get();
    return view('admin_transport.pemasukan.create', compact('mitras'));
}


    /* ================= STORE ================= */
    public function store(Request $request)
{
    $request->validate([
        'tanggal'   => 'required|date',
        'mitra_id'  => 'required|exists:mitras,id',
        'kategori'  => 'required|in:setoran,cicilan,deposit',
        'deskripsi' => 'required',
        'nominal'   => 'required|numeric',
        'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->only([
        'tanggal','mitra_id','kategori','deskripsi','nominal'
    ]);

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $namaFile = time().'_'.$file->getClientOriginalName();
        $file->storeAs('public/pemasukan', $namaFile);
        $data['gambar'] = $namaFile;
    }

    $pemasukan = Pemasukan::create($data);

    OwnerNotification::create([
        'type'    => 'pemasukan',
        'data_id' => $pemasukan->id,
        'message' => 'Pemasukan baru ditambahkan sebesar Rp ' .
                     number_format($pemasukan->nominal, 0, ',', '.'),
        'is_read' => 0
    ]);

    return redirect()->route('pemasukan.index')
        ->with('success', 'Pemasukan berhasil ditambahkan');
}


    /* ================= EDIT ================= */
   public function edit($id)
{
    $pemasukan = Pemasukan::findOrFail($id);
    $mitras = Mitra::aktif()->orderBy('nama_mitra')->get();

    return view('admin_transport.pemasukan.edit',
        compact('pemasukan','mitras'));
}

    /* ================= UPDATE ================= */
public function update(Request $request, $id)
{
    $pemasukan = Pemasukan::findOrFail($id);

    $request->validate([
        'tanggal'   => 'required|date',
        'kategori'  => 'required|in:setoran,cicilan,deposit',
        'deskripsi' => 'required',
        'nominal'   => 'required|numeric',
        'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // upload gambar
    if ($request->hasFile('gambar')) {
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
        'kategori'  => $request->kategori,
        'deskripsi' => $request->deskripsi,
        'nominal'   => $request->nominal,
        // mitra_id TIDAK DIUBAH
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

        return back()->with('success', 'Data pemasukan berhasil dihapus');
    }

    /* ================= LAPORAN HARIAN ================= */
public function laporanHarian(Request $request)
{
    $tanggal = $request->tanggal ?? date('Y-m-d');

    $pemasukan = Pemasukan::whereDate('tanggal', $tanggal)
        ->orderBy('tanggal', 'desc')
        ->get();

    $total = $pemasukan->sum('nominal');

    return view('admin_transport.pemasukan.laporan-harian', compact(
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

    return view('admin_transport.pemasukan.laporan-bulanan', compact(
        'pemasukan',
        'bulan',
        'total'
    ));
}

public function printHarian(Request $request)
{
    $tanggal = $request->tanggal ?? date('Y-m-d');

    $pemasukan = Pemasukan::whereDate('tanggal', $tanggal)
        ->orderBy('tanggal', 'asc')
        ->get();

    $total = $pemasukan->sum('nominal');

    $pdf = Pdf::loadView('admin_transport.pemasukan.print-harian', [
        'pemasukan' => $pemasukan,
        'tanggal'   => $tanggal,
        'total'     => $total
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('pemasukan-harian-'.$tanggal.'.pdf');
}

public function printBulanan(Request $request)
{
    $bulan = $request->bulan ?? date('Y-m');

    $tahun = substr($bulan, 0, 4);
    $bulanAngka = substr($bulan, 5, 2);

    $pemasukan = Pemasukan::whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulanAngka)
        ->orderBy('tanggal', 'asc')
        ->get();

    $total = $pemasukan->sum('nominal');

    $pdf = Pdf::loadView('admin_transport.pemasukan.print-bulanan', [
        'pemasukan' => $pemasukan,
        'bulan'     => $bulan,
        'total'     => $total
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('pemasukan-bulanan-'.$bulan.'.pdf');
}

}
