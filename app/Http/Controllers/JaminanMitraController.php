<?php

namespace App\Http\Controllers;

use App\Models\JaminanMitra;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JaminanMitraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = JaminanMitra::with('mitra')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('mitra', function ($m) use ($search) {
                        $m->where('nama_mitra', 'like', "%{$search}%")
                          ->orWhere('no_hp', 'like', "%{$search}%");
                    })
                    ->orWhere('jaminan', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
            'mitra_id' => 'required|exists:mitras,id',
            'jaminan'  => 'required|string',
            'gambar_1' => 'nullable|image|max:2048',
            'gambar_2' => 'nullable|image|max:2048',
            'gambar_3' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('mitra_id', 'jaminan');

        foreach (['gambar_1','gambar_2','gambar_3'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('jaminan', 'public');
            }
        }

        JaminanMitra::create($data);

        return redirect()
            ->route('jaminan_mitra.index')
            ->with('success', 'Data jaminan berhasil disimpan');
    }

    public function edit(JaminanMitra $jaminanMitra)
    {
        return view('admin_transport.jaminan_mitra.edit', compact('jaminanMitra'));
    }

    public function update(Request $request, JaminanMitra $jaminanMitra)
    {
        $request->validate([
            'jaminan'  => 'required|string',
            'gambar_1' => 'nullable|image|max:2048',
            'gambar_2' => 'nullable|image|max:2048',
            'gambar_3' => 'nullable|image|max:2048',
        ]);

        $data = [
            'jaminan' => $request->jaminan
        ];

        foreach (['gambar_1','gambar_2','gambar_3'] as $img) {
            if ($request->hasFile($img)) {

                // hapus file lama
                if ($jaminanMitra->$img) {
                    Storage::disk('public')->delete($jaminanMitra->$img);
                }

                // simpan file baru
                $data[$img] = $request->file($img)->store('jaminan', 'public');
            }
        }

        $jaminanMitra->update($data);

        return redirect()
            ->route('jaminan_mitra.index')
            ->with('success', 'Data jaminan berhasil diperbarui');
    }

    public function destroy(JaminanMitra $jaminanMitra)
    {
        foreach (['gambar_1', 'gambar_2', 'gambar_3'] as $img) {
            if ($jaminanMitra->$img) {
                Storage::disk('public')->delete($jaminanMitra->$img);
            }
        }

        $jaminanMitra->delete();

        return redirect()
            ->route('jaminan_mitra.index')
            ->with('success', 'Data jaminan berhasil dihapus');
    }
}
