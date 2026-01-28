<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use App\Models\CalonMitra;
use App\Models\AdminNotification;

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
    
    public function show($id)
{
    // tandai notif calon mitra sebagai dibaca
    AdminNotification::where('type', 'calon_mitra')
        ->where('data_id', $id)
        ->update(['is_read' => 1]);

    $calonmitra = CalonMitra::findOrFail($id);
    return view('calon-mitra.show', compact('calonmitra'));
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
