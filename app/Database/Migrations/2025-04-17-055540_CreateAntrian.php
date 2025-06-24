<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameTanggalAntrianColumn extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('antrian', [
            'Tanggal_Antrian' => [
                'name' => 'waktu_antrian',
                'type' => 'DATETIME',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('antrian', [
            'waktu_antrian' => [
                'name' => 'Tanggal_Antrian',
                'type' => 'DATETIME',
            ],
        ]);
    }
}
