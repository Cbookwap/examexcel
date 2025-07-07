<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'setting_key' => 'required|max_length[100]',
        'setting_type' => 'required|in_list[string,integer,boolean,json]'
    ];

    protected $validationMessages = [
        'setting_key' => [
            'required' => 'Setting key is required',
            'max_length' => 'Setting key cannot exceed 100 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get a setting value by key
     */
    public function getSetting($key, $default = null)
    {
        try {
            $setting = $this->where('setting_key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return $this->castValue($setting['setting_value'], $setting['setting_type']);
        } catch (\Exception $e) {
            log_message('error', 'SettingsModel::getSetting failed: ' . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set a setting value
     */
    public function setSetting($key, $value, $type = 'string', $description = null)
    {
        try {
            $data = [
                'setting_key' => $key,
                'setting_value' => $this->prepareValue($value, $type),
                'setting_type' => $type,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($description !== null) {
                $data['description'] = $description;
            }

            // Check if setting exists
            $existing = $this->where('setting_key', $key)->first();

            if ($existing) {
                return $this->update($existing['id'], $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                return $this->insert($data);
            }
        } catch (\Exception $e) {
            log_message('error', 'SettingsModel::setSetting failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all settings as key-value pairs
     */
    public function getAllSettings()
    {
        try {
            $settings = $this->findAll();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting['setting_key']] = $this->castValue(
                    $setting['setting_value'],
                    $setting['setting_type']
                );
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'SettingsModel::getAllSettings failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update multiple settings at once
     */
    public function updateSettings($settings)
    {
        try {
            $this->db->transStart();

            foreach ($settings as $key => $value) {
                $type = $this->determineType($value);
                $this->setSetting($key, $value, $type);
            }

            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'SettingsModel::updateSettings failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cast value to appropriate type
     */
    private function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    private function prepareValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'json':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Determine type from value
     */
    private function determineType($value)
    {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_int($value)) {
            return 'integer';
        } elseif (is_array($value)) {
            return 'json';
        } else {
            return 'string';
        }
    }

    /**
     * Reset all settings to defaults
     */
    public function resetToDefaults()
    {
        try {
            $defaults = [
                'system_name' => 'ExamExcel',
                'system_version' => '1.0.0',
                'institution_name' => 'ExamExcel',
                'default_exam_duration' => 80,
                'auto_submit_on_time_up' => true,
                'backup_frequency' => 'weekly',
                'backup_retention_days' => 30,
                'app_locked' => false,
                'locked_roles' => '[]',
                'news_flash_enabled' => false,
                'news_flash_content' => '',
                'logo_path' => '',
                'favicon_path' => '',
                'calculator_enabled' => true,
                'exam_pause_enabled' => false,
                'student_id_prefix' => 'STD'
            ];

            return $this->updateSettings($defaults);
        } catch (\Exception $e) {
            log_message('error', 'SettingsModel::resetToDefaults failed: ' . $e->getMessage());
            return false;
        }
    }
}
