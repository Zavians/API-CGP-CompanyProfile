<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RegisterHome extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_registerhome ' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'home_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'nama_lengkap' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'no_telp' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'email_addres' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
        ]);

        $this->forge->addPrimaryKey('id_registerhome');
        $this->forge->createTable('registerhome');
    }

    public function down()
    {
        $this->forge->dropTable('registerhome');
    }
}
