<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .theme-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    
    .theme-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .theme-card.active {
        border: 3px solid var(--primary-color);
        box-shadow: 0 5px 20px rgba(var(--primary-color-rgb), 0.3);
    }
    
    .theme-preview {
        height: 120px;
        position: relative;
        overflow: hidden;
    }

    /* Special preview styles for gradient theme */
    .theme-card[data-theme="gradient"] .theme-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .theme-card[data-theme="gradient"] .theme-content {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px);
    }

    .theme-card[data-theme="gradient"] .theme-card-mini {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Special preview styles for white theme */
    .theme-card[data-theme="white"] .theme-preview {
        background: #ffffff;
        border: 1px solid #e5e7eb;
    }

    .theme-card[data-theme="white"] .theme-sidebar {
        background: #ffffff !important;
        border-right: 1px solid #e5e7eb;
    }

    .theme-card[data-theme="white"] .theme-content {
        background: #ffffff !important;
    }

    .theme-card[data-theme="white"] .theme-card-mini {
        background: #ffffff;
        border: 1px solid #e5e7eb;
    }
    
    .theme-sidebar {
        width: 30%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
    }
    
    .theme-content {
        width: 70%;
        height: 100%;
        position: absolute;
        right: 0;
        top: 0;
        background: #f8f9fa;
        padding: 8px;
    }
    
    .theme-card-mini {
        width: 100%;
        height: 15px;
        border-radius: 4px;
        margin-bottom: 4px;
        background: rgba(255,255,255,0.9);
    }
    
    .theme-button-mini {
        width: 40%;
        height: 12px;
        border-radius: 6px;
        margin-top: 8px;
    }
    
    .color-picker-container {
        position: relative;
    }
    
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        cursor: pointer;
        display: inline-block;
        margin-right: 10px;
    }
    
    .font-preview {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-top: 10px;
        background: white;
    }
    
    .settings-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        color: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
    }
    
    .theme-check {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .theme-card.active .theme-check {
        opacity: 1;
    }
    
    .custom-theme-section {
        display: none;
    }
    
    .custom-theme-section.show {
        display: block;
    }
    
    .color-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .color-input-group input[type="color"] {
        width: 50px;
        height: 40px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
    
    .color-input-group input[type="text"] {
        flex: 1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Theme Settings</h4>
                <p class="text-muted mb-0">Customize the appearance and colors of your application</p>
            </div>
            <a href="<?= base_url('admin/settings') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Settings
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?= base_url('admin/theme-settings/update') ?>">
    <?= csrf_field() ?>
    
    <!-- Predefined Themes Section -->
    <div class="settings-section">
        <h5 class="fw-bold mb-3">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">palette</i>
            Predefined Themes
        </h5>
        <p class="text-muted mb-4">Choose from our carefully crafted color themes</p>
        
        <div class="row">
            <?php foreach ($predefinedThemes as $key => $theme): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="theme-card <?= $currentTheme === $key ? 'active' : '' ?>"
                     data-theme="<?= $key ?>" onclick="selectTheme('<?= $key ?>')">
                    <div class="theme-preview">
                        <div class="theme-sidebar" style="background: <?= $theme['sidebarBg'] ?>"></div>
                        <div class="theme-content">
                            <div class="theme-card-mini"></div>
                            <div class="theme-card-mini" style="width: 80%;"></div>
                            <div class="theme-button-mini" style="background: <?= $theme['primaryColor'] ?>"></div>
                        </div>
                    </div>
                    <div class="theme-check">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="p-3">
                        <h6 class="fw-semibold mb-1"><?= $theme['name'] ?></h6>
                        <small class="text-muted"><?= $theme['description'] ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- Custom Theme Option -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="theme-card <?= $currentTheme === 'custom' ? 'active' : '' ?>" 
                     data-theme="custom" onclick="selectTheme('custom')">
                    <div class="theme-preview">
                        <div class="theme-sidebar" style="background: linear-gradient(45deg, #667eea, #764ba2)"></div>
                        <div class="theme-content">
                            <div class="theme-card-mini"></div>
                            <div class="theme-card-mini" style="width: 80%;"></div>
                            <div class="theme-button-mini" style="background: #667eea"></div>
                        </div>
                    </div>
                    <div class="theme-check">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="p-3">
                        <h6 class="fw-semibold mb-1">Custom Theme</h6>
                        <small class="text-muted">Create your own unique color scheme</small>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" name="selected_theme" id="selectedTheme" value="<?= $currentTheme ?>">
    </div>

    <!-- Custom Theme Configuration -->
    <div class="settings-section custom-theme-section <?= $currentTheme === 'custom' ? 'show' : '' ?>" id="customThemeSection">
        <h5 class="fw-bold mb-3">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">tune</i>
            Custom Theme Configuration
        </h5>
        <p class="text-muted mb-4">Customize colors to match your brand</p>

        <div class="row">
            <!-- Primary Colors -->
            <div class="col-lg-6 mb-4">
                <h6 class="fw-semibold mb-3">Primary Colors</h6>

                <div class="color-input-group">
                    <input type="color" name="custom_primary_color" id="customPrimaryColor"
                           value="<?= $customTheme['primaryColor'] ?? '#A05AFF' ?>">
                    <input type="text" class="form-control" name="custom_primary_color_hex"
                           value="<?= $customTheme['primaryColor'] ?? '#A05AFF' ?>"
                           placeholder="#A05AFF">
                    <label class="form-label mb-0">Primary Color</label>
                </div>

                <div class="color-input-group">
                    <input type="color" name="custom_primary_dark" id="customPrimaryDark"
                           value="<?= $customTheme['primaryDark'] ?? '#8B47E6' ?>">
                    <input type="text" class="form-control" name="custom_primary_dark_hex"
                           value="<?= $customTheme['primaryDark'] ?? '#8B47E6' ?>"
                           placeholder="#8B47E6">
                    <label class="form-label mb-0">Primary Dark</label>
                </div>

                <div class="color-input-group">
                    <input type="color" name="custom_primary_light" id="customPrimaryLight"
                           value="<?= $customTheme['primaryLight'] ?? '#B574FF' ?>">
                    <input type="text" class="form-control" name="custom_primary_light_hex"
                           value="<?= $customTheme['primaryLight'] ?? '#B574FF' ?>"
                           placeholder="#B574FF">
                    <label class="form-label mb-0">Primary Light</label>
                </div>
            </div>

            <!-- Background Colors -->
            <div class="col-lg-6 mb-4">
                <h6 class="fw-semibold mb-3">Background Colors</h6>

                <div class="color-input-group">
                    <input type="color" name="custom_body_bg" id="customBodyBg"
                           value="<?= $customTheme['bodyBg'] ?? '#f5f5f5' ?>">
                    <input type="text" class="form-control" name="custom_body_bg_hex"
                           value="<?= $customTheme['bodyBg'] ?? '#f5f5f5' ?>"
                           placeholder="#f5f5f5">
                    <label class="form-label mb-0">Body Background</label>
                </div>

                <div class="color-input-group">
                    <input type="color" name="custom_card_bg" id="customCardBg"
                           value="<?= $customTheme['cardBg'] ?? '#ffffff' ?>">
                    <input type="text" class="form-control" name="custom_card_bg_hex"
                           value="<?= $customTheme['cardBg'] ?? '#ffffff' ?>"
                           placeholder="#ffffff">
                    <label class="form-label mb-0">Card Background</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sidebar Background</label>
                    <input type="text" class="form-control" name="custom_sidebar_bg"
                           value="<?= $customTheme['sidebarBg'] ?? 'linear-gradient(180deg, rgba(160, 90, 255, 0.75) 0%, rgba(160, 90, 255, 0.85) 100%)' ?>"
                           placeholder="linear-gradient(180deg, rgba(160, 90, 255, 0.75) 0%, rgba(160, 90, 255, 0.85) 100%)">
                    <small class="text-muted">Use linear-gradient() for gradient backgrounds or rgba() for solid colors</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Settings -->
    <div class="settings-section">
        <h5 class="fw-bold mb-3">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">text_fields</i>
            Typography Settings
        </h5>
        <p class="text-muted mb-4">Customize fonts and text appearance</p>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <label class="form-label">Font Family</label>
                <select class="form-select" name="font_family" id="fontFamily">
                    <option value="'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif"
                            <?= $fontFamily === "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" ? 'selected' : '' ?>>
                        Inter (Default)
                    </option>
                    <option value="'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
                            <?= $fontFamily === "'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif" ? 'selected' : '' ?>>
                        Roboto
                    </option>
                    <option value="'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
                            <?= $fontFamily === "'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif" ? 'selected' : '' ?>>
                        Open Sans
                    </option>
                    <option value="'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
                            <?= $fontFamily === "'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif" ? 'selected' : '' ?>>
                        Poppins
                    </option>
                    <option value="'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
                            <?= $fontFamily === "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif" ? 'selected' : '' ?>>
                        Nunito
                    </option>
                </select>
            </div>

            <div class="col-lg-6 mb-4">
                <label class="form-label">Base Font Size</label>
                <select class="form-select" name="font_size" id="fontSize">
                    <option value="12px" <?= $fontSize === '12px' ? 'selected' : '' ?>>Small (12px)</option>
                    <option value="14px" <?= $fontSize === '14px' ? 'selected' : '' ?>>Default (14px)</option>
                    <option value="16px" <?= $fontSize === '16px' ? 'selected' : '' ?>>Large (16px)</option>
                    <option value="18px" <?= $fontSize === '18px' ? 'selected' : '' ?>>Extra Large (18px)</option>
                </select>
            </div>
        </div>

        <div class="font-preview" id="fontPreview">
            <h6>Font Preview</h6>
            <p class="mb-2">This is how your text will appear with the selected font settings.</p>
            <small class="text-muted">Sample text in different sizes and weights.</small>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="resetToDefault()">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">refresh</i>Reset to Default
                </button>
                <div>
                    <button type="button" class="btn btn-info me-2" onclick="previewTheme()">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">visibility</i>Preview Changes
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Save Theme Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize color pickers and sync with text inputs
    initializeColorPickers();

    // Initialize font preview
    updateFontPreview();

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

function selectTheme(themeKey) {
    // Remove active class from all theme cards
    document.querySelectorAll('.theme-card').forEach(card => {
        card.classList.remove('active');
    });

    // Add active class to selected theme
    document.querySelector(`[data-theme="${themeKey}"]`).classList.add('active');

    // Update hidden input
    document.getElementById('selectedTheme').value = themeKey;

    // Show/hide custom theme section
    const customSection = document.getElementById('customThemeSection');
    if (themeKey === 'custom') {
        customSection.classList.add('show');
    } else {
        customSection.classList.remove('show');
    }
}

function initializeColorPickers() {
    // Sync color pickers with text inputs
    const colorInputs = [
        'customPrimaryColor', 'customPrimaryDark', 'customPrimaryLight',
        'customBodyBg', 'customCardBg'
    ];

    colorInputs.forEach(inputId => {
        const colorInput = document.getElementById(inputId);
        const textInput = document.querySelector(`input[name="${inputId.replace('custom', 'custom_').toLowerCase()}_hex"]`);

        if (colorInput && textInput) {
            // Update text input when color picker changes
            colorInput.addEventListener('change', function() {
                textInput.value = this.value;
            });

            // Update color picker when text input changes
            textInput.addEventListener('change', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    colorInput.value = this.value;
                }
            });
        }
    });
}

function updateFontPreview() {
    const fontFamily = document.getElementById('fontFamily').value;
    const fontSize = document.getElementById('fontSize').value;
    const preview = document.getElementById('fontPreview');

    preview.style.fontFamily = fontFamily;
    preview.style.fontSize = fontSize;
}

// Update font preview when selections change
document.getElementById('fontFamily').addEventListener('change', updateFontPreview);
document.getElementById('fontSize').addEventListener('change', updateFontPreview);

function previewTheme() {
    // Get current form data
    const formData = new FormData(document.querySelector('form'));

    // Apply preview styles temporarily
    const selectedTheme = formData.get('selected_theme');

    if (selectedTheme === 'custom') {
        // Apply custom colors for preview
        const primaryColor = formData.get('custom_primary_color_hex') || '#A05AFF';
        const primaryDark = formData.get('custom_primary_dark_hex') || '#8B47E6';
        const sidebarBg = formData.get('custom_sidebar_bg') || 'rgba(160, 90, 255, 0.85)';

        // Create temporary style element
        const previewStyle = document.createElement('style');
        previewStyle.id = 'theme-preview-style';
        previewStyle.innerHTML = `
            :root {
                --primary-color: ${primaryColor} !important;
                --primary-dark: ${primaryDark} !important;
                --sidebar-bg: ${sidebarBg} !important;
            }
            .sidenav {
                background: ${sidebarBg} !important;
            }
        `;

        // Remove existing preview style
        const existingPreview = document.getElementById('theme-preview-style');
        if (existingPreview) {
            existingPreview.remove();
        }

        document.head.appendChild(previewStyle);

        // Show preview notification
        showNotification('Theme preview applied! Save to make changes permanent.', 'info');

        // Remove preview after 10 seconds
        setTimeout(() => {
            previewStyle.remove();
            showNotification('Preview ended. Save your changes to apply permanently.', 'warning');
        }, 10000);
    } else {
        showNotification('Preview is available for custom themes only.', 'info');
    }
}

function resetToDefault() {
    if (confirm('Are you sure you want to reset all theme settings to default? This action cannot be undone.')) {
        window.location.href = '<?= base_url('admin/theme-settings/reset') ?>';
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
<?= $this->endSection() ?>
