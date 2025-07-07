<?php

namespace App\Controllers;

use App\Models\AntrianModel;

class AntrianController extends BaseController
{
    public function index()
    {
        $model = new AntrianModel();
        $today = date('Y-m-d');

        // Hitung jumlah antrian per kategori HARI INI (gunakan clone() untuk mencegah overwrite)
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

        // Validasi kategori
        if (!in_array($kategori, $allowed)) {
            return redirect()->back()->with('error', 'Kategori tidak valid.');
        }

        // Ambil id_pengunjung dari session
        $idPengunjung = session()->get('idPengunjung');
        if (!$idPengunjung) {
            return redirect()->to('/pengunjung')->with('error', 'Silakan isi data pengunjung terlebih dahulu.');
        }

        $model = new AntrianModel();
        $today = date('Y-m-d');

        // Hitung nomor antrian berdasarkan kategori & hari ini
        $nomorAntrian = $model
            ->where('kategori', $kategori)
            ->where('DATE(waktu_antrian)', $today)
            ->countAllResults() + 1;

        // Simpan data antrian
        $data = [
            'kategori'       => $kategori,
            'waktu_antrian'  => date('Y-m-d H:i:s'),
            'nomor_antrian'  => $nomorAntrian,
            'status'         => 'menunggu',
            'id_pengunjung'  => $idPengunjung,
        ];

        if ($model->save($data)) {
            // Set kategori aktif ke session (tetap boleh ambil lagi jika diperlukan)
            session()->set('kategoriAktif', $kategori);

            // Flashdata untuk menonaktifkan tombol (hanya 1x ambil)
            session()->setFlashdata('kategoriDiambil', $kategori);

            return redirect()->to('/antrian')
                ->with('success', "Nomor antrian $nomorAntrian berhasil diambil untuk " . ucfirst($kategori));
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan nomor antrian.');
        }
    }
}
