<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalonMitra;
  use App\Models\AdminNotification;

class CalonMitraController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'no_handphone' => 'required|string|max:20',
        'alamat' => 'required|string',
    ]);

    $calonMitra = CalonMitra::create([
        'nama' => $request->nama,
        'no_handphone' => $request->no_handphone,
        'alamat' => $request->alamat,
        'is_checked' => 0
    ]);

    AdminNotification::create([
        'type' => 'calon_mitra',
        'data_id' => $calonMitra->id,
        'message' => 'Calon mitra baru mendaftar'
    ]);

    return back()->with('success', 'Pendaftaran berhasil, silakan tunggu konfirmasi admin.');
}

}
