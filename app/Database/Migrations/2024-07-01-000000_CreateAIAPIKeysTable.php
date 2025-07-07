<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAIAPIKeysTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'provider' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'api_key' => [
                'type' => 'TEXT',
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ai_api_keys');
    }

    public function down()
    {
        $this->forge->dropTable('ai_api_keys');
    }
} 