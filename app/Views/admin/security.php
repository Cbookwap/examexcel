<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .security-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .security-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        border: none;
        box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(var(--primary-color-rgb), 0.4);
    }

    .stats-card.variant-1 {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.3);
    }

    .stats-card.variant-2 {
        background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
        box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3);
    }

    .stats-card.variant-3 {
        background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    }

    .stats-card.variant-4 {
        background: linear-gradient(135deg, #a78bfa 0%, #c4b5fd 100%);
        box-shadow: 0 10px 30px rgba(167, 139, 250, 0.3);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }

    .severity-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .severity-low {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .severity-medium {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .severity-high {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .severity-critical {
        background: linear-gradient(135deg, #7c2d12 0%, #991b1b 100%);
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .event-type-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
    }

    .security-setting {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 1rem;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .security-setting:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }

    .security-setting.enabled {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-left: 4px solid #10b981;
    }

    .security-setting.disabled {
        background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
    }

    .table th {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280 !important;
        padding: 0.75rem !important;
        border: none;
        background: #f8fafc;
    }

    .table td {
        font-size: 0.875rem !important;
        padding: 0.75rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .filter-form .form-control, .filter-form .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    .filter-form .form-control:focus, .filter-form .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-security {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-security:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-danger-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-danger-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .violation-item {
        padding: 1rem;
        border-radius: 10px;
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .violation-item:hover {
        background: #fecaca;
        transform: translateY(-1px);
    }

    .violator-item {
        padding: 1rem;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        border-left: 4px solid #f59e0b;
    }

    .violator-item:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }

    .real-time-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: blink 1.5s infinite;
        margin-right: 0.5rem;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.3; }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }

        .filter-form .row > div {
            margin-bottom: 1rem;
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        .stats-number {
            font-size: 2rem;
        }
    }

    /* Ensure consistent card styling */
    .security-card {
        background: white !important;
        color: #1f2937 !important;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
        border-bottom: 1px solid #e2e8f0 !important;
        color: #1f2937 !important;
    }

    /* Section headers styling */
    .security-section-header {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
    }

    .security-section-header i {
        font-size: 18px;
        margin-right: 0.5rem;
        color: #667eea;
    }

    /* Prominent configure button styling */
    .configure-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
    }

    .configure-section:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }

    .configure-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.875rem 2rem;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .configure-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }

    .configure-btn:active {
        transform: translateY(-1px);
    }

    .configure-btn i {
        font-size: 1.25rem;
        margin-right: 0.5rem;
    }

    /* Enhanced security setting items */
    .security-setting {
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .security-setting:hover {
        border-color: #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .security-setting.enabled:hover {
        border-color: #10b981;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }

    .security-setting.disabled:hover {
        border-color: #ef4444;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row">
    <div class="ms-3">
        <h3 class="mb-0 font-weight-bolder">
            <span class="real-time-indicator"></span>
            <?= $pageTitle ?>
        </h3>
        <p class="mb-4"><?= $pageSubtitle ?></p>
    </div>
</div>

<!-- Security Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card variant-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Critical Events</div>
                    <div class="stats-number"><?= number_format($stats['critical_events']) ?></div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">warning</i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card variant-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Failed Logins (24h)</div>
                    <div class="stats-number"><?= number_format($failedLogins) ?></div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">lock</i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card variant-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Active Sessions</div>
                    <div class="stats-number"><?= number_format($activeSessions) ?></div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">people</i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card variant-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Total Events</div>
                    <div class="stats-number"><?= number_format($stats['total_events']) ?></div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">analytics</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Security Settings & Recent Violations -->
<div class="row mb-4">
    <!-- Security Settings -->
    <div class="col-lg-6 mb-4">
        <div class="security-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #667eea;">security</i>
                    Security Settings
                </h5>
                <p class="text-muted small mb-0">Current system security configuration</p>
            </div>
            <div class="card-body">
                <!-- Primary Security Features -->
                <div class="security-setting <?= ($securitySettings['csrf_protection'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>CSRF Protection</strong>
                            <small class="d-block text-muted">Cross-Site Request Forgery protection</small>
                        </div>
                        <span class="badge <?= ($securitySettings['csrf_protection'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['csrf_protection'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['require_https'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>HTTPS Required</strong>
                            <small class="d-block text-muted">Secure connection enforcement</small>
                        </div>
                        <span class="badge <?= ($securitySettings['require_https'] ?? false) ? 'bg-success' : 'bg-warning' ?>">
                            <?= ($securitySettings['require_https'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['browser_lockdown'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Browser Lockdown</strong>
                            <small class="d-block text-muted">Prevent tab switching during exams</small>
                        </div>
                        <span class="badge <?= ($securitySettings['browser_lockdown'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['browser_lockdown'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['proctoring_enabled'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Exam Proctoring</strong>
                            <small class="d-block text-muted">Monitor student behavior during exams</small>
                        </div>
                        <span class="badge <?= ($securitySettings['proctoring_enabled'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['proctoring_enabled'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['fullscreen_mode'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Fullscreen Mode</strong>
                            <small class="d-block text-muted">Force fullscreen during exams</small>
                        </div>
                        <span class="badge <?= ($securitySettings['fullscreen_mode'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['fullscreen_mode'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['prevent_copy_paste'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Prevent Copy/Paste</strong>
                            <small class="d-block text-muted">Disable copy and paste functions</small>
                        </div>
                        <span class="badge <?= ($securitySettings['prevent_copy_paste'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['prevent_copy_paste'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['disable_right_click'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Disable Right Click</strong>
                            <small class="d-block text-muted">Prevent right-click context menu</small>
                        </div>
                        <span class="badge <?= ($securitySettings['disable_right_click'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['disable_right_click'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting <?= ($securitySettings['tab_switching_detection'] ?? false) ? 'enabled' : 'disabled' ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Tab Switching Detection</strong>
                            <small class="d-block text-muted">Monitor and log tab switching attempts</small>
                        </div>
                        <span class="badge <?= ($securitySettings['tab_switching_detection'] ?? false) ? 'bg-success' : 'bg-danger' ?>">
                            <?= ($securitySettings['tab_switching_detection'] ?? false) ? 'Enabled' : 'Disabled' ?>
                        </span>
                    </div>
                </div>

                <!-- Advanced Security Features -->
                <?php if (($securitySettings['prevent_screen_capture'] ?? false) ||
                          ($securitySettings['enhanced_devtools_detection'] ?? false) ||
                          ($securitySettings['browser_extension_detection'] ?? false) ||
                          ($securitySettings['virtual_machine_detection'] ?? false)): ?>
                <div class="security-section-header mt-4">
                    <i class="material-symbols-rounded">security</i>
                    Advanced Security
                </div>

                <?php if ($securitySettings['prevent_screen_capture'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Screen Capture Prevention</strong>
                            <small class="d-block text-muted">Block screenshot attempts</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($securitySettings['enhanced_devtools_detection'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Developer Tools Detection</strong>
                            <small class="d-block text-muted">Enhanced detection and blocking</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($securitySettings['browser_extension_detection'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Browser Extension Detection</strong>
                            <small class="d-block text-muted">Detect suspicious extensions</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($securitySettings['virtual_machine_detection'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Virtual Machine Detection</strong>
                            <small class="d-block text-muted">Detect virtual environments</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Monitoring Features -->
                <?php if (($securitySettings['mouse_tracking_enabled'] ?? false) ||
                          ($securitySettings['keyboard_pattern_analysis'] ?? false) ||
                          ($securitySettings['window_resize_detection'] ?? false) ||
                          ($securitySettings['clipboard_monitoring'] ?? false)): ?>
                <div class="security-section-header mt-4">
                    <i class="material-symbols-rounded">visibility</i>
                    Monitoring Features
                </div>

                <?php if ($securitySettings['mouse_tracking_enabled'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Mouse Movement Tracking</strong>
                            <small class="d-block text-muted">Monitor unusual mouse behavior</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($securitySettings['keyboard_pattern_analysis'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Keyboard Pattern Analysis</strong>
                            <small class="d-block text-muted">Analyze typing patterns</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($securitySettings['window_resize_detection'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Window Resize Detection</strong>
                            <small class="d-block text-muted">Monitor window size changes</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($securitySettings['clipboard_monitoring'] ?? false): ?>
                <div class="security-setting enabled">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Clipboard Monitoring</strong>
                            <small class="d-block text-muted">Monitor clipboard access</small>
                        </div>
                        <span class="badge bg-success">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Configuration Values -->
                <div class="security-section-header mt-4">
                    <i class="material-symbols-rounded">tune</i>
                    Configuration
                </div>

                <div class="security-setting">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Session Timeout</strong>
                            <small class="d-block text-muted">Automatic logout after inactivity</small>
                        </div>
                        <span class="badge bg-info">
                            <?= $securitySettings['session_timeout'] ?? 30 ?> minutes
                        </span>
                    </div>
                </div>

                <div class="security-setting">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Max Login Attempts</strong>
                            <small class="d-block text-muted">Account lockout threshold</small>
                        </div>
                        <span class="badge bg-info">
                            <?= $securitySettings['max_login_attempts'] ?? 5 ?> attempts
                        </span>
                    </div>
                </div>

                <?php if ($securitySettings['strict_security_mode'] ?? false): ?>
                <div class="security-setting">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Strict Security Mode</strong>
                            <small class="d-block text-muted">Block exam continuation on violations</small>
                        </div>
                        <span class="badge bg-danger">Enabled</span>
                    </div>
                </div>
                <?php endif; ?>

                <div class="security-setting">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Max Tab Switches</strong>
                            <small class="d-block text-muted">Allowed before action</small>
                        </div>
                        <span class="badge bg-warning">
                            <?= $securitySettings['max_tab_switches'] ?? 5 ?>
                        </span>
                    </div>
                </div>

                <div class="security-setting">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Max Window Focus Loss</strong>
                            <small class="d-block text-muted">Allowed before action</small>
                        </div>
                        <span class="badge bg-warning">
                            <?= $securitySettings['max_window_focus_loss'] ?? 3 ?>
                        </span>
                    </div>
                </div>

                <!-- Prominent Configure Button -->
                <div class="configure-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-primary fw-bold">
                                <i class="material-symbols-rounded me-2" style="color: #667eea;">settings</i>
                                Security Configuration
                            </h6>
                            <p class="text-muted mb-0 small">Customize and manage all security settings for your CBT system</p>
                        </div>
                        <div>
                            <button class="configure-btn" onclick="configureSecuritySettings()">
                                <i class="material-symbols-rounded">tune</i>
                                Configure Settings
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportSecurityReport()">
                        <i class="material-symbols-rounded me-1">download</i>Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Violations -->
    <div class="col-lg-6 mb-4">
        <div class="security-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #ef4444;">report_problem</i>
                    Recent Violations
                </h5>
                <p class="text-muted small mb-0">High-priority security events</p>
            </div>
            <div class="card-body">
                <?php if (!empty($recentViolations)): ?>
                    <?php foreach ($recentViolations as $violation): ?>
                        <div class="violation-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong><?= esc($violation['event_type']) ?></strong>
                                    <span class="severity-badge severity-<?= $violation['severity'] ?>">
                                        <?= strtoupper($violation['severity']) ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <?= date('M j, g:i A', strtotime($violation['created_at'])) ?>
                                </small>
                            </div>
                            <?php if ($violation['first_name']): ?>
                                <div class="mb-1">
                                    <small class="text-muted">Student:</small>
                                    <strong><?= esc($violation['first_name'] . ' ' . $violation['last_name']) ?></strong>
                                    <small class="text-muted">(<?= esc($violation['student_id']) ?>)</small>
                                </div>
                            <?php endif; ?>
                            <?php if ($violation['exam_title']): ?>
                                <div class="mb-1">
                                    <small class="text-muted">Exam:</small>
                                    <strong><?= esc($violation['exam_title']) ?></strong>
                                </div>
                            <?php endif; ?>
                            <div>
                                <small class="text-muted">IP:</small>
                                <code><?= esc($violation['ip_address']) ?></code>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded text-success mb-3" style="font-size: 3rem;">verified</i>
                        <p class="text-muted">No recent violations detected</p>
                        <small class="text-muted">System security is operating normally</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Top Violators & Event Types -->
<div class="row mb-4">
    <!-- Top Violators -->
    <div class="col-lg-6 mb-4">
        <div class="security-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #f59e0b;">person_alert</i>
                    Top Violators
                </h5>
                <p class="text-muted small mb-0">Students with most security violations</p>
            </div>
            <div class="card-body">
                <?php if (!empty($topViolators)): ?>
                    <?php foreach ($topViolators as $index => $violator): ?>
                        <div class="violator-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">
                                        #<?= $index + 1 ?> <?= esc($violator['first_name'] . ' ' . $violator['last_name']) ?>
                                    </div>
                                    <small class="text-muted"><?= esc($violator['student_number']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger"><?= $violator['violation_count'] ?> violations</div>
                                    <small class="text-muted">
                                        Last: <?= date('M j', strtotime($violator['last_violation'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded text-success mb-3" style="font-size: 3rem;">sentiment_satisfied</i>
                        <p class="text-muted">No violations recorded</p>
                        <small class="text-muted">All students are following security protocols</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Event Types Breakdown -->
    <div class="col-lg-6 mb-4">
        <div class="security-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #06b6d4;">pie_chart</i>
                    Event Types
                </h5>
                <p class="text-muted small mb-0">Security event distribution</p>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['event_types'])): ?>
                    <?php foreach ($stats['event_types'] as $eventType): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="event-type-badge"><?= esc($eventType['event_type']) ?></span>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold"><?= number_format($eventType['count']) ?></div>
                                <div class="progress" style="width: 100px; height: 6px;">
                                    <?php
                                    $percentage = $stats['total_events'] > 0 ? ($eventType['count'] / $stats['total_events']) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-primary" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 3rem;">analytics</i>
                        <p class="text-muted">No event data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="security-card mb-4">
    <div class="card-header-custom">
        <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
            <i class="material-symbols-rounded me-2" style="color: #667eea;">filter_list</i>
            Filter Security Logs
        </h5>
        <p class="text-muted small mb-0">Filter security events by various criteria</p>
    </div>
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">Severity</label>
                    <select name="severity" class="form-select">
                        <option value="">All Severities</option>
                        <?php foreach ($severityLevels as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filters['severity'] == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Event Type</label>
                    <select name="event_type" class="form-select">
                        <option value="">All Event Types</option>
                        <?php foreach ($eventTypes as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filters['event_type'] == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="<?= $filters['date_from'] ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="<?= $filters['date_to'] ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Exam</label>
                    <select name="exam_id" class="form-select">
                        <option value="">All Exams</option>
                        <?php foreach ($exams as $exam): ?>
                            <option value="<?= $exam['id'] ?>" <?= $filters['exam_id'] == $exam['id'] ? 'selected' : '' ?>>
                                <?= esc($exam['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-1 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-security">
                            <i class="material-symbols-rounded">search</i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('admin/security') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="material-symbols-rounded me-1">clear</i>Clear Filters
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshSecurityLogs()">
                            <i class="material-symbols-rounded me-1">refresh</i>Refresh
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="exportSecurityLogs()">
                            <i class="material-symbols-rounded me-1">download</i>Export
                        </button>
                        <button type="button" class="btn btn-danger-custom btn-sm" onclick="clearOldLogs()">
                            <i class="material-symbols-rounded me-1">delete</i>Clear Old Logs
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Security Logs Table -->
<div class="security-card">
    <div class="card-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #667eea;">table_view</i>
                    Security Logs
                </h5>
                <p class="text-muted small mb-0">
                    Showing <?= count($securityLogs) ?> of <?= $stats['total_events'] ?> security events
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="toggleAutoRefresh()">
                    <i class="material-symbols-rounded me-1">autorenew</i>Auto Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($securityLogs)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Event Type</th>
                            <th>Severity</th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>IP Address</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($securityLogs as $log): ?>
                            <tr>
                                <td>
                                    <div><?= date('M j, Y', strtotime($log['created_at'])) ?></div>
                                    <small class="text-muted"><?= date('g:i:s A', strtotime($log['created_at'])) ?></small>
                                </td>
                                <td>
                                    <span class="event-type-badge"><?= esc($log['event_type']) ?></span>
                                </td>
                                <td>
                                    <span class="severity-badge severity-<?= $log['severity'] ?>">
                                        <?= strtoupper($log['severity']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($log['first_name']): ?>
                                        <div class="fw-medium"><?= esc($log['first_name'] . ' ' . $log['last_name']) ?></div>
                                        <small class="text-muted"><?= esc($log['student_id']) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($log['exam_title']): ?>
                                        <div class="fw-medium"><?= esc($log['exam_title']) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code><?= esc($log['ip_address']) ?></code>
                                </td>
                                <td>
                                    <?php if ($log['event_data']): ?>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewEventDetails(<?= $log['id'] ?>)">
                                            <i class="material-symbols-rounded">info</i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="View Details"
                                                onclick="viewLogDetails(<?= $log['id'] ?>)">
                                            <i class="material-symbols-rounded">visibility</i>
                                        </button>
                                        <?php if (in_array($log['severity'], ['high', 'critical'])): ?>
                                            <button class="btn btn-outline-warning" title="Investigate"
                                                    onclick="investigateEvent(<?= $log['id'] ?>)">
                                                <i class="material-symbols-rounded">search</i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="material-symbols-rounded text-muted mb-3" style="font-size: 4rem;">security</i>
                <h5 class="text-muted">No Security Logs Found</h5>
                <p class="text-muted">No security events match your current filters.</p>
                <a href="<?= base_url('admin/security') ?>" class="btn btn-outline-primary">
                    <i class="material-symbols-rounded me-1">refresh</i>Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Auto refresh functionality
let autoRefreshInterval = null;
let autoRefreshEnabled = false;

// Refresh security logs
function refreshSecurityLogs() {
    window.location.reload();
}

// Toggle auto refresh
function toggleAutoRefresh() {
    if (autoRefreshEnabled) {
        clearInterval(autoRefreshInterval);
        autoRefreshEnabled = false;
        showNotification('Auto Refresh Disabled', 'Security logs will no longer auto-refresh.', 'info');
    } else {
        autoRefreshInterval = setInterval(refreshSecurityLogs, 30000); // Refresh every 30 seconds
        autoRefreshEnabled = true;
        showNotification('Auto Refresh Enabled', 'Security logs will refresh every 30 seconds.', 'success');
    }
}

// Export security logs
function exportSecurityLogs() {
    const urlParams = new URLSearchParams(window.location.search);
    const exportUrl = '<?= base_url("admin/security/export") ?>?' + urlParams.toString();

    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'security_logs_' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showNotification('Export Started', 'Security logs export is being prepared.', 'success');
}

// Export security report
function exportSecurityReport() {
    const exportUrl = '<?= base_url("admin/security/report") ?>';

    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'security_report_' + new Date().toISOString().split('T')[0] + '.pdf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showNotification('Report Export Started', 'Security report is being generated.', 'success');
}

// Clear old logs
function clearOldLogs() {
    if (confirm('Are you sure you want to clear old security logs? This action cannot be undone.')) {
        fetch('<?= base_url("admin/security/clear-old-logs") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success', data.message, 'success');
                setTimeout(refreshSecurityLogs, 2000);
            } else {
                showNotification('Error', data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error', 'Failed to clear old logs.', 'error');
        });
    }
}

// View log details
function viewLogDetails(logId) {
    // Open modal or new window with log details
    window.open('<?= base_url("admin/security/view/") ?>' + logId, '_blank', 'width=800,height=600');
}

// View event details
function viewEventDetails(logId) {
    fetch('<?= base_url("admin/security/event-details/") ?>' + logId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEventDetailsModal(data.event_data);
            } else {
                showNotification('Error', 'Failed to load event details.', 'error');
            }
        })
        .catch(error => {
            showNotification('Error', 'Failed to load event details.', 'error');
        });
}

// Investigate event
function investigateEvent(logId) {
    // Open investigation panel or redirect to investigation page
    window.open('<?= base_url("admin/security/investigate/") ?>' + logId, '_blank');
}

// Configure security settings
function configureSecuritySettings() {
    // Open security settings modal or redirect to settings page
    window.location.href = '<?= base_url("admin/security/settings") ?>';
}

// Show event details modal
function showEventDetailsModal(eventData) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <pre class="bg-light p-3 rounded">${JSON.stringify(eventData, null, 2)}</pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
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

// Real-time security monitoring
function initializeSecurityMonitoring() {
    // Check for critical events every 10 seconds
    setInterval(() => {
        fetch('<?= base_url("admin/security/check-critical") ?>')
            .then(response => response.json())
            .then(data => {
                if (data.critical_events > 0) {
                    showNotification(
                        'Critical Security Alert',
                        `${data.critical_events} critical security event(s) detected!`,
                        'danger'
                    );
                }
            })
            .catch(error => {
                console.error('Security monitoring error:', error);
            });
    }, 10000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize security monitoring
    initializeSecurityMonitoring();

    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn-security, .btn-danger-custom');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Loading...';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });

    // Add animation to security cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe security cards
    document.querySelectorAll('.security-card, .stats-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Handle responsive table on mobile
    function handleResponsiveTable() {
        const tables = document.querySelectorAll('.table-responsive table');

        tables.forEach(table => {
            if (window.innerWidth < 768) {
                table.classList.add('table-sm');
            } else {
                table.classList.remove('table-sm');
            }
        });
    }

    window.addEventListener('resize', handleResponsiveTable);
    handleResponsiveTable();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R for refresh
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshSecurityLogs();
    }

    // Ctrl/Cmd + E for export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        exportSecurityLogs();
    }

    // Ctrl/Cmd + Shift + A for auto refresh toggle
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        toggleAutoRefresh();
    }
});
</script>
<?= $this->endSection() ?>
