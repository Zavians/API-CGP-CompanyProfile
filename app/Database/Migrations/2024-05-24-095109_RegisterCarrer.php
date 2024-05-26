<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RegisterCarrer extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_registercarrer' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'carrer_id' => [
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
            'status_pendidikan' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'resume' => [
                "type" => "VARCHAR",
                "constraint" => 180,
                "null" => true
            ],
            'cover_letter' => [
                "type" => "VARCHAR",
                "constraint" => 180,
                "null" => true
            ],
        ]);
        $this->forge->addPrimaryKey('id_registercarrer');
        $this->forge->createTable('registercarrer');
        $this->forge->addForeignKey('carrer_id', 'carrer', 'id_carrer', '', '', 'carrer_fk');
    }

    public function down()
    {
        $this->forge->dropTable('registercarrer');
    }
}
