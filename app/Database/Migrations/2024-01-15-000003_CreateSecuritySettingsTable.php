<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSecuritySettingsTable extends Migration
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
        $this->forge->createTable('security_settings');

        // Insert default security settings
        $data = [
            [
                'setting_key' => 'session_timeout',
                'setting_value' => '30',
                'setting_type' => 'integer',
                'description' => 'Session timeout in minutes',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'max_login_attempts',
                'setting_value' => '5',
                'setting_type' => 'integer',
                'description' => 'Maximum login attempts before lockout',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'lockout_duration',
                'setting_value' => '30',
                'setting_type' => 'integer',
                'description' => 'Account lockout duration in minutes',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'password_min_length',
                'setting_value' => '6',
                'setting_type' => 'integer',
                'description' => 'Minimum password length',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'auto_logout_idle',
                'setting_value' => '15',
                'setting_type' => 'integer',
                'description' => 'Auto logout after idle time in minutes',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'require_https',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Require HTTPS for all connections',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'csrf_protection',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable CSRF protection',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'browser_lockdown',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable browser lockdown during exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'proctoring_enabled',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Enable exam proctoring',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'prevent_copy_paste',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Prevent copy and paste during exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'disable_right_click',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Disable right-click context menu during exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'fullscreen_mode',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Force fullscreen mode during exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'tab_switching_detection',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'description' => 'Detect and log tab switching during exams',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'ip_whitelist_enabled',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Enable IP address whitelisting',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'two_factor_enabled',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Enable two-factor authentication',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('security_settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('security_settings');
    }
}
