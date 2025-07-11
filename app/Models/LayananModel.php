<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    protected $allowedFields = ['layanan', 'created_at']; // Hapus 'kategori'
}
