<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .settings-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .settings-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
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
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }
    .exam-types-list .list-group-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .exam-types-list .list-group-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .exam-types-list .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
    .setting-section {
        border-bottom: 1px solid #eee;
        padding-bottom: 2rem;
        margin-bottom: 2rem;
    }
    .setting-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .form-switch .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .form-switch .form-check-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-color-rgb), 0.25);
    }
    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
    }
    .file-upload-area {
        border: 2px dashed var(--primary-color);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .file-upload-area:hover {
        background-color: #e9ecef;
    }
    .news-flash-preview {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }
    .image-preview-container {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .image-preview-container:hover {
        background-color: #e9ecef;
    }
    .current-image-container {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">System Settings</h4>
                <p class="text-muted mb-0">Configure system preferences and settings</p>
            </div>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
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

<!-- Settings Form -->
<form action="<?= base_url('admin/settings') ?>" method="post" enctype="multipart/form-data" id="settingsForm">
    <?= csrf_field() ?>

    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card settings-card h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-semibold text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">settings</i>
                        General Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">System Information</h6>
                        <div class="mb-3">
                            <label class="form-label">System Name</label>
                            <input type="text" class="form-control" value="ExamExcel" readonly disabled>
                            <small class="text-muted">This field cannot be modified</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Version</label>
                            <input type="text" class="form-control" value="<?= $settings['system_version'] ?? '1.0.0' ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Institution Name</label>
                            <input type="text" class="form-control"
                                   value="<?= old('institution_name', $settings['institution_name'] ?? 'ExamExcel') ?>"
                                   placeholder="Enter institution name" readonly disabled>
                            <small class="text-muted">This field cannot be modified</small>
                            <input type="hidden" name="institution_name" value="ExamExcel">
                        </div>
                    </div>

                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">Student Settings</h6>
                        <div class="mb-3">
                            <label class="form-label">Student ID Prefix</label>
                            <input type="text" name="student_id_prefix" class="form-control"
                                   value="<?= old('student_id_prefix', $settings['student_id_prefix'] ?? 'STD') ?>"
                                   maxlength="5" pattern="[A-Za-z]{2,5}"
                                   title="Only letters allowed, 2-5 characters">
                            <small class="text-muted">Prefix for auto-generated student IDs (e.g., STD-1234)</small>
                        </div>
                    </div>

                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">Exam Settings</h6>
                        <div class="mb-3">
                            <label class="form-label">Default Exam Duration (minutes)</label>
                            <input type="number" name="default_exam_duration" class="form-control"
                                   value="<?= old('default_exam_duration', $settings['default_exam_duration'] ?? 80) ?>"
                                   min="1" max="600">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="auto_submit_on_time_up"
                                       id="autoSubmit" value="1"
                                       <?= ($settings['auto_submit_on_time_up'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="autoSubmit">
                                    Auto-submit on Time Up
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">info</i>
                                For exam-specific settings like exam types, calculator access, and pause functionality,
                                please visit the <a href="<?= base_url('admin/exam-settings') ?>" class="text-decoration-none">Exam Settings</a> page.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- App Control & News Flash -->
        <div class="col-lg-6 mb-4">
            <div class="card settings-card h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-semibold text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">lock</i>
                        App Control & News
                    </h5>
                </div>
                <div class="card-body">
                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">Application Lock</h6>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="app_locked"
                                       id="appLocked" value="1"
                                       <?= ($settings['app_locked'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="appLocked">
                                    Lock Application (Prevent logins)
                                </label>
                            </div>
                            <small class="text-muted">When enabled, users with selected roles cannot login</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Roles to Lock</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="locked_roles[]"
                                       value="student" id="lockStudents">
                                <label class="form-check-label" for="lockStudents">Students</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="locked_roles[]"
                                       value="teacher" id="lockTeachers">
                                <label class="form-check-label" for="lockTeachers">Teachers</label>
                            </div>
                        </div>
                    </div>

                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">News Flash</h6>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="news_flash_enabled"
                                       id="newsFlashEnabled" value="1"
                                       <?= ($settings['news_flash_enabled'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="newsFlashEnabled">
                                    Enable News Flash on Login Page
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">News Flash Content</label>
                            <textarea name="news_flash_content" class="form-control" rows="3"
                                      placeholder="Enter news or announcement to display on login page"><?= old('news_flash_content', $settings['news_flash_content'] ?? '') ?></textarea>
                            <small class="text-muted">This will be displayed prominently on the login page</small>
                        </div>
                        <div id="newsFlashPreview" class="news-flash-preview" style="display: none;">
                            <strong>Preview:</strong>
                            <div id="newsFlashPreviewContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branding Settings -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card settings-card">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-semibold text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">palette</i>
                        Branding Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">Logo & Favicon</h6>
                        <div class="mb-3">
                            <label class="form-label">Application Logo</label>
                            <div class="file-upload-area" id="logoUploadArea">
                                <input type="file" name="logo" class="form-control" accept="image/*" id="logoUpload">
                                <div class="mt-2" id="logoUploadContent">
                                    <i class="material-symbols-rounded" style="font-size: 48px; color: var(--primary-color);">image</i>
                                    <p class="mb-0">Upload logo (PNG, JPG, SVG)</p>
                                    <small class="text-muted">Recommended size: 200x60px</small>
                                </div>
                            </div>
                            <?php if (!empty($settings['logo_path'])): ?>
                                <div class="mt-3" id="currentLogo">
                                    <label class="form-label">Current Logo:</label>
                                    <div class="current-image-container">
                                        <img src="<?= base_url($settings['logo_path']) ?>"
                                             alt="Current Logo"
                                             style="max-height: 60px; max-width: 200px;"
                                             class="img-fluid">
                                        <div class="mt-2">
                                            <small class="text-muted"><?= basename($settings['logo_path']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="mt-3" id="logoPreview" style="display: none;">
                                <label class="form-label">Preview:</label>
                                <div class="image-preview-container">
                                    <img id="logoPreviewImg" alt="Logo Preview"
                                         style="max-height: 60px; max-width: 200px;"
                                         class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Favicon</label>
                            <div class="file-upload-area" id="faviconUploadArea">
                                <input type="file" name="favicon" class="form-control" accept="image/*" id="faviconUpload">
                                <div class="mt-2" id="faviconUploadContent">
                                    <i class="material-symbols-rounded" style="font-size: 48px; color: var(--primary-color);">tab</i>
                                    <p class="mb-0">Upload favicon (ICO, PNG)</p>
                                    <small class="text-muted">Recommended size: 32x32px</small>
                                </div>
                            </div>
                            <?php if (!empty($settings['favicon_path'])): ?>
                                <div class="mt-3" id="currentFavicon">
                                    <label class="form-label">Current Favicon:</label>
                                    <div class="current-image-container">
                                        <img src="<?= base_url($settings['favicon_path']) ?>"
                                             alt="Current Favicon"
                                             style="max-height: 32px; max-width: 32px;"
                                             class="img-fluid">
                                        <div class="mt-2">
                                            <small class="text-muted"><?= basename($settings['favicon_path']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="mt-3" id="faviconPreview" style="display: none;">
                                <label class="form-label">Preview:</label>
                                <div class="image-preview-container">
                                    <img id="faviconPreviewImg" alt="Favicon Preview"
                                         style="max-height: 32px; max-width: 32px;"
                                         class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Question Generation Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card settings-card">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-semibold text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">psychology</i>
                        AI Question Generation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">AI Configuration</h6>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="ai_generation_enabled"
                                       id="aiGenerationEnabled" value="1"
                                       <?= ($settings['ai_generation_enabled'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aiGenerationEnabled">
                                    Enable AI Question Generation
                                </label>
                            </div>
                            <small class="text-muted">Allow teachers to generate questions using AI</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">AI Model Provider</label>
                            <select name="ai_model_provider" class="form-select" id="aiModelProvider">
                                <option value="">Select AI Provider</option>
                                <option value="openai" <?= ($settings['ai_model_provider'] ?? '') === 'openai' ? 'selected' : '' ?>>OpenAI (GPT-3.5/GPT-4) - Paid</option>
                                <option value="gemini" <?= ($settings['ai_model_provider'] ?? '') === 'gemini' ? 'selected' : '' ?>>Google Gemini - FREE ⭐</option>
                                <option value="claude" <?= ($settings['ai_model_provider'] ?? '') === 'claude' ? 'selected' : '' ?>>Anthropic Claude - Paid</option>
                                <option value="groq" <?= ($settings['ai_model_provider'] ?? '') === 'groq' ? 'selected' : '' ?>>Groq - FREE ⭐</option>
                                <option value="huggingface" <?= ($settings['ai_model_provider'] ?? '') === 'huggingface' ? 'selected' : '' ?>>Hugging Face - FREE</option>
                            </select>
                            <div id="providerInfo" class="mt-2" style="display: none;">
                                <!-- Provider information will be shown here -->
                            </div>
                        </div>

                        <div class="mb-3" id="aiModelSelect" style="display: none;">
                            <label class="form-label">AI Model</label>
                            <select name="ai_model" class="form-select" id="aiModel">
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API Key</label>
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Enter your AI API key" id="aiApiKey" name="ai_api_key" value="<?= htmlspecialchars($ai_api_key ?? '') ?>">
                                <button class="btn btn-outline-secondary" type="button" id="toggleApiKey">
                                    <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                                </button>
                            </div>
                            <small class="text-muted">Your API key is stored securely</small>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-info btn-sm" id="testAiConnection">
                                <i class="material-symbols-rounded me-2" style="font-size: 16px;">wifi_tethering</i>
                                Test AI Connection
                            </button>
                            <div id="aiTestResult" class="mt-2" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card settings-card">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-semibold text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">backup</i>
                        Backup & Maintenance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">Backup Configuration</h6>
                        <div class="mb-3">
                            <label class="form-label">Auto Backup Frequency</label>
                            <select name="backup_frequency" class="form-select">
                                <option value="daily" <?= ($settings['backup_frequency'] ?? 'weekly') === 'daily' ? 'selected' : '' ?>>Daily</option>
                                <option value="weekly" <?= ($settings['backup_frequency'] ?? 'weekly') === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                                <option value="monthly" <?= ($settings['backup_frequency'] ?? 'weekly') === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="disabled" <?= ($settings['backup_frequency'] ?? 'weekly') === 'disabled' ? 'selected' : '' ?>>Disabled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Backup Retention (days)</label>
                            <input type="number" name="backup_retention_days" class="form-control"
                                   value="<?= old('backup_retention_days', $settings['backup_retention_days'] ?? 30) ?>"
                                   min="1" max="365">
                        </div>
                    </div>

                    <div class="setting-section">
                        <h6 class="fw-semibold mb-3">Manual Actions</h6>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary w-100" id="createBackupBtn">
                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">download</i>
                                Create Manual Backup
                            </button>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-warning w-100" id="clearCacheBtn">
                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">cleaning_services</i>
                                Clear System Cache
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Save All Settings
                </button>
            </div>
        </div>
    </div>
</form>



<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // News flash preview
    const newsFlashContent = document.querySelector('textarea[name="news_flash_content"]');
    const newsFlashEnabled = document.querySelector('#newsFlashEnabled');
    const newsFlashPreview = document.querySelector('#newsFlashPreview');
    const newsFlashPreviewContent = document.querySelector('#newsFlashPreviewContent');

    function updateNewsFlashPreview() {
        if (newsFlashEnabled.checked && newsFlashContent.value.trim()) {
            newsFlashPreviewContent.textContent = newsFlashContent.value;
            newsFlashPreview.style.display = 'block';
        } else {
            newsFlashPreview.style.display = 'none';
        }
    }

    newsFlashContent.addEventListener('input', updateNewsFlashPreview);
    newsFlashEnabled.addEventListener('change', updateNewsFlashPreview);

    // Initialize preview
    updateNewsFlashPreview();

    // Handle locked roles checkboxes
    const lockedRoles = <?= json_encode($settings['locked_roles'] ?? []) ?>;

    if (Array.isArray(lockedRoles)) {
        lockedRoles.forEach(role => {
            const checkbox = document.querySelector(`input[name="locked_roles[]"][value="${role}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    } else if (typeof lockedRoles === 'string') {
        try {
            const parsedRoles = JSON.parse(lockedRoles);
            if (Array.isArray(parsedRoles)) {
                parsedRoles.forEach(role => {
                    const checkbox = document.querySelector(`input[name="locked_roles[]"][value="${role}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
        } catch (e) {
            console.error('Failed to parse locked roles:', e);
        }
    }

    // Create backup button
    document.getElementById('createBackupBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to create a backup? This may take a few moments.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('admin/settings/backup') ?>';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    });

    // Clear cache button
    document.getElementById('clearCacheBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear the system cache? This will temporarily slow down the application.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('admin/settings/clear-cache') ?>';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    });

    // File upload preview
    function handleFilePreview(input, previewContainerId, previewImgId) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById(previewContainerId);
            const previewImg = document.getElementById(previewImgId);

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

    // Initialize file preview handlers
    handleFilePreview(document.getElementById('logoUpload'), 'logoPreview', 'logoPreviewImg');
    handleFilePreview(document.getElementById('faviconUpload'), 'faviconPreview', 'faviconPreviewImg');

    // AI Settings functionality
    initializeAISettings();

    // Form validation
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        const examDuration = document.querySelector('input[name="default_exam_duration"]').value;

        if (!examDuration || examDuration < 1 || examDuration > 600) {
            alert('Exam duration must be between 1 and 600 minutes.');
            e.preventDefault();
            return false;
        }

        return true;
    });

});

// AI Settings Functions
function initializeAISettings() {
    const aiModelProvider = document.getElementById('aiModelProvider');
    const aiModelSelect = document.getElementById('aiModelSelect');
    const aiModel = document.getElementById('aiModel');
    const toggleApiKey = document.getElementById('toggleApiKey');
    const aiApiKey = document.getElementById('aiApiKey');
    const testAiConnection = document.getElementById('testAiConnection');

    // AI Model configurations
    const aiModels = {
        openai: [
            { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo (Recommended)' },
            { value: 'gpt-4', label: 'GPT-4 (Premium)' },
            { value: 'gpt-4-turbo', label: 'GPT-4 Turbo (Latest)' }
        ],
        gemini: [
            { value: 'gemini-1.5-flash', label: 'Gemini 1.5 Flash (Free, Fast)' },
            { value: 'gemini-1.5-pro', label: 'Gemini 1.5 Pro (Free, Better)' },
            { value: 'gemini-pro', label: 'Gemini Pro (Legacy)' }
        ],
        claude: [
            { value: 'claude-3-haiku', label: 'Claude 3 Haiku (Fast)' },
            { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet (Balanced)' },
            { value: 'claude-3-opus', label: 'Claude 3 Opus (Best)' }
        ],
        groq: [
            { value: 'llama-3.1-8b-instant', label: 'Llama 3.1 8B' },
            { value: 'llama-3.3-70b-versatile', label: 'Llama 3.3 70B' },
            { value: 'llama-3.3-70b-specdec', label: 'Llama 3.3 Specdec 70B' }
        ],
        huggingface: [
            { value: 'microsoft/DialoGPT-medium', label: 'DialoGPT Medium (Free)' },
            { value: 'facebook/blenderbot-400M-distill', label: 'BlenderBot (Free)' }
        ]
    };

    // Provider information content
    const providerInfoContent = {
        'openai': `
            <div class="alert alert-info">
                <h6><i class="material-symbols-rounded me-2">info</i>OpenAI API Setup</h6>
                <p><strong>Cost:</strong> Paid service (requires billing setup)</p>
                <p><strong>Get API Key:</strong> <a href="https://platform.openai.com/api-keys" target="_blank">https://platform.openai.com/api-keys</a></p>
                <p><strong>Steps:</strong></p>
                <ol>
                    <li>Create account at OpenAI</li>
                    <li>Add billing information</li>
                    <li>Generate API key</li>
                    <li>Copy and paste the key above</li>
                </ol>
            </div>
        `,
        'gemini': `
            <div class="alert alert-success">
                <h6><i class="material-symbols-rounded me-2">star</i>Google Gemini API Setup (FREE)</h6>
                <p><strong>Cost:</strong> FREE with generous limits (15 requests/minute, 1M tokens/day)</p>
                <p><strong>Get API Key:</strong> <a href="https://aistudio.google.com/app/apikey" target="_blank">https://aistudio.google.com/app/apikey</a></p>
                <p><strong>Alternative:</strong> <a href="https://makersuite.google.com/app/apikey" target="_blank">https://makersuite.google.com/app/apikey</a></p>
                <p><strong>Steps:</strong></p>
                <ol>
                    <li>Go to Google AI Studio (link above)</li>
                    <li>Sign in with Google account</li>
                    <li>Click "Get API Key" or "Create API Key"</li>
                    <li>Create new API key for your project</li>
                    <li>Copy and paste the key above</li>
                </ol>
                <p><strong>⭐ Recommended for schools!</strong> No billing required.</p>
                <p><small><strong>Note:</strong> If you get a 404 error, the API might not be available in your region yet. Try using Groq instead.</small></p>
            </div>
        `,
        'claude': `
            <div class="alert alert-info">
                <h6><i class="material-symbols-rounded me-2">info</i>Anthropic Claude API Setup</h6>
                <p><strong>Cost:</strong> Paid service</p>
                <p><strong>Get API Key:</strong> <a href="https://console.anthropic.com/" target="_blank">https://console.anthropic.com/</a></p>
                <p><strong>Steps:</strong></p>
                <ol>
                    <li>Create account at Anthropic</li>
                    <li>Add billing information</li>
                    <li>Generate API key</li>
                    <li>Copy and paste the key above</li>
                </ol>
            </div>
        `,
        'groq': `
            <div class="alert alert-success">
                <h6><i class="material-symbols-rounded me-2">star</i>Groq API Setup (FREE)</h6>
                <p><strong>Cost:</strong> FREE with good limits</p>
                <p><strong>Get API Key:</strong> <a href="https://console.groq.com/keys" target="_blank">https://console.groq.com/keys</a></p>
                <p><strong>Steps:</strong></p>
                <ol>
                    <li>Create account at Groq</li>
                    <li>Go to API Keys section</li>
                    <li>Create new API key</li>
                    <li>Copy and paste the key above</li>
                </ol>
                <p><strong>⚡ Very fast inference!</strong> Great free alternative.</p>
            </div>
        `,
        'huggingface': `
            <div class="alert alert-success">
                <h6><i class="material-symbols-rounded me-2">star</i>Hugging Face API Setup (FREE)</h6>
                <p><strong>Cost:</strong> FREE with rate limits</p>
                <p><strong>Get API Key:</strong> <a href="https://huggingface.co/settings/tokens" target="_blank">https://huggingface.co/settings/tokens</a></p>
                <p><strong>Steps:</strong></p>
                <ol>
                    <li>Create account at Hugging Face</li>
                    <li>Go to Settings > Access Tokens</li>
                    <li>Create new token</li>
                    <li>Copy and paste the token above</li>
                </ol>
            </div>
        `
    };

    // Handle provider change
    aiModelProvider.addEventListener('change', function() {
        const provider = this.value;
        const providerInfo = document.getElementById('providerInfo');

        if (provider && aiModels[provider]) {
            aiModel.innerHTML = '<option value="">Select Model</option>';
            aiModels[provider].forEach(model => {
                const option = document.createElement('option');
                option.value = model.value;
                option.textContent = model.label;
                aiModel.appendChild(option);
            });
            aiModelSelect.style.display = 'block';

            // Show provider info
            if (providerInfoContent[provider]) {
                providerInfo.innerHTML = providerInfoContent[provider];
                providerInfo.style.display = 'block';
            }

            // Load provider-specific API key
            loadProviderApiKey(provider);
        } else {
            aiModelSelect.style.display = 'none';
            providerInfo.style.display = 'none';
            aiApiKey.value = '';
        }
    });

    // Function to load provider-specific API key
    function loadProviderApiKey(provider) {
        const hiddenField = document.getElementById(provider + 'ApiKey');
        if (hiddenField) {
            aiApiKey.value = hiddenField.value;
        } else {
            aiApiKey.value = '';
        }
    }

    // Function to save current API key to provider-specific field
    function saveProviderApiKey(provider) {
        const hiddenField = document.getElementById(provider + 'ApiKey');
        if (hiddenField) {
            hiddenField.value = aiApiKey.value;
        }
    }

    // Save API key when it changes
    aiApiKey.addEventListener('input', function() {
        const currentProvider = aiModelProvider.value;
        if (currentProvider) {
            saveProviderApiKey(currentProvider);
        }
    });

    // Initialize on page load
    if (aiModelProvider.value) {
        aiModelProvider.dispatchEvent(new Event('change'));
        // Set selected model if exists
        const currentModel = '<?= $settings['ai_model'] ?? '' ?>';
        if (currentModel) {
            setTimeout(() => {
                aiModel.value = currentModel;
            }, 100);
        }
    }

    // Toggle API key visibility
    toggleApiKey.addEventListener('click', function() {
        const type = aiApiKey.type === 'password' ? 'text' : 'password';
        aiApiKey.type = type;
        const icon = this.querySelector('i');
        icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
    });

    // Test AI connection
    testAiConnection.addEventListener('click', function() {
        const provider = aiModelProvider.value;
        const model = aiModel.value;
        const apiKey = aiApiKey.value;
        const resultDiv = document.getElementById('aiTestResult');

        if (!provider || !model || !apiKey) {
            resultDiv.innerHTML = '<div class="alert alert-warning alert-sm">Please fill in all AI configuration fields first.</div>';
            resultDiv.style.display = 'block';
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Testing...';

        fetch('<?= base_url('admin/test-ai-connection') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                provider: provider,
                model: model,
                api_key: apiKey
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success alert-sm"><i class="material-symbols-rounded me-2">check_circle</i>Connection successful! AI is ready to generate questions.</div>';
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger alert-sm"><i class="material-symbols-rounded me-2">error</i>Connection failed: ' + (data.message || 'Unknown error') + '</div>';
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response text:', text);
                resultDiv.innerHTML = '<div class="alert alert-danger alert-sm"><i class="material-symbols-rounded me-2">error</i>Invalid response from server. Please check the console for details.</div>';
            }
            resultDiv.style.display = 'block';
        })
        .catch(error => {
            console.error('Fetch error:', error);
            resultDiv.innerHTML = '<div class="alert alert-danger alert-sm"><i class="material-symbols-rounded me-2">error</i>Connection test failed: ' + error.message + '</div>';
            resultDiv.style.display = 'block';
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<i class="material-symbols-rounded me-2" style="font-size: 16px;">wifi_tethering</i>Test AI Connection';
        });
    });
}

</script>
<?= $this->endSection() ?>
