<?php

namespace App\Controllers;

use App\Models\LayananModel;
use CodeIgniter\Controller;

class LayananController extends Controller
{
    public function index()
    {
        return view('layanan'); // Nama file view HTML kamu
    }

    public function simpan()
    {
        $model = new LayananModel();

        $layanan = $this->request->getPost('layanan');
        $layananLain = $this->request->getPost('layanan_lain');

        $data = [
            'layanan' => $layanan ?: $layananLain,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $model->insert($data);

        return redirect()->to('/antrian')->with('success', 'Layanan berhasil disimpan.');
    }
}
