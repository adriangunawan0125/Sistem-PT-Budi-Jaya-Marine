<?php

namespace App\Http\Controllers\AdminTransport;

use App\Http\Controllers\Controller;
use App\Models\CalonMitra;
use App\Models\Mitra;
use App\Models\JaminanMitra;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;

class CalonMitraController extends Controller
{
    /**
     * List calon mitra
     */
    public function index()
    {
        $calonmitra = CalonMitra::latest()->get();
        return view('admin_transport.calon-mitra.index', compact('calonmitra'));
    }

    /**
     * Detail calon mitra
     */
    public function show($id)
    {
        AdminNotification::where('type', 'calon_mitra')
            ->where('data_id', $id)
            ->update(['is_read' => 1]);

        $calonmitra = CalonMitra::findOrFail($id);
        return view('admin_transport.calon-mitra.show', compact('calonmitra'));
    }

    /**
     * SETUJUI calon mitra
     */
    public function approve($id)
    {
        $calon = CalonMitra::findOrFail($id);

        if ($calon->is_checked == 1) {
            return back()->with('error', 'Calon mitra sudah diproses');
        }

        DB::transaction(function () use ($calon) {

            $mitra = Mitra::create([
                'nama_mitra' => $calon->nama,
                'alamat'     => $calon->alamat,
                'no_hp'      => $calon->no_handphone,
                'status'     => 'aktif',
            ]);

            JaminanMitra::create([
                'mitra_id' => $mitra->id,
                'jaminan'  => $calon->jaminan,
                'gambar_1' => $calon->gambar_1,
                'gambar_2' => $calon->gambar_2,
                'gambar_3' => $calon->gambar_3,
            ]);

            $calon->update([
                'is_checked' => 1
            ]);
        });

        return redirect()->route('admin.calon-mitra.index')
            ->with('success', 'Calon mitra berhasil disetujui');
    }

    /**
     * Hapus calon mitra
     */
    public function destroy($id)
    {
        $calon = CalonMitra::findOrFail($id);
        $calon->delete();

        return back()->with('success', 'Data calon mitra berhasil dihapus');
    }
}
