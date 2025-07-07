<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1" style="color: #1f2937; font-weight: 600;"><?= esc($pageTitle) ?></h4>
                <p class="text-muted mb-0"><?= esc($pageSubtitle) ?></p>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="refreshPage()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div>
                <strong>Success!</strong> <?= session()->getFlashdata('success') ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>
                <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Main Content -->
<div class="row">
    <!-- Backup Actions Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-database me-2 text-primary"></i>Backup Actions
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-primary btn-lg" id="createBackupBtn">
                        <i class="fas fa-download me-2"></i>
                        Create New Backup
                    </button>

                    <!-- Progress indicator (hidden by default) -->
                    <div id="backupProgress" class="mt-3" style="display: none;">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <small class="text-muted">Creating backup, please wait...</small>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 mb-0" style="background: rgba(var(--primary-color-rgb), 0.1);">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle me-2 mt-1" style="color: var(--primary-color);"></i>
                            <div class="small">
                                <strong>Auto Backup:</strong> <?= ucfirst($settings['backup_frequency'] ?? 'weekly') ?><br>
                                <strong>Retention:</strong> <?= $settings['backup_retention_days'] ?? 30 ?> days
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Statistics Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-bar me-2 text-success"></i>Statistics
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="icon-shape bg-primary text-white rounded-circle mx-auto mb-2" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-archive"></i>
                            </div>
                            <h4 class="mb-1" style="color: var(--primary-color);"><?= count($backupFiles) ?></h4>
                            <p class="text-muted small mb-0">Total Backups</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="icon-shape bg-success text-white rounded-circle mx-auto mb-2" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <h4 class="text-success mb-1">
                                <?php
                                $totalSize = 0;
                                foreach ($backupFiles as $file) {
                                    $filePath = $backupPath . '/' . $file['name'];
                                    if (file_exists($filePath)) {
                                        $totalSize += filesize($filePath);
                                    }
                                }
                                echo $totalSize > 0 ? number_format($totalSize / (1024 * 1024), 1) . ' MB' : '0 MB';
                                ?>
                            </h4>
                            <p class="text-muted small mb-0">Total Size</p>
                        </div>
                    </div>
                </div>

                <?php if (!empty($backupFiles)): ?>
                <hr class="my-3">
                <div class="text-center">
                    <p class="small mb-1 fw-semibold">Latest Backup:</p>
                    <p class="text-muted small mb-0"><?= date('M j, Y - g:i A', strtotime($backupFiles[0]['date'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('admin/settings') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-cog me-2"></i>
                        Backup Settings
                    </a>

                    <a href="<?= base_url('admin/system-info') ?>" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-info-circle me-2"></i>
                        System Info
                    </a>

                    <a href="<?= base_url('admin/activity-log') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-history me-2"></i>
                        Activity Log
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Backup Files List -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-folder-open me-2"></i>Backup Files
                        </h6>
                        <p class="text-muted small mb-0">Manage your database backup files</p>
                    </div>
                    <span class="badge bg-light text-dark"><?= count($backupFiles) ?> files</span>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($backupFiles)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No backup files found</h6>
                        <p class="text-muted small">Create your first backup to get started</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold" style="color: black !important;">File Name</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Size</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Created Date</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backupFiles as $file): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-shape bg-light text-primary rounded me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-file-archive"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?= esc($file['name']) ?></div>
                                                    <small class="text-muted">SQL Database Backup</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?= esc($file['size']) ?></span>
                                        </td>
                                        <td>
                                            <div><?= date('M j, Y', strtotime($file['date'])) ?></div>
                                            <small class="text-muted"><?= date('g:i A', strtotime($file['date'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= base_url('admin/backup/download/' . urlencode($file['name'])) ?>"
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="confirmDelete('<?= esc($file['name']) ?>')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Backup Confirmation Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1" aria-labelledby="createBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold" id="createBackupModalLabel">
                    <i class="fas fa-database text-primary me-2"></i>Create Database Backup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="icon-shape bg-primary text-white rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-download fa-2x"></i>
                    </div>
                    <h6 class="mb-2">Create New Database Backup?</h6>
                    <p class="text-muted small mb-3">This will create a complete backup of your database. The process may take a few moments depending on your database size.</p>
                    <div class="alert alert-info border-0 mb-0" style="background: rgba(var(--primary-color-rgb), 0.1);">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>
                            <small><strong>Note:</strong> The backup will be saved as an SQL file that you can download.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCreateBackupBtn">
                    <i class="fas fa-download me-2"></i>Create Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="icon-shape bg-danger text-white rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trash fa-2x"></i>
                    </div>
                    <h6 class="mb-2">Are you sure you want to delete this backup?</h6>
                    <p class="text-muted small mb-3">This action cannot be undone.</p>
                    <div class="alert alert-warning border-0 mb-0">
                        <strong>File:</strong> <span id="fileNameDisplay" class="text-dark"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Delete Backup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    box-shadow: 0 6px 20px rgba(var(--primary-color-rgb), 0.4);
}

.alert {
    border-radius: 10px;
    border: none;
}

.table th {
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
}

.btn-group-sm > .btn {
    padding: 0.375rem 0.5rem;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    background: #f8f9fa;
    border-radius: 0 0 15px 15px;
}

.badge {
    font-weight: 500;
}

/* Loading state for backup button */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Modal animations */
.modal.fade .modal-dialog {
    transition: transform 0.4s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}

/* Notification styles */
.alert.position-fixed {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .modal-dialog {
        margin: 1rem;
    }

    .alert.position-fixed {
        right: 10px;
        left: 10px;
        min-width: auto;
    }
}
</style>

<script>
// Create backup button - show modal instead of browser confirm
document.getElementById('createBackupBtn').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('createBackupModal'));
    modal.show();
});

// Confirm create backup from modal
document.getElementById('confirmCreateBackupBtn').addEventListener('click', function() {
    const btn = document.getElementById('createBackupBtn');
    const modalBtn = this;
    const progressDiv = document.getElementById('backupProgress');
    const originalText = btn.innerHTML;
    const originalModalText = modalBtn.innerHTML;

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('createBackupModal'));
    modal.hide();

    // Show loading state on main button
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Backup...';

    // Show progress indicator
    progressDiv.style.display = 'block';

    // Show loading state on modal button (in case modal is still visible)
    modalBtn.disabled = true;
    modalBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';

    // Show notification
    showNotification('Backup creation started. Please wait...', 'info');

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/backup/create') ?>';

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '<?= csrf_token() ?>';
    csrfInput.value = '<?= csrf_hash() ?>';
    form.appendChild(csrfInput);

    document.body.appendChild(form);
    form.submit();

    // Reset buttons after a delay (in case of errors)
    setTimeout(function() {
        btn.disabled = false;
        btn.innerHTML = originalText;
        modalBtn.disabled = false;
        modalBtn.innerHTML = originalModalText;
        progressDiv.style.display = 'none';
    }, 30000);
});

// Delete confirmation
function confirmDelete(filename) {
    document.getElementById('fileNameDisplay').textContent = filename;

    // Store filename for deletion
    document.getElementById('confirmDeleteBtn').setAttribute('data-filename', filename);

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    const filename = this.getAttribute('data-filename');
    const btn = this;
    const originalText = btn.innerHTML;

    if (!filename) {
        showNotification('Error: No file selected for deletion', 'error');
        return;
    }

    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
    modal.hide();

    // Show notification
    showNotification('Deleting backup file...', 'warning');

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/backup/delete/') ?>' + encodeURIComponent(filename);

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '<?= csrf_token() ?>';
    csrfInput.value = '<?= csrf_hash() ?>';
    form.appendChild(csrfInput);

    document.body.appendChild(form);
    form.submit();

    // Reset button after a delay (in case of errors)
    setTimeout(function() {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 10000);
});

// Refresh page
function refreshPage() {
    // Show loading indicator
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    btn.disabled = true;

    setTimeout(function() {
        window.location.reload();
    }, 500);
}

// Show success/error messages with auto-dismiss
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 8 seconds (longer for backup messages)
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 8000);
    });

    // Add tooltips to action buttons
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Reset modal states when modals are hidden
    document.getElementById('createBackupModal').addEventListener('hidden.bs.modal', function() {
        const modalBtn = document.getElementById('confirmCreateBackupBtn');
        modalBtn.disabled = false;
        modalBtn.innerHTML = '<i class="fas fa-download me-2"></i>Create Backup';
    });

    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function() {
        const modalBtn = document.getElementById('confirmDeleteBtn');
        modalBtn.disabled = false;
        modalBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Delete Backup';
        modalBtn.removeAttribute('data-filename');
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + B for backup
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            document.getElementById('createBackupBtn').click();
        }

        // F5 for refresh (override default to use our refresh function)
        if (e.key === 'F5') {
            e.preventDefault();
            refreshPage();
        }
    });

    // Add visual feedback for file operations
    const downloadLinks = document.querySelectorAll('a[href*="download"]');
    downloadLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // Show brief download notification
            showNotification('Download started...', 'info');
        });
    });
});

// Utility function to show notifications
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      type === 'warning' ? 'alert-warning' : 'alert-info';

    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            <div>${message}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-remove after 3 seconds
    setTimeout(function() {
        if (notification && notification.parentNode) {
            const bsAlert = new bootstrap.Alert(notification);
            bsAlert.close();
        }
    }, 3000);
}
</script>

<?= $this->endSection() ?>
