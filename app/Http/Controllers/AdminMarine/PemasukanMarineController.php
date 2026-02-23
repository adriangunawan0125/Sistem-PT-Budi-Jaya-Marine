<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use App\Models\PemasukanMarine;
use App\Models\PoMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PemasukanMarineController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
 public function index(Request $request)
{
    $query = PemasukanMarine::with('poMasuk');

    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            // Jika format tanggal valid (YYYY-MM-DD)
            if (strtotime($search)) {
                $q->orWhereDate('tanggal', $search);
            }

            // Relasi PO
            $q->orWhereHas('poMasuk', function ($po) use ($search) {
                $po->where('no_po_klien', 'like', "%{$search}%")
                   ->orWhere('mitra_marine', 'like', "%{$search}%")
                   ->orWhere('vessel', 'like', "%{$search}%");
            });

        });
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */
    if ($request->filled('month')) {
        $query->whereMonth('tanggal', $request->month);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */
    if ($request->filled('year')) {
        $query->whereYear('tanggal', $request->year);
    }

    $pemasukan = $query->latest()->paginate(10);

    return view(
        'admin_marine.pemasukan_marine.index',
        compact('pemasukan')
    );
}
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $poMasuk = PoMasuk::orderBy('tanggal_po','desc')->get();

        return view('admin_marine.pemasukan_marine.create', compact('poMasuk'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
    'po_masuk_id'   => 'required|exists:po_masuk,id',
    'tanggal'       => 'required|date',
    'nama_pengirim' => 'required|string|max:255',
    'metode'        => 'required|string|max:100',
    'keterangan'    => 'nullable|string',
    'nominal'       => 'required|numeric|min:0',
    'bukti'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
]);

$data = $request->only([
    'po_masuk_id',
    'tanggal',
    'nama_pengirim',
    'metode',
    'keterangan',
    'nominal', // tambahkan kolom nominal
]);

if ($request->hasFile('bukti')) {
    $data['bukti'] = $request->file('bukti')
        ->store('pemasukan_marine', 'public');
}

PemasukanMarine::create($data);

            DB::commit();

            return redirect()
                ->route('pemasukan-marine.index')
                ->with('success','Pemasukan berhasil disimpan');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(PemasukanMarine $pemasukanMarine)
    {
        return view('admin_marine.pemasukan_marine.show', compact('pemasukanMarine'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(PemasukanMarine $pemasukanMarine)
    {
        $poMasuk = PoMasuk::orderBy('tanggal_po','desc')->get();

        return view('admin_marine.pemasukan_marine.edit',
            compact('pemasukanMarine','poMasuk'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, PemasukanMarine $pemasukanMarine)
    {
        DB::beginTransaction();

        try {

            // VALIDASI
            $request->validate([
                'po_masuk_id'   => 'required|exists:po_masuk,id',
                'tanggal'       => 'required|date',
                'nama_pengirim' => 'required|string|max:255',
                'metode'        => 'required|string|max:100',
                'keterangan'    => 'nullable|string',
                'nominal'       => 'required|numeric|min:0', // Tambahkan validasi nominal
                'bukti'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // AMBIL DATA YANG DIUPDATE
            $data = $request->only([
                'po_masuk_id',
                'tanggal',
                'nama_pengirim',
                'metode',
                'keterangan',
                'nominal', // Tambahkan kolom nominal
            ]);

            // HANDLE UPLOAD BUKTI
            if ($request->hasFile('bukti')) {
                if ($pemasukanMarine->bukti) {
                    Storage::disk('public')->delete($pemasukanMarine->bukti);
                }
                $data['bukti'] = $request->file('bukti')
                    ->store('pemasukan_marine', 'public');
            }

// UPDATE DATA
$pemasukanMarine->update($data);

            DB::commit();

            return redirect()
                ->route('pemasukan-marine.index')
                ->with('success','Data berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(PemasukanMarine $pemasukanMarine)
    {
        DB::beginTransaction();

        try {

            if ($pemasukanMarine->bukti) {
                Storage::disk('public')
                    ->delete($pemasukanMarine->bukti);
            }

            $pemasukanMarine->delete();

            DB::commit();

            return back()->with('success','Data berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }
}