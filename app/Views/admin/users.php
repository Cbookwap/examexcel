<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .user-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .user-avatar {
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
    .role-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }
    .role-admin { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; }
    .role-teacher { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; }
    .role-student { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; }
    .role-principal { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; }
    .status-active { color: var(--primary-color); }
    .status-inactive { color: #6c757d; }
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
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">User Management</h4>
                <p class="text-muted mb-0">Manage different categories of user's role</p>
            </div>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New User
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- User Statistics -->
<div class="row mb-4">
    <?php
    $totalUsers = count($users);
    $adminCount = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
    $teacherCount = count(array_filter($users, fn($u) => $u['role'] === 'teacher'));
    $studentCount = count(array_filter($users, fn($u) => $u['role'] === 'student'));
    $activeCount = count(array_filter($users, fn($u) => $u['is_active'] == 1));
    ?>

    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $totalUsers ?></h3>
            <p class="mb-0">Total Users</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $activeCount ?></h3>
            <p class="mb-0">Active Users</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $teacherCount ?></h3>
            <p class="mb-0">Teachers</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $studentCount ?></h3>
            <p class="mb-0">Students</p>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">All Users</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="searchUsers" placeholder="Search users...">
                            <span class="input-group-text"><i class="material-symbols-rounded" style="font-size: 18px;">search</i></span>
                        </div>
                        <select class="form-select" id="filterRole" style="width: 150px;">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                            <option value="principal">Principal</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($users)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">User</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Role</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Contact</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Status</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Joined</th>
                                    <th class="border-0 fw-semibold text-center" style="background: var(--primary-color); color: white !important; padding: 12px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr data-role="<?= $user['role'] ?>" data-status="<?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3">
                                                <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h6>
                                                <small class="text-muted">
                                                    <?php if ($user['role'] === 'student' && !empty($user['student_id'])): ?>
                                                        ID: <?= esc($user['student_id']) ?>
                                                    <?php elseif ($user['role'] === 'teacher' && !empty($user['employee_id'])): ?>
                                                        ID: <?= esc($user['employee_id']) ?>
                                                    <?php else: ?>
                                                        <?= esc($user['username'] ?? 'N/A') ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge role-<?= $user['role'] ?>">
                                            <?php if ($user['role'] === 'principal' && !empty($user['title'])): ?>
                                                <?= esc($user['title']) ?>
                                            <?php else: ?>
                                                <?= ucfirst($user['role']) ?>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium"><?= esc($user['email']) ?></div>
                                            <?php if (!empty($user['phone'])): ?>
                                                <small class="text-muted"><?= esc($user['phone']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="material-symbols-rounded me-2 <?= $user['is_active'] ? 'status-active' : 'status-inactive' ?>" style="font-size: 12px;">circle</i>
                                            <span class="<?= $user['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                                <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>"
                                               class="btn btn-outline-primary btn-action" title="Edit User">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                                            </a>

                                            <?php if ($user['id'] != session()->get('user_id')): ?>
                                                <button type="button"
                                                        class="btn btn-outline-<?= $user['is_active'] ? 'warning' : 'success' ?> btn-action"
                                                        title="<?= $user['is_active'] ? 'Deactivate' : 'Activate' ?> User"
                                                        onclick="showToggleModal(<?= $user['id'] ?>, '<?= esc($user['first_name'] . ' ' . $user['last_name']) ?>', <?= $user['is_active'] ? 'true' : 'false' ?>)">
                                                    <i class="material-symbols-rounded" style="font-size: 18px;"><?= $user['is_active'] ? 'pause' : 'play_arrow' ?></i>
                                                </button>

                                                <button type="button"
                                                        class="btn btn-outline-danger btn-action"
                                                        title="Delete User"
                                                        onclick="showDeleteModal(<?= $user['id'] ?>, '<?= esc($user['first_name'] . ' ' . $user['last_name']) ?>')">
                                                    <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                                                </button>
                                            <?php else: ?>
                                                <span class="btn btn-outline-secondary btn-action disabled" title="Cannot modify own account">
                                                    <i class="material-symbols-rounded" style="font-size: 18px;">lock</i>
                                                </span>
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
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">group</i>
                        <h6 class="text-muted">No users found</h6>
                        <p class="text-muted small">Start by creating your first user</p>
                        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add First User
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
                <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete User
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
                <p>Are you sure you want to <span id="toggleAction"></span> the user <strong id="toggleUserName"></strong>?</p>
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
    const searchInput = document.getElementById('searchUsers');
    const roleFilter = document.getElementById('filterRole');
    const table = document.getElementById('usersTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const role = row.getAttribute('data-role');

            const matchesSearch = text.includes(searchTerm);
            const matchesRole = !selectedRole || role === selectedRole;

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);

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
let currentUserId = null;
let currentAction = null;

// Show delete modal
function showDeleteModal(userId, userName) {
    currentUserId = userId;
    currentAction = 'delete';
    document.getElementById('deleteUserName').textContent = userName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Show toggle status modal
function showToggleModal(userId, userName, isActive) {
    currentUserId = userId;
    currentAction = 'toggle';

    const action = isActive ? 'deactivate' : 'activate';
    const icon = isActive ? 'pause' : 'play_arrow';
    const buttonText = isActive ? 'Deactivate User' : 'Activate User';

    document.getElementById('toggleUserName').textContent = userName;
    document.getElementById('toggleAction').textContent = action;
    document.getElementById('toggleIcon').textContent = icon;
    document.getElementById('toggleButtonText').textContent = buttonText;

    const modal = new bootstrap.Modal(document.getElementById('toggleModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentUserId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = `<?= base_url('admin/users/delete/') ?>${currentUserId}`;
    }
});

// Handle toggle confirmation
document.getElementById('confirmToggle').addEventListener('click', function() {
    if (currentUserId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Redirect to toggle URL
        window.location.href = `<?= base_url('admin/users/toggle/') ?>${currentUserId}`;
    }
});
</script>
<?= $this->endSection() ?>
