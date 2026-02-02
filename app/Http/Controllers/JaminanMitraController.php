<?php

namespace App\Http\Controllers;
use App\Models\JaminanMitra;
use Illuminate\Http\Request;
use App\Models\Mitra;
use Illuminate\Support\Facades\Storage;

class JaminanMitraController extends Controller
{
   public function index(Request $request)
{
    $search = $request->search;

    $data = JaminanMitra::with('mitra')
        ->when($search, function ($query) use ($search) {
            $query->whereHas('mitra', function ($q) use ($search) {
                $q->where('nama_mitra', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->orWhere('jaminan', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString(); // biar search kepake pas pindah halaman

    return view('admin_transport.jaminan_mitra.index', compact('data', 'search'));
}


    public function create()
    {
        $mitras = Mitra::all();
        return view('admin_transport.jaminan_mitra.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mitra_id' => 'required',
            'jaminan' => 'required|string',
            'gambar_1' => 'image|nullable',
            'gambar_2' => 'image|nullable',
            'gambar_3' => 'image|nullable',
        ]);

        $data = $request->only('mitra_id', 'jaminan');

        foreach (['gambar_1','gambar_2','gambar_3'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('jaminan', 'public');
            }
        }

        JaminanMitra::create($data);

        return redirect()->route('jaminan_mitra.index')->with('success','Data jaminan disimpan');
    }
    public function destroy(JaminanMitra $jaminanMitra)
{
    // hapus file gambar kalau ada
    foreach (['gambar_1', 'gambar_2', 'gambar_3'] as $img) {
        if ($jaminanMitra->$img) {
            Storage::disk('public')->delete($jaminanMitra->$img);
        }
    }

    // hapus data
    $jaminanMitra->delete();

    return redirect()->route('jaminan_mitra.index')
                     ->with('success', 'Data jaminan berhasil dihapus');
}
}
