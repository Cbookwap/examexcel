<?php
// Determine layout based on user role
$layout = 'layouts/dashboard'; // Default layout
if (isset($user['role'])) {
    switch ($user['role']) {
        case 'principal':
            $layout = 'layouts/principal';
            break;
        case 'teacher':
            $layout = 'layouts/teacher';
            break;
        case 'student':
            $layout = 'layouts/student';
            break;
        case 'admin':
        default:
            $layout = 'layouts/dashboard';
            break;
    }
}
?>
<?= $this->extend($layout) ?>

<?= $this->section('css') ?>
<style>
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        margin: 0 auto 1rem;
        box-shadow: 0 10px 30px rgba(160, 90, 255, 0.3);
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(160, 90, 255, 0.4);
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        transform: translateY(-1px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">My Profile</h4>
                <p class="text-muted mb-0">Manage your account information and settings</p>
            </div>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="row">
    <!-- Profile Information Card -->
    <div class="col-lg-4 mb-4">
        <div class="card profile-card">
            <div class="card-body text-center">
                <!-- Profile Avatar -->
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>

                <!-- User Info -->
                <h5 class="mb-1"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                <p class="text-muted mb-2"><?= ucfirst(esc($user['role'])) ?></p>

                <?php if ($user['role'] === 'student' && !empty($user['student_id'])): ?>
                    <p class="text-info mb-2">
                        <i class="fas fa-id-card me-1"></i>
                        <?= esc($user['student_id']) ?>
                    </p>
                <?php endif; ?>

                <?php if ($user['role'] === 'student' && !empty($classInfo)): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-school me-1"></i>
                        <?= esc($classInfo['name']) ?><?= !empty($classInfo['section']) ? ' - Section ' . esc($classInfo['section']) : '' ?>
                    </p>
                <?php endif; ?>

                <?php if ($user['role'] === 'teacher' && !empty($user['employee_id'])): ?>
                    <p class="text-info mb-2">
                        <i class="fas fa-id-badge me-1"></i>
                        <?= esc($user['employee_id']) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($user['email'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-1"></i>
                        <?= esc($user['email']) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($user['phone'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-1"></i>
                        <?= esc($user['phone']) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($user['department'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-building me-1"></i>
                        <?= esc($user['department']) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($user['qualification'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-graduation-cap me-1"></i>
                        <?= esc($user['qualification']) ?>
                    </p>
                <?php endif; ?>

                <!-- Account Status -->
                <div class="mt-3">
                    <?php if ($user['is_active']): ?>
                        <span class="badge bg-success">Active Account</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactive Account</span>
                    <?php endif; ?>

                    <?php if ($user['is_verified']): ?>
                        <span class="badge bg-primary">Verified</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Pending Verification</span>
                    <?php endif; ?>
                </div>

                <!-- Last Login -->
                <?php if (!empty($user['last_login'])): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Last login: <?= date('M j, Y g:i A', strtotime($user['last_login'])) ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="card profile-card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Account Information</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h6 class="text-primary mb-0"><?= date('M Y', strtotime($user['created_at'])) ?></h6>
                            <small class="text-muted">Member Since</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success mb-0"><?= ucfirst($user['role']) ?></h6>
                        <small class="text-muted">Account Type</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <div class="col-lg-8">
        <div class="card profile-card">
            <div class="card-header">
                <h6 class="mb-0">Edit Profile Information</h6>
            </div>
            <div class="card-body">
                <!-- Display Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <?= form_open('auth/profile', ['class' => 'needs-validation', 'novalidate' => true]) ?>

                    <!-- Personal Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="<?= old('first_name', $user['first_name']) ?>" required>
                            <?php if ($validation->hasError('first_name')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('first_name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?= old('last_name', $user['last_name']) ?>" required>
                            <?php if ($validation->hasError('last_name')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('last_name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?= old('phone', $user['phone']) ?>">
                            <?php if ($validation->hasError('phone')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('phone') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= esc($user['email']) ?>" readonly>
                            <small class="text-muted">Email cannot be changed. Contact administrator if needed.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                   value="<?= old('date_of_birth', $user['date_of_birth']) ?>">
                            <?php if ($validation->hasError('date_of_birth')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('date_of_birth') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" <?= old('gender', $user['gender']) === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= old('gender', $user['gender']) === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= old('gender', $user['gender']) === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if ($validation->hasError('gender')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('gender') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($user['role'] === 'teacher'): ?>
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department"
                                   value="<?= old('department', $user['department']) ?>">
                            <?php if ($validation->hasError('department')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('department') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="qualification" class="form-label">Qualification</label>
                            <input type="text" class="form-control" id="qualification" name="qualification"
                                   value="<?= old('qualification', $user['qualification']) ?>"
                                   placeholder="e.g., B.Ed, M.Sc, Ph.D">
                            <?php if ($validation->hasError('qualification')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('qualification') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $user['address']) ?></textarea>
                            <?php if ($validation->hasError('address')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('address') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-lock me-2"></i>Security Settings
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                            <?php if ($validation->hasError('password')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            <?php if ($validation->hasError('confirm_password')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('confirm_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (password && confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?= $this->endSection() ?>
