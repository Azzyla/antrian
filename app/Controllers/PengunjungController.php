<?php

namespace App\Controllers;

use App\Models\PengunjungModel;
use App\Models\AntrianModel;
use CodeIgniter\Controller;

class PengunjungController extends Controller
{
    public function index()
    {
        return view('pengunjung'); // View pengunjung ada di app/Views/pengunjung.php
    }

    public function submit()
{
    $model = new PengunjungModel();

    $pengguna = $this->request->getPost('pengguna');
    $prodi = $this->request->getPost('prodi');
    $createdAt = date('Y-m-d H:i:s');

    $data = [
        'pengguna'   => $pengguna,
        'prodi'      => $prodi,
        'created_at' => $createdAt,
    ];

    // Tambah identitas
    if ($pengguna === 'mahasiswa') {
        $data['nim'] = $this->request->getPost('nim');
    } elseif ($pengguna === 'dosen') {
        $data['nidn'] = $this->request->getPost('nidn');
    } elseif ($pengguna === 'umum') {
        $data['nik'] = $this->request->getPost('nik');
    }

    // Simpan dan dapatkan ID
    $model->insert($data);
    $idPengunjung = $model->getInsertID();

    // Simpan ke session untuk digunakan di antrian
    session()->set('kategoriAktif', $pengguna);
    session()->set('idPengunjung', $idPengunjung); // ✅ simpan ID pengunjung

    return redirect()->to('/layanan')->with('success', 'Data berhasil disimpan.');
}
}