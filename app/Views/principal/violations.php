<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<style>
.violation-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    overflow: hidden;
    transition: all 0.3s ease;
}

.violation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.severity-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.severity-low {
    background: #d4edda;
    color: #155724;
}

.severity-medium {
    background: #fff3cd;
    color: #856404;
}

.severity-high {
    background: #f8d7da;
    color: #721c24;
}

.severity-critical {
    background: #d1ecf1;
    color: #0c5460;
}

.ban-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-banned {
    background: #f8d7da;
    color: #721c24;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

@media (max-width: 768px) {
    .table-responsive {
        border-radius: 15px;
    }
}
</style>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;">Violation Management</h4>
                <p class="text-light mb-0">Monitor and manage student exam violations</p>
            </div>
            <a href="<?= base_url('principal/dashboard') ?>" class="btn btn-outline-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Dashboard
            </a>
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

<!-- Violations Table -->
<div class="card violation-card">
    <div class="card-header bg-white border-bottom-0 py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">security</i>
            Security Violations
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($violations)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="color: white; background: var(--primary-color);">Student</th>
                        <th style="color: white; background: var(--primary-color);">Violation Type</th>
                        <th style="color: white; background: var(--primary-color);">Severity</th>
                        <th style="color: white; background: var(--primary-color);">Description</th>
                        <th style="color: white; background: var(--primary-color);">Date</th>
                        <th style="color: white; background: var(--primary-color);">Status</th>
                        <th style="color: white; background: var(--primary-color);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($violations as $violation): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <span class="text-white fw-bold"><?= strtoupper(substr($violation['first_name'], 0, 1)) ?></span>
                                </div>
                                <div>
                                    <div class="fw-medium"><?= esc($violation['first_name'] . ' ' . $violation['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($violation['student_id'] ?? $violation['username']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-medium"><?= esc($violation['violation_type']) ?></span>
                        </td>
                        <td>
                            <?php
                            $severityClass = 'severity-low';
                            switch (strtolower($violation['severity'])) {
                                case 'medium':
                                    $severityClass = 'severity-medium';
                                    break;
                                case 'high':
                                    $severityClass = 'severity-high';
                                    break;
                                case 'critical':
                                    $severityClass = 'severity-critical';
                                    break;
                            }
                            ?>
                            <span class="severity-badge <?= $severityClass ?>"><?= esc($violation['severity']) ?></span>
                        </td>
                        <td>
                            <span class="text-muted"><?= esc($violation['description']) ?></span>
                        </td>
                        <td>
                            <span class="fw-medium"><?= date('M j, Y', strtotime($violation['created_at'])) ?></span><br>
                            <small class="text-muted"><?= date('g:i A', strtotime($violation['created_at'])) ?></small>
                        </td>
                        <td>
                            <?php if ($violation['is_banned']): ?>
                                <span class="ban-status status-banned">Banned</span>
                            <?php else: ?>
                                <span class="ban-status status-active">Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <?php if ($violation['is_banned']): ?>
                                    <button class="btn btn-sm btn-outline-success" onclick="confirmLiftBan(<?= $violation['student_id'] ?>, '<?= esc($violation['first_name'] . ' ' . $violation['last_name']) ?>')">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">lock_open</i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteViolation(<?= $violation['id'] ?>, '<?= esc($violation['violation_type']) ?>')">
                                    <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">security</i>
            <h5 class="text-muted">No Violations Recorded</h5>
            <p class="text-muted">All students are following exam protocols properly.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Lift Ban Confirmation Modal -->
<div class="modal fade" id="liftBanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lift Ban</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to lift the ban for "<span id="studentName"></span>"?</p>
                <p class="text-info small">The student will be able to access the system again.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="liftBanForm" style="display: inline;">
                    <button type="submit" class="btn btn-success">Lift Ban</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Violation Confirmation Modal -->
<div class="modal fade" id="deleteViolationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Violation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this "<span id="violationType"></span>" violation record?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteViolationForm" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Delete Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
});

function confirmLiftBan(userId, studentName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('liftBanForm').action = '<?= base_url('principal/violations/lift-ban/') ?>' + userId;
    
    const modal = new bootstrap.Modal(document.getElementById('liftBanModal'));
    modal.show();
}

function confirmDeleteViolation(violationId, violationType) {
    document.getElementById('violationType').textContent = violationType;
    document.getElementById('deleteViolationForm').action = '<?= base_url('principal/violations/delete/') ?>' + violationId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteViolationModal'));
    modal.show();
}
</script>
<?= $this->endSection() ?>
