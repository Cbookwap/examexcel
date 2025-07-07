<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppConfigTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'config_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'config_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'config_type' => [
                'type'       => 'ENUM',
                'constraint' => ['string', 'integer', 'boolean', 'json', 'text'],
                'default'    => 'string',
                'null'       => false,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_public' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'comment'    => 'Whether this config is safe to expose to frontend'
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
        $this->forge->addUniqueKey('config_key');
        $this->forge->addKey('is_public');
        $this->forge->createTable('app_config');
    }

    public function down()
    {
        $this->forge->dropTable('app_config');
    }
}
