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

    public function login()
    {
        return view('kepala/login');
    }

    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->db->table('users')
                         ->where('username', $username)
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/kepala/login');
    }

    public function dashboard()
    {
        if (!$this->isKepala()) {
            return redirect()->to('/kepala/login');
        }

        $kepuasanModel = new KepuasanModel();

        $kategoriMap = [
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik'
        ];

        // Grafik Kepuasan Layanan
        $penilaian = [];
        foreach ($kepuasanModel->select('penilaian, COUNT(*) as jumlah')->groupBy('penilaian')->findAll() as $row) {
            $label = $kategoriMap[$row['penilaian']] ?? 'Tidak Diketahui';
            $penilaian[$label] = (int) $row['jumlah'];
        }

        // Grafik per Jenis CS
        $csList = ['CS 1', 'CS 2', 'CS 3'];
        $penilaianPerCS = [];

        foreach ($csList as $cs) {
            $data = $kepuasanModel->select('penilaian, COUNT(*) as jumlah')
                                  ->where('cs', $cs)
                                  ->groupBy('penilaian')
                                  ->findAll();
            foreach ($data as $row) {
                $label = $kategoriMap[$row['penilaian']] ?? 'Tidak Diketahui';
                $penilaianPerCS[$cs][$label] = (int) $row['jumlah'];
            }
        }

        // Grafik CS Terbaik per Bulan (berdasarkan penilaian = 5)
        $query = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%M') AS bulan, cs, COUNT(*) AS total
            FROM kepuasan_layanan
            WHERE penilaian = 5
            GROUP BY bulan, cs
        ")->getResult();

        $csTerbaikPerBulan = [];

        foreach ($query as $row) {
            $bulan = $row->bulan;
            $cs = $row->cs;
            $total = $row->total;

            if (!isset($csTerbaikPerBulan[$bulan]) || $total > $csTerbaikPerBulan[$bulan]['total']) {
                $csTerbaikPerBulan[$bulan] = [
                    'cs' => $cs,
                    'total' => $total
                ];
            }
        }

        $csTerbaikBulanLabels = array_keys($csTerbaikPerBulan);
        $csTerbaikBulanValues = array_map(function ($item) {
            return (int) filter_var($item['cs'], FILTER_SANITIZE_NUMBER_INT);
        }, array_values($csTerbaikPerBulan));

        return view('kepala/dashboard_kepalaPLT', [
            'penilaian'            => $penilaian,
            'penilaianPerCS'       => $penilaianPerCS,
            'csTerbaikBulanLabels' => $csTerbaikBulanLabels,
            'csTerbaikBulanValues' => $csTerbaikBulanValues
        ]);
    }

    public function rekap_kepuasan()
    {
        if (!$this->isKepala()) {
            return redirect()->to('/kepala/login');
        }

        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end');

        $builder = $this->db->table('kepuasan_layanan');

        if ($start && $end) {
            try {
                $builder->where('DATE(created_at) >=', date('Y-m-d', strtotime($start)))
                        ->where('DATE(created_at) <=', date('Y-m-d', strtotime($end)));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }
        }

        $builder->orderBy('created_at', 'DESC');
        $data = $builder->get()->getResultArray();

        return view('kepala/rekap_kepuasan', [
            'kepuasan' => $data,
            'start'    => $start,
            'end'      => $end
        ]);
    }

    public function rekap_antrian()
    {
        if (!$this->isKepala()) {
            return redirect()->to('/kepala/login');
        }

        $start     = $this->request->getGet('start');
        $end       = $this->request->getGet('end');
        $perPage   = (int)($this->request->getGet('perPage') ?? 10);
        $page      = (int)($this->request->getVar('page') ?? 1);

        $builder = $this->antrianModel->select('antrian.*, users.username AS nama_cs, pengunjung.nim')
    ->join('users', 'users.id = antrian.id_cs_plt', 'left')
    ->join('pengunjung', 'pengunjung.id = antrian.id_pengunjung', 'left');

        if ($start && $end) {
            try {
                $builder->where('DATE(antrian.created_at) >=', date('Y-m-d', strtotime($start)))
                        ->where('DATE(antrian.created_at) <=', date('Y-m-d', strtotime($end)));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }
        }

        $laporan = $builder->orderBy('antrian.created_at', 'DESC')
                           ->paginate($perPage, 'default', $page);

        return view('kepala/rekap_antrian', [
            'laporan'     => $laporan,
            'pager'       => $this->antrianModel->pager,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalData'   => $this->antrianModel->countAllResults(false),
            'start'       => $start,
            'end'         => $end
        ]);
    }

    private function isKepala(): bool
    {
        return session()->get('logged_in') && session()->get('role') === 'kepala';
    }
}
