<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
  .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.2);
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
    }

    .session-badge {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
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
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .table-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
    .badge-current {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
    }
    .badge-upcoming {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    .badge-completed {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        color: white;
    }
    .bg-primary {
        background-color: var(--primary-color) !important;
    }
    .bg-success {
        background-color: #28a745 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Academic Sessions Management</h4>
                <p class="text-muted mb-0">Manage academic sessions and terms</p>
                <?php if ($currentSession && $currentTerm): ?>
                    <div class="mt-2">
                        <span class="badge bg-primary me-2">
                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">school</i>
                            Current: <?= esc($currentSession['session_name']) ?>
                        </span>
                        <span class="badge bg-success">
                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">schedule</i>
                            <?= esc($currentTerm['term_name']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <a href="<?= base_url('admin/sessions/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create New Session
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="icon-shape bg-gradient-primary shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">calendar_today</i>
                </div>
                <h5 class="text-white mb-0"><?= $stats['total'] ?? 0 ?></h5>
                <p class="text-sm mb-0 text-white">Total Sessions</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="icon-shape bg-gradient-success shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #1e7e34) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">play_arrow</i>
                </div>
                <h5 class="text-white mb-0"><?= $stats['current'] ?? 0 ?></h5>
                <p class="text-sm mb-0 text-white">Current Session</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="icon-shape shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, #ff6b35, #f7931e) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">schedule</i>
                </div>
                <h5 class="text-white mb-0"><?= $currentTerm ? esc($currentTerm['term_name']) : 'None' ?></h5>
                <p class="text-sm mb-0 text-white">Current Term</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="icon-shape bg-gradient-info shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, #17a2b8, #138496) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">upcoming</i>
                </div>
                <h5 class="text-white mb-0"><?= $stats['upcoming'] ?? 0 ?></h5>
                <p class="text-sm mb-0 text-white">Upcoming Sessions</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="icon-shape bg-gradient-secondary shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, #6c757d, #545b62) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">check_circle</i>
                </div>
                <h5 class="text-white mb-0"><?= $stats['completed'] ?? 0 ?></h5>
                <p class="text-sm mb-0 text-white">Completed Sessions</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="icon-shape shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, #e83e8c, #d63384) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">event_available</i>
                </div>
                <h5 class="text-white mb-0"><?= $stats['active'] ?? 0 ?></h5>
                <p class="text-sm mb-0 text-white">Active Sessions</p>
            </div>
        </div>
    </div>
</div>

<!-- Sessions Table -->
<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">list</i>
                        Academic Sessions
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshTable()">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">refresh</i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase font-weight-bolder" style="background: var(--primary-color); color: white !important; padding: 12px; font-size: 0.75rem;">Session</th>
                                <th class="text-uppercase font-weight-bolder" style="background: var(--primary-color); color: white !important; padding: 12px; font-size: 0.75rem;">Duration</th>
                                <th class="text-uppercase font-weight-bolder" style="background: var(--primary-color); color: white !important; padding: 12px; font-size: 0.75rem;">Terms</th>
                                <th class="text-uppercase font-weight-bolder" style="background: var(--primary-color); color: white !important; padding: 12px; font-size: 0.75rem;">Status</th>
                                <th class="text-uppercase font-weight-bolder" style="background: var(--primary-color); color: white !important; padding: 12px; font-size: 0.75rem;">Created</th>
                                <th class="text-uppercase font-weight-bolder text-center" style="background: var(--primary-color); color: white !important; padding: 12px; font-size: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sessions)): ?>
                                <?php foreach ($sessions as $session): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-shape bg-gradient-primary shadow-sm text-center border-radius-sm me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;">
                                                    <i class="material-symbols-rounded text-white" style="font-size: 16px; line-height: 32px;">school</i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-sm fw-medium"><?= esc($session['session_name']) ?></h6>
                                                    <?php if ($session['is_current']): ?>
                                                        <span class="badge badge-current badge-sm">Current Session</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?= date('M j, Y', strtotime($session['start_date'])) ?></p>
                                            <p class="text-xs text-muted mb-0">to <?= date('M j, Y', strtotime($session['end_date'])) ?></p>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?= $session['term_count'] ?? 0 ?> Terms</span>
                                        </td>
                                        <td>
                                            <?php
                                            $today = date('Y-m-d');
                                            if ($session['is_current']) {
                                                echo '<span class="badge badge-current">Current</span>';
                                            } elseif ($session['start_date'] > $today) {
                                                echo '<span class="badge badge-upcoming">Upcoming</span>';
                                            } else {
                                                echo '<span class="badge badge-completed">Completed</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?= date('M j, Y', strtotime($session['created_at'])) ?></p>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= base_url('admin/sessions/view/' . $session['id']) ?>"
                                                   class="btn btn-outline-primary btn-sm" title="View Details">
                                                    <i class="material-symbols-rounded" style="font-size: 16px;">visibility</i>
                                                </a>
                                                <a href="<?= base_url('admin/sessions/edit/' . $session['id']) ?>"
                                                   class="btn btn-outline-primary btn-sm" title="Edit">
                                                    <i class="material-symbols-rounded" style="font-size: 16px;">edit</i>
                                                </a>
                                                <?php if (!$session['is_current']): ?>
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                            onclick="showSetCurrentModal(<?= $session['id'] ?>, '<?= esc($session['session_name']) ?>')" title="Set as Current">
                                                        <i class="material-symbols-rounded" style="font-size: 16px;">play_arrow</i>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="showDeleteModal(<?= $session['id'] ?>, '<?= esc($session['session_name']) ?>')" title="Delete">
                                                    <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="material-symbols-rounded text-muted mb-2" style="font-size: 48px;">calendar_today</i>
                                            <h6 class="text-muted">No Academic Sessions Found</h6>
                                            <p class="text-sm text-muted mb-3">Create your first academic session to get started</p>
                                            <a href="<?= base_url('admin/sessions/create') ?>" class="btn btn-primary btn-sm">
                                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">add</i>Create Session
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Set Current Session Modal -->
<div class="modal fade" id="setCurrentModal" tabindex="-1" aria-labelledby="setCurrentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setCurrentModalLabel">
                    <i class="material-symbols-rounded me-2">play_arrow</i>Set Current Session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to set <strong id="currentSessionName"></strong> as the current academic session?</p>
                <div class="alert alert-warning">
                    <i class="material-symbols-rounded me-2">warning</i>
                    This will deactivate the current session and make this session active for all operations.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSetCurrent">Set as Current</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Session Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="material-symbols-rounded me-2">delete</i>Delete Session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteSessionName"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="material-symbols-rounded me-2">error</i>
                    This action cannot be undone and will affect all related data including terms, exams, and results.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Session</button>
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

function refreshTable() {
    window.location.reload();
}

let currentSessionId = null;
let deleteSessionId = null;

function showSetCurrentModal(sessionId, sessionName) {
    currentSessionId = sessionId;
    document.getElementById('currentSessionName').textContent = sessionName;
    const modal = new bootstrap.Modal(document.getElementById('setCurrentModal'));
    modal.show();
}

function showDeleteModal(sessionId, sessionName) {
    deleteSessionId = sessionId;
    document.getElementById('deleteSessionName').textContent = sessionName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handle set current confirmation
document.getElementById('confirmSetCurrent').addEventListener('click', function() {
    if (currentSessionId) {
        window.location.href = `<?= base_url('admin/sessions/set-current/') ?>${currentSessionId}`;
    }
});

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteSessionId) {
        window.location.href = `<?= base_url('admin/sessions/delete/') ?>${deleteSessionId}`;
    }
});
</script>
<?= $this->endSection() ?>
