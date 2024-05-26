<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CompanyInfo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_info' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'judul' => [
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
            'image_info' => [
                "type" => "VARCHAR",
                "constraint" => 180,
                "null" => true
            ],
        ]);
        $this->forge->addPrimaryKey('id_info');
        $this->forge->createTable('company_info');
    }

    public function down()
    {
        $this->forge->dropTable('company_info');
    }
}
