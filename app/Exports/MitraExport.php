<?php
namespace App\Exports;

use App\Models\Mitra;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MitraExport implements WithEvents
{
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {

                // 🔥 LOAD TEMPLATE PUNYA LU
                $templatePath = storage_path('app/templates/laporan_mitra.xlsx');
                $spreadsheet = IOFactory::load($templatePath);
                $sheet = $spreadsheet->getActiveSheet();

                // 🔥 ISI DATA (MISAL DATA MULAI ROW 4)
                $row = 4;
                foreach (Mitra::with('unit')->get() as $mitra) {
                    $sheet->setCellValue('A'.$row, $mitra->nama_mitra);
                    $sheet->setCellValue('B'.$row, $mitra->unit->nama_unit ?? '-');
                    $sheet->setCellValue('C'.$row, $mitra->alamat);
                    $sheet->setCellValue('D'.$row, $mitra->no_hp);
                    $sheet->setCellValue(
                        'E'.$row,
                        $mitra->created_at->format('d-m-Y')
                    );
                    $row++;
                }

                // 🔥 INI KUNCI NYA
                $event->writer->setSpreadsheet($spreadsheet);
            }
        ];
    }
}
