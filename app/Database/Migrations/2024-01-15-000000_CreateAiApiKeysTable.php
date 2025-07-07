<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAiApiKeysTable extends Migration
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
                'null' => false,
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'api_key' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['provider', 'model'], 'unique_provider_model');
        $this->forge->createTable('ai_api_keys');
    }

    public function down()
    {
        $this->forge->dropTable('ai_api_keys');
    }
}
