<?= $this->extend('layouts/dashboard') ?>

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
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
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
                    <i class="material-symbols-rounded">account_circle</i>
                </div>

                <!-- User Info -->
                <h5 class="mb-1"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h5>
                <p class="text-muted mb-2">Student</p>

                <?php if (!empty($student['student_id'])): ?>
                    <p class="text-info mb-2">
                        <i class="material-symbols-rounded me-1">badge</i>
                        <?= esc($student['student_id']) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($classInfo)): ?>
                    <p class="text-muted mb-2">
                        <i class="material-symbols-rounded me-1">school</i>
                        <?= esc($classInfo['name']) ?><?= !empty($classInfo['section']) ? ' - Section ' . esc($classInfo['section']) : '' ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($student['email'])): ?>
                    <p class="text-muted mb-2">
                        <i class="material-symbols-rounded me-1">mail</i>
                        <?= esc($student['email']) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($student['phone'])): ?>
                    <p class="text-muted mb-2">
                        <i class="material-symbols-rounded me-1">phone</i>
                        <?= esc($student['phone']) ?>
                    </p>
                <?php endif; ?>

                <!-- Account Status -->
                <div class="mt-3">
                    <?php if ($student['is_active']): ?>
                        <span class="badge bg-success">Active Account</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactive Account</span>
                    <?php endif; ?>

                    <?php if ($student['is_verified']): ?>
                        <span class="badge bg-primary">Verified</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Pending Verification</span>
                    <?php endif; ?>
                </div>

                <!-- Last Login -->
                <?php if (!empty($student['last_login'])): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">schedule</i>
                            Last login: <?= date('M j, Y g:i A', strtotime($student['last_login'])) ?>
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
                            <h6 class="text-primary mb-0"><?= date('M Y', strtotime($student['created_at'])) ?></h6>
                            <small class="text-muted">Member Since</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success mb-0">Student</h6>
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

                <!-- Profile Form -->
                <?= form_open('/student/profile', ['class' => 'needs-validation', 'novalidate' => true]) ?>

                    <!-- Personal Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="material-symbols-rounded me-2">person</i>Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="<?= old('first_name', $student['first_name']) ?>" required>
                            <?php if ($validation->hasError('first_name')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('first_name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?= old('last_name', $student['last_name']) ?>" required>
                            <?php if ($validation->hasError('last_name')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('last_name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?= old('phone', $student['phone']) ?>">
                            <?php if ($validation->hasError('phone')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('phone') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= esc($student['email']) ?>" readonly>
                            <small class="text-muted">Email cannot be changed. Contact administrator if needed.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id"
                                   value="<?= esc($student['student_id']) ?>" readonly>
                            <small class="text-muted">Student ID cannot be changed. Contact administrator if needed.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="class_name" class="form-label">Class</label>
                            <input type="text" class="form-control" id="class_name" name="class_name"
                                   value="<?= esc($student['class_name']) ?><?= !empty($student['class_section']) ? ' - Section ' . esc($student['class_section']) : '' ?>" readonly>
                            <small class="text-muted">Class assignment cannot be changed. Contact administrator if needed.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                   value="<?= old('date_of_birth', $student['date_of_birth']) ?>">
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
                                <option value="male" <?= old('gender', $student['gender']) === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= old('gender', $student['gender']) === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= old('gender', $student['gender']) === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if ($validation->hasError('gender')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('gender') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $student['address']) ?></textarea>
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
                                <i class="material-symbols-rounded me-2">lock</i>Security Settings
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
                                <a href="/student/dashboard" class="btn btn-outline-secondary">
                                    <i class="material-symbols-rounded me-2">arrow_back</i>Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">save</i>Update Profile
                                </button>
                            </div>
                        </div>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
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

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
<?= $this->endSection() ?>
