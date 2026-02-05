<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Mitra;
use App\Models\PengeluaranInternal;
use App\Models\PengeluaranPajak;
use App\Models\PengeluaranTransport;
use App\Models\Pemasukan;
use Carbon\Carbon;

class AdminTransportController extends Controller
{
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


        return view('admin_transport.dashboard', compact(
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
