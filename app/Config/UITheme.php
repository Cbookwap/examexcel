<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class UITheme extends BaseConfig
{
    // Default Theme Colors (Purple Theme)
    public $primaryColor = '#A05AFF';      // Purple Primary
    public $primaryDark = '#8B47E6';       // Darker Purple
    public $primaryLight = '#B574FF';      // Lighter Purple

    // Secondary Colors - Minimal for clean design
    public $secondaryColor = '#6c757d';    // Gray
    public $secondaryDark = '#495057';     // Darker Gray
    public $secondaryLight = '#adb5bd';    // Lighter Gray

    // Status Colors - Unified with primary theme
    public $successColor = '#A05AFF';      // Same as primary for consistency
    public $warningColor = '#FE9496';      // Soft coral (minimal use)
    public $dangerColor = '#FE9496';       // Soft coral (minimal use)
    public $infoColor = '#A05AFF';         // Same as primary for consistency

    // Neutral Colors
    public $darkColor = '#1f2937';         // Dark Gray
    public $lightColor = '#f8fafc';        // Very Light Gray
    public $whiteColor = '#ffffff';        // White
    public $blackColor = '#000000';        // Black

    // Background Colors - Material Dashboard Style
    public $bodyBg = '#f5f5f5';            // Material Light Gray Background
    public $cardBg = '#ffffff';            // White Card Background
    public $sidebarBg = 'linear-gradient(180deg, rgba(160, 90, 255, 0.75) 0%, rgba(160, 90, 255, 0.85) 100%)';  // Dynamic Sidebar Background
    public $navbarBg = '#ffffff';          // White Navbar

    // Text Colors
    public $textPrimary = '#1f2937';       // Dark Gray Text
    public $textSecondary = '#6b7280';     // Medium Gray Text
    public $textMuted = '#9ca3af';         // Light Gray Text
    public $textWhite = '#ffffff';         // White Text

    // Border Colors
    public $borderColor = '#e5e7eb';       // Light Border
    public $borderDark = '#d1d5db';        // Darker Border

    // Predefined Theme Configurations
    public $predefinedThemes = [
        'purple' => [
            'name' => 'Purple Professional',
            'primaryColor' => '#A05AFF',
            'primaryDark' => '#8B47E6',
            'primaryLight' => '#B574FF',
            'sidebarBg' => 'linear-gradient(180deg, rgba(160, 90, 255, 0.75) 0%, rgba(160, 90, 255, 0.85) 100%)',
            'description' => 'Modern purple theme with professional appeal'
        ],
        'blue' => [
            'name' => 'Ocean Blue',
            'primaryColor' => '#1e40af',
            'primaryDark' => '#1e3a8a',
            'primaryLight' => '#3b82f6',
            'sidebarBg' => 'linear-gradient(180deg, rgba(30, 64, 175, 0.75) 0%, rgba(30, 64, 175, 0.85) 100%)',
            'description' => 'Classic blue theme for corporate environments'
        ],
        'emerald' => [
            'name' => 'Emerald Green',
            'primaryColor' => '#059669',
            'primaryDark' => '#047857',
            'primaryLight' => '#10b981',
            'sidebarBg' => 'linear-gradient(180deg, rgba(5, 150, 105, 0.75) 0%, rgba(5, 150, 105, 0.85) 100%)',
            'description' => 'Fresh green theme promoting growth and success'
        ],
        'orange' => [
            'name' => 'Sunset Orange',
            'primaryColor' => '#ea580c',
            'primaryDark' => '#c2410c',
            'primaryLight' => '#f97316',
            'sidebarBg' => 'linear-gradient(180deg, rgba(234, 88, 12, 0.75) 0%, rgba(234, 88, 12, 0.85) 100%)',
            'description' => 'Energetic orange theme for dynamic environments'
        ],
        'slate' => [
            'name' => 'Professional Slate',
            'primaryColor' => '#475569',
            'primaryDark' => '#334155',
            'primaryLight' => '#64748b',
            'sidebarBg' => 'linear-gradient(180deg, rgba(71, 85, 105, 0.75) 0%, rgba(71, 85, 105, 0.85) 100%)',
            'description' => 'Sophisticated slate theme for professional settings'
        ],
        'gradient' => [
            'name' => 'Gradient Dream',
            'primaryColor' => '#6366f1',
            'primaryDark' => '#4f46e5',
            'primaryLight' => '#8b5cf6',
            'sidebarBg' => 'linear-gradient(135deg, rgba(99, 102, 241, 0.8) 0%, rgba(139, 92, 246, 0.8) 100%)',
            'bodyBg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'cardBg' => 'rgba(255, 255, 255, 0.9)',
            'navbarBg' => 'rgba(255, 255, 255, 0.95)',
            'description' => 'Beautiful gradient background with glassmorphism effects'
        ],
        'white' => [
            'name' => 'Clean White',
            'primaryColor' => '#6366f1',
            'primaryDark' => '#4f46e5',
            'primaryLight' => '#8b5cf6',
            'sidebarBg' => '#ffffff',
            'bodyBg' => '#ffffff',
            'cardBg' => '#ffffff',
            'navbarBg' => '#ffffff',
            'borderColor' => '#e5e7eb',
            'textPrimary' => '#1f2937',
            'textSecondary' => '#6b7280',
            'description' => 'Minimalist white theme with clean white sidebar'
        ]
    ];

    // Typography
    public $fontFamily = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    public $fontSizeBase = '14px';
    public $fontSizeSmall = '12px';
    public $fontSizeLarge = '16px';
    public $fontSizeXLarge = '18px';
    public $fontSizeXXLarge = '24px';

    // Font Weights
    public $fontWeightNormal = '400';
    public $fontWeightMedium = '500';
    public $fontWeightSemibold = '600';
    public $fontWeightBold = '700';

    // Spacing
    public $spacingXs = '0.25rem';         // 4px
    public $spacingSm = '0.5rem';          // 8px
    public $spacingMd = '1rem';            // 16px
    public $spacingLg = '1.5rem';          // 24px
    public $spacingXl = '2rem';            // 32px
    public $spacingXxl = '3rem';           // 48px

    // Border Radius
    public $borderRadiusSm = '0.25rem';    // 4px
    public $borderRadiusMd = '0.375rem';   // 6px
    public $borderRadiusLg = '0.5rem';     // 8px
    public $borderRadiusXl = '0.75rem';    // 12px
    public $borderRadiusXxl = '1rem';      // 16px

    // Shadows
    public $shadowSm = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
    public $shadowMd = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
    public $shadowLg = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
    public $shadowXl = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';

    // Component Sizes
    public $buttonHeightSm = '2rem';       // 32px
    public $buttonHeightMd = '2.5rem';     // 40px
    public $buttonHeightLg = '3rem';       // 48px

    public $inputHeightSm = '2rem';        // 32px
    public $inputHeightMd = '2.5rem';      // 40px
    public $inputHeightLg = '3rem';        // 48px

    // Layout
    public $sidebarWidth = '16rem';        // 256px
    public $sidebarCollapsedWidth = '4rem'; // 64px
    public $navbarHeight = '4rem';         // 64px
    public $containerMaxWidth = '1200px';

    // Animation
    public $transitionDuration = '0.2s';
    public $transitionEasing = 'ease-in-out';

    // CBT Specific Colors - Aligned with Material Theme
    public $examActiveColor = '#26a69a';   // Primary color for active exams
    public $examPendingColor = '#ff9800';  // Material Orange for pending exams
    public $examCompletedColor = '#78909c'; // Light gray for completed exams
    public $examCancelledColor = '#f44336'; // Material Red for cancelled exams

    public $questionCorrectColor = '#26a69a'; // Primary color for correct answers
    public $questionIncorrectColor = '#f44336'; // Material Red for incorrect answers
    public $questionUnansweredColor = '#78909c'; // Light gray for unanswered
    public $questionFlaggedColor = '#ff9800'; // Material Orange for flagged questions

    // Security Alert Colors - Minimal color variation
    public $securityLowColor = '#26a69a';     // Primary color
    public $securityMediumColor = '#ff9800';  // Material Orange
    public $securityHighColor = '#f44336';    // Material Red
    public $securityCriticalColor = '#d32f2f'; // Darker Material Red

    /**
     * Load theme settings from database and apply them
     */
    public function loadThemeFromDatabase()
    {
        $settingsModel = new \App\Models\SettingsModel();

        // Get current theme settings
        $currentTheme = $settingsModel->getSetting('current_theme', 'purple');
        $customTheme = $settingsModel->getSetting('custom_theme_settings', []);

        // Apply predefined theme if selected
        if (isset($this->predefinedThemes[$currentTheme])) {
            $theme = $this->predefinedThemes[$currentTheme];
            $this->primaryColor = $theme['primaryColor'];
            $this->primaryDark = $theme['primaryDark'];
            $this->primaryLight = $theme['primaryLight'];
            $this->sidebarBg = $theme['sidebarBg'];

            // Apply additional background properties if they exist
            if (isset($theme['bodyBg'])) {
                $this->bodyBg = $theme['bodyBg'];
            }
            if (isset($theme['cardBg'])) {
                $this->cardBg = $theme['cardBg'];
            }
            if (isset($theme['navbarBg'])) {
                $this->navbarBg = $theme['navbarBg'];
            }
            if (isset($theme['borderColor'])) {
                $this->borderColor = $theme['borderColor'];
            }
            if (isset($theme['textPrimary'])) {
                $this->textPrimary = $theme['textPrimary'];
            }
            if (isset($theme['textSecondary'])) {
                $this->textSecondary = $theme['textSecondary'];
            }

            // Update status colors to match primary
            $this->successColor = $theme['primaryColor'];
            $this->infoColor = $theme['primaryColor'];
        }

        // Apply custom theme settings if they exist
        if (!empty($customTheme)) {
            foreach ($customTheme as $property => $value) {
                if (property_exists($this, $property) && !empty($value)) {
                    $this->$property = $value;
                }
            }
        }

        // Load font settings
        $this->fontFamily = $settingsModel->getSetting('theme_font_family', $this->fontFamily);
        $this->fontSizeBase = $settingsModel->getSetting('theme_font_size', $this->fontSizeBase);
    }

    /**
     * Get all theme variables as CSS custom properties
     */
    public function getCSSVariables(): string
    {
        // Load theme from database first
        $this->loadThemeFromDatabase();

        $css = ":root {\n";

        // Colors
        $css .= "  --primary-color: {$this->primaryColor};\n";
        $css .= "  --primary-dark: {$this->primaryDark};\n";
        $css .= "  --primary-light: {$this->primaryLight};\n";

        // RGB values for rgba() usage
        $primaryRgb = $this->hexToRgb($this->primaryColor);
        $css .= "  --primary-color-rgb: {$primaryRgb};\n";
        $css .= "  --secondary-color: {$this->secondaryColor};\n";
        $css .= "  --secondary-dark: {$this->secondaryDark};\n";
        $css .= "  --secondary-light: {$this->secondaryLight};\n";

        $css .= "  --success-color: {$this->successColor};\n";
        $css .= "  --warning-color: {$this->warningColor};\n";
        $css .= "  --danger-color: {$this->dangerColor};\n";
        $css .= "  --info-color: {$this->infoColor};\n";

        $css .= "  --dark-color: {$this->darkColor};\n";
        $css .= "  --light-color: {$this->lightColor};\n";
        $css .= "  --white-color: {$this->whiteColor};\n";
        $css .= "  --black-color: {$this->blackColor};\n";

        // Backgrounds
        $css .= "  --body-bg: {$this->bodyBg};\n";
        $css .= "  --card-bg: {$this->cardBg};\n";
        $css .= "  --sidebar-bg: {$this->sidebarBg};\n";
        $css .= "  --navbar-bg: {$this->navbarBg};\n";

        // Text
        $css .= "  --text-primary: {$this->textPrimary};\n";
        $css .= "  --text-secondary: {$this->textSecondary};\n";
        $css .= "  --text-muted: {$this->textMuted};\n";
        $css .= "  --text-white: {$this->textWhite};\n";

        // Borders
        $css .= "  --border-color: {$this->borderColor};\n";
        $css .= "  --border-dark: {$this->borderDark};\n";

        // Typography
        $css .= "  --font-family: {$this->fontFamily};\n";
        $css .= "  --font-size-base: {$this->fontSizeBase};\n";
        $css .= "  --font-size-sm: {$this->fontSizeSmall};\n";
        $css .= "  --font-size-lg: {$this->fontSizeLarge};\n";
        $css .= "  --font-size-xl: {$this->fontSizeXLarge};\n";
        $css .= "  --font-size-xxl: {$this->fontSizeXXLarge};\n";

        // Font Weights
        $css .= "  --font-weight-normal: {$this->fontWeightNormal};\n";
        $css .= "  --font-weight-medium: {$this->fontWeightMedium};\n";
        $css .= "  --font-weight-semibold: {$this->fontWeightSemibold};\n";
        $css .= "  --font-weight-bold: {$this->fontWeightBold};\n";

        // Spacing
        $css .= "  --spacing-xs: {$this->spacingXs};\n";
        $css .= "  --spacing-sm: {$this->spacingSm};\n";
        $css .= "  --spacing-md: {$this->spacingMd};\n";
        $css .= "  --spacing-lg: {$this->spacingLg};\n";
        $css .= "  --spacing-xl: {$this->spacingXl};\n";
        $css .= "  --spacing-xxl: {$this->spacingXxl};\n";

        // Border Radius
        $css .= "  --border-radius-sm: {$this->borderRadiusSm};\n";
        $css .= "  --border-radius-md: {$this->borderRadiusMd};\n";
        $css .= "  --border-radius-lg: {$this->borderRadiusLg};\n";
        $css .= "  --border-radius-xl: {$this->borderRadiusXl};\n";
        $css .= "  --border-radius-xxl: {$this->borderRadiusXxl};\n";

        // Shadows
        $css .= "  --shadow-sm: {$this->shadowSm};\n";
        $css .= "  --shadow-md: {$this->shadowMd};\n";
        $css .= "  --shadow-lg: {$this->shadowLg};\n";
        $css .= "  --shadow-xl: {$this->shadowXl};\n";

        // Component Sizes
        $css .= "  --button-height-sm: {$this->buttonHeightSm};\n";
        $css .= "  --button-height-md: {$this->buttonHeightMd};\n";
        $css .= "  --button-height-lg: {$this->buttonHeightLg};\n";

        $css .= "  --input-height-sm: {$this->inputHeightSm};\n";
        $css .= "  --input-height-md: {$this->inputHeightMd};\n";
        $css .= "  --input-height-lg: {$this->inputHeightLg};\n";

        // Layout
        $css .= "  --sidebar-width: {$this->sidebarWidth};\n";
        $css .= "  --sidebar-collapsed-width: {$this->sidebarCollapsedWidth};\n";
        $css .= "  --navbar-height: {$this->navbarHeight};\n";
        $css .= "  --container-max-width: {$this->containerMaxWidth};\n";

        // Animation
        $css .= "  --transition-duration: {$this->transitionDuration};\n";
        $css .= "  --transition-easing: {$this->transitionEasing};\n";

        // CBT Specific
        $css .= "  --exam-active-color: {$this->examActiveColor};\n";
        $css .= "  --exam-pending-color: {$this->examPendingColor};\n";
        $css .= "  --exam-completed-color: {$this->examCompletedColor};\n";
        $css .= "  --exam-cancelled-color: {$this->examCancelledColor};\n";

        $css .= "  --question-correct-color: {$this->questionCorrectColor};\n";
        $css .= "  --question-incorrect-color: {$this->questionIncorrectColor};\n";
        $css .= "  --question-unanswered-color: {$this->questionUnansweredColor};\n";
        $css .= "  --question-flagged-color: {$this->questionFlaggedColor};\n";

        $css .= "  --security-low-color: {$this->securityLowColor};\n";
        $css .= "  --security-medium-color: {$this->securityMediumColor};\n";
        $css .= "  --security-high-color: {$this->securityHighColor};\n";
        $css .= "  --security-critical-color: {$this->securityCriticalColor};\n";

        $css .= "}\n";

        return $css;
    }

    /**
     * Convert hex color to RGB values
     */
    private function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "{$r}, {$g}, {$b}";
    }

    /**
     * Get predefined themes for selection
     */
    public function getPredefinedThemes(): array
    {
        return $this->predefinedThemes;
    }

    /**
     * Apply a predefined theme
     */
    public function applyPredefinedTheme(string $themeKey): bool
    {
        if (!isset($this->predefinedThemes[$themeKey])) {
            return false;
        }

        $settingsModel = new \App\Models\SettingsModel();

        // Save the selected theme
        $settingsModel->setSetting('current_theme', $themeKey, 'string', 'Currently selected theme');

        // Clear any custom theme settings when applying predefined theme
        $settingsModel->setSetting('custom_theme_settings', [], 'json', 'Custom theme configuration');

        return true;
    }

    /**
     * Save custom theme settings
     */
    public function saveCustomTheme(array $themeSettings): bool
    {
        $settingsModel = new \App\Models\SettingsModel();

        // Validate theme settings
        $validProperties = [
            'primaryColor', 'primaryDark', 'primaryLight',
            'secondaryColor', 'secondaryDark', 'secondaryLight',
            'successColor', 'warningColor', 'dangerColor', 'infoColor',
            'bodyBg', 'cardBg', 'sidebarBg', 'navbarBg',
            'textPrimary', 'textSecondary', 'textMuted', 'textWhite',
            'borderColor', 'borderDark'
        ];

        $filteredSettings = [];
        foreach ($themeSettings as $property => $value) {
            if (in_array($property, $validProperties) && !empty($value)) {
                $filteredSettings[$property] = $value;
            }
        }

        // Save custom theme
        $settingsModel->setSetting('current_theme', 'custom', 'string', 'Currently selected theme');
        $settingsModel->setSetting('custom_theme_settings', $filteredSettings, 'json', 'Custom theme configuration');

        return true;
    }

    /**
     * Save font settings
     */
    public function saveFontSettings(string $fontFamily, string $fontSize): bool
    {
        $settingsModel = new \App\Models\SettingsModel();

        $settingsModel->setSetting('theme_font_family', $fontFamily, 'string', 'Theme font family');
        $settingsModel->setSetting('theme_font_size', $fontSize, 'string', 'Theme base font size');

        return true;
    }

    /**
     * Reset theme to default
     */
    public function resetToDefault(): bool
    {
        $settingsModel = new \App\Models\SettingsModel();

        $settingsModel->setSetting('current_theme', 'purple', 'string', 'Currently selected theme');
        $settingsModel->setSetting('custom_theme_settings', [], 'json', 'Custom theme configuration');
        $settingsModel->setSetting('theme_font_family', $this->fontFamily, 'string', 'Theme font family');
        $settingsModel->setSetting('theme_font_size', $this->fontSizeBase, 'string', 'Theme base font size');

        return true;
    }
}
