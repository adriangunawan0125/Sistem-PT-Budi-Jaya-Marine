<?php

namespace App\Http\Controllers\owner_transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\PengeluaranInternal;
use Carbon\Carbon;
use App\Models\PengeluaranTransport;
use App\Models\PengeluaranTransportItem;
use App\Models\PengeluaranPajak;
use App\Models\Mitra;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class OwnerTransportController extends Controller
{
  // Laporan Harian
public function laporanHarian(Request $request)
{
    $tanggal     = $request->tanggal ?? date('Y-m-d');
    $nama        = $request->nama;
    $kategori    = $request->kategori;
    $tidakSetor  = $request->tidak_setor;

    $query = Pemasukan::with('mitra')
        ->whereDate('tanggal', $tanggal);

    // FILTER NAMA 
    if ($nama) {
        $query->whereHas('mitra', function ($q) use ($nama) {
            $q->where('nama_mitra', 'like', "%$nama%");
        });
    }

    //FILTER KATEGORI 
    if ($kategori) {
        $query->where('kategori', $kategori);
    }

    // TIDAK SETOR 
    if ($tidakSetor) {

        $mitraTidakSetor = Mitra::whereDoesntHave('pemasukans', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        })->orderBy('nama_mitra')->get();

        return view('owner_transport.laporan_pemasukan_harian', [
            'pemasukan'   => collect(),
            'mitraKosong' => $mitraTidakSetor,
            'tanggal'     => $tanggal,
            'total'       => 0
        ]);
    }

    $pemasukan = $query->orderBy('tanggal', 'desc')->get();
    $total = $pemasukan->sum('nominal');

    return view(
        'owner_transport.laporan_pemasukan_harian',
        compact('pemasukan', 'tanggal', 'total')
    );
}

   // Laporan Bulanan
public function laporanBulanan(Request $request)
{
    $bulan = $request->bulan ?? date('Y-m');

    $pemasukan = Pemasukan::with('mitra')
        ->whereYear('tanggal', substr($bulan, 0, 4))
        ->whereMonth('tanggal', substr($bulan, 5, 2))
        ->orderBy('tanggal', 'asc')
        ->get();

    $total = $pemasukan->sum('nominal');

    return view('owner_transport.laporan_pemasukan_bulanan', compact(
        'pemasukan',
        'total',
        'bulan'
    ));
}

public function detail(Pemasukan $pemasukan)
{
    $pemasukan->load('mitra');

    return view('owner_transport.detail_pemasukan', compact('pemasukan'));
}


public function laporanPengeluaranInternal(Request $request)
{
    $bulan = $request->bulan ?? now()->format('Y-m');

    $pengeluaran = PengeluaranInternal::whereMonth(
            'tanggal',
            Carbon::parse($bulan)->month
        )
        ->whereYear(
            'tanggal',
            Carbon::parse($bulan)->year
        )
        ->orderBy('tanggal', 'asc')
        ->get();

    $total = $pengeluaran->sum('nominal');

    return view(
        'owner_transport.laporan_pengeluaran_internal',
        compact('pengeluaran', 'total', 'bulan')
    );
}

public function detailPengeluaranInternal($id)
{
    $pengeluaran = PengeluaranInternal::findOrFail($id);

    return view(
        'owner_transport.detail_pengeluaraninternal',
        compact('pengeluaran')
    );
}

public function rekapPengeluaranTransport(Request $request)
{
    $bulan = $request->bulan ?? now()->format('Y-m');

    $pengeluaran = PengeluaranTransport::with(['unit', 'items'])
        ->whereMonth('tanggal', Carbon::parse($bulan)->month)
        ->whereYear('tanggal', Carbon::parse($bulan)->year)
        ->orderBy('tanggal', 'desc')
        ->get();

    $total = $pengeluaran->sum('total_amount');

    return view(
        'owner_transport.laporan_pengeluaran_transport',
        compact('pengeluaran', 'total', 'bulan')
    );
}

public function detailPengeluaranTransport($id)
{
    $pengeluaran = PengeluaranTransport::with([
        'unit',
        'items' => function ($q) {
            $q->select(
                'id',
                'transport_id',
                'keterangan',
                'nominal',
                'gambar'
            );
        }
    ])->findOrFail($id);

    return view(
        'owner_transport.detail_pengeluaran_transport',
        compact('pengeluaran')
    );
}

public function detailItemPengeluaranTransport($id)
{
    $item = PengeluaranTransportItem::with([
        'pengeluaran.unit'
    ])->findOrFail($id);

    return view(
        'owner_transport.detailitem_pengeluarantransport',
        compact('item')
    );
}


// Laporan Pengeluaran Pajak Mobil
public function rekapPengeluaranPajak(Request $request)
{
   
    $bulan = $request->bulan ?? now()->format('Y-m');

    $pengeluaran = PengeluaranPajak::with('unit')
        ->whereMonth('tanggal', Carbon::parse($bulan)->month)
        ->whereYear('tanggal', Carbon::parse($bulan)->year)
        ->orderBy('tanggal', 'desc')
        ->get();

    $total_all = $pengeluaran->sum('nominal');

    return view(
        'owner_transport.laporan_pengeluaran_pajak',
        compact('pengeluaran', 'total_all', 'bulan')
    );
}

