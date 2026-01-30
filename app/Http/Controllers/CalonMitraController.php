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
            'nama'          => 'required|string|max:255',
            'no_handphone'  => 'required|string|max:20',
            'alamat'        => 'required|string',
            'jaminan'       => 'required|string',

            'gambar_1' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // upload gambar
        $gambar1 = $request->file('gambar_1')->store('jaminan', 'public');
        $gambar2 = $request->file('gambar_2')
            ? $request->file('gambar_2')->store('jaminan', 'public')
            : null;

        $gambar3 = $request->file('gambar_3')
            ? $request->file('gambar_3')->store('jaminan', 'public')
            : null;

        $calonMitra = CalonMitra::create([
            'nama'          => $request->nama,
            'no_handphone'  => $request->no_handphone,
            'alamat'        => $request->alamat,
            'jaminan'       => $request->jaminan,
            'gambar_1'      => $gambar1,
            'gambar_2'      => $gambar2,
            'gambar_3'      => $gambar3,
            'is_checked'    => 0,
        ]);

        // notif ke admin
        AdminNotification::create([
            'type'    => 'calon_mitra',
            'data_id'=> $calonMitra->id,
            'message'=> 'Calon mitra baru mendaftar'
        ]);

        return back()->with('success', 'Pendaftaran berhasil, silakan tunggu konfirmasi admin.');
    }
}
