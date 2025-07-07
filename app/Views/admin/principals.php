<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .principal-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .principal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .principal-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
    }
    .title-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
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
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Principal Management</h4>
                <p class="text-muted mb-0">Manage principal accounts and their titles</p>
            </div>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New Principal
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Principal Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1"><?= count($principals) ?></h3>
            <p class="mb-0">Total Principals</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1"><?= count(array_filter($principals, fn($p) => $p['is_active'] == 1)) ?></h3>
            <p class="mb-0">Active Principals</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1"><?= count(array_unique(array_column($principals, 'title'))) ?></h3>
            <p class="mb-0">Different Titles</p>
        </div>
    </div>
</div>

<!-- Principals List -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">school</i>
                    All Principals
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($principals)): ?>
                    <div class="row p-4">
                        <?php foreach ($principals as $principal): ?>
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card principal-card h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="principal-avatar me-3">
                                            <?= strtoupper(substr($principal['first_name'], 0, 1) . substr($principal['last_name'], 0, 1)) ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold"><?= esc($principal['first_name'] . ' ' . $principal['last_name']) ?></h6>
                                            <p class="text-muted mb-2 small"><?= esc($principal['email']) ?></p>
                                            <span class="title-badge">
                                                <?= esc($principal['title'] ?? 'Principal') ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Username:</span>
                                            <span class="fw-medium"><?= esc($principal['username']) ?></span>
                                        </div>
                                        <?php if (!empty($principal['phone'])): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Phone:</span>
                                            <span class="fw-medium"><?= esc($principal['phone']) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Status:</span>
                                            <span class="badge <?= $principal['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= $principal['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Joined:</span>
                                            <span class="fw-medium"><?= date('M j, Y', strtotime($principal['created_at'])) ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('admin/users/edit/' . $principal['id']) ?>" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">edit</i>
                                            Edit
                                        </a>
                                        <?php if ($principal['id'] != session()->get('user_id')): ?>
                                        <button type="button" 
                                                class="btn btn-outline-<?= $principal['is_active'] ? 'warning' : 'success' ?> btn-sm"
                                                onclick="togglePrincipalStatus(<?= $principal['id'] ?>, '<?= esc($principal['first_name'] . ' ' . $principal['last_name']) ?>', <?= $principal['is_active'] ? 'true' : 'false' ?>)">
                                            <i class="material-symbols-rounded" style="font-size: 16px;"><?= $principal['is_active'] ? 'pause' : 'play_arrow' ?></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">school</i>
                        <h6 class="text-muted">No principals found</h6>
                        <p class="text-muted small">Start by creating your first principal account</p>
                        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add First Principal
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
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

function togglePrincipalStatus(userId, userName, isActive) {
    const action = isActive ? 'deactivate' : 'activate';
    const confirmMessage = `Are you sure you want to ${action} ${userName}?`;
    
    if (confirm(confirmMessage)) {
        window.location.href = `<?= base_url('admin/users/toggle/') ?>${userId}`;
    }
}
</script>
<?= $this->endSection() ?>
