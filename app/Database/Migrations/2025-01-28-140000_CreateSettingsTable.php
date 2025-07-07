<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
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
            'setting_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_type' => [
                'type' => 'ENUM',
                'constraint' => ['string', 'integer', 'boolean', 'json'],
                'default' => 'string',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey('setting_key');
        $this->forge->createTable('settings');

        // Insert default settings
        $data = [
            [
                'setting_key' => 'system_name',
                'setting_value' => 'ExamExcel',
                'setting_type' => 'string',
                'description' => 'System name displayed throughout the application',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'system_version',
                'setting_value' => '1.0.0',
                'setting_type' => 'string',
                'description' => 'Current system version',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'institution_name',
                'setting_value' => 'ExamExcel',
                'setting_type' => 'string',
                'description' => 'Institution name',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'default_exam_duration',
                'setting_value' => '80',
                'setting_type' => 'integer',
                'description' => 'Default exam duration in minutes',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'auto_submit_on_time_up',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Auto submit exam when time is up',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'backup_frequency',
                'setting_value' => 'weekly',
                'setting_type' => 'string',
                'description' => 'Automatic backup frequency',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'backup_retention_days',
                'setting_value' => '30',
                'setting_type' => 'integer',
                'description' => 'Number of days to retain backups',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_locked',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Whether the application is locked',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'locked_roles',
                'setting_value' => '[]',
                'setting_type' => 'json',
                'description' => 'Roles that are locked out when app is locked',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'news_flash_enabled',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Whether news flash is enabled on login page',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'news_flash_content',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'News flash content displayed on login page',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'logo_path',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Path to application logo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'favicon_path',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Path to application favicon',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'calculator_enabled',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Whether calculator is enabled during exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'exam_pause_enabled',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Whether students can pause their exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'student_id_prefix',
                'setting_value' => 'STD',
                'setting_type' => 'string',
                'description' => 'Prefix for auto-generated student IDs',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
