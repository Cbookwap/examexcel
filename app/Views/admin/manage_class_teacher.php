<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .class-info-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
    }
    .credentials-card {
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        transition: transform 0.2s ease-in-out;
    }
    .credentials-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }
    .btn-generate {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: white;
    }
    .btn-generate:hover {
        background: linear-gradient(135deg, #218838, #1ea080);
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="page-content-wrapper">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold"><?= $pageTitle ?></h4>
                    <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
                </div>
                <div>
                    <a href="<?= base_url('admin/classes') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Classes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card class-info-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2 fw-bold"><?= esc($class['name']) ?></h5>
                            <p class="mb-1 opacity-75">Academic Year: <?= esc($class['academic_year']) ?></p>
                            <?php if ($class['section']): ?>
                                <p class="mb-1 opacity-75">Section: <?= esc($class['section']) ?></p>
                            <?php endif; ?>
                            <p class="mb-0 opacity-75">Max Students: <?= $class['max_students'] ?></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column align-items-md-end">
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        Class ID: <?= $class['id'] ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="badge <?= $class['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $class['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Teacher Credentials -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card credentials-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user-tie me-2"></i>
                        Class Teacher Login Credentials
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($class['class_teacher']) && $class['class_teacher']): ?>
                        <!-- Current Credentials Display -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Current Class Teacher Account:</strong><br>
                                    <span class="text-light">Username: </span><strong><?= esc($class['class_teacher']['username']) ?></strong><br>
                                    <span class="text-light">Status: </span>
                                    <span class="badge <?= $class['class_teacher']['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $class['class_teacher']['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- No Class Teacher Account -->
                        <div class="alert alert-warning mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>No Class Teacher Account Found</strong><br>
                                    <span class="text-muted">A class teacher account will be created automatically when you save the credentials below.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Credentials Form -->
                    <form method="POST" action="<?= base_url('admin/classes/manage-teacher/' . $class['id']) ?>">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-semibold">
                                    <i class="fas fa-user me-1"></i>Username
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           value="<?= isset($class['class_teacher']) ? esc($class['class_teacher']['username']) : '' ?>" 
                                           placeholder="e.g., JSS-ONE, SS-TWO" 
                                           required>
                                    <button type="button" class="btn btn-generate" onclick="generateUsername()">
                                        <i class="fas fa-magic"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Tip: Use format like JSS-ONE, JSS-TWO, SS-ONE, etc.
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-1"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Leave empty to keep current password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <?= isset($class['class_teacher']) ? 'Leave empty to keep current password' : 'Default password will be "class123" if left empty' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Class teacher will have access to class marksheet and student results only.
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            <?= isset($class['class_teacher']) ? 'Update Credentials' : 'Create Class Teacher' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Instructions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-question-circle me-2"></i>
                        How to Use Class Teacher Account
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Login Process
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Use the username and password set above
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Login at the same URL as other users
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Will be redirected to class teacher dashboard
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-primary">
                                <i class="fas fa-features me-1"></i>Available Features
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-table text-info me-2"></i>
                                    View class marksheet with all student results
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-chart-bar text-info me-2"></i>
                                    Class performance statistics and analytics
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-print text-info me-2"></i>
                                    Print and export marksheet reports
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateUsername() {
    const className = "<?= esc($class['name']) ?>";
    let username = className.toUpperCase();
    
    // Replace spaces with hyphens
    username = username.replace(/\s+/g, '-');
    
    // Remove special characters except hyphens
    username = username.replace(/[^A-Z0-9\-]/g, '');
    
    // Convert numbers to words
    const numberWords = {
        '1': 'ONE', '2': 'TWO', '3': 'THREE', '4': 'FOUR', '5': 'FIVE',
        '6': 'SIX', '7': 'SEVEN', '8': 'EIGHT', '9': 'NINE', '10': 'TEN',
        '11': 'ELEVEN', '12': 'TWELVE'
    };
    
    for (const [number, word] of Object.entries(numberWords)) {
        username = username.replace(new RegExp(number, 'g'), word);
    }
    
    document.getElementById('username').value = username;
}

function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Auto-generate username on page load if no class teacher exists
<?php if (!isset($class['class_teacher']) || !$class['class_teacher']): ?>
document.addEventListener('DOMContentLoaded', function() {
    generateUsername();
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>
