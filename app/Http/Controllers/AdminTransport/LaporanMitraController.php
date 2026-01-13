<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Exports\MitraExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanMitraController extends Controller
{
    /**
     * GET
     * Tampilkan laporan mitra
     */
    public function getIndex()
{
    $mitras = Mitra::with('unit')->get();
    $total = $mitras->count();

    return view(
        'admin_transport.kelola_mitra.laporan',
        compact('mitras', 'total')
    );
}


    /**
     * Export Excel
     */
public function exportExcel()
{
    // Load template
    $templatePath = storage_path('app/templates/laporan_mitra.xlsx');
    $spreadsheet = IOFactory::load($templatePath);
    $sheet = $spreadsheet->getActiveSheet();

    // DATA MULAI DI ROW 3
    $row = 3;
    $no  = 1;

    foreach (Mitra::with('unit')->get() as $mitra) {
        $sheet->setCellValue('A' . $row, $no); // No urut
        $sheet->setCellValue('B' . $row, $mitra->nama_mitra);
        $sheet->setCellValue('C' . $row, $mitra->unit->nama_unit ?? '-'); // No Polisi
        $sheet->setCellValue('D' . $row, $mitra->alamat);
        $sheet->setCellValue('E' . $row, $mitra->no_hp);

        $row++;
        $no++;
    }

    // Simpan sementara
    $fileName = 'laporan_daftar_mitra.xlsx';
    $tempPath = storage_path('app/temp/' . $fileName);

    if (!file_exists(storage_path('app/temp'))) {
        mkdir(storage_path('app/temp'), 0755, true);
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($tempPath);

    return response()->download($tempPath)->deleteFileAfterSend(true);
}

    /**
     * Export PDF
     */
    public function exportPdf()
    {
        $mitras = Mitra::with('unit')->get();
        $total = $mitras->count();

        $pdf = Pdf::loadView(
            'admin_transport.laporan_mitra.pdf',
            compact('mitras', 'total')
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan_daftar_mitra.pdf');
    }
}
