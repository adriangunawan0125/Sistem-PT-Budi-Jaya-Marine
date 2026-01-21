<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use App\Models\CalonMitra;

class CalonMitraController extends Controller
{
    /**
     * Tampilkan daftar calon mitra
     */
    public function index()
    {
        $calonmitra = CalonMitra::latest()->get();
        return view('calon-mitra.index', compact('calonmitra'));
    }

    /**
     * Hapus calon mitra
     */
    public function destroy($id)
    {
        $mitra = CalonMitra::findOrFail($id);
        $mitra->delete();

        return back()->with('success', 'Data calon mitra berhasil dihapus');
    }
}
