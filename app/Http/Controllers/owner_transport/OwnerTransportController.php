<?php

namespace App\Http\Controllers\owner_transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use Carbon\Carbon;

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
}
