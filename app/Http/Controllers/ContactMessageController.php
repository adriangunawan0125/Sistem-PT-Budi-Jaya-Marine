<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Models\AdminNotification;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $messages = $query->latest()->get();

        return view('admin_transport.contact.index', compact('messages'));
    }
public function show($id)
{
    // tandai notif contact sebagai dibaca
    AdminNotification::where('type', 'contact')
        ->where('data_id', $id)
        ->update(['is_read' => 1]);

    $message = ContactMessage::findOrFail($id);
    return view('admin_transport.contact.show', compact('message'));
}


    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return redirect()->route('contact.index')
            ->with('success', 'Pesan berhasil dihapus');
    }
}