public function detailPengeluaranPajak($id)
{
    $pengeluaran = PengeluaranPajak::with('unit')
        ->findOrFail($id);

    return view(
        'owner_transport.detail_pengeluaranpajak',
        compact('pengeluaran')
    );
}


// Laporan Mitra Aktif
 public function laporanMitraAktif(Request $request)
{
    $query = Mitra::aktif()->with('unit');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama_mitra', 'like', '%' . $request->search . '%')
              ->orWhere('alamat', 'like', '%' . $request->search . '%')
              ->orWhere('no_hp', 'like', '%' . $request->search . '%');
        });
    }

    $mitras = $query->orderBy('nama_mitra')
                    ->paginate(10)
                    ->withQueryString();

    $total = $mitras->total(); 

    return view('owner_transport.laporan_mitra_aktif', compact('mitras', 'total'));
}


    // Laporan Ex Mitra
   public function laporanExMitra(Request $request)
{
    $search = $request->search;

    $query = Mitra::berakhir()
        ->with('unit')
        ->when($search, function ($q) use ($search) {
            $q->where('nama_mitra', 'like', "%{$search}%")
              ->orWhere('no_hp', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%");
        })
        ->orderBy('nama_mitra');

    $mitras = $query->paginate(10)->withQueryString();

    $total = (clone $query)->count();

    return view(
        'owner_transport.laporan_ex_mitra',
        compact('mitras', 'total', 'search')
    );
}


    // Detail Mitra
    public function detailMitra($id)
    {
        $mitra = Mitra::with(['unit', 'jaminan'])->findOrFail($id);

        return view('owner_transport.detail_mitra', compact('mitra'));
    }

public function laporanInvoice(Request $request)
{
    $search = $request->search ?? '';

    $data = Invoice::selectRaw('
            mitras.id,
            mitras.nama_mitra,
            COALESCE(SUM(invoices.total), 0) as total_amount,
            MAX(invoice_items.tanggal_tf) as tanggal_tf_terakhir
        ')
        ->rightJoin('mitras', 'mitras.id', '=', 'invoices.mitra_id')
        ->leftJoin('invoice_items', function ($join) {
            $join->on('invoice_items.invoice_id', '=', 'invoices.id')
                 ->whereNotNull('invoice_items.tanggal_tf');
        })
        ->when($search, function ($q) use ($search) {
            $q->where('mitras.nama_mitra', 'like', "%{$search}%");
        })
        ->groupBy('mitras.id', 'mitras.nama_mitra')
        ->paginate(10)
        ->withQueryString();

    $total_all = Invoice::sum('total');

    return view(
        'owner_transport.laporan_invoice',
        compact('data', 'total_all')
    );
}

public function detailInvoice($mitraId)
{
    $mitra = Mitra::findOrFail($mitraId);

    $invoices = Invoice::with('items')
        ->where('mitra_id', $mitraId)
        ->get();

    return view('owner_transport.detail_invoice', compact('mitra', 'invoices'));
}

 public function dashboard(Request $request)
    {
        // FILTER BULAN & TAHUN
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // INVOICE BELUM LUNAS (PER BULAN)
        $mitraBelumLunas = Invoice::where('status', 'belum_lunas')
            ->distinct('mitra_id')
            ->count('mitra_id');

        // MITRA TOTAL & STATUS
        $totalMitra = Mitra::count();
        $mitraAktif = Mitra::where('status', 'aktif')->count();
        $mitraBerakhir = Mitra::where('status', 'berakhir')->count();

        // PENGELUARAN (PER BULAN)
        $totalPengeluaranInternal = PengeluaranInternal::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $totalPengeluaranPajak = PengeluaranPajak::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $totalPengeluaranTransport = PengeluaranTransport::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('total_amount');

        // Total semua pengeluaran bulan ini
        $totalPengeluaranBulanan = $totalPengeluaranInternal + $totalPengeluaranPajak + $totalPengeluaranTransport;

        // PEMASUKAN
        // Harian (hari ini)
        $totalPemasukanHarian = Pemasukan::whereDate('tanggal', Carbon::today())
            ->sum('nominal');

        // Bulanan (sesuai filter dashboard)
        $totalPemasukanBulanan = Pemasukan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $labels = [];
        $pemasukanTahunan = [];
        $pengeluaranTahunan = [];

        for ($i = 1; $i <= 12; $i++) {

            $labels[] = Carbon::create()->month($i)->translatedFormat('M');

            // PEMASUKAN PER BULAN
            $pemasukan = Pemasukan::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('nominal');

            // PENGELUARAN PER BULAN
            $internal = PengeluaranInternal::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('nominal');

            $pajak = PengeluaranPajak::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('nominal');

            $transport = PengeluaranTransport::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('total_amount');

            $pengeluaran = $internal + $pajak + $transport;

            $pemasukanTahunan[] = $pemasukan;
            $pengeluaranTahunan[] = $pengeluaran;
        }

        return view('owner_transport.dashboard', compact(
            'bulan',
            'tahun',
            'mitraBelumLunas',
            'totalMitra',
            'mitraAktif',
            'mitraBerakhir',
            'totalPengeluaranInternal',
            'totalPengeluaranPajak',
            'totalPengeluaranTransport',
            'totalPengeluaranBulanan',
            'totalPemasukanHarian',
            'totalPemasukanBulanan',

            // DATA CHART
            'labels',
            'pemasukanTahunan',
            'pengeluaranTahunan'
        ));
    }

}
