<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function save()
    {
        $model = new UserModel();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
            'password' => 'required|min_length[6]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/register')->withInput()->with('validation', $validation);
        }

        // Simpan data pengguna
        $model->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function authenticate()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan username
        $user = $model->where('username', $username)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Username tidak ditemukan.');
        }

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            session()->set([
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'isLoggedIn' => true
            ]);

            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/login')->with('error', 'Password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}
