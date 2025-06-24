<?php

namespace App\Controllers;

use App\Models\AntrianModel;

class DashboardController extends BaseController
{
    protected $antrianModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
    }

    public function index()
    {
        // Mendapatkan jumlah antrian hari ini
        $jumlahAntrian = $this->antrianModel->getJumlahAntrianHariIni();

        // Mendapatkan antrian yang sedang dilayani
        $antrianSekarang = $this->antrianModel->getAntrianSedangDipanggil();

        // Jika tidak ada antrian aktif (misal: '-')
        if ($antrianSekarang === '-' || $antrianSekarang === null) {
            $antrianSelanjutnya = '-';
        } else {
            // Menentukan antrian selanjutnya
            $antrianSelanjutnya = (int)$antrianSekarang + 1;
        }

        // Mengambil data jumlah pengunjung per CS hari ini
        $layananHariIni = $this->antrianModel->getJumlahLayananHariIni();

        // Pastikan data $layananHariIni valid (setiap item harus memiliki cs_id dan total)
        if (!is_array($layananHariIni)) {
            $layananHariIni = [];
        }

        // Siapkan data untuk view
        $data = [
            'jumlahAntrian'      => $jumlahAntrian,
            'antrianSekarang'    => $antrianSekarang,
            'antrianSelanjutnya' => $antrianSelanjutnya,
            'layananHariIni'     => $layananHariIni,
        ];

        // Mengirim data ke view
        return view('dashboard', $data);
    }
}
