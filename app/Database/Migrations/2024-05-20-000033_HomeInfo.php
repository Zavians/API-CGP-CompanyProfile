<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HomeInfo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_home' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'home_name' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'type' => [
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
            'image_home' => [
                "type" => "VARCHAR",
                "constraint" => 180,
                "null" => true
            ],
        ]);
        $this->forge->addPrimaryKey('id_home');
        $this->forge->createTable('home');
    }

    public function down()
    {
        $this->forge->dropTable('home');
    }
}
