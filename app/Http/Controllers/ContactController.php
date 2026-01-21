<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // simpan dari user
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'no_telepon' => 'nullable|string|max:20',
            'pesan' => 'required|string',
        ]);

        ContactMessage::create($request->all());

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}
