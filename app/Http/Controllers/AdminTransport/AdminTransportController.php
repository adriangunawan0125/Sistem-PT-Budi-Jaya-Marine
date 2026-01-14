<?php
namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Mitra;
use App\Models\PengeluaranInternal;
use App\Models\PengeluaranPajak;
use App\Models\PengeluaranTransport;

class AdminTransportController extends Controller
{
    public function dashboard()
    {
        // Invoice belum lunas
        $invoiceBelumLunas = Invoice::where('status', 'belum_lunas')->count();

        $totalInvoiceBelumLunas = Invoice::where('status', 'belum_lunas')
            ->sum('total');

        // Jumlah mitra
        $totalMitra = Mitra::count();

        // Pengeluaran
        $totalPengeluaranInternal = PengeluaranInternal::sum('nominal');

        $totalPengeluaranPajak = PengeluaranPajak::sum('nominal');

        $totalPengeluaranTransport = PengeluaranTransport::sum('total_amount');

        return view('admin_transport.dashboard', compact(
            'invoiceBelumLunas',
            'totalInvoiceBelumLunas',
            'totalMitra',
            'totalPengeluaranInternal',
            'totalPengeluaranPajak',
            'totalPengeluaranTransport'
        ));
    }
}
