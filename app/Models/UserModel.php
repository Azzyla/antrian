<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    // Tambahkan 'role' ke dalam allowedFields agar bisa disimpan/digunakan
    protected $allowedFields = ['username', 'password', 'role'];

    /**
     * Ambil data user berdasarkan username
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
