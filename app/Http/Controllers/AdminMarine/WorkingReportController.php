<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingReport;
use App\Models\WorkingReportItem;
use App\Models\WorkingReportImage;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkingReportController extends Controller
{
    /* ================= INDEX ================= */
public function index(Request $request)
{
    $query = WorkingReport::with('poMasuk')->latest();

    // SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('project', 'like', '%' . $request->search . '%')
              ->orWhereHas('poMasuk', function ($po) use ($request) {
                  $po->where('mitra_marine', 'like', '%' . $request->search . '%')
                     ->orWhere('vessel', 'like', '%' . $request->search . '%')
                     ->orWhere('no_po_klien', 'like', '%' . $request->search . '%');
              });
        });
    }

    // FILTER BULAN
    if ($request->month) {
        $query->whereMonth('created_at', $request->month);
    }

    // FILTER TAHUN
    if ($request->year) {
        $query->whereYear('created_at', $request->year);
    }

    $workingReports = $query->paginate(10);

    return view('admin_marine.working_report.index',
        compact('workingReports'));
}


    /* ================= CREATE ================= */
    public function create($poMasukId)
    {
        $poMasuk = PoMasuk::findOrFail($poMasukId);

        return view('admin_marine.working_report.create',
            compact('poMasuk'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_masuk_id' => 'required|exists:po_masuk,id',
                'project'     => 'required|string',
                'periode'     => 'required|string',
            ]);

            $report = WorkingReport::create([
                'po_masuk_id' => $request->po_masuk_id,
                'project'     => $request->project,
                'place'       => $request->place,
                'periode'     => $request->periode,
            ]);

            if ($request->items) {

                foreach ($request->items as $item) {

                    if (empty($item['work_date'])) continue;

                    $newItem = $report->items()->create([
                        'work_date' => $item['work_date'],
                        'detail'    => $item['detail'] ?? null,
                    ]);

                    /* ---------- MULTIPLE IMAGE ---------- */
                    if (!empty($item['images'])) {

                        foreach ($item['images'] as $image) {

                            if ($image) {

                                $path = $image->store(
                                    'working_reports',
                                    'public'
                                );

                                $newItem->images()->create([
                                    'image_path' => $path
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $report->po_masuk_id)
                ->with('success', 'Working Report berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= SHOW ================= */
    public function show(WorkingReport $workingReport)
    {
        $workingReport->load('poMasuk','items.images');

        return view('admin_marine.working_report.show',
            compact('workingReport'));
    }

    /* ================= EDIT ================= */
    public function edit(WorkingReport $workingReport)
    {
        $workingReport->load('poMasuk','items.images');

        return view('admin_marine.working_report.edit',
            compact('workingReport'));
    }

    /* ================= UPDATE ================= */
   public function update(Request $request, WorkingReport $workingReport)
{
    DB::beginTransaction();

    try {

        $workingReport->update([
            'project' => $request->project,
            'place'   => $request->place,
            'periode' => $request->periode,
        ]);

        /* ================= DELETE SELECTED IMAGES ================= */
        if ($request->delete_images) {

            foreach ($request->delete_images as $imageId) {

                $image = WorkingReportImage::find($imageId);

                if ($image) {
                    \Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        /* ================= PROCESS ITEMS ================= */
        if ($request->items) {

            foreach ($request->items as $itemData) {

                // UPDATE ITEM jika ada ID
                if (!empty($itemData['id'])) {

                    $item = WorkingReportItem::find($itemData['id']);

                    if ($item) {
                        $item->update([
                            'work_date' => $itemData['work_date'],
                            'detail'    => $itemData['detail'] ?? null,
                        ]);
                    }

                } else {

                    // CREATE ITEM BARU
                    $item = $workingReport->items()->create([
                        'work_date' => $itemData['work_date'],
                        'detail'    => $itemData['detail'] ?? null,
                    ]);
                }

                /* ================= TAMBAH GAMBAR BARU ================= */
                if (!empty($itemData['images'])) {

                    foreach ($itemData['images'] as $image) {

                        if ($image) {

                            $path = $image->store(
                                'working_reports',
                                'public'
                            );

                            $item->images()->create([
                                'image_path' => $path
                            ]);
                        }
                    }
                }
            }
        }

        DB::commit();

        return redirect()
            ->route('working-report.show', $workingReport->id)
            ->with('success','Working Report berhasil diupdate');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}

    /* ================= DESTROY ================= */
    public function destroy(WorkingReport $workingReport)
    {
        foreach ($workingReport->items as $item) {

            foreach ($item->images as $img) {
                \Storage::disk('public')->delete($img->image_path);
            }
        }

        $workingReport->delete();

        return redirect()
            ->route('working-report.index')
            ->with('success','Working Report berhasil dihapus');
    }

    /* ================= PRINT ================= */
    public function print(WorkingReport $workingReport)
    {
        $workingReport->load('poMasuk','items.images');

        $pdf = Pdf::loadView(
            'admin_marine.working_report.print',
            compact('workingReport')
        )->setPaper('A4','portrait');

        return $pdf->stream('working-report-'.$workingReport->id.'.pdf');
    }
}
