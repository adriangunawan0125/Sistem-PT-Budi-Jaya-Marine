<?php

namespace App\Http\Controllers\owner_transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\PengeluaranInternal;
use Carbon\Carbon;
use App\Models\PengeluaranTransport;
use App\Models\PengeluaranPajak;
use App\Models\Mitra;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class OwnerTransportController extends Controller
{
  // Laporan Harian
public function laporanHarian(Request $request)
{
    // kalau tidak pilih tanggal → default HARI INI
    $tanggal = $request->tanggal ?? now()->toDateString();

    $pemasukan = Pemasukan::whereDate('tanggal', $tanggal)
        ->orderBy('tanggal', 'desc')
        ->get();

    $total = $pemasukan->sum('nominal');

    return view(
        'owner_transport.laporan_pemasukan_harian',
        compact('pemasukan', 'total', 'tanggal')
    );
}


    // Laporan Bulanan
    public function laporanBulanan(Request $request)
    {
        $query = Pemasukan::query();

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $pemasukan = $query->orderBy('tanggal', 'desc')->get();
        $total = $pemasukan->sum('nominal');

        return view('owner_transport.laporan_pemasukan_bulanan', compact('pemasukan', 'total'));
    }

public function laporanPengeluaranInternal(Request $request)
{
    // default bulan sekarang (YYYY-MM)
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
public function rekapPengeluaranTransport(Request $request)
{
    // default bulan sekarang
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

// Laporan Pengeluaran Pajak Mobil
public function rekapPengeluaranPajak(Request $request)
{
    // default bulan sekarang
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


// Laporan Mitra Aktif
    public function laporanMitraAktif()
    {
        $mitras = Mitra::aktif()->with('unit')->orderBy('nama_mitra')->get();
        $total = $mitras->count();

        return view('owner_transport.laporan_mitra_aktif', compact('mitras', 'total'));
    }

    // Laporan Ex Mitra
    public function laporanExMitra()
    {
        $mitras = Mitra::berakhir()->with('unit')->orderBy('nama_mitra')->get();
        $total = $mitras->count();

        return view('owner_transport.laporan_ex_mitra', compact('mitras', 'total'));
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
            MAX(invoice_items.tanggal) as tanggal_tf_terakhir
        ')
        ->rightJoin('mitras', 'mitras.id', '=', 'invoices.mitra_id')
        ->leftJoin('invoice_items', function ($join) {
            $join->on('invoice_items.invoice_id', '=', 'invoices.id')
                 ->whereNotNull('invoice_items.tanggal');
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


public function detailInvoice($id)
{
    $invoice = Invoice::with('mitra', 'items')->findOrFail($id);

    return view('owner_transport.detail_invoice', compact('invoice'));
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


        /* ======================================================
           DATA CHART TAHUNAN (12 BULAN KE BELAKANG DALAM TAHUN)
        ====================================================== */
        $labels = [];
        $pemasukanTahunan = [];
        $pengeluaranTahunan = [];

        for ($i = 1; $i <= 12; $i++) {

            // label bulan (Jan, Feb, Mar...)
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
