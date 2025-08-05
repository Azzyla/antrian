<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KepuasanModel;
use App\Models\AntrianModel;
use App\Models\PengunjungModel;
use App\Models\CsPltModel;

class KepuasanController extends BaseController
{
    public function index()
    {
        return view('kepuasan');
    }

    public function simpan()
    {
        $nomorAntrian = trim($this->request->getPost('nomor_antrian'));
        $penilaian    = (int) $this->request->getPost('penilaian');
        $saran        = htmlentities(trim($this->request->getPost('saran')));

        // Validasi input
        if (empty($nomorAntrian) || $penilaian < 1 || $penilaian > 5) {
            return redirect()->back()->withInput()->with('error', 'Nomor antrian dan penilaian wajib diisi dengan benar.');
        }

        $antrianModel     = new AntrianModel();
        $csPltModel       = new CsPltModel();
        $pengunjungModel  = new PengunjungModel();
        $kepuasanModel    = new KepuasanModel();

        // Cari data antrian berdasarkan nomor antrian dan status selesai
        $antrian = $antrianModel
            ->where('nomor_antrian', $nomorAntrian)
            ->where('status', 'selesai')
            ->orderBy('id', 'desc')
            ->first();

        if (!$antrian) {
            return redirect()->back()->withInput()->with('error', 'Nomor antrian tidak valid atau belum selesai dilayani.');
        }

        // Ambil data pengunjung jika ada
        $pengunjung = $antrian['id_pengunjung'] ? $pengunjungModel->find($antrian['id_pengunjung']) : null;

        // Ambil nama CS dari tabel cs_plt
        $cs = $csPltModel->find($antrian['id_cs_plt']);

        if (!$cs) {
            return redirect()->back()->withInput()->with('error', 'Data CS tidak ditemukan.');
        }

        // Simpan ke tabel kepuasan_layanan
        $kepuasanModel->insert([
            'id_antrian'     => $antrian['id'],
            'nim'            => $pengunjung['nim'] ?? null,
            'cs'             => $cs['nama'],
            'penilaian'      => $penilaian,
            'saran'          => $saran ?: null,
            'waktu_mulai'    => $antrian['waktu_mulai'],
            'waktu_selesai'  => $antrian['waktu_selesai'],
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/kepuasan')->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
