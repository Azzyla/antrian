<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KepuasanModel;
use App\Models\AntrianModel;
use Config\Database;

class KepalaController extends BaseController
{
    protected $db;
    protected $antrianModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->antrianModel = new AntrianModel();
    }

    // === LOGIN VIEW ===
    public function login()
    {
        return view('kepala/login');
    }

    // === LOGIN POST ===
    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

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

    // === LOGOUT ===
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/kepala/login');
    }

    // === DASHBOARD: GRAFIK KEPUASAN ===
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'kepala') {
            return redirect()->to('/kepala/login');
        }

        $model = new KepuasanModel();

        $kategoriMap = [
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik'
        ];

        $query = $model
            ->select('penilaian, COUNT(*) as jumlah')
            ->groupBy('penilaian')
            ->findAll();

        $penilaian = [];
        foreach ($query as $row) {
            $label = $kategoriMap[$row['penilaian']] ?? 'Tidak Diketahui';
            $penilaian[$label] = (int)$row['jumlah'];
        }

        $csList = ['CS 1', 'CS 2', 'CS 3'];
        $penilaianPerCS = [];
        foreach ($csList as $cs) {
            $queryCS = $model
                ->select('penilaian, COUNT(*) as jumlah')
                ->where('cs', $cs)
                ->groupBy('penilaian')
                ->findAll();

            foreach ($queryCS as $row) {
                $label = $kategoriMap[$row['penilaian']] ?? 'Tidak Diketahui';
                $penilaianPerCS[$cs][$label] = (int)$row['jumlah'];
            }
        }

        return view('kepala/dashboard_kepalaPLT', [
            'penilaian'      => $penilaian,
            'penilaianPerCS' => $penilaianPerCS
        ]);
    }

    // === REKAP KEPUASAN LAYANAN ===
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

        return view('kepala/rekap_kepuasan', [
            'kepuasan' => $query->getResultArray(),
            'start'    => $start,
            'end'      => $end
        ]);
    }

    // === REKAP ANTRIAN ===
    public function rekap_antrian()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'kepala') {
            return redirect()->to('/kepala/login');
        }

        $start     = $this->request->getGet('start');
        $end       = $this->request->getGet('end');
        $perPage = (int)($this->request->getGet('perPage') ?? 10);
        $page      = $this->request->getVar('page') ?? 1;

        $builder = $this->antrianModel->select('antrian.*, users.username AS nama_cs')
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

        $laporan = $builder->orderBy('antrian.created_at', 'DESC')
                           ->paginate($perPage, 'default', $page);

        return view('kepala/rekap_antrian', [
            'laporan'      => $laporan,
            'pager'        => $this->antrianModel->pager,
            'perPage'      => $perPage,
            'currentPage'  => (int)$page,
            'totalData'        => $this->antrianModel->countAllResults(false),
            'start'        => $start,
            'end'          => $end
        ]);
    }
}
