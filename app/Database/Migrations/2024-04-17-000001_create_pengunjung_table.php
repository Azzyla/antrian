<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengunjungTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'pengguna'  => ['type' => 'VARCHAR', 'constraint' => 20],
            'nim'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nidn'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nik'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'prodi'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at'=> ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengunjung');
    }

    public function down()
    {
        $this->forge->dropTable('pengunjung');
    }
}
