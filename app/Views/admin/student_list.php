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

/* More specific selector to override Material Dashboard defaults */
.table-card .table thead th,
#studentsTable thead th {
    background: var(--primary-color) !important;
    background-color: var(--primary-color) !important;
    color: white !important;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(var(--primary-color-rgb), 0.05);
    transform: scale(1.01);
}

.student-avatar {
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
    
    .student-avatar {
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
                <h4 class="fw-bold mb-1">Student List</h4>
                <p class="text-muted mb-0">View and manage all students in the system</p>
            </div>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New Student
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
                    <h6 class="card-title mb-1">Total Students</h6>
                    <h3 class="mb-0 text-white"><?= number_format($stats['total']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">school</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Active Students</h6>
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
                    <h6 class="card-title mb-1">Male Students</h6>
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
                    <h6 class="card-title mb-1">Female Students</h6>
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
        <form method="GET" action="<?= base_url('admin/students') ?>">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Search Students</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= esc($filters['search']) ?>" 
                           placeholder="Search by Student ID or Name...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="class" class="form-label">Filter by Class</label>
                    <select class="form-select" id="class" name="class">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $filters['class'] == $class['id'] ? 'selected' : '' ?>>
                                <?= esc($class['display_name']) ?>
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
                    <a href="<?= base_url('admin/students') ?>" class="btn btn-clear btn-sm">
                        <i class="material-symbols-rounded me-1">clear</i>Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Students Table -->
<div class="card table-card">
    <div class="card-body p-0">
        <?php if (!empty($students)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="studentsTable">
                    <thead>
                        <tr>
                            <th class="border-0 fw-semibold">Student</th>
                            <th class="border-0 fw-semibold">Exam ID</th>
                            <th class="border-0 fw-semibold">Class</th>
                            <th class="border-0 fw-semibold">Gender</th>
                            <th class="border-0 fw-semibold">Status</th>
                            <th class="border-0 fw-semibold">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="student-avatar me-3">
                                        <?= strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h6>
                                        <small class="text-muted"><?= esc($student['email']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary"><?= esc($student['student_id'] ?? 'Not Assigned') ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= esc($student['class_name'] ?? 'No Class') ?></span>
                            </td>
                            <td>
                                <?php if ($student['gender']): ?>
                                    <span class="badge gender-badge gender-<?= $student['gender'] ?>">
                                        <?= ucfirst($student['gender']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge status-badge status-<?= $student['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($student['created_at'])) ?>
                                </small>
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
                        Showing <?= count($students) ?> of <?= $pager->getTotal() ?> students
                    </div>
                    <div>
                        <?= $pager->links() ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <i class="material-symbols-rounded text-muted">school</i>
                <h5>No Students Found</h5>
                <?php if (!empty($filters['search']) || !empty($filters['class']) || !empty($filters['gender'])): ?>
                    <p>No students match your current filters. Try adjusting your search criteria.</p>
                    <a href="<?= base_url('admin/students') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2">clear</i>Clear Filters
                    </a>
                <?php else: ?>
                    <p>No students have been added to the system yet.</p>
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2">add</i>Add First Student
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
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Add animation to stats cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    // Observe stats cards
    document.querySelectorAll('.stats-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Real-time search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit form after 500ms of no typing
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }

    // Enhanced table interactions
    const tableRows = document.querySelectorAll('#studentsTable tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });

        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Export functionality (future enhancement)
function exportStudents() {
    // This can be implemented later for CSV/Excel export
    alert('Export functionality will be available soon!');
}

// Print functionality
function printStudentList() {
    window.print();
}
</script>
<?= $this->endSection() ?>
