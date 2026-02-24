<?php

namespace App\Http\Controllers\owner_transport;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
    

class AkunController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->role) {
        $query->where('role', $request->role);
    }

    $users = $query->orderBy('id','desc')->get();

    return view('owner_transport.akun.index', compact('users'));
}

    public function create()
    {
        return view('owner_transport.akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('akun.index')
            ->with('success','Akun berhasil dibuat');
    }

    public function edit(User $akun)
    {
        return view('owner_transport.akun.edit', compact('akun'));
    }

    public function update(Request $request, User $akun)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$akun->id,
            'role' => 'required'
        ]);

        $akun->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if($request->password){
            $akun->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('akun.index')
            ->with('success','Akun berhasil diupdate');
    }

    public function destroy(User $akun)
    {
        if($akun->id == auth()->id()){
            return back()->with('error','Tidak bisa hapus akun sendiri');
        }

        $akun->delete();

        return back()->with('success','Akun berhasil dihapus');
    }


public function resetPassword(Request $request, User $akun)
{
    $request->validate([
        'password' => 'required|min:6'
    ]);

    $akun->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with('success', 'Password berhasil direset');
}
}