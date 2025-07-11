<?php

namespace App\Controllers;

use App\Models\LayananModel;
use CodeIgniter\Controller;

class LayananController extends Controller
{
    public function index()
    {
        return view('layanan'); // Tampilkan form view layanan
    }

    public function simpan()
    {
        $model = new LayananModel();

        $layanan = $this->request->getPost('layanan');
        $layananLain = $this->request->getPost('layanan_lain');

        // Pakai yang diisi user
        $namaLayanan = $layanan ?: $layananLain;

      

        // Cari apakah layanan sudah ada
        #$layananData = $model->where('layanan', $namaLayanan)->first();

       # if (!$layananData) {
            // Jika belum ada, simpan baru
            $model->insert([
                'layanan' => $namaLayanan,
                'created_at' => date('Y-m-d H:i:s')
            ]);

           $idLayanan = $model->getInsertID(); // ✅ AMAN ambil ID setelah insert
        #} else {
            // Jika sudah ada, ambil ID-nya
           # $idLayanan = $layananData['id_layanan']; // ✅ AMAN karena sudah dicek sebelumnya
        #}

        // Simpan ke session untuk digunakan saat ambil antrian
        session()->remove('id_Layanan');
        session()->set('id_Layanan', $idLayanan);
       
    

        return redirect()->to('/antrian')->with('success', 'Layanan berhasil dipilih.');
    }
}
