<?php

namespace App\Models;

use CodeIgniter\Model;

class PengunjungModel extends Model
{
    protected $table            = 'pengunjung';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['pengguna', 'nim', 'nidn', 'nik', 'prodi', 'created_at'];
    protected $useTimestamps    = false;
}
