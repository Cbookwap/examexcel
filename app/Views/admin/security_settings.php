<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .settings-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .settings-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }

    .setting-group {
        background: #f8fafc;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .setting-group:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }

    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-label {
        flex: 1;
        margin-right: 1rem;
    }

    .setting-label h6 {
        margin: 0;
        font-weight: 600;
        color: #1f2937;
    }

    .setting-label small {
        color: #6b7280;
        display: block;
        margin-top: 0.25rem;
    }

    .setting-control {
        min-width: 200px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-check-input {
        width: 2.5rem;
        height: 1.25rem;
        border-radius: 1rem;
        background-color: #d1d5db;
        border: none;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-reset {
        background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(107, 114, 128, 0.3);
        color: white;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 1px solid #93c5fd;
        border-radius: 10px;
        color: #1e40af;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #fbbf24;
        border-radius: 10px;
        color: #92400e;
    }

    .security-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
    }

    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: #764ba2;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .setting-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .setting-control {
            width: 100%;
            margin-top: 1rem;
            min-width: auto;
        }

        .setting-group {
            padding: 1rem;
        }
    }

    /* Animation for form submission */
    .form-saving {
        opacity: 0.7;
        pointer-events: none;
    }

    .form-saving .btn-save {
        background: linear-gradient(135deg, #9ca3af 0%, #d1d5db 100%);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/security') ?>">Security</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center mb-4">
            <div class="security-icon">
                <i class="material-symbols-rounded">settings_applications</i>
            </div>
            <div>
                <h3 class="mb-0 font-weight-bolder"><?= $pageTitle ?></h3>
                <p class="mb-0 text-muted"><?= $pageSubtitle ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2">check_circle</i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2">error</i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Security Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <div class="d-flex align-items-center">
                <i class="material-symbols-rounded me-3" style="font-size: 2rem;">info</i>
                <div>
                    <h6 class="mb-1">Security Configuration</h6>
                    <p class="mb-0">Configure system-wide security settings to protect your CBT environment. Changes will affect all users and exam sessions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings Form -->
<form method="POST" action="<?= base_url('admin/security/settings') ?>" id="securitySettingsForm">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Authentication & Session Settings -->
        <div class="col-lg-6 mb-4">
            <div class="settings-card">
                <div class="card-header-custom">
                    <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="material-symbols-rounded me-2" style="color: #667eea;">account_circle</i>
                        Authentication & Sessions
                    </h5>
                    <p class="text-muted small mb-0">Configure login and session security</p>
                </div>
                <div class="card-body">
                    <div class="setting-group">
                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Session Timeout</h6>
                                <small>Automatic logout after inactivity (minutes)</small>
                            </div>
                            <div class="setting-control">
                                <input type="number" name="session_timeout" class="form-control"
                                       value="<?= old('session_timeout', $settings['session_timeout']) ?>"
                                       min="5" max="1440" required>
                                <?php if ($validation->hasError('session_timeout')): ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('session_timeout') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Max Login Attempts</h6>
                                <small>Account lockout after failed attempts</small>
                            </div>
                            <div class="setting-control">
                                <input type="number" name="max_login_attempts" class="form-control"
                                       value="<?= old('max_login_attempts', $settings['max_login_attempts']) ?>"
                                       min="1" max="10" required>
                                <?php if ($validation->hasError('max_login_attempts')): ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('max_login_attempts') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Lockout Duration</h6>
                                <small>Account lockout time (minutes)</small>
                            </div>
                            <div class="setting-control">
                                <input type="number" name="lockout_duration" class="form-control"
                                       value="<?= old('lockout_duration', $settings['lockout_duration']) ?>"
                                       min="1" max="1440" required>
                                <?php if ($validation->hasError('lockout_duration')): ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('lockout_duration') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Password Minimum Length</h6>
                                <small>Minimum characters required for passwords</small>
                            </div>
                            <div class="setting-control">
                                <input type="number" name="password_min_length" class="form-control"
                                       value="<?= old('password_min_length', $settings['password_min_length']) ?>"
                                       min="4" max="50" required>
                                <?php if ($validation->hasError('password_min_length')): ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('password_min_length') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Auto Logout (Idle)</h6>
                                <small>Logout after idle time (minutes)</small>
                            </div>
                            <div class="setting-control">
                                <input type="number" name="auto_logout_idle" class="form-control"
                                       value="<?= old('auto_logout_idle', $settings['auto_logout_idle']) ?>"
                                       min="1" max="120" required>
                                <?php if ($validation->hasError('auto_logout_idle')): ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('auto_logout_idle') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Security Settings -->
        <div class="col-lg-6 mb-4">
            <div class="settings-card">
                <div class="card-header-custom">
                    <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="material-symbols-rounded me-2" style="color: #667eea;">shield</i>
                        System Security
                    </h5>
                    <p class="text-muted small mb-0">Configure system-level security features</p>
                </div>
                <div class="card-body">
                    <div class="setting-group">
                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Require HTTPS</h6>
                                <small>Force secure connections for all users</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="require_https"
                                           id="require_https" <?= $settings['require_https'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="require_https"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>CSRF Protection</h6>
                                <small>Cross-Site Request Forgery protection</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="csrf_protection"
                                           id="csrf_protection" <?= $settings['csrf_protection'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="csrf_protection"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>IP Whitelist</h6>
                                <small>Restrict access to specific IP addresses</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="ip_whitelist_enabled"
                                           id="ip_whitelist_enabled" <?= $settings['ip_whitelist_enabled'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ip_whitelist_enabled"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Two-Factor Authentication</h6>
                                <small>Require 2FA for admin accounts</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="two_factor_enabled"
                                           id="two_factor_enabled" <?= $settings['two_factor_enabled'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="two_factor_enabled"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Exam Security Settings -->
        <div class="col-lg-6 mb-4">
            <div class="settings-card">
                <div class="card-header-custom">
                    <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="material-symbols-rounded me-2" style="color: #667eea;">quiz</i>
                        Exam Security
                    </h5>
                    <p class="text-muted small mb-0">Configure exam-specific security measures</p>
                </div>
                <div class="card-body">
                    <div class="setting-group">
                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Browser Lockdown</h6>
                                <small>Prevent tab switching during exams</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="browser_lockdown"
                                           id="browser_lockdown" <?= $settings['browser_lockdown'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="browser_lockdown"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Fullscreen Mode</h6>
                                <small>Force fullscreen during exams</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="fullscreen_mode"
                                           id="fullscreen_mode" <?= $settings['fullscreen_mode'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="fullscreen_mode"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Prevent Copy/Paste</h6>
                                <small>Disable copy and paste functions</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="prevent_copy_paste"
                                           id="prevent_copy_paste" <?= $settings['prevent_copy_paste'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="prevent_copy_paste"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Disable Right Click</h6>
                                <small>Prevent right-click context menu</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="disable_right_click"
                                           id="disable_right_click" <?= $settings['disable_right_click'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="disable_right_click"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Tab Switching Detection</h6>
                                <small>Monitor and log tab switching attempts</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="tab_switching_detection"
                                           id="tab_switching_detection" <?= $settings['tab_switching_detection'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tab_switching_detection"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Require Proctoring</h6>
                                <small>Enable webcam monitoring during exams</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="require_proctoring"
                                           id="require_proctoring" <?= $settings['require_proctoring'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="require_proctoring"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Strict Security Mode</h6>
                                <small>Block exam continuation when violations occur (recommended)</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="strict_security_mode"
                                           id="strict_security_mode" <?= $settings['strict_security_mode'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="strict_security_mode"></label>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Auto-Submit on Violation Limit</h6>
                                <small>Automatically submit exam when violation limits are exceeded</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_submit_on_violation"
                                           id="auto_submit_on_violation" <?= $settings['auto_submit_on_violation'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="auto_submit_on_violation"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proctoring Settings -->
        <div class="col-lg-6 mb-4">
            <div class="settings-card">
                <div class="card-header-custom">
                    <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="material-symbols-rounded me-2" style="color: #667eea;">videocam</i>
                        Proctoring & Monitoring
                    </h5>
                    <p class="text-muted small mb-0">Configure student monitoring features</p>
                </div>
                <div class="card-body">
                    <div class="setting-group">
                        <div class="setting-item">
                            <div class="setting-label">
                                <h6>Enable Proctoring</h6>
                                <small>Monitor student behavior during exams</small>
                            </div>
                            <div class="setting-control">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="proctoring_enabled"
                                           id="proctoring_enabled" <?= $settings['proctoring_enabled'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="proctoring_enabled"></label>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded me-2">warning</i>
                                <div>
                                    <small><strong>Note:</strong> Some features may require additional browser permissions or plugins. Test thoroughly before enabling in production.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Violation Limits & Auto-Submit -->
        <div class="col-lg-6 mb-4">
            <div class="settings-card">
                <div class="card-header-custom">
                    <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="material-symbols-rounded me-2" style="color: #dc2626;">warning</i>
                        Violation Limits & Auto-Submit
                    </h5>
                    <p class="text-muted small mb-0">Configure how many violations are allowed before automatic exam submission</p>
                </div>
                <div class="card-body">
                    <div class="setting-group">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_tab_switches" class="form-label">Maximum Tab Switches</label>
                                <input type="number" class="form-control" name="max_tab_switches" id="max_tab_switches"
                                       value="<?= $settings['max_tab_switches'] ?? 5 ?>" min="1" max="50">
                                <small class="text-muted">Number of tab switches allowed before auto-submit</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_window_focus_loss" class="form-label">Maximum Window Focus Loss</label>
                                <input type="number" class="form-control" name="max_window_focus_loss" id="max_window_focus_loss"
                                       value="<?= $settings['max_window_focus_loss'] ?? 3 ?>" min="1" max="20">
                                <small class="text-muted">Number of window focus losses allowed before auto-submit</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_monitor_warnings" class="form-label">Maximum Monitor Warnings</label>
                                <input type="number" class="form-control" name="max_monitor_warnings" id="max_monitor_warnings"
                                       value="<?= $settings['max_monitor_warnings'] ?? 2 ?>" min="1" max="10">
                                <small class="text-muted">Number of multiple monitor warnings before auto-submit</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_security_violations" class="form-label">Maximum Total Violations</label>
                                <input type="number" class="form-control" name="max_security_violations" id="max_security_violations"
                                       value="<?= $settings['max_security_violations'] ?? 10 ?>" min="1" max="100">
                                <small class="text-muted">Total violations allowed before auto-submit</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Security Features -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="settings-card">
                <div class="card-header-custom">
                    <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="material-symbols-rounded me-2" style="color: #667eea;">security</i>
                        Advanced Security Features
                    </h5>
                    <p class="text-muted small mb-0">Enhanced monitoring and detection capabilities</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="setting-group">
                                <div class="setting-item">
                                    <div class="setting-label">
                                        <h6>Window Resize Detection</h6>
                                        <small>Monitor window size changes and screen extensions</small>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="window_resize_detection"
                                                   id="window_resize_detection" <?= $settings['window_resize_detection'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="window_resize_detection"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="setting-item">
                                    <div class="setting-label">
                                        <h6>Mouse Tracking</h6>
                                        <small>Advanced mouse behavior monitoring</small>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="mouse_tracking_enabled"
                                                   id="mouse_tracking_enabled" <?= $settings['mouse_tracking_enabled'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="mouse_tracking_enabled"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="setting-item">
                                    <div class="setting-label">
                                        <h6>Keyboard Pattern Analysis</h6>
                                        <small>Analyze typing patterns for suspicious behavior</small>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="keyboard_pattern_analysis"
                                                   id="keyboard_pattern_analysis" <?= $settings['keyboard_pattern_analysis'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="keyboard_pattern_analysis"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="setting-group">
                                <div class="setting-item">
                                    <div class="setting-label">
                                        <h6>Enhanced Screen Capture Prevention</h6>
                                        <small>Advanced protection against screen recording</small>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="prevent_screen_capture"
                                                   id="prevent_screen_capture" <?= $settings['prevent_screen_capture'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="prevent_screen_capture"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="setting-item">
                                    <div class="setting-label">
                                        <h6>Enhanced DevTools Detection</h6>
                                        <small>Advanced developer tools detection methods</small>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="enhanced_devtools_detection"
                                                   id="enhanced_devtools_detection" <?= $settings['enhanced_devtools_detection'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="enhanced_devtools_detection"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="setting-item">
                                    <div class="setting-label">
                                        <h6>Browser Extension Detection</h6>
                                        <small>Detect suspicious browser extensions</small>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="browser_extension_detection"
                                                   id="browser_extension_detection" <?= $settings['browser_extension_detection'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="browser_extension_detection"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="material-symbols-rounded me-2">info</i>
                        <strong>Note:</strong> Advanced security features provide enhanced protection but may impact exam performance.
                        Enable only the features you need and test thoroughly before deploying to students.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="settings-card">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-save">
                            <i class="material-symbols-rounded me-2">save</i>
                            Save Security Settings
                        </button>
                        <button type="button" class="btn btn-reset" onclick="resetForm()">
                            <i class="material-symbols-rounded me-2">refresh</i>
                            Reset to Defaults
                        </button>
                        <a href="<?= base_url('admin/security') ?>" class="btn btn-outline-secondary">
                            <i class="material-symbols-rounded me-2">arrow_back</i>
                            Back to Security
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Form submission handling
document.getElementById('securitySettingsForm').addEventListener('submit', function(e) {
    const form = this;
    const submitBtn = form.querySelector('.btn-save');

    // Add loading state
    form.classList.add('form-saving');
    submitBtn.innerHTML = '<i class="material-symbols-rounded me-2">hourglass_empty</i>Saving...';
    submitBtn.disabled = true;

    // Show confirmation for critical settings
    const criticalSettings = ['require_https', 'csrf_protection', 'browser_lockdown'];
    let criticalChanges = [];

    criticalSettings.forEach(setting => {
        const checkbox = form.querySelector(`[name="${setting}"]`);
        if (checkbox && checkbox.checked !== checkbox.defaultChecked) {
            criticalChanges.push(setting.replace('_', ' ').toUpperCase());
        }
    });

    if (criticalChanges.length > 0) {
        const confirmed = confirm(
            `You are about to change critical security settings: ${criticalChanges.join(', ')}.\n\n` +
            'This may affect system functionality. Are you sure you want to continue?'
        );

        if (!confirmed) {
            e.preventDefault();
            form.classList.remove('form-saving');
            submitBtn.innerHTML = '<i class="material-symbols-rounded me-2">save</i>Save Security Settings';
            submitBtn.disabled = false;
            return;
        }
    }
});

// Reset form to defaults
function resetForm() {
    if (confirm('Are you sure you want to reset all settings to their default values?')) {
        // Reset all form fields to their default values
        const form = document.getElementById('securitySettingsForm');

        // Reset number inputs to default values
        form.querySelector('[name="session_timeout"]').value = 30;
        form.querySelector('[name="max_login_attempts"]').value = 5;
        form.querySelector('[name="lockout_duration"]').value = 30;
        form.querySelector('[name="password_min_length"]').value = 6;
        form.querySelector('[name="auto_logout_idle"]').value = 15;

        // Reset checkboxes to default states
        const defaultChecked = ['csrf_protection', 'browser_lockdown', 'proctoring_enabled',
                               'prevent_copy_paste', 'disable_right_click', 'fullscreen_mode',
                               'tab_switching_detection', 'require_proctoring'];

        const defaultUnchecked = ['require_https', 'ip_whitelist_enabled', 'two_factor_enabled',
                                 'window_resize_detection', 'mouse_tracking_enabled', 'keyboard_pattern_analysis',
                                 'prevent_screen_capture', 'enhanced_devtools_detection', 'browser_extension_detection',
                                 'virtual_machine_detection', 'clipboard_monitoring'];

        defaultChecked.forEach(name => {
            const checkbox = form.querySelector(`[name="${name}"]`);
            if (checkbox) checkbox.checked = true;
        });

        defaultUnchecked.forEach(name => {
            const checkbox = form.querySelector(`[name="${name}"]`);
            if (checkbox) checkbox.checked = false;
        });

        showNotification('Settings Reset', 'All settings have been reset to default values.', 'info');
    }
}

// Show notification
function showNotification(title, message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <strong>${title}</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add tooltips for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Add smooth animations to cards
    const cards = document.querySelectorAll('.settings-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add change detection for unsaved changes warning
    let formChanged = false;
    const form = document.getElementById('securitySettingsForm');
    const inputs = form.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    // Warn about unsaved changes
    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Reset change detection on form submit
    form.addEventListener('submit', () => {
        formChanged = false;
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('securitySettingsForm').submit();
    }

    // Ctrl/Cmd + R to reset (with confirmation)
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        resetForm();
    }
});
</script>
<?= $this->endSection() ?>