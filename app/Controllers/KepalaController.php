<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class KepalaController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ==== LOGIN VIEW ====
    public function login()
    {
        return view('kepala/login'); // pastikan file ini ada
    }

    // ==== LOGIN POST ====
    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ganti sesuai tabel kepala (misalnya 'users' dengan role kepala)
        $builder = $this->db->table('users');
        $user = $builder->where('username', $username)
                        ->where('role', 'kepala')
                        ->get()
                        ->getRow();

        if ($user && password_verify($password, $user->password)) {
            session()->set([
                'logged_in' => true,
                'username'  => $user->username,
                'role'      => $user->role
            ]);
            return redirect()->to('/kepala/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau Password salah');
    }

    // ==== LOGOUT ====
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/kepala/login');
    }

    // ==== DASHBOARD ====
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'kepala') {
            return redirect()->to('/kepala/login');
        }

       return view('kepala/dashboard_kepalaPLT'); // Buat view ini jika belum ada
    }

    // ==== REKAP KEPUASAN ====
    public function rekap_kepuasan()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'kepala') {
            return redirect()->to('/kepala/login');
        }

        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end');

        $builder = $this->db->table('kepuasan_layanan');

        if ($start && $end) {
            try {
                $startDate = date('Y-m-d', strtotime($start));
                $endDate   = date('Y-m-d', strtotime($end));

                $builder->where('DATE(created_at) >=', $startDate)
                        ->where('DATE(created_at) <=', $endDate);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }
        }

        $builder->orderBy('created_at', 'DESC');
        $query = $builder->get();

        $data = [
            'kepuasan' => $query->getResultArray(),
            'start'    => $start,
            'end'      => $end
        ];

        return view('kepala/rekap_kepuasan', $data);
    }

    // ==== REKAP ANTRIAN ====
    public function rekap_antrian()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'kepala') {
            return redirect()->to('/kepala/login');
        }

        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end');

        $builder = $this->db->table('antrian')
            ->select('antrian.*, users.username AS nama_cs')
            ->join('users', 'users.id = antrian.id_cs_plt', 'left');

        if ($start && $end) {
            try {
                $startDate = date('Y-m-d', strtotime($start));
                $endDate   = date('Y-m-d', strtotime($end));

                $builder->where('DATE(antrian.created_at) >=', $startDate)
                        ->where('DATE(antrian.created_at) <=', $endDate);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }
        }

        $builder->orderBy('antrian.created_at', 'DESC');
        $query = $builder->get();

        $data = [
            'laporan' => $query->getResultArray(),
            'start'   => $start,
            'end'     => $end
        ];

        return view('kepala/rekap_antrian', $data);
    }
}
