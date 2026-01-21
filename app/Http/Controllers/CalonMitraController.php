<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalonMitra;

class CalonMitraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        CalonMitra::create([
            'nama' => $request->nama,
            'no_handphone' => $request->no_handphone,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil, silakan tunggu konfirmasi admin.');
    }
}
