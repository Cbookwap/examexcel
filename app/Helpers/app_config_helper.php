<?php

/**
 * Application Configuration Helper
 * 
 * Provides easy access to application configuration values
 * stored in the database or environment variables.
 */

if (!function_exists('app_config')) {
    /**
     * Get application configuration value
     * 
     * @param string $key Configuration key
     * @param mixed $default Default value if not found
     * @return mixed Configuration value
     */
    function app_config(string $key, $default = null)
    {
        static $configModel = null;
        static $cache = [];
        
        // Return cached value if available
        if (isset($cache[$key])) {
            return $cache[$key];
        }
        
        try {
            // Initialize model if not already done
            if ($configModel === null) {
                $configModel = new \App\Models\AppConfigModel();
            }
            
            // Get value from database
            $value = $configModel->getConfig($key, $default);
            
            // Cache the value
            $cache[$key] = $value;
            
            return $value;
            
        } catch (\Exception $e) {
            // Fallback to environment variable or default
            return env('app.' . $key, $default);
        }
    }
}

if (!function_exists('set_app_config')) {
    /**
     * Set application configuration value
     * 
     * @param string $key Configuration key
     * @param mixed $value Configuration value
     * @param string $type Value type (string, integer, boolean, json, text)
     * @param string $description Configuration description
     * @param bool $isPublic Whether config is safe for frontend
     * @return bool Success status
     */
    function set_app_config(string $key, $value, string $type = 'string', string $description = '', bool $isPublic = false): bool
    {
        try {
            $configModel = new \App\Models\AppConfigModel();
            return $configModel->setConfig($key, $value, $type, $description, $isPublic);
        } catch (\Exception $e) {
            log_message('error', 'Failed to set app config: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('app_name')) {
    /**
     * Get application name
     * 
     * @return string Application name
     */
    function app_name(): string
    {
        return app_config('app_name', 'ExamExcel');
    }
}

if (!function_exists('institution_name')) {
    /**
     * Get institution name
     * 
     * @return string Institution name
     */
    function institution_name(): string
    {
        return app_config('institution_name', 'ExamExcel');
    }
}

if (!function_exists('app_version')) {
    /**
     * Get application version
     * 
     * @return string Application version
     */
    function app_version(): string
    {
        return app_config('app_version', '1.0.0');
    }
}

if (!function_exists('app_logo')) {
    /**
     * Get application logo path
     * 
     * @return string Logo path or empty string
     */
    function app_logo(): string
    {
        return app_config('logo_path', '');
    }
}

if (!function_exists('app_favicon')) {
    /**
     * Get application favicon path
     * 
     * @return string Favicon path or empty string
     */
    function app_favicon(): string
    {
        return app_config('favicon_path', '');
    }
}

if (!function_exists('theme_color')) {
    /**
     * Get primary theme color
     * 
     * @return string Theme color hex code
     */
    function theme_color(): string
    {
        return app_config('theme_color', '#667eea');
    }
}

if (!function_exists('is_maintenance_mode')) {
    /**
     * Check if application is in maintenance mode
     * 
     * @return bool Maintenance mode status
     */
    function is_maintenance_mode(): bool
    {
        return (bool) app_config('maintenance_mode', false);
    }
}

if (!function_exists('is_registration_allowed')) {
    /**
     * Check if user registration is allowed
     * 
     * @return bool Registration allowed status
     */
    function is_registration_allowed(): bool
    {
        return (bool) app_config('allow_registration', false);
    }
}

if (!function_exists('get_public_app_config')) {
    /**
     * Get all public configuration values (safe for frontend)
     * 
     * @return array Public configuration values
     */
    function get_public_app_config(): array
    {
        try {
            $configModel = new \App\Models\AppConfigModel();
            return $configModel->getPublicConfigs();
        } catch (\Exception $e) {
            // Return basic fallback configuration
            return [
                'app_name' => env('app.name', 'CBT Examination System'),
                'institution_name' => env('app.institution', 'Educational Institution'),
                'app_version' => '1.0.0',
                'theme_color' => '#667eea',
                'maintenance_mode' => false,
                'allow_registration' => false
            ];
        }
    }
}

if (!function_exists('initialize_app_config')) {
    /**
     * Initialize default application configuration
     * 
     * @return bool Success status
     */
    function initialize_app_config(): bool
    {
        try {
            $configModel = new \App\Models\AppConfigModel();
            return $configModel->initializeDefaults();
        } catch (\Exception $e) {
            log_message('error', 'Failed to initialize app config: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('update_app_configs')) {
    /**
     * Bulk update application configurations
     * 
     * @param array $configs Key-value pairs of configurations
     * @return bool Success status
     */
    function update_app_configs(array $configs): bool
    {
        try {
            $configModel = new \App\Models\AppConfigModel();
            return $configModel->updateConfigs($configs);
        } catch (\Exception $e) {
            log_message('error', 'Failed to update app configs: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('get_installation_info')) {
    /**
     * Get installation information
     * 
     * @return array Installation details
     */
    function get_installation_info(): array
    {
        return [
            'installation_date' => app_config('installation_date'),
            'installer_version' => app_config('installer_version'),
            'app_version' => app_version(),
            'folder_name' => basename(ROOTPATH),
            'base_url' => base_url()
        ];
    }
}
