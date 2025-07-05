<?php

namespace App\Models;

use CodeIgniter\Model;

class AntrianModel extends Model
{
    protected $table      = 'antrian';
    protected $primaryKey = 'id';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'kategori',
        'tujuan_layanan',
        'waktu_antrian',
        'nomor_antrian',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'created_at',
        'id_pengunjung', // ✅ Ditambahkan agar bisa menyimpan relasi ke tabel pengunjung
    ];

    // ✅ Hitung jumlah antrian HARI INI
    public function getJumlahAntrianHariIni()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
    }

    // ✅ Ambil antrian terakhir secara global (berdasarkan waktu antrian)
    public function getAntrianSedangDipanggil()
    {
        $antrian = $this->orderBy('waktu_antrian', 'DESC')->first();
        return $antrian ? $antrian['nomor_antrian'] : '-';
    }

    // ✅ Ambil antrian terakhir per kategori
    public function getLastDipanggilPerKategori()
    {
        $kategoriList = ['mahasiswa', 'dosen', 'umum'];
        $result = [];

        foreach ($kategoriList as $kategori) {
            $data = $this->where('kategori', $kategori)
                         ->orderBy('waktu_antrian', 'DESC')
                         ->first();
            $result[$kategori] = $data ?? null;
        }

        return $result;
    }

    // ✅ Hitung jumlah layanan hari ini per kategori
    public function getJumlahLayananHariIni()
    {
        return $this->select('kategori, COUNT(*) as total')
                    ->where('DATE(created_at)', date('Y-m-d'))
                    ->groupBy('kategori')
                    ->findAll();
    }

    // ✅ Rekap layanan berdasarkan tanggal dan kategori
    public function getRekap($startDate, $endDate, $status)
    {
        $builder = $this->db->table($this->table);
        $builder->select('kategori AS status_pengunjung, tujuan_layanan, COUNT(*) as jumlah');
        $builder->where('DATE(created_at) >=', $startDate);
        $builder->where('DATE(created_at) <=', $endDate);

        if ($status !== 'Semua') {
            $builder->where('kategori', $status);
        }

        $builder->groupBy(['kategori', 'tujuan_layanan']);
        return $builder->get()->getResultArray();
    }

    // ✅ Ambil semua antrian hari ini, urut berdasarkan status dan waktu
    public function getAntrianHariIni()
    {
        $builder = $this->builder();
        $builder->where('DATE(created_at)', date('Y-m-d'));
        $builder->orderBy("FIELD(status, 'menunggu', 'dipanggil', 'dilayani', 'selesai')", '', false);
        $builder->orderBy('waktu_antrian', 'ASC');

        return $builder->get()->getResultArray();
    }
}
