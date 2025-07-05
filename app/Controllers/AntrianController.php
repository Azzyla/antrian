<?php

namespace App\Controllers;

use App\Models\AntrianModel;

class AntrianController extends BaseController
{
    public function index()
    {
        $model = new AntrianModel();
        $today = date('Y-m-d');

        // Hitung jumlah antrian per kategori HARI INI
        $data['antrian'] = [
            'mahasiswa' => $model->where('kategori', 'mahasiswa')
                                 ->where('DATE(waktu_antrian)', $today)
                                 ->countAllResults(),

            'umum' => $model->where('kategori', 'umum')
                            ->where('DATE(waktu_antrian)', $today)
                            ->countAllResults(),

            'dosen' => $model->where('kategori', 'dosen')
                             ->where('DATE(waktu_antrian)', $today)
                             ->countAllResults(),
        ];

        // Ambil kategori dari session
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
        $nomorAntrian = $model->where('kategori', $kategori)
                              ->where('DATE(waktu_antrian)', $today)
                              ->countAllResults() + 1;

        // Simpan data antrian
        $data = [
            'kategori'       => $kategori,
            'waktu_antrian'  => date('Y-m-d H:i:s'),
            'nomor_antrian'  => $nomorAntrian,
            'status'         => 'menunggu',
            'id_pengunjung'  => $idPengunjung, // ✅ relasi ke pengunjung
        ];

        if ($model->save($data)) {
            // Set ulang kategori ke session (opsional)
            session()->set('kategoriAktif', $kategori);

            return redirect()->to('/antrian')
                ->with('success', "Nomor antrian $nomorAntrian berhasil diambil untuk " . ucfirst($kategori));
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan nomor antrian.');
        }
    }
}
