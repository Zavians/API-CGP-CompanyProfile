<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Collaboration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_collabor' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'company_name' => [
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
            'image_collabor' => [
                "type" => "VARCHAR",
                "constraint" => 180,
                "null" => true
            ],
        ]);
        $this->forge->addPrimaryKey('id_collabor');
        $this->forge->createTable('collabor');
    }

    public function down()
    {
        $this->forge->dropTable('collabor');
    }
}
