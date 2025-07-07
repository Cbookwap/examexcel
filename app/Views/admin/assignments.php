<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .assignment-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .assignment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .teacher-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .btn-action {
        border-radius: 8px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        margin: 0 0.125rem;
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .btn-outline-danger {
        color: #f44336;
        border-color: #f44336;
        background-color: transparent;
    }
    .btn-outline-danger:hover {
        color: white;
        background-color: #f44336;
        border-color: #f44336;
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
    
    /* Ensure Material Icons display properly */
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }

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
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Teacher Assignments</h4>
                <p class="text-muted mb-0">Manage teacher-subject-class assignments</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (!empty($currentSession)): ?>
                    <span class="session-badge">
                        <i class="material-symbols-rounded me-2" style="font-size: 16px;">school</i>
                        <?= esc($currentSession['session_name']) ?>
                    </span>
                <?php endif; ?>
                <a href="<?= base_url('admin/assignments/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Assign Teacher
                </a>
            </div>
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

<!-- Assignment Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['total_assignments'] ?? 0 ?></h3>
            <p class="mb-0">Total Assignments</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['teachers_assigned'] ?? 0 ?></h3>
            <p class="mb-0">Teachers Assigned</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['subjects_assigned'] ?? 0 ?></h3>
            <p class="mb-0">Subjects Assigned</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['classes_assigned'] ?? 0 ?></h3>
            <p class="mb-0">Classes Assigned</p>
        </div>
    </div>
</div>

<!-- Session Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="d-flex align-items-center gap-3">
                    <label class="form-label mb-0 fw-semibold">Filter by Session:</label>
                    <select name="session" class="form-select" style="width: 200px;" onchange="this.form.submit()">
                        <option value="">All Sessions</option>
                        <?php foreach ($sessions as $session): ?>
                            <option value="<?= $session['id'] ?>" <?= $selectedSession == $session['id'] ? 'selected' : '' ?>>
                                <?= esc($session['session_name']) ?>
                                <?= $session['is_current'] ? ' (Current)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Assignments Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Teacher Assignments</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchAssignments" placeholder="Search assignments...">
                        <span class="input-group-text"><i class="material-symbols-rounded" style="font-size: 18px;">search</i></span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($assignments)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="assignmentsTable">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Teacher</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Subject</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Class</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Session</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Assigned</th>
                                    <th class="border-0 fw-semibold text-center" style="background: var(--primary-color); color: white !important; padding: 12px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="teacher-avatar me-3">
                                                <?= strtoupper(substr($assignment['first_name'], 0, 1) . substr($assignment['last_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($assignment['first_name'] . ' ' . $assignment['last_name']) ?></h6>
                                                <small class="text-muted">Subject Teacher</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?= esc($assignment['subject_name']) ?></span>
                                            <small class="text-muted d-block"><?= esc($assignment['subject_code']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium"><?= esc($assignment['class_name']) ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-medium"><?= esc($assignment['session_name']) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?= date('M j, Y', strtotime($assignment['created_at'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/assignments/delete/' . $assignment['id']) ?>"
                                           class="btn btn-outline-danger btn-action" title="Remove Assignment"
                                           onclick="return confirm('Are you sure you want to remove this teacher assignment?')">
                                            <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">assignment_ind</i>
                        <h6 class="text-muted">No teacher assignments found</h6>
                        <p class="text-muted small">Start by assigning teachers to subjects and classes</p>
                        <a href="<?= base_url('admin/assignments/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create First Assignment
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchAssignments');
    const table = document.getElementById('assignmentsTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            row.style.display = matchesSearch ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
<?= $this->endSection() ?>
