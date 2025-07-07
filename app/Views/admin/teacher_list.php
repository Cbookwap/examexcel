<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.stats-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border: none;
    border-radius: 15px;
    color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.stats-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.filter-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    overflow: hidden;
}

.table thead th {
    background: var(--primary-color) !important;
    color: white !important;
    border: none;
    font-weight: 600;
    padding: 1rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    transform: scale(1.01);
}

.teacher-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-inactive {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.gender-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.gender-male {
    background: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

.gender-female {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.gender-other {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.department-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    background: rgba(var(--primary-color), 0.1);
    color: var(--primary-color);
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
.btn-filter {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    color: white;
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    color: white;
}

.btn-clear {
    background: #6c757d;
    border: none;
    color: white;
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-clear:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.pagination {
    justify-content: center;
    margin-top: 2rem;
}

.page-link {
    border: none;
    color: var(--primary-color);
    padding: 0.75rem 1rem;
    margin: 0 0.25rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .teacher-avatar {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
}
</style>

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

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Teacher List</h4>
                <p class="text-muted mb-0">View and manage all teachers in the system</p>
            </div>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New Teacher
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Teachers</h6>
                    <h3 class="mb-0 text-white"><?= number_format($stats['total']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">person</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Active Teachers</h6>
                    <h3 class="mb-0 text-white"><?= number_format($stats['active']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">person_check</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Male Teachers</h6>
                    <h3 class="mb-0 text-white"><?= number_format($stats['male']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">man</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Female Teachers</h6>
                    <h3 class="mb-0 text-white"><?= number_format($stats['female']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">woman</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= base_url('admin/teachers') ?>">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Search Teachers</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="<?= esc($filters['search']) ?>"
                           placeholder="Search by Employee ID or Name...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="department" class="form-label">Filter by Department</label>
                    <select class="form-select" id="department" name="department">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= esc($dept) ?>" <?= $filters['department'] == $dept ? 'selected' : '' ?>>
                                <?= esc($dept) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="gender" class="form-label">Filter by Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="">All Genders</option>
                        <option value="male" <?= $filters['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $filters['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $filters['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-filter">
                            <i class="material-symbols-rounded me-1">search</i>Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="<?= base_url('admin/teachers') ?>" class="btn btn-clear btn-sm">
                        <i class="material-symbols-rounded me-1">clear</i>Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Teachers Table -->
<div class="card table-card">
    <div class="card-body p-0">
        <?php if (!empty($teachers)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="teachersTable">
                    <thead>
                        <tr>
                            <th class="border-0 fw-semibold">Teacher</th>
                            <th class="border-0 fw-semibold">Employee ID</th>
                            <th class="border-0 fw-semibold">Department</th>
                            <th class="border-0 fw-semibold">Qualification</th>
                            <th class="border-0 fw-semibold">Gender</th>
                            <th class="border-0 fw-semibold">Status</th>
                            <th class="border-0 fw-semibold">Joined</th>
                            <th class="border-0 fw-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="teacher-avatar me-3">
                                        <?= strtoupper(substr($teacher['first_name'], 0, 1) . substr($teacher['last_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?></h6>
                                        <small class="text-muted"><?= esc($teacher['email']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary"><?= esc($teacher['employee_id'] ?? 'Not Assigned') ?></span>
                            </td>
                            <td>
                                <?php if ($teacher['department']): ?>
                                    <span class="badge department-badge"><?= esc($teacher['department']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= esc($teacher['qualification'] ?? 'Not specified') ?></small>
                            </td>
                            <td>
                                <?php if ($teacher['gender']): ?>
                                    <span class="badge gender-badge gender-<?= $teacher['gender'] ?>">
                                        <?= ucfirst($teacher['gender']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge status-badge status-<?= $teacher['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $teacher['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($teacher['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= base_url('admin/users/edit/' . $teacher['id']) ?>"
                                       class="btn btn-outline-primary btn-sm" title="Edit Teacher">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">edit</i>
                                    </a>
                                    <button type="button" class="btn btn-outline-info btn-sm"
                                            onclick="viewTeacher(<?= $teacher['id'] ?>)" title="View Details">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">visibility</i>
                                    </button>
                                    <button type="button" class="btn btn-outline-<?= $teacher['is_active'] ? 'warning' : 'success' ?> btn-sm"
                                            onclick="toggleTeacherStatus(<?= $teacher['id'] ?>)"
                                            title="<?= $teacher['is_active'] ? 'Deactivate' : 'Activate' ?> Teacher">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">
                                            <?= $teacher['is_active'] ? 'person_off' : 'person_check' ?>
                                        </i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pager): ?>
            <div class="card-footer bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing <?= count($teachers) ?> of <?= $pager->getTotal() ?> teachers
                    </div>
                    <div>
                        <?= $pager->links() ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <i class="material-symbols-rounded text-muted">person</i>
                <h5>No Teachers Found</h5>
                <?php if (!empty($filters['search']) || !empty($filters['department']) || !empty($filters['gender'])): ?>
                    <p>No teachers match your current filters. Try adjusting your search criteria.</p>
                    <a href="<?= base_url('admin/teachers') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2">clear</i>Clear Filters
                    </a>
                <?php else: ?>
                    <p>No teachers have been added to the system yet.</p>
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2">add</i>Add First Teacher
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

function viewTeacher(teacherId) {
    // Redirect to teacher profile/details page
    window.location.href = `<?= base_url('admin/users/edit/') ?>${teacherId}`;
}

function toggleTeacherStatus(teacherId) {
    if (confirm('Are you sure you want to change this teacher\'s status?')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="material-symbols-rounded" style="font-size: 16px;">hourglass_empty</i>';
        button.disabled = true;

        // Make AJAX request to toggle status
        fetch(`<?= base_url('admin/users/toggle/') ?>${teacherId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification(data.message, 'success');

                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Show error message
                showNotification(data.message || 'Failed to update teacher status', 'error');

                // Restore button
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating teacher status', 'error');

            // Restore button
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="material-symbols-rounded me-2">${type === 'success' ? 'check_circle' : 'error'}</i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Enhanced search functionality
document.getElementById('search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#teachersTable tbody tr');

    tableRows.forEach(row => {
        const teacherName = row.querySelector('h6').textContent.toLowerCase();
        const teacherEmail = row.querySelector('small').textContent.toLowerCase();
        const employeeId = row.querySelector('.text-primary').textContent.toLowerCase();

        if (teacherName.includes(searchTerm) ||
            teacherEmail.includes(searchTerm) ||
            employeeId.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
<?= $this->endSection() ?>
