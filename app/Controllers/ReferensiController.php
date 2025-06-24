<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ReferensiController extends Controller
{
    public function index()
    {
        return view('referensi');
    }
    
    public function show()
    {
        return view('referensi_audio');
    }
}
