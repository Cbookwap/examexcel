<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

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

.class-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    overflow: hidden;
    transition: all 0.3s ease;
}

.class-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
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
    background-color: rgba(var(--primary-color-rgb), 0.05);
    transform: scale(1.01);
}

.class-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
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

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .class-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
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
                <h4 class="fw-bold mb-1" style="color: white;">Class Management</h4>
                <p class="text-light mb-0">View and manage academic classes</p>
            </div>
            <a href="<?= base_url('principal/classes/create') ?>" class="btn btn-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New Class
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Classes</h6>
                    <h3 class="mb-0"><?= number_format($stats['total']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">school</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Active Classes</h6>
                    <h3 class="mb-0"><?= number_format($stats['active']) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">check_circle</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Students</h6>
                    <h3 class="mb-0"><?= number_format(array_sum(array_column($classes, 'student_count'))) ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">groups</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Classes Table -->
<div class="card table-card">
    <div class="card-body p-0">
        <?php if (!empty($classes)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="classesTable">
                    <thead>
                        <tr>
                            <th class="border-0 fw-semibold">Class</th>
                            <th class="border-0 fw-semibold">Students</th>
                            <th class="border-0 fw-semibold">Status</th>
                            <th class="border-0 fw-semibold">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classes as $class): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="class-avatar me-3">
                                        <?= strtoupper(substr($class['name'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?= esc($class['name']) ?></h6>
                                        <small class="text-muted">
                                            <?= esc($class['section'] ?? 'No Section') ?> • <?= esc($class['academic_year'] ?? 'Current Year') ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary"><?= number_format($class['student_count']) ?></span>
                                <small class="text-muted d-block">students enrolled</small>
                            </td>
                            <td>
                                <span class="badge status-badge status-<?= $class['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $class['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($class['created_at'])) ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <i class="material-symbols-rounded text-muted">school</i>
                <h5>No Classes Found</h5>
                <p>No classes have been created yet.</p>
                <a href="<?= base_url('principal/classes/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2">add</i>Create First Class
                </a>
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

    // Enhanced table interactions
    const tableRows = document.querySelectorAll('#classesTable tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });

        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
<?= $this->endSection() ?>
