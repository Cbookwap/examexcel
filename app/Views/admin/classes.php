<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .class-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .class-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .class-avatar {
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
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }
    .status-active { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; }
    .status-inactive { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
    .status-active-text { color: var(--primary-color); }
    .status-inactive-text { color: #6c757d; }
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
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: transparent;
    }
    .btn-outline-primary:hover {
        color: white;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-outline-warning {
        color: #ff9800;
        border-color: #ff9800;
        background-color: transparent;
    }
    .btn-outline-warning:hover {
        color: white;
        background-color: #ff9800;
        border-color: #ff9800;
    }
    .btn-outline-success {
        color: #4caf50;
        border-color: #4caf50;
        background-color: transparent;
    }
    .btn-outline-success:hover {
        color: white;
        background-color: #4caf50;
        border-color: #4caf50;
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
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
        background-color: transparent;
    }
    .btn-group {
        display: inline-flex;
        gap: 0.25rem;
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
        box-shadow: 0 8px 25px rgba(160, 90, 255, 0.3);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Class Management</h4>
                <p class="text-muted mb-0">Manage academic classes and their class teacher credentials</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/classes/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New Class
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Class Teacher Info Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-radius: 12px;">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="material-symbols-rounded text-primary" style="font-size: 32px;">info</i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-semibold text-primary">Class Teacher Management</h6>
                    <p class="mb-0 text-muted">
                        Each class automatically gets a class teacher account. Use the
                        <i class="material-symbols-rounded mx-1" style="font-size: 16px;">person</i>
                        button in the Actions column to customize usernames and passwords for class teachers.
                    </p>
                </div>
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

<!-- Class Statistics -->
<div class="row mb-4">
    <?php
    $totalClasses = count($classes);
    $activeClasses = count(array_filter($classes, fn($c) => $c['is_active'] == 1));
    $totalStudents = array_sum(array_column($classes, 'max_students'));
    ?>

    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $totalClasses ?></h3>
            <p class="mb-0">Total Classes</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $activeClasses ?></h3>
            <p class="mb-0">Active Classes</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $totalStudents ?></h3>
            <p class="mb-0">Total Capacity</p>
        </div>
    </div>
</div>

<!-- Classes Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">All Classes</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="searchClasses" placeholder="Search classes...">
                            <span class="input-group-text"><i class="material-symbols-rounded" style="font-size: 18px;">search</i></span>
                        </div>
                        <select class="form-select" id="filterStatus" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($classes)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="classesTable">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Class</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Students</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Status</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Created</th>
                                    <th class="border-0 fw-semibold text-center" style="background: var(--primary-color); color: white !important; padding: 12px;">Actions & Class Teacher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                <tr data-status="<?= $class['is_active'] ? 'active' : 'inactive' ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="class-avatar me-3">
                                                <?= strtoupper(substr($class['name'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($class['name']) ?></h6>
                                                <small class="text-muted">
                                                    <?= esc($class['section']) ?> • <?= esc($class['academic_year']) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium"><?= $class['student_count'] ?></span>
                                        <small class="text-muted d-block">Enrolled students</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="material-symbols-rounded me-2 <?= $class['is_active'] ? 'status-active-text' : 'status-inactive-text' ?>" style="font-size: 12px;">circle</i>
                                            <span class="<?= $class['is_active'] ? 'status-active-text' : 'status-inactive-text' ?>">
                                                <?= $class['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?= date('M j, Y', strtotime($class['created_at'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/classes/edit/' . $class['id']) ?>"
                                               class="btn btn-outline-primary btn-action" title="Edit Class">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                                            </a>
                                            <a href="<?= base_url('admin/classes/manage-teacher/' . $class['id']) ?>"
                                               class="btn btn-outline-info btn-action" title="Manage Class Teacher">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">person</i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-<?= $class['is_active'] ? 'warning' : 'success' ?> btn-action"
                                                    title="<?= $class['is_active'] ? 'Deactivate' : 'Activate' ?> Class"
                                                    onclick="showToggleModal(<?= $class['id'] ?>, '<?= esc($class['name'] . ' ' . $class['section']) ?>', <?= $class['is_active'] ? 'true' : 'false' ?>)">
                                                <i class="material-symbols-rounded" style="font-size: 18px;"><?= $class['is_active'] ? 'pause' : 'play_arrow' ?></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-action"
                                                    title="Delete Class"
                                                    onclick="showDeleteModal(<?= $class['id'] ?>, '<?= esc($class['name'] . ' ' . $class['section']) ?>')">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
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
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">school</i>
                        <h6 class="text-muted">No classes found</h6>
                        <p class="text-muted small">Start by creating your first class</p>
                        <a href="<?= base_url('admin/classes/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add First Class
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">warning</i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the class <strong id="deleteClassName"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Class
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Confirmation Modal -->
<div class="modal fade" id="toggleModal" tabindex="-1" aria-labelledby="toggleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span id="toggleAction"></span> the class <strong id="toggleClassName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmToggle">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;" id="toggleIcon"></i>
                    <span id="toggleButtonText"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchClasses');
    const statusFilter = document.getElementById('filterStatus');
    const table = document.getElementById('classesTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Modal variables
let currentClassId = null;
let currentAction = null;

// Show delete modal
function showDeleteModal(classId, className) {
    currentClassId = classId;
    currentAction = 'delete';
    document.getElementById('deleteClassName').textContent = className;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Show toggle status modal
function showToggleModal(classId, className, isActive) {
    currentClassId = classId;
    currentAction = 'toggle';

    const action = isActive ? 'deactivate' : 'activate';
    const icon = isActive ? 'pause' : 'play_arrow';
    const buttonText = isActive ? 'Deactivate Class' : 'Activate Class';

    document.getElementById('toggleClassName').textContent = className;
    document.getElementById('toggleAction').textContent = action;
    document.getElementById('toggleIcon').textContent = icon;
    document.getElementById('toggleButtonText').textContent = buttonText;

    const modal = new bootstrap.Modal(document.getElementById('toggleModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentClassId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = `<?= base_url('admin/classes/delete/') ?>${currentClassId}`;
    }
});

// Handle toggle confirmation
document.getElementById('confirmToggle').addEventListener('click', function() {
    if (currentClassId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Redirect to toggle URL
        window.location.href = `<?= base_url('admin/classes/toggle/') ?>${currentClassId}`;
    }
});
</script>
<?= $this->endSection() ?>
