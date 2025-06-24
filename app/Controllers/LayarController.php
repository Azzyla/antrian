<?php

// App/Controllers/LayarController.php
namespace App\Controllers;

use App\Models\AntrianModel;

class LayarController extends BaseController
{
    protected $antrianModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
    }

    public function index()
    {
        $today = date('Y-m-d');

        $mahasiswa = $this->antrianModel
            ->where('kategori', 'mahasiswa')
            ->where('DATE(created_at)', $today)
            ->whereIn('status', ['dipanggil', 'dilayani'])
            ->orderBy('created_at', 'DESC')
            ->first();

        $umum = $this->antrianModel
            ->where('kategori', 'umum')
            ->where('DATE(created_at)', $today)
            ->whereIn('status', ['dipanggil', 'dilayani'])
            ->orderBy('created_at', 'DESC')
            ->first();

        $dosen = $this->antrianModel
            ->where('kategori', 'dosen')
            ->where('DATE(created_at)', $today)
            ->whereIn('status', ['dipanggil', 'dilayani'])
            ->orderBy('created_at', 'DESC')
            ->first();

        return view('layar_antrian', [
            'mahasiswa' => $mahasiswa,
            'umum' => $umum,
            'dosen' => $dosen
        ]);
    }
}
