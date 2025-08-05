<?php

namespace App\Models;

use CodeIgniter\Model;

class CsPltModel extends Model
{
    protected $table = 'cs_plt';
    protected $primaryKey = 'id_cs_plt';
    protected $allowedFields = ['nama'];
}
