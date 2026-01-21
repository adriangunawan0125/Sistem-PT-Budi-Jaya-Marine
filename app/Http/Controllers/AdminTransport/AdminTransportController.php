<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Mitra;
use App\Models\ExMitra;
use App\Models\PengeluaranInternal;
use App\Models\PengeluaranPajak;
use App\Models\PengeluaranTransport;
use Carbon\Carbon;

class AdminTransportController extends Controller
{
    public function dashboard(Request $request)
    {
        // ===============================
        // FILTER BULAN & TAHUN
        // ===============================
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // ===============================
        // INVOICE BELUM LUNAS (PER BULAN)
        // ===============================
      $mitraBelumLunas = Invoice::where('status', 'belum_lunas')
    ->distinct('mitra_id')
    ->count('mitra_id');


        // ===============================
        // MITRA & EX MITRA (TOTAL)
        // ===============================
        $totalMitra = Mitra::count();
        $totalExMitra = ExMitra::count();

        // ===============================
        // PENGELUARAN (PER BULAN)
        // ===============================
        $totalPengeluaranInternal = PengeluaranInternal::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $totalPengeluaranPajak = PengeluaranPajak::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $totalPengeluaranTransport = PengeluaranTransport::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('total_amount');

        return view('admin_transport.dashboard', compact(
    'bulan',
    'tahun',
    'mitraBelumLunas',
    'totalMitra',
    'totalExMitra',
    'totalPengeluaranInternal',
    'totalPengeluaranPajak',
    'totalPengeluaranTransport'
));

    }
}
