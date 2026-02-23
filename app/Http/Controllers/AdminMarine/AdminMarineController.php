<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PoMasuk;
use App\Models\PemasukanMarine;
use App\Models\PoSupplier;
use App\Models\PengeluaranPo;

class AdminMarineController extends Controller
{
    public function index(Request $request)
    {
        // ================= FILTER =================
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $end   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        // ================= PO =================
        $poBulanan = PoMasuk::whereBetween('tanggal_po', [$start, $end]);
        $jumlahPo = $poBulanan->count();

        // ================= NILAI PO MASUK (TOTAL JUAL) =================
        $totalNilaiPoMasukBulanan = $poBulanan->sum('total_jual');

        // ================= PEMASUKAN =================
        $totalPemasukanBulanan = PemasukanMarine::whereBetween('tanggal', [$start, $end])
                                    ->sum('nominal');

        // ================= PO SUPPLIER =================
        $totalNilaiPoSupplierBulanan = PoSupplier::whereHas('poMasuk', function($q) use ($start,$end){
                $q->whereBetween('tanggal_po', [$start,$end]);
            })->sum('grand_total');

        // ================= PENGELUARAN TAMBAHAN =================
        $pengeluaranTambahan = PengeluaranPo::whereHas('poMasuk', function($q) use ($start,$end){
                $q->whereBetween('tanggal_po', [$start,$end]);
            })->sum('amount');

        $totalPengeluaranBulanan = $totalNilaiPoSupplierBulanan + $pengeluaranTambahan;

        // ================= DATA TAHUNAN =================
        $labels = [];
        $pemasukanTahunan = [];
        $pengeluaranTahunan = [];

        for ($i = 1; $i <= 12; $i++) {

            $startMonth = Carbon::create($tahun, $i, 1)->startOfMonth();
            $endMonth   = Carbon::create($tahun, $i, 1)->endOfMonth();

            $labels[] = Carbon::create()->month($i)->translatedFormat('M');

            $pemasukanTahunan[] = PemasukanMarine::whereBetween('tanggal', [$startMonth,$endMonth])
                                    ->sum('nominal');

            $beli = PoSupplier::whereHas('poMasuk', function($q) use ($startMonth,$endMonth){
                        $q->whereBetween('tanggal_po', [$startMonth,$endMonth]);
                    })->sum('grand_total');

            $tambahan = PengeluaranPo::whereHas('poMasuk', function($q) use ($startMonth,$endMonth){
                        $q->whereBetween('tanggal_po', [$startMonth,$endMonth]);
                    })->sum('amount');

            $pengeluaranTahunan[] = $beli + $tambahan;
        }

        // ================= RECENT INCOME =================
        $recentIncome = PemasukanMarine::orderBy('tanggal','desc')
                            ->limit(5)
                            ->get();

        return view('admin_marine.dashboard', compact(
            'bulan',
            'tahun',
            'jumlahPo',
            'totalPemasukanBulanan',
            'totalPengeluaranBulanan',
            'totalNilaiPoMasukBulanan',
            'totalNilaiPoSupplierBulanan',
            'labels',
            'pemasukanTahunan',
            'pengeluaranTahunan',
            'recentIncome'
        ));
    }
}