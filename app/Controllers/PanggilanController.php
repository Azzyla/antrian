<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AntrianModel;
use CodeIgniter\I18n\Time;

class PanggilanController extends BaseController
{
    protected $antrianModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
    }

    // Halaman utama pemanggilan antrian
    public function index()
    {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');

        $belumDipanggil = $this->antrianModel
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $sudahDipanggil = $this->antrianModel
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->whereIn('status', ['dipanggil', 'dilayani', 'selesai'])
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return view('panggilan', [
            'belumDipanggil' => $belumDipanggil,
            'sudahDipanggil' => $sudahDipanggil,
        ]);
    }

    // Ubah status antrian menjadi 'dipanggil'
    public function panggil($id)
    {
        // Validasi data jika perlu
        $data = $this->antrianModel->find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $this->antrianModel->update($id, [
            'status' => 'dipanggil',
        ]);

        return redirect()->to('/panggilan');
    }

    // Mulai layanan - simpan waktu mulai & id_cs_plt dari session
    public function mulaiLayanan($id)
    {
        $data = $this->antrianModel->find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $updateData = [
            'status' => 'dilayani',
            'waktu_mulai' => Time::now('Asia/Jakarta'),
            'id_cs_plt' => session()->get('id_user') // Ambil ID CS dari session
        ];

        // Optional: jika created_at kosong, isi dengan sekarang
        if (empty($data['created_at'])) {
            $updateData['created_at'] = Time::now('Asia/Jakarta');
        }

        $this->antrianModel->update($id, $updateData);

        return redirect()->to('/panggilan');
    }

    // Selesaikan layanan - simpan waktu selesai
    public function selesaiLayanan($id)
    {
        $data = $this->antrianModel->find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $this->antrianModel->update($id, [
            'status' => 'selesai',
            'waktu_selesai' => Time::now('Asia/Jakarta')
        ]);

        return redirect()->to('/panggilan');
    }

    // Fungsi untuk tombol 'Panggil Ulang' (jika ada)
    public function ulang($id)
    {
        // Tidak perlu update status, hanya redirect (audio sudah ditangani oleh view)
        return redirect()->to('/panggilan');
    }
}
