<?php

namespace App\Models;

use CodeIgniter\Model;

class LayarModel extends Model
{
    protected $table = 'antrian_aktif';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kategori', 'nomor_antrian', 'cs', 'waktu_panggil'];

    // Fungsi untuk ambil berdasarkan kategori terbaru
    public function getByKategori($kategori)
    {
        return $this->where('kategori', $kategori)
                    ->orderBy('waktu_panggil', 'DESC')
                    ->first();
    }

    // Fungsi untuk ambil berdasarkan kategori dan CS tertentu
    public function getByKategoriDanCS($kategori, $cs)
    {
        return $this->where(['kategori' => $kategori, 'cs' => $cs])
                    ->orderBy('waktu_panggil', 'DESC')
                    ->first();
    }
}
