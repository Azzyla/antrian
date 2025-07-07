<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengunjungModel;

class PengunjungController extends BaseController
{
    public function index()
    {
        return view('pengunjung'); // Menampilkan halaman form pengunjung
    }

    public function submit()
    {
        $model = new PengunjungModel();

        // Ambil data dari form
        $pengguna = $this->request->getPost('pengguna');
        $prodi = $this->request->getPost('prodi');
        $createdAt = date('Y-m-d H:i:s');

        // Siapkan array data dasar
        $data = [
            'pengguna'   => $pengguna,
            'created_at' => $createdAt,
        ];

        // Tambah identitas berdasarkan kategori
        if ($pengguna === 'mahasiswa') {
            $data['nim'] = $this->request->getPost('nim');
            $data['prodi'] = $prodi;
        } elseif ($pengguna === 'dosen') {
            $data['nidn'] = $this->request->getPost('nidn');
            $data['prodi'] = $prodi;
        } elseif ($pengguna === 'umum') {
            $data['nik'] = $this->request->getPost('nik');
            // prodi tidak perlu disimpan
        }

        // Simpan data ke database
        $model->insert($data);
        $idPengunjung = $model->getInsertID();

        // Simpan ke session
        session()->set('kategoriAktif', $pengguna);
        session()->set('idPengunjung', $idPengunjung);

        // Redirect ke halaman layanan
        return redirect()->to('/layanan')->with('success', 'Data berhasil disimpan.');
    }
}
