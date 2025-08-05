<?php

namespace App\Models;

use CodeIgniter\Model;

class KepuasanModel extends Model
{
    protected $table      = 'kepuasan_layanan';
    protected $primaryKey = 'id';

    // Kolom yang diizinkan untuk diisi
    protected $allowedFields = [
        'nim',
        'nomor_antrian',
        'cs',
        'penilaian',
        'durasi_pelayanan',
        'saran',
        'created_at'
    ];

    // Aktifkan otomatis timestamp (created_at dan updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
