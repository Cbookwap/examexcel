<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="page-content-wrapper">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">Database Check - Class Teachers</h4>
                    <p class="text-muted mb-0">Raw database query results to verify class teacher accounts</p>
                </div>
                <div>
                    <a href="<?= base_url('admin/classes/test-login') ?>" class="btn btn-warning me-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Test Login
                    </a>
                    <a href="<?= base_url('admin/classes/create-test-teacher') ?>" class="btn btn-success me-2">
                        <i class="fas fa-plus me-2"></i>Create Test Teacher
                    </a>
                    <a href="<?= base_url('admin/classes/debug-teachers') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Debug
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Class Teachers in Database -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2"></i>
                        Class Teachers in Database (<?= count($classTeachers) ?> found)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($classTeachers)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Password Hash</th>
                                        <th>Class ID</th>
                                        <th>Active</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($classTeachers as $teacher): ?>
                                        <tr>
                                            <td><?= $teacher['id'] ?></td>
                                            <td><strong><?= esc($teacher['username']) ?></strong></td>
                                            <td><?= esc($teacher['email']) ?></td>
                                            <td>
                                                <small class="text-muted font-monospace">
                                                    <?= substr($teacher['password'], 0, 20) ?>...
                                                </small>
                                                <br>
                                                <small class="text-info">
                                                    <?php if (password_verify('class123', $teacher['password'])): ?>
                                                        ✓ Password is "class123"
                                                    <?php elseif (password_verify('test123', $teacher['password'])): ?>
                                                        ✓ Password is "test123"
                                                    <?php else: ?>
                                                        ❌ Unknown password
                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                            <td><?= $teacher['class_id'] ?></td>
                                            <td>
                                                <span class="badge <?= $teacher['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $teacher['is_active'] ? 'Yes' : 'No' ?>
                                                </span>
                                            </td>
                                            <td><?= $teacher['created_at'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-warning mb-3">No Class Teacher Accounts Found in Database!</h5>
                            <p class="text-muted">This confirms that class teacher accounts are not being created automatically.</p>
                            <a href="<?= base_url('admin/classes/create-test-teacher') ?>" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Create Test Class Teacher
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- All Classes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-school me-2"></i>
                        All Classes (<?= count($classes) ?> found)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Academic Year</th>
                                    <th>Active</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                    <tr>
                                        <td><?= $class['id'] ?></td>
                                        <td><strong><?= esc($class['name']) ?></strong></td>
                                        <td><?= esc($class['section']) ?></td>
                                        <td><?= esc($class['academic_year']) ?></td>
                                        <td>
                                            <span class="badge <?= $class['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $class['is_active'] ? 'Yes' : 'No' ?>
                                            </span>
                                        </td>
                                        <td><?= $class['created_at'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Users Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        All Users Summary (<?= count($allUsers) ?> total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Class ID</th>
                                    <th>Active</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $user): ?>
                                    <tr class="<?= $user['role'] === 'class_teacher' ? 'table-warning' : '' ?>">
                                        <td><?= $user['id'] ?></td>
                                        <td>
                                            <strong><?= esc($user['username']) ?></strong>
                                            <?php if ($user['role'] === 'class_teacher'): ?>
                                                <span class="badge bg-warning text-dark ms-2">CLASS TEACHER</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'primary' : ($user['role'] === 'class_teacher' ? 'warning' : 'success')) ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td><?= $user['class_id'] ?: '-' ?></td>
                                        <td>
                                            <span class="badge <?= $user['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $user['is_active'] ? 'Yes' : 'No' ?>
                                            </span>
                                        </td>
                                        <td><?= $user['created_at'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analysis -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    Analysis
                </h6>
                <ul class="mb-0">
                    <li><strong>Classes found:</strong> <?= count($classes) ?></li>
                    <li><strong>Class teachers found:</strong> <?= count($classTeachers) ?></li>
                    <li><strong>Missing class teachers:</strong> <?= count($classes) - count($classTeachers) ?></li>
                    <li><strong>Total users:</strong> <?= count($allUsers) ?></li>
                    <?php if (count($classTeachers) === 0): ?>
                        <li class="text-danger"><strong>Issue:</strong> No class teacher accounts exist in database - automatic creation is not working</li>
                    <?php else: ?>
                        <li class="text-success"><strong>Good:</strong> Class teacher accounts found in database</li>
                        <li class="text-info"><strong>Test URLs:</strong>
                            <a href="<?= base_url('admin/classes/test-login') ?>" class="text-decoration-none">Test Login</a> |
                            <a href="<?= base_url('class-teacher/debug') ?>" class="text-decoration-none">Debug Dashboard</a> |
                            <a href="<?= base_url('class-teacher/test-dashboard') ?>" class="text-decoration-none">Test Dashboard (No Filter)</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
