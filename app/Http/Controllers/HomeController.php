<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.index');
    }
    public function about()
    {
        return view('user.about');
    }
    public function contact()
    {
        return view('user.contact'); 
    }
     public function transportService()
    {
        return view('user.transportservice');
    }
     public function marineService()
    {
        return view('user.marineservice');
    }
     public function marineSpareparts()
    {
        return view('user.marinespareparts');
    }
    public function transportgallery()
    {
        return view('user.gallerytransport');
    }
    public function servicegallery()
    {
        return view('user.galleryservice');
    }
    public function sparepartsgallery()
    {
        return view('user.galleryspareparts');
    }
    public function daftarMitra()
{
    return view('user.daftar-mitra');
}

}
