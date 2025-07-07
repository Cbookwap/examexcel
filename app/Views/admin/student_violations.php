<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
    .student-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(var(--primary-color-rgb), 0.3);
    }

    .violation-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #dc3545;
    }

    .security-event-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #6c757d;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .punishment-actions {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-badge.active {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-badge.suspended {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-badge.banned {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .violation-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .violation-timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1rem;
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 1rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #dc3545;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #dee2e6;
    }

    .timeline-item.warning::before {
        background: #ffc107;
    }

    .timeline-item.suspension::before {
        background: #fd7e14;
    }

    .timeline-item.ban::before {
        background: #dc3545;
    }

    .quick-action-btn {
        transition: all 0.3s ease;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 0.5rem 1rem;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .quick-actions-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #dee2e6;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }

    .advanced-actions-card {
        border: 2px dashed #dee2e6;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .advanced-actions-card:hover {
        border-color: #667eea;
        background: white;
    }
</style>

<!-- Student Header -->
<div class="student-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-2">
                <a href="<?= base_url('admin/violations') ?>" class="btn btn-light btn-sm me-3">
                    <i class="fas fa-arrow-left"></i> Back to Violations
                </a>
                <h3 class="mb-0 font-weight-bolder">
                    <i class="fas fa-user-shield me-2"></i>
                    <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
                </h3>
            </div>
            <p class="mb-0 opacity-75">
                Student ID: <?= esc($student['student_id']) ?> | 
                Email: <?= esc($student['email']) ?> |
                Class: <?= esc($student['class_id'] ?? 'Not assigned') ?>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($suspensionDetails['is_banned']): ?>
                <span class="status-badge banned">
                    <i class="fas fa-ban me-1"></i>Permanently Banned
                </span>
            <?php elseif ($suspensionDetails['is_suspended']): ?>
                <span class="status-badge suspended">
                    <i class="fas fa-clock me-1"></i>Suspended until <?= date('M j, Y', strtotime($suspensionDetails['suspended_until'])) ?>
                </span>
            <?php else: ?>
                <span class="status-badge active">
                    <i class="fas fa-check me-1"></i>Active
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-container">
    <h5 class="mb-3"><i class="fas fa-bolt me-2 text-primary"></i>Quick Actions</h5>

    <!-- Current Status and Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <?php if ($suspensionDetails['is_banned']): ?>
                    <span class="status-indicator banned">
                        <i class="fas fa-ban me-2"></i>Permanently Banned
                    </span>
                    <button type="button" class="btn btn-success quick-action-btn" onclick="quickLiftBan()">
                        <i class="fas fa-unlock me-1"></i>Lift Ban
                    </button>
                <?php elseif ($suspensionDetails['is_suspended']): ?>
                    <span class="status-indicator suspended">
                        <i class="fas fa-clock me-2"></i>Suspended until <?= date('M j, Y', strtotime($suspensionDetails['suspended_until'])) ?>
                    </span>
                    <button type="button" class="btn btn-warning quick-action-btn" onclick="quickRemoveSuspension()">
                        <i class="fas fa-clock me-1"></i>Remove Suspension
                    </button>
                <?php else: ?>
                    <span class="status-indicator active">
                        <i class="fas fa-check me-2"></i>Active - No Restrictions
                    </span>
                <?php endif; ?>

                <button type="button" class="btn btn-info quick-action-btn" onclick="showClearViolationsModal()">
                    <i class="fas fa-eraser me-1"></i>Clear Violations
                </button>

                <button type="button" class="btn btn-outline-secondary quick-action-btn" onclick="toggleAdvancedActions()">
                    <i class="fas fa-cog me-1"></i>Advanced Actions
                </button>
            </div>
        </div>
    </div>

    <!-- Advanced Actions (Hidden by default) -->
    <div id="advancedActions" style="display: none;">
        <div class="card advanced-actions-card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-gavel me-2 text-danger"></i>Apply New Punishment</h6>
                <small class="text-muted">Use this section to apply additional punishments if needed</small>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/apply-punishment') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="student_id" value="<?= $student['id'] ?>">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="punishment_type" class="form-label">Punishment Type</label>
                            <select name="punishment_type" id="punishment_type" class="form-select" required>
                                <option value="">Select Punishment</option>
                                <option value="warning">Warning</option>
                                <option value="temporary_suspension">Temporary Suspension</option>
                                <option value="permanent_ban">Permanent Ban</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="duration-field" style="display: none;">
                            <label for="duration" class="form-label">Duration (Days)</label>
                            <input type="number" name="duration" id="duration" class="form-control" min="1" max="365">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-gavel me-1"></i>Apply Punishment
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea name="reason" id="reason" class="form-control" rows="2" placeholder="Enter reason for punishment..."></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Violation History -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Violation History</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($violations)): ?>
                    <div class="violation-timeline">
                        <?php foreach ($violations as $violation): ?>
                            <div class="timeline-item <?= $violation['punishment_type'] ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-<?= $violation['severity'] === 'critical' ? 'danger' : ($violation['severity'] === 'high' ? 'warning' : 'secondary') ?>">
                                                <?= ucfirst($violation['severity']) ?>
                                            </span>
                                            <span class="ms-2 fw-bold">
                                                <?= ucfirst(str_replace('_', ' ', $violation['punishment_type'])) ?>
                                            </span>
                                            <?php if ($violation['punishment_duration']): ?>
                                                <span class="ms-2 text-muted">(<?= $violation['punishment_duration'] ?> days)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Violation Count:</strong> <?= $violation['violation_count'] ?>
                                        </div>
                                        <?php if ($violation['notes']): ?>
                                            <div class="mb-2">
                                                <small class="text-muted"><?= esc($violation['notes']) ?></small>
                                            </div>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('M j, Y g:i A', strtotime($violation['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-shield-alt text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">No Violations</h5>
                        <p class="text-muted">This student has a clean record.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Security Events -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Events</h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                <?php if (!empty($securityEvents)): ?>
                    <?php foreach ($securityEvents as $event): ?>
                        <div class="security-event-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-<?= $event['severity'] === 'critical' ? 'danger' : ($event['severity'] === 'high' ? 'warning' : 'secondary') ?>">
                                            <?= ucfirst($event['severity']) ?>
                                        </span>
                                        <span class="ms-2 fw-bold">
                                            <?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?>
                                        </span>
                                    </div>
                                    <?php if ($event['exam_title']): ?>
                                        <div class="mb-1">
                                            <small class="text-muted">Exam:</small>
                                            <strong><?= esc($event['exam_title']) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('M j, Y g:i A', strtotime($event['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">No Security Events</h5>
                        <p class="text-muted">No security events recorded for this student.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Lift Ban Modal -->
<div class="modal fade" id="quickLiftBanModal" tabindex="-1" aria-labelledby="quickLiftBanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickLiftBanModalLabel">
                    <i class="fas fa-unlock text-success me-2"></i>
                    Lift Ban
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Quick Action:</strong> This will immediately lift the ban and restore exam access.
                </div>
                <p>Are you sure you want to lift the ban for <strong><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></strong>?</p>
                <div class="mb-3">
                    <label for="liftBanReason" class="form-label">Reason (Optional)</label>
                    <textarea class="form-control" id="liftBanReason" rows="2" placeholder="Enter reason for lifting the ban..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="confirmQuickLiftBan()">
                    <i class="fas fa-unlock me-1"></i>Yes, Lift Ban
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Remove Suspension Modal -->
<div class="modal fade" id="quickRemoveSuspensionModal" tabindex="-1" aria-labelledby="quickRemoveSuspensionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickRemoveSuspensionModalLabel">
                    <i class="fas fa-clock text-warning me-2"></i>
                    Remove Suspension
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Quick Action:</strong> This will immediately remove the suspension and restore exam access.
                </div>
                <p>Are you sure you want to remove the suspension for <strong><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></strong>?</p>
                <div class="mb-3">
                    <label for="removeSuspensionReason" class="form-label">Reason (Optional)</label>
                    <textarea class="form-control" id="removeSuspensionReason" rows="2" placeholder="Enter reason for removing the suspension..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="confirmQuickRemoveSuspension()">
                    <i class="fas fa-clock me-1"></i>Yes, Remove Suspension
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Violations Confirmation Modal -->
<div class="modal fade" id="clearViolationsModal" tabindex="-1" aria-labelledby="clearViolationsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearViolationsModalLabel">
                    <i class="fas fa-eraser text-info me-2"></i>
                    Clear All Violations
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> This will clear violation history but won't change current ban/suspension status.
                </div>
                <p>Are you sure you want to clear all violation records for <strong><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></strong>?</p>
                <div class="mb-3">
                    <label for="clearViolationsReason" class="form-label">Reason (Optional)</label>
                    <textarea class="form-control" id="clearViolationsReason" rows="2" placeholder="Enter reason for clearing violations..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-info" onclick="confirmClearViolations()">
                    <i class="fas fa-eraser me-1"></i>Yes, Clear Violations
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const punishmentType = document.getElementById('punishment_type');
    const durationField = document.getElementById('duration-field');
    const durationInput = document.getElementById('duration');

    if (punishmentType) {
        punishmentType.addEventListener('change', function() {
            if (this.value === 'temporary_suspension') {
                durationField.style.display = 'block';
                durationInput.required = true;
            } else {
                durationField.style.display = 'none';
                durationInput.required = false;
            }
        });
    }
});

// Toggle advanced actions
function toggleAdvancedActions() {
    const advancedActions = document.getElementById('advancedActions');
    if (advancedActions.style.display === 'none') {
        advancedActions.style.display = 'block';
    } else {
        advancedActions.style.display = 'none';
    }
}

// Quick lift ban
function quickLiftBan() {
    document.getElementById('liftBanReason').value = '';
    const modal = new bootstrap.Modal(document.getElementById('quickLiftBanModal'));
    modal.show();
}

// Quick remove suspension
function quickRemoveSuspension() {
    document.getElementById('removeSuspensionReason').value = '';
    const modal = new bootstrap.Modal(document.getElementById('quickRemoveSuspensionModal'));
    modal.show();
}

// Show clear violations modal
function showClearViolationsModal() {
    document.getElementById('clearViolationsReason').value = '';
    const modal = new bootstrap.Modal(document.getElementById('clearViolationsModal'));
    modal.show();
}

// Confirm quick lift ban
function confirmQuickLiftBan() {
    const reason = document.getElementById('liftBanReason').value;
    performQuickAction('clear', reason || 'Ban lifted by admin');
}

// Confirm quick remove suspension
function confirmQuickRemoveSuspension() {
    const reason = document.getElementById('removeSuspensionReason').value;
    performQuickAction('clear', reason || 'Suspension removed by admin');
}

// Confirm clear violations
function confirmClearViolations() {
    const reason = document.getElementById('clearViolationsReason').value;

    // Create and submit form for clearing violations
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/clear-violations') ?>';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Add form fields
    const fields = {
        'student_id': '<?= $student['id'] ?>',
        'reason': reason || 'Violations cleared by admin'
    };

    for (const [name, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

// Perform quick action (lift ban/remove suspension)
function performQuickAction(punishmentType, reason) {
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/apply-punishment') ?>';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Add form fields
    const fields = {
        'student_id': '<?= $student['id'] ?>',
        'punishment_type': punishmentType,
        'reason': reason
    };

    for (const [name, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}
</script>

<?= $this->endSection() ?>
