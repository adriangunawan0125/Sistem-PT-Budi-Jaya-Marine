<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTransportController extends Controller
{
    public function dashboard()
    {
        return view('admin_transport.dashboard');
    }
}

