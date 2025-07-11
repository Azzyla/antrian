<?php

namespace App\Controllers;

use App\Models\AntrianModel;

class AntrianController extends BaseController
{
    public function index()
    {
        $model = new AntrianModel();
        $today = date('Y-m-d');

        $data['antrian'] = [
            'mahasiswa' => (clone $model)
                ->where('kategori', 'mahasiswa')
                ->where('DATE(waktu_antrian)', $today)
                ->countAllResults(),

            'umum' => (clone $model)
                ->where('kategori', 'umum')
                ->where('DATE(waktu_antrian)', $today)
                ->countAllResults(),

            'dosen' => (clone $model)
                ->where('kategori', 'dosen')
                ->where('DATE(waktu_antrian)', $today)
                ->countAllResults(),
        ];

        $data['kategoriAktif'] = session()->get('kategoriAktif');

        return view('antrian', $data);
    }

    public function ambil($kategori)
    {
        $allowed = ['mahasiswa', 'umum', 'dosen'];

        if (!in_array($kategori, $allowed)) {
            return redirect()->back()->with('error', 'Kategori tidak valid.');
        }

        $idPengunjung = session()->get('idPengunjung');
        $idLayanan = session()->get('id_Layanan'); // Ambil ID layanan dari session

        if (!$idPengunjung || !$idLayanan) {
            return redirect()->to('/pengunjung')->with('error', 'Silakan isi data pengunjung dan pilih layanan terlebih dahulu.');
        }

        $model = new AntrianModel();
        $today = date('Y-m-d');

        $nomorUrut = $model
            ->where('kategori', $kategori)
            ->where('DATE(waktu_antrian)', $today)
            ->countAllResults() + 1;

        // Mapping prefix berdasarkan kategori
        $prefix = match ($kategori) {
            'mahasiswa' => 'A',
            'umum' => 'B',
            'dosen' => 'C',
            default => 'X',
        };

        $kodeAntrian = $prefix . str_pad($nomorUrut, 2, '0', STR_PAD_LEFT);

        $data = [
            'kategori'       => $kategori,
            'waktu_antrian'  => date('Y-m-d H:i:s'),
            'nomor_antrian'  => $nomorUrut,
            'status'         => 'menunggu',
            'id_pengunjung'  => $idPengunjung,
            'id_layanan'     => $idLayanan, // Ini penting agar tidak NULL!
        ];

        if ($model->save($data)) {
            session()->set('kategoriAktif', $kategori);
            session()->setFlashdata('kategoriDiambil', $kategori);
            session()->setFlashdata('success', "Nomor antrian $kodeAntrian berhasil diambil untuk " . ucfirst($kategori));

            return redirect()->to('/antrian');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan nomor antrian.');
        }
    }
}
