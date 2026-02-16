<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\TimesheetItem;
use App\Models\PoMasuk;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    /* ================= INDEX ================= */
   public function index()
{
    $timesheets = Timesheet::with(['poMasuk','items'])
                    ->latest()
                    ->get();

    return view('admin_marine.timesheet.index', compact('timesheets'));
}


    /* ================= CREATE ================= */
    public function create($poMasukId)
    {
        $poMasuk = PoMasuk::findOrFail($poMasukId);

        return view('admin_marine.timesheet.create', compact('poMasuk'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_masuk_id' => 'required|exists:po_masuk,id',
                'project'     => 'required',
                'manpower'    => 'required',
            ]);

            $timesheet = Timesheet::create([
                'po_masuk_id' => $request->po_masuk_id,
                'project'     => $request->project,
                'manpower'    => $request->manpower,
                'status'      => 'draft',
            ]);

            /* SAVE ITEMS */
            if ($request->items) {
                foreach ($request->items as $item) {

                    if (empty($item['work_date'])) continue;

                    $start = Carbon::parse($item['time_start']);
                    $end   = Carbon::parse($item['time_end']);
                    $hours = $start->floatDiffInHours($end);

                    $timesheet->items()->create([
                        'work_date'    => $item['work_date'],
                        'day'          => Carbon::parse($item['work_date'])->format('l'),
                        'time_start'   => $item['time_start'],
                        'time_end'     => $item['time_end'],
                        'hours'        => $hours,
                        'manpower'     => $item['manpower'] ?? null,
                        'kind_of_work' => $item['kind_of_work'],
                    ]);
                }
            }

            $timesheet->recalculateTotalHours();

            DB::commit();

            return redirect()
                ->route('po-masuk.show', $timesheet->po_masuk_id)
                ->with('success', 'Timesheet berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= SHOW ================= */
    public function show(Timesheet $timesheet)
    {
        $timesheet->load('poMasuk', 'items');

        return view('admin_marine.timesheet.show', compact('timesheet'));
    }

    /* ================= EDIT ================= */
    public function edit(Timesheet $timesheet)
    {
        $timesheet->load('poMasuk', 'items');

        return view('admin_marine.timesheet.edit', compact('timesheet'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, Timesheet $timesheet)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'project'  => 'required',
                'manpower' => 'required',
            ]);

            $timesheet->update([
                'project'  => $request->project,
                'manpower' => $request->manpower,
            ]);

            /* DELETE OLD ITEMS */
            $timesheet->items()->delete();

            /* SAVE NEW ITEMS */
            if ($request->items) {
                foreach ($request->items as $item) {

                    if (empty($item['work_date'])) continue;

                    $start = Carbon::parse($item['time_start']);
                    $end   = Carbon::parse($item['time_end']);
                    $hours = $start->floatDiffInHours($end);

                    $timesheet->items()->create([
                        'work_date'    => $item['work_date'],
                        'day'          => Carbon::parse($item['work_date'])->format('l'),
                        'time_start'   => $item['time_start'],
                        'time_end'     => $item['time_end'],
                        'hours'        => $hours,
                        'manpower'     => $item['manpower'] ?? null,
                        'kind_of_work' => $item['kind_of_work'],
                    ]);
                }
            }

            $timesheet->recalculateTotalHours();

            DB::commit();

            return redirect()
                ->route('timesheet.show', $timesheet->id)
                ->with('success', 'Timesheet berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= DESTROY ================= */
    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();

        return redirect()
            ->route('timesheet.index')
            ->with('success', 'Timesheet berhasil dihapus');
    }

    /* ================= PRINT ================= */
    public function print(Timesheet $timesheet)
    {
        $timesheet->load('poMasuk', 'items');

        $pdf = Pdf::loadView(
            'admin_marine.timesheet.print',
            compact('timesheet')
        )->setPaper('A4', 'portrait');

        return $pdf->stream('timesheet-'.$timesheet->id.'.pdf');
    }
}
