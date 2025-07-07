<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .academic-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .academic-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .feature-card {
        text-align: center;
        padding: 2rem;
        border-radius: 15px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary-color);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .stat-description {
        color: #9ca3af;
        font-size: 0.8rem;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .btn-feature {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-feature:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(160, 90, 255, 0.3);
        color: white;
    }

    .session-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    /* Activity List Styles */
    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .activity-content h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #333;
    }

    .activity-content p {
        font-size: 0.8rem;
        line-height: 1.4;
    }

    .activity-content small {
        font-size: 0.75rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0 font-weight-bolder">Academic Management</h3>
                <p class="mb-0">Manage student promotions, academic records, and term results</p>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="showSetupRulesModal()">
                    <i class="fas fa-cog me-2"></i>Setup Default Rules
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Current Session/Term Info -->
<?php if ($currentSession && $currentTerm): ?>
<div class="session-info">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="mb-1">
                <i class="fas fa-calendar-alt me-2"></i>
                Current Academic Period
            </h6>
            <p class="mb-0">
                <strong><?= esc($currentSession['session_name']) ?></strong> -
                <strong><?= esc($currentTerm['term_name']) ?></strong>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <small class="text-muted">
                <?= date('M d, Y', strtotime($currentSession['start_date'])) ?> -
                <?= date('M d, Y', strtotime($currentSession['end_date'])) ?>
            </small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Academic Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= $stats['active_sessions'] ?></div>
        <div class="stat-label">Active Sessions</div>
        <div class="stat-description">Academic sessions in the system</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $stats['active_classes'] ?></div>
        <div class="stat-label">Active Classes</div>
        <div class="stat-description">Classes available for enrollment</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $stats['total_students'] ?></div>
        <div class="stat-label">Total Students</div>
        <div class="stat-description">Students across all classes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $stats['pending_promotions'] ?></div>
        <div class="stat-label">Pending Promotions</div>
        <div class="stat-description">Students awaiting promotion</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <h5 class="mb-3">Academic Management Tools</h5>
    </div>
</div>

<div class="quick-actions">
    <!-- Student History -->
    <div class="academic-card">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h6 class="mb-2">Student Academic History</h6>
            <p class="text-muted mb-3">View and manage individual student academic records and progression</p>
            <a href="<?= base_url('admin/users?role=student') ?>" class="btn-feature">
                <i class="fas fa-search me-2"></i>View Students
            </a>
        </div>
    </div>

    <!-- Class Promotion -->
    <div class="academic-card">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-level-up-alt"></i>
            </div>
            <h6 class="mb-2">Class Promotion</h6>
            <p class="text-muted mb-3">Promote students to next class based on performance criteria</p>
            <a href="<?= base_url('academic/class-promotion') ?>" class="btn-feature">
                <i class="fas fa-arrow-up me-2"></i>Manage Promotions
            </a>
        </div>
    </div>

    <!-- Term Results -->
    <div class="academic-card">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h6 class="mb-2">Term Results</h6>
            <p class="text-muted mb-3">Calculate and review term results for all students</p>
            <a href="<?= base_url('academic/term-results') ?>" class="btn-feature">
                <i class="fas fa-calculator me-2"></i>Calculate Results
            </a>
        </div>
    </div>



    <!-- Academic Sessions -->
    <div class="academic-card">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <h6 class="mb-2">Academic Sessions</h6>
            <p class="text-muted mb-3">Manage academic sessions and terms</p>
            <a href="<?= base_url('admin/sessions') ?>" class="btn-feature">
                <i class="fas fa-calendar-plus me-2"></i>Manage Sessions
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="academic-card">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h6 class="mb-2">Academic Reports</h6>
            <p class="text-muted mb-3">Generate comprehensive academic performance reports</p>
            <a href="<?= base_url('admin/reports') ?>" class="btn-feature">
                <i class="fas fa-download me-2"></i>Generate Reports
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="academic-card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Recent Academic Activities
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($recentActivities)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Recent Activities</h6>
                        <p class="text-muted mb-0">Academic activities will appear here once you start managing student records.</p>
                    </div>
                <?php else: ?>
                    <div class="activity-list">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item d-flex align-items-start mb-3">
                                <div class="activity-icon me-3">
                                    <div class="icon-circle bg-<?= $activity['color'] ?>">
                                        <i class="<?= $activity['icon'] ?> text-white"></i>
                                    </div>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <h6 class="mb-1"><?= esc($activity['title']) ?></h6>
                                    <p class="text-muted mb-1"><?= esc($activity['description']) ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('M j, Y g:i A', strtotime($activity['timestamp'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($recentActivities) >= 10): ?>
                        <div class="text-center mt-3">
                            <a href="<?= base_url('admin/reports') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-2"></i>View All Activities
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Setup Default Rules Modal -->
<div class="modal fade" id="setupRulesModal" tabindex="-1" aria-labelledby="setupRulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="setupRulesModalLabel">
                    <i class="fas fa-cog me-2"></i>Setup Default Rules
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Information:</strong> This will create default promotion rules for the Nigerian school system.
                </div>
                <p class="mb-3">This action will set up:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>Standard promotion criteria</li>
                    <li><i class="fas fa-check text-success me-2"></i>Grade boundaries and thresholds</li>
                    <li><i class="fas fa-check text-success me-2"></i>Subject requirements</li>
                    <li><i class="fas fa-check text-success me-2"></i>Class-specific rules</li>
                </ul>
                <p class="text-muted mb-0">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Existing rules will be updated if they already exist.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="createDefaultRules()">
                    <i class="fas fa-cog me-2"></i>Setup Rules
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function showSetupRulesModal() {
    const modal = new bootstrap.Modal(document.getElementById('setupRulesModal'));
    modal.show();
}

function createDefaultRules() {
    // Hide the modal first
    const modal = bootstrap.Modal.getInstance(document.getElementById('setupRulesModal'));
    modal.hide();

    // Show loading state
    showNotification('Processing', 'Setting up default rules...', 'info');

    fetch('<?= base_url('academic/create-default-rules') ?>', {
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
            setTimeout(() => location.reload(), 2000);
        } else {
            showNotification('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error', 'An error occurred while creating default rules.', 'error');
    });
}

// Notification system
function showNotification(title, message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };

    const iconClass = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };

    const alertHtml = `
        <div class="alert ${alertClass[type]} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            <i class="${iconClass[type]} me-2"></i>
            <strong>${title}:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHtml);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.textContent.includes(title)) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
}
</script>
<?= $this->endSection() ?>
