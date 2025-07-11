<?php

namespace App\Controllers;

use App\Models\PenilaianModel;
use App\Models\AntrianModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class KepalaController extends Controller
{
    protected $antrianModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    private function isKepala(): bool
    {
        return session()->get('logged_in') && session()->get('role') === 'kepala';
    }

    public function login()
    {
        return view('kepala/login');
    }

    public function proses_login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password']) && $user['role'] === 'kepala') {
            session()->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'role'      => $user['role']
            ]);
            return redirect()->to('/kepala/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
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

        $kategoriMap = [
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik'
        ];

        $penilaian = array_fill_keys(array_values($kategoriMap), 0);
        $query = $this->db->query("SELECT penilaian, COUNT(*) AS total FROM kepuasan_layanan GROUP BY penilaian");
        foreach ($query->getResult() as $row) {
            $label = $kategoriMap[(int)$row->penilaian] ?? 'Lainnya';
            $penilaian[$label] = (int)$row->total;
        }

        $csList = ['CS Dayu', 'CS Riska', 'CS Robi', 'CS Dewi'];
        $penilaianPerCS = [];
        foreach ($csList as $cs) {
            $penilaianPerCS[$cs] = array_fill_keys(array_values($kategoriMap), 0);
        }

        $query = $this->db->query("SELECT cs, penilaian, COUNT(*) AS total FROM kepuasan_layanan GROUP BY cs, penilaian");
        foreach ($query->getResult() as $row) {
            $cs = $row->cs;
            $label = $kategoriMap[(int)$row->penilaian] ?? 'Lainnya';
            if (isset($penilaianPerCS[$cs])) {
                $penilaianPerCS[$cs][$label] = (int)$row->total;
            }
        }

        $grafikBerjalanLabels = [];
        $grafikBerjalanValues = [];
        foreach ($csList as $cs) {
            $grafikBerjalanValues[$cs] = [];
        }

        $bulanMap = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
        $labelBulanSet = [];

        $query = $this->db->query("SELECT DISTINCT MONTH(created_at) AS bulan FROM kepuasan_layanan ORDER BY bulan ASC");
        foreach ($query->getResult() as $row) {
            $labelBulanSet[(int)$row->bulan] = $bulanMap[(int)$row->bulan] ?? 'Bulan ' . $row->bulan;
        }
        ksort($labelBulanSet);
        $grafikBerjalanLabels = array_values($labelBulanSet);

        foreach ($csList as $cs) {
            foreach (array_keys($labelBulanSet) as $bulan) {
                $grafikBerjalanValues[$cs][] = 0;
            }
        }

        $query = $this->db->query("SELECT cs, MONTH(created_at) AS bulan, COUNT(*) AS total FROM kepuasan_layanan WHERE penilaian = 5 GROUP BY cs, MONTH(created_at)");
        foreach ($query->getResult() as $row) {
            $cs = $row->cs;
            $bulanIndex = array_search($bulanMap[(int)$row->bulan], $grafikBerjalanLabels);
            if ($cs && isset($grafikBerjalanValues[$cs][$bulanIndex])) {
                $grafikBerjalanValues[$cs][$bulanIndex] = (int)$row->total;
            }
        }

        $query = $this->db->query("SELECT layanan.layanan, COUNT(*) AS total FROM antrian JOIN layanan ON layanan.id_layanan = antrian.id_layanan GROUP BY layanan.layanan");
        $layananLabels = [];
        $layananValues = [];
        foreach ($query->getResult() as $row) {
            $layananLabels[] = $row->layanan;
            $layananValues[] = (int)$row->total;
        }

        return view('kepala/dashboard_kepalaPLT', [
            'penilaian'            => $penilaian,
            'penilaianPerCS'       => $penilaianPerCS,
            'grafikBerjalanLabels' => $grafikBerjalanLabels,
            'grafikBerjalanValues' => $grafikBerjalanValues,
            'layananLabels'        => $layananLabels,
            'layananValues'        => $layananValues
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

        $builder = $this->antrianModel
            ->select('antrian.*, users.username AS nama_cs, pengunjung.pengguna, pengunjung.nim, pengunjung.nidn, pengunjung.nik, layanan.layanan AS nama_layanan')
            ->join('users', 'users.id = antrian.id_cs_plt', 'left')
            ->join('pengunjung', 'pengunjung.id = antrian.id_pengunjung', 'left')
            ->join('layanan', 'layanan.id_layanan = antrian.id_layanan', 'left');

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

    public function kelola_user()
    {
        if (!$this->isKepala()) {
            return redirect()->to('/kepala/login');
        }

        $users = $this->userModel->findAll();

        return view('kepala/kelola_user', [
            'users'      => $users,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function simpan_user()
    {
        if (!$this->isKepala()) {
            return redirect()->to('/kepala/login');
        }

        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[cs1,cs2,cs3,cs4]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', \Config\Services::validation());
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role')
        ]);

        return redirect()->to('/kepala/kelola_user')->with('success', 'User berhasil ditambahkan.');
    }

    public function delete_user($id)
    {
        if (!$this->isKepala()) {
            return redirect()->to('/kepala/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/kepala/kelola_user')->with('error', 'User tidak ditemukan.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/kepala/kelola_user')->with('success', 'User berhasil dihapus.');
    }
}