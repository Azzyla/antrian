<?php

namespace App\Controllers;

use App\Models\AntrianModel;

class RekapController extends BaseController
{
    public function index()
    {
        $antrianModel = new AntrianModel();

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? 'Semua';

        $rekap = $antrianModel->getRekap($startDate, $endDate, $status);

        $data = [
            'rekap' => $rekap,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status
        ];

        return view('rekap', $data);
    }
}
