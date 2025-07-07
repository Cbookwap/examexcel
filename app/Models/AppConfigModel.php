<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Application Configuration Model
 * 
 * Manages application-wide configuration settings including
 * branding, installation state, and system preferences.
 */
class AppConfigModel extends Model
{
    protected $table = 'app_config';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'config_key',
        'config_value',
        'config_type',
        'description',
        'is_public',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'config_key' => 'required|max_length[100]|is_unique[app_config.config_key,id,{id}]',
        'config_value' => 'permit_empty',
        'config_type' => 'required|in_list[string,integer,boolean,json,text]',
        'description' => 'permit_empty|max_length[255]',
        'is_public' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'config_key' => [
            'required' => 'Configuration key is required',
            'is_unique' => 'Configuration key must be unique'
        ]
    ];

    /**
     * Get configuration value by key
     */
    public function getConfig(string $key, $default = null)
    {
        $config = $this->where('config_key', $key)->first();
        
        if (!$config) {
            return $default;
        }
        
        return $this->castValue($config['config_value'], $config['config_type']);
    }

    /**
     * Set configuration value
     */
    public function setConfig(string $key, $value, string $type = 'string', string $description = '', bool $isPublic = false): bool
    {
        $data = [
            'config_key' => $key,
            'config_value' => $this->prepareValue($value, $type),
            'config_type' => $type,
            'description' => $description,
            'is_public' => $isPublic ? 1 : 0
        ];

        $existing = $this->where('config_key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data) !== false;
        }
    }

    /**
     * Get multiple configuration values
     */
    public function getConfigs(array $keys = []): array
    {
        $query = $this->select('config_key, config_value, config_type');
        
        if (!empty($keys)) {
            $query->whereIn('config_key', $keys);
        }
        
        $configs = $query->findAll();
        $result = [];
        
        foreach ($configs as $config) {
            $result[$config['config_key']] = $this->castValue(
                $config['config_value'], 
                $config['config_type']
            );
        }
        
        return $result;
    }

    /**
     * Get public configuration values (safe for frontend)
     */
    public function getPublicConfigs(): array
    {
        $configs = $this->select('config_key, config_value, config_type')
                       ->where('is_public', 1)
                       ->findAll();
        
        $result = [];
        foreach ($configs as $config) {
            $result[$config['config_key']] = $this->castValue(
                $config['config_value'], 
                $config['config_type']
            );
        }
        
        return $result;
    }

    /**
     * Initialize default application configuration
     */
    public function initializeDefaults(): bool
    {
        $defaults = [
            'app_name' => [
                'value' => env('app.name', 'CBT Examination System'),
                'type' => 'string',
                'description' => 'Application name displayed throughout the system',
                'public' => true
            ],
            'institution_name' => [
                'value' => env('app.institution', 'Educational Institution'),
                'type' => 'string',
                'description' => 'Institution or organization name',
                'public' => true
            ],
            'app_version' => [
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Current application version',
                'public' => true
            ],
            'installation_date' => [
                'value' => date('Y-m-d H:i:s'),
                'type' => 'string',
                'description' => 'Date when application was installed',
                'public' => false
            ],
            'installer_version' => [
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Version of installer used',
                'public' => false
            ],
            'logo_path' => [
                'value' => '',
                'type' => 'string',
                'description' => 'Path to application logo',
                'public' => true
            ],
            'favicon_path' => [
                'value' => '',
                'type' => 'string',
                'description' => 'Path to application favicon',
                'public' => true
            ],
            'theme_color' => [
                'value' => '#667eea',
                'type' => 'string',
                'description' => 'Primary theme color',
                'public' => true
            ],
            'maintenance_mode' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Whether application is in maintenance mode',
                'public' => true
            ],
            'allow_registration' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Whether user registration is allowed',
                'public' => true
            ]
        ];

        $success = true;
        foreach ($defaults as $key => $config) {
            if (!$this->getConfig($key)) {
                $result = $this->setConfig(
                    $key,
                    $config['value'],
                    $config['type'],
                    $config['description'],
                    $config['public']
                );
                if (!$result) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Cast value to appropriate type
     */
    private function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            case 'string':
            case 'text':
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    private function prepareValue($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'integer':
                return (string) (int) $value;
            case 'json':
                return json_encode($value);
            case 'string':
            case 'text':
            default:
                return (string) $value;
        }
    }

    /**
     * Delete configuration by key
     */
    public function deleteConfig(string $key): bool
    {
        return $this->where('config_key', $key)->delete();
    }

    /**
     * Check if configuration exists
     */
    public function hasConfig(string $key): bool
    {
        return $this->where('config_key', $key)->countAllResults() > 0;
    }

    /**
     * Get configuration with metadata
     */
    public function getConfigWithMeta(string $key): ?array
    {
        return $this->where('config_key', $key)->first();
    }

    /**
     * Bulk update configurations
     */
    public function updateConfigs(array $configs): bool
    {
        $success = true;
        
        foreach ($configs as $key => $value) {
            $existing = $this->where('config_key', $key)->first();
            if ($existing) {
                $type = $existing['config_type'];
                if (!$this->setConfig($key, $value, $type, $existing['description'], $existing['is_public'])) {
                    $success = false;
                }
            }
        }
        
        return $success;
    }
}
