<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class ContactController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'no_telepon' => 'nullable|string|max:20',
        'pesan' => 'required|string',
    ]);

    $contact = ContactMessage::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'no_telepon' => $request->no_telepon,
        'pesan' => $request->pesan,
        'is_read' => 0
    ]);

    AdminNotification::create([
        'type' => 'contact',
        'data_id' => $contact->id,
        'message' => 'Pesan baru dari Hubungi Kami'
    ]);

    return back()->with('success', 'Pesan berhasil dikirim.');
}

}
