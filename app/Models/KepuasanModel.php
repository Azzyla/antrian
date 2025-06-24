<?php

namespace App\Models;

use CodeIgniter\Model;

class KepuasanModel extends Model
{
    protected $table = 'kepuasan_layanan';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nim', 'cs', 'penilaian', 'saran'];
    protected $useTimestamps = true; // atau false jika tidak ingin
}
