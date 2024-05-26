<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CarrerInfo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_carrer' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'carrer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'description' => [
                'type' => 'TEXT',
                'constraint' => 225
            ],
            'publish_date' => [
                'type' => 'DATE'
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 5
            ],
        ]);
        $this->forge->addPrimaryKey('id_carrer');
        $this->forge->createTable('carrer');
    }

    public function down()
    {
        $this->forge->dropTable('carrer');
    }
}
