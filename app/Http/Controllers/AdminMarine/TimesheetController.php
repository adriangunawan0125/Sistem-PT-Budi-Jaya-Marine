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
  public function index(Request $request)
{
    $query = Timesheet::with(['poMasuk','items'])->latest();

    // SEARCH
    if($request->search){
        $search = $request->search;

        $query->where(function($q) use ($search){
            $q->where('project','like',"%$search%")
              ->orWhere('manpower','like',"%$search%")
              ->orWhereHas('poMasuk', function($qq) use ($search){
                    $qq->where('mitra_marine','like',"%$search%")
                       ->orWhere('vessel','like',"%$search%")
                       ->orWhere('no_po_klien','like',"%$search%");
              });
        });
    }

    // FILTER BULAN
    if($request->month){
        $query->whereMonth('created_at', $request->month);
    }

    // FILTER TAHUN
    if($request->year){
        $query->whereYear('created_at', $request->year);
    }

    $timesheets = $query->paginate(10);

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

        if ($request->items) {

            foreach ($request->items as $item) {

                if (empty($item['work_date']) || empty($item['time_start']) || empty($item['time_end'])) {
                    continue;
                }

                $start = Carbon::parse($item['work_date'].' '.$item['time_start']);
                $end   = Carbon::parse($item['work_date'].' '.$item['time_end']);

                // handle lewat tengah malam
                if ($end->lt($start)) {
                    $end->addDay();
                }

                $hours = $start->diffInMinutes($end) / 60;

                $timesheet->items()->create([
                    'work_date'    => $item['work_date'],
                    'day'          => Carbon::parse($item['work_date'])->format('l'),
                    'time_start'   => $item['time_start'],
                    'time_end'     => $item['time_end'],
                    'hours'        => round($hours, 2),
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

        // hapus item lama
        $timesheet->items()->delete();

        if ($request->items) {

            foreach ($request->items as $item) {

                if (empty($item['work_date']) || empty($item['time_start']) || empty($item['time_end'])) {
                    continue;
                }

                $start = Carbon::parse($item['work_date'].' '.$item['time_start']);
                $end   = Carbon::parse($item['work_date'].' '.$item['time_end']);

                // handle lewat tengah malam
                if ($end->lt($start)) {
                    $end->addDay();
                }

                $hours = $start->diffInMinutes($end) / 60;

                $timesheet->items()->create([
                    'work_date'    => $item['work_date'],
                    'day'          => Carbon::parse($item['work_date'])->format('l'),
                    'time_start'   => $item['time_start'],
                    'time_end'     => $item['time_end'],
                    'hours'        => round($hours, 2),
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
