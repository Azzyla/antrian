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
        'id_layanan',
        'nomor_antrian',
        'kode_antrian',
        'waktu_antrian',
        'created_at',
        'updated_at',
        'id_pengunjung',
        'id_cs_plt',
        'feedback',
        'status',
        'waktu_mulai',
        'waktu_selesai'
    ];

    public function getJumlahAntrianHariIni()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
    }

    public function getAntrianSedangDipanggil()
    {
        $antrian = $this->orderBy('waktu_antrian', 'DESC')->first();
        return $antrian ? $antrian['nomor_antrian'] : '-';
    }

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

    public function getJumlahLayananHariIni()
    {
        return $this->select('kategori, COUNT(*) as total')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->groupBy('kategori')
            ->findAll();
    }

public function getRekap($startDate, $endDate, $status)
{
    $builder = $this->db->table($this->table);
    $builder->select('pengunjung.pengguna, COUNT(*) as jumlah');
    $builder->join('pengunjung', 'pengunjung.id = antrian.id_pengunjung', 'left');
    $builder->where('DATE(antrian.created_at) >=', $startDate);
    $builder->where('DATE(antrian.created_at) <=', $endDate);

    if ($status !== 'Semua') {
        $builder->where('pengunjung.pengguna', $status);
    }

    $builder->groupBy('pengunjung.pengguna');
    $builder->orderBy('pengunjung.pengguna', 'ASC');

    return $builder->get()->getResult();
}

    public function getAntrianHariIni()
    {
        $builder = $this->db->table($this->table);
        $builder->select('antrian.*, layanan.layanan AS nama_layanan');
        $builder->join('layanan', 'antrian.id_layanan = layanan.id_layanan', 'left');
        $builder->where('DATE(antrian.created_at)', date('Y-m-d'));
        $builder->orderBy("FIELD(antrian.status, 'menunggu', 'dipanggil', 'dilayani', 'selesai')", '', false);
        $builder->orderBy('antrian.waktu_antrian', 'ASC');
        return $builder->get()->getResult();
    }

    // Tambahan: Ambil semua antrian lengkap dengan nama layanan
    public function getAntrianWithLayanan()
    {
        return $this->db->table($this->table)
            ->select('antrian.*, layanan.layanan AS nama_layanan')
            ->join('layanan', 'antrian.id_layanan = layanan.id_layanan', 'left')
            ->get()->getResult();
    }
}
