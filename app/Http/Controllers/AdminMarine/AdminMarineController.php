<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminMarineController extends Controller
{
    public function dashboard()
    {
        return view('admin_marine.dashboard');
    }
}

