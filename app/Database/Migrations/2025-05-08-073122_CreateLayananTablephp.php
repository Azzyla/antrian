<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_layanan' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_layanan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id_layanan', true);
        $this->forge->createTable('layanan');
    }

    public function down()
    {
        $this->forge->dropTable('layanan');
    }
}
