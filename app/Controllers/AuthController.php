<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

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
        $rules = [
            'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[cs1,cs2,cs3,kepala]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role')
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Username tidak ditemukan.');
        }

        if (password_verify($password, $user['password'])) {
            session()->set([
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'name'       => ucfirst($user['username']), // Set 'name' untuk ditampilkan di dashboard
                'role'       => $user['role'],
                'isLoggedIn' => true
            ]);

            // Arahkan berdasarkan role
            if (in_array($user['role'], ['cs1', 'cs2', 'cs3','cs4'])) {
                return redirect()->to('/panggilan');
            } elseif ($user['role'] === 'kepala') {
                return redirect()->to('/kepala/dashboard');
            } else {
                return redirect()->to('/login')->with('error', 'Role tidak dikenali.');
            }

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
