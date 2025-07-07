<?php

if (!function_exists('get_app_setting')) {
    /**
     * Get application setting value
     *
     * @param string $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function get_app_setting($key, $default = null)
    {
        static $settingsModel = null;
        static $settings = null;

        // Initialize settings model and cache settings on first call
        if ($settingsModel === null) {
            try {
                $settingsModel = new \App\Models\SettingsModel();
                $settings = $settingsModel->getAllSettings();

                // If no settings found, use defaults
                if (empty($settings)) {
                    $settings = [
                        'system_name' => env('app.name', 'ExamExcel'),
                        'system_version' => '1.0.0',
                        'institution_name' => env('app.institution', 'ExamExcel'),
                        'default_exam_duration' => 80,
                        'default_max_attempts' => 5,
                        'auto_submit_on_time_up' => true,
                        'backup_frequency' => 'weekly',
                        'backup_retention_days' => 30,
                        'app_locked' => false,
                        'locked_roles' => [],
                        'news_flash_enabled' => false,
                        'news_flash_content' => '',
                        'logo_path' => '',
                        'favicon_path' => '',
                        'calculator_enabled' => true,
                        'exam_pause_enabled' => false,
                        'student_id_prefix' => 'STD',
                        'browser_lockdown' => false,
                        'prevent_copy_paste' => false,
                        'disable_right_click' => false,
                        'require_proctoring' => false,
                        'max_tab_switches' => 5,
                        'max_window_focus_loss' => 3,
                        'max_monitor_warnings' => 2,
                        'max_security_violations' => 10,
                        'strict_security_mode' => true,
                        'auto_submit_on_violation' => true
                    ];
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to load app settings: ' . $e->getMessage());
                // Use default settings if model fails to load
                $settings = [
                    'system_name' => env('app.name', 'ExamExcel'),
                    'system_version' => '1.0.0',
                    'institution_name' => env('app.institution', 'ExamExcel'),
                    'default_exam_duration' => 80,
                    'default_max_attempts' => 5,
                    'auto_submit_on_time_up' => true,
                    'backup_frequency' => 'weekly',
                    'backup_retention_days' => 30,
                    'app_locked' => false,
                    'locked_roles' => [],
                    'news_flash_enabled' => false,
                    'news_flash_content' => '',
                    'logo_path' => '',
                    'favicon_path' => '',
                    'calculator_enabled' => true,
                    'exam_pause_enabled' => false,
                    'student_id_prefix' => 'STD',
                    'browser_lockdown' => false,
                    'prevent_copy_paste' => false,
                    'disable_right_click' => false,
                    'require_proctoring' => false
                ];
                $settingsModel = null; // Mark as failed to prevent further attempts
            }
        }

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('get_app_name')) {
    /**
     * Get application name (school name)
     *
     * @return string
     */
    function get_app_name()
    {
        // Try to get institution name first (set by admin/principal), fallback to legacy school_name, then system_name, then default
        $institutionName = get_app_setting('institution_name', '');
        if (!empty($institutionName)) {
            return $institutionName;
        }

        $schoolName = get_app_setting('school_name', '');
        if (!empty($schoolName)) {
            return $schoolName;
        }

        return get_app_setting('system_name', 'ExamExcel');
    }
}

if (!function_exists('get_institution_name')) {
    /**
     * Get institution name (school name)
     *
     * @return string
     */
    function get_institution_name()
    {
        // Use the same logic as get_app_name for consistency
        return get_app_name();
    }
}

if (!function_exists('get_app_logo')) {
    /**
     * Get application logo URL
     *
     * @return string
     */
    function get_app_logo()
    {
        $logoPath = get_app_setting('logo_path', '');
        return $logoPath ? base_url($logoPath) : '';
    }
}

if (!function_exists('get_app_favicon')) {
    /**
     * Get application favicon URL
     *
     * @return string
     */
    function get_app_favicon()
    {
        $faviconPath = get_app_setting('favicon_path', '');
        return $faviconPath ? base_url($faviconPath) : '';
    }
}

