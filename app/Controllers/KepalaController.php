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
        $query = $this->db->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS bulan, cs, COUNT(*) AS total
            FROM kepuasan_layanan
            WHERE penilaian = 5
            GROUP BY bulan, cs"
        )->getResult();

        $csTerbaikPerBulan = [];
        $perBulanSemuaCS = [];

        foreach ($query as $row) {
            $bulan = $row->bulan;
            $cs = $row->cs;
            $total = $row->total;

            $perBulanSemuaCS[$bulan][$cs] = (int) $total;

            if (!isset($csTerbaikPerBulan[$bulan]) || $total > $csTerbaikPerBulan[$bulan]['total']) {
                $csTerbaikPerBulan[$bulan] = [
                    'cs' => $cs,
                    'total' => $total
                ];
            } elseif ($total == $csTerbaikPerBulan[$bulan]['total']) {
                $csTerbaikPerBulan[$bulan] = [
                    'cs' => 'Seri',
                    'total' => $total
                ];
            }
        }

        // Buat daftar bulan tetap Januari–Desember tahun ini
        $bulanDefault = [];
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        for ($i = 1; $i <= 12; $i++) {
            $bulanKey = date('Y') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $bulanDefault[$bulanKey] = $namaBulan[$i];
        }

        $grafikBerjalanLabels = array_values($bulanDefault);
        $grafikBerjalanValues = [];

        foreach ($csList as $cs) {
            foreach (array_keys($bulanDefault) as $bulan) {
                $grafikBerjalanValues[$cs][] = $perBulanSemuaCS[$bulan][$cs] ?? 0;
            }
        }

        $csTerbaikBulanLabels = array_map(function ($bulan) use ($namaBulan) {
            $parts = explode('-', $bulan);
            return $namaBulan[(int)$parts[1]];
        }, array_keys($csTerbaikPerBulan));

        $csTerbaikBulanValues = array_map(function ($item) {
            return $item['cs'] === 'Seri' ? 0 : (int) filter_var($item['cs'], FILTER_SANITIZE_NUMBER_INT);
        }, array_values($csTerbaikPerBulan));

        $totalPerBulanQuery = $this->db->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS bulan, COUNT(*) AS total
            FROM kepuasan_layanan
            GROUP BY bulan
            ORDER BY bulan ASC"
        )->getResult();

        $grafikPerBulanLabels = [];
        $grafikPerBulanValues = [];

        foreach ($totalPerBulanQuery as $row) {
            $parts = explode('-', $row->bulan);
            $grafikPerBulanLabels[] = $namaBulan[(int)$parts[1]];
            $grafikPerBulanValues[] = (int) $row->total;
        }

        return view('kepala/dashboard_kepalaPLT', [
            'penilaian'              => $penilaian,
            'penilaianPerCS'         => $penilaianPerCS,
            'csTerbaikBulanLabels'   => $csTerbaikBulanLabels,
            'csTerbaikBulanValues'   => $csTerbaikBulanValues,
            'grafikPerBulanLabels'   => $grafikPerBulanLabels,
            'grafikPerBulanValues'   => $grafikPerBulanValues,
            'grafikBerjalanLabels'   => $grafikBerjalanLabels,
            'grafikBerjalanValues'   => $grafikBerjalanValues
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
