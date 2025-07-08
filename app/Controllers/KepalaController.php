<?php

namespace App\Controllers;

use App\Models\PenilaianModel;
use App\Models\AntrianModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class KepalaController extends Controller
{
    protected $antrianModel;
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->antrianModel = new \App\Models\AntrianModel();
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $db = \Config\Database::connect();

        // Data kepuasan dummy
        $penilaian = [
            'Sangat Buruk' => 2,
            'Buruk' => 5,
            'Cukup' => 8,
            'Baik' => 15,
            'Sangat Baik' => 20
        ];

        // Data per CS dummy
        $penilaianPerCS = [
            'CS 1' => ['Sangat Buruk' => 1, 'Buruk' => 2, 'Cukup' => 4, 'Baik' => 6, 'Sangat Baik' => 8],
            'CS 2' => ['Sangat Buruk' => 0, 'Buruk' => 1, 'Cukup' => 3, 'Baik' => 4, 'Sangat Baik' => 5],
            'CS 3' => ['Sangat Buruk' => 1, 'Buruk' => 2, 'Cukup' => 1, 'Baik' => 2, 'Sangat Baik' => 3]
        ];

        // Grafik berjalan dummy
        $grafikBerjalanLabels = ['Januari', 'Februari', 'Maret', 'April'];
        $grafikBerjalanValues = [
            'CS 1' => [5, 6, 7, 8],
            'CS 2' => [2, 4, 5, 6],
            'CS 3' => [3, 4, 6, 7]
        ];

        // Ambil jumlah layanan
        $query = $db->query("
            SELECT layanan, COUNT(*) AS total 
            FROM layanan 
            GROUP BY layanan
        ");
        $layananRows = $query->getResult();

        $layananLabels = [];
        $layananValues = [];

        foreach ($layananRows as $row) {
            $layananLabels[] = $row->layanan;
            $layananValues[] = (int) $row->total;
        }

        return view('kepala/dashboard_kepalaPLT', [
            'penilaian' => $penilaian,
            'penilaianPerCS' => $penilaianPerCS,
            'grafikBerjalanLabels' => $grafikBerjalanLabels,
            'grafikBerjalanValues' => $grafikBerjalanValues,
            'layananLabels' => $layananLabels,
            'layananValues' => $layananValues
        ]);
    }

    public function rekap_kepuasan()
    {
        // if (!$this->isKepala()) {
        //     return redirect()->to('/kepala/login');
        // }

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
        // if (!$this->isKepala()) {
        //     return redirect()->to('/kepala/login');
        // }

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
    public function kelola_user()
    {
        $users = $this->userModel->findAll();
        return view('kepala/kelola_user', ['users' => $users]);
    }

    public function tambah_user()
    {
        return view('kepala/tambah_user');
    }

    public function simpan_user()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[cs1,cs2,cs3]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => $this->request->getPost('role')
        ]);

        return redirect()->to('/kepala/kelola_user')->with('success', 'User berhasil ditambahkan.');
    }
    public function delete_user($id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (!$user) {
            return redirect()->to('/kepala/kelola_user')->with('error', 'User tidak ditemukan.');
        }

        $model->delete($id);
        return redirect()->to('/kepala/kelola_user')->with('success', 'User berhasil dihapus.');
    }
}
