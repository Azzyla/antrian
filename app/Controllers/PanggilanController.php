<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AntrianModel;
use CodeIgniter\I18n\Time;
use Config\Database;

class PanggilanController extends BaseController
{
    protected $antrianModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
    }

    public function index()
    {
        $session = session();
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');
        $sessionRole = $session->get('role'); // cs1, cs2, cs3

        // Mapping role ke nomor loket
        $loketNumber = '1';
        switch ($sessionRole) {
            case 'cs1':
                $loketNumber = '1';
                break;
            case 'cs2':
                $loketNumber = '2';
                break;
            case 'cs3':
                $loketNumber = '3';
                break;
        }

        $belumDipanggil = $this->antrianModel
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $db = Database::connect();
        $sudahDipanggilQuery = $db->table('antrian')
            ->select('antrian.*, users.role as cs_role, users.username as cs_name')
            ->join('users', 'users.id = antrian.id_cs_plt', 'left')
            ->where('antrian.created_at >=', $todayStart)
            ->where('antrian.created_at <=', $todayEnd)
            ->whereIn('antrian.status', ['dipanggil', 'dilayani', 'selesai']);

        if (str_starts_with($sessionRole, 'cs')) {
            $sudahDipanggilQuery->where('users.role', $sessionRole);
        }

        $sudahDipanggil = $sudahDipanggilQuery->get()->getResultArray();

        return view('panggilan', [
            'belumDipanggil' => $belumDipanggil,
            'sudahDipanggil' => $sudahDipanggil,
            'loketNumber'    => $loketNumber
        ]);
    }

    public function panggil($id)
    {
        $data = $this->antrianModel->find($id);
        if (!$data) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $update = [
            'status' => 'dipanggil',
            'waktu_antrian' => Time::now('Asia/Jakarta'),
        ];

        if (empty($data['id_cs_plt'])) {
            $update['id_cs_plt'] = session()->get('user_id');
        }

        $this->antrianModel->update($id, $update);
        return redirect()->to('/panggilan');
    }

    public function ulang($id)
    {
        return redirect()->to('/panggilan');
    }

    public function mulaiLayanan($id)
    {
        $data = $this->antrianModel->find($id);
        if (!$data) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $userId = session()->get('user_id');

        // Cek apakah CS ini sudah melayani antrian lain
        $sedangMelayani = $this->antrianModel
            ->where('id_cs_plt', $userId)
            ->where('status', 'dilayani')
            ->first();

        if ($sedangMelayani) {
            return redirect()->back()->with('error', 'Selesaikan layanan sebelumnya terlebih dahulu.');
        }

        $this->antrianModel->update($id, [
            'status' => 'dilayani',
            'waktu_mulai' => Time::now('Asia/Jakarta'),
            'id_cs_plt' => $userId
        ]);

        return redirect()->to('/panggilan')->with('success', 'Layanan dimulai.');
    }

    public function selesaiLayanan($id)
    {
        $data = $this->antrianModel->find($id);
        if (!$data) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $update = [
            'status' => 'selesai',
            'waktu_selesai' => Time::now('Asia/Jakarta'),
        ];

        if (empty($data['id_cs_plt'])) {
            $update['id_cs_plt'] = session()->get('user_id');
        }

        $this->antrianModel->update($id, $update);
        return redirect()->to('/panggilan')->with('success', 'Layanan diselesaikan.');
    }
}
