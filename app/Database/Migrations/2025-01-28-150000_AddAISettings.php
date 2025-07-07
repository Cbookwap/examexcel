<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAISettings extends Migration
{
    public function up()
    {
        // Insert AI-related settings if they don't exist
        $settings = [
            [
                'setting_key' => 'ai_generation_enabled',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'description' => 'Enable AI-powered question generation',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'ai_model_provider',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Selected AI model provider (openai, gemini, claude, groq, huggingface)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'ai_model',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Selected AI model for the chosen provider',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'openai_api_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'OpenAI API key (encrypted)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'gemini_api_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Google Gemini API key (encrypted)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'claude_api_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Anthropic Claude API key (encrypted)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'groq_api_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Groq API key (encrypted)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'huggingface_api_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'description' => 'Hugging Face API key (encrypted)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
            $existing = $this->db->table('settings')
                ->where('setting_key', $setting['setting_key'])
                ->get()
                ->getRow();

            if (!$existing) {
                $this->db->table('settings')->insert($setting);
            }
        }
    }

    public function down()
    {
        // Remove AI settings
        $aiSettingKeys = [
            'ai_generation_enabled',
            'ai_model_provider',
            'ai_model',
            'openai_api_key',
            'gemini_api_key',
            'claude_api_key',
            'groq_api_key',
            'huggingface_api_key'
        ];

        $this->db->table('settings')
            ->whereIn('setting_key', $aiSettingKeys)
            ->delete();
    }
}
