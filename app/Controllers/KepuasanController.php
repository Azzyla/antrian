<?php

namespace App\Controllers;

use App\Models\KepuasanModel;
use CodeIgniter\Controller;

class KepuasanController extends BaseController
{
    public function index()
    {
        return view('kepuasan');
    }    

    public function simpan()
    {
        $model = new KepuasanModel();

        $data = [
            'nim'       => $this->request->getPost('nim'),
            'cs'        => $this->request->getPost('cs'),
            'penilaian' => $this->request->getPost('penilaian'),
            'saran'     => $this->request->getPost('saran'),
        ];

        $model->insert($data);
        return redirect()->to('/kepuasan')->with('success', 'Terima kasih atas feedback Anda!');
    }
}