if (!function_exists('get_news_flash')) {
    /**
     * Get news flash content if enabled
     *
     * @return array
     */
    function get_news_flash()
    {
        return [
            'enabled' => get_app_setting('news_flash_enabled', false),
            'content' => get_app_setting('news_flash_content', '')
        ];
    }
}

if (!function_exists('get_setting')) {
    /**
     * Alias for get_app_setting for backward compatibility
     *
     * @param string $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        return get_app_setting($key, $default);
    }
}

if (!function_exists('is_app_locked')) {
    /**
     * Check if app is locked for specific role
     *
     * @param string $role User role to check
     * @return bool
     */
    function is_app_locked($role = null)
    {
        $appLocked = get_app_setting('app_locked', false);

        if (!$appLocked || !$role) {
            return $appLocked;
        }

        $lockedRoles = get_app_setting('locked_roles', []);

        if (is_string($lockedRoles)) {
            $lockedRoles = json_decode($lockedRoles, true) ?: [];
        }

        return in_array($role, $lockedRoles);
    }
}

if (!function_exists('get_theme_settings')) {
    /**
     * Get theme settings with defaults
     *
     * @return array
     */
    function get_theme_settings()
    {
        try {
            $settingsModel = new \App\Models\SettingsModel();
            $themeConfig = new \App\Config\UITheme();

            // Get current theme from database
            $currentTheme = $settingsModel->getSetting('current_theme', 'purple');
            $customTheme = $settingsModel->getSetting('custom_theme_settings', []);
            $fontFamily = $settingsModel->getSetting('theme_font_family', "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif");
            $fontSize = $settingsModel->getSetting('theme_font_size', '14px');

            // Get predefined themes
            $predefinedThemes = $themeConfig->getPredefinedThemes();

            if ($currentTheme === 'custom' && !empty($customTheme)) {
                // Use custom theme settings
                $primaryColor = $customTheme['primaryColor'] ?? '#6f42c1';
            } else if (isset($predefinedThemes[$currentTheme])) {
                // Use predefined theme
                $primaryColor = $predefinedThemes[$currentTheme]['primaryColor'];
            } else {
                // Fallback to purple theme
                $primaryColor = '#6f42c1';
            }

            // Convert hex color to RGB
            $rgb = hex_to_rgb($primaryColor);

            return [
                'primary_color' => $primaryColor,
                'primary_light' => lighten_color($primaryColor, 10),
                'primary_dark' => darken_color($primaryColor, 15),
                'primary_color_rgb' => $rgb,
                'font_family' => $fontFamily,
                'font_size' => $fontSize
            ];
        } catch (\Exception $e) {
            // Return defaults if there's an error
            return [
                'primary_color' => '#6f42c1',
                'primary_light' => '#8a63d2',
                'primary_dark' => '#5a359a',
                'primary_color_rgb' => '111, 66, 193',
                'font_family' => "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
                'font_size' => '14px'
            ];
        }
    }
}

if (!function_exists('hex_to_rgb')) {
    /**
     * Convert hex color to RGB string
     *
     * @param string $hex
     * @return string
     */
    function hex_to_rgb($hex)
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "$r, $g, $b";
    }
}

if (!function_exists('lighten_color')) {
    /**
     * Lighten a hex color by percentage
     *
     * @param string $hex
     * @param int $percent
     * @return string
     */
    function lighten_color($hex, $percent)
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, $r + ($r * $percent / 100));
        $g = min(255, $g + ($g * $percent / 100));
        $b = min(255, $b + ($b * $percent / 100));

        return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
    }
}

if (!function_exists('darken_color')) {
    /**
     * Darken a hex color by percentage
     *
     * @param string $hex
     * @param int $percent
     * @return string
     */
    function darken_color($hex, $percent)
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, $r - ($r * $percent / 100));
        $g = max(0, $g - ($g * $percent / 100));
        $b = max(0, $b - ($b * $percent / 100));

        return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
    }
}
