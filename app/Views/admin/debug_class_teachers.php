<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="page-content-wrapper">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">Debug Class Teachers</h4>
                    <p class="text-muted mb-0">View all class teacher accounts and their details</p>
                </div>
                <div>
                    <a href="<?= base_url('admin/classes/check-database') ?>" class="btn btn-info me-2">
                        <i class="fas fa-database me-2"></i>Check Database
                    </a>
                    <a href="<?= base_url('admin/classes/fix-teachers') ?>" class="btn btn-warning me-2">
                        <i class="fas fa-tools me-2"></i>Fix Missing Class Teachers
                    </a>
                    <a href="<?= base_url('admin/classes') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Classes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Teachers Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Class Teacher Accounts (<?= count($classTeachers) ?>)</h5>
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
                                        <th>Class ID</th>
                                        <th>Class Name</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($classTeachers as $teacher): ?>
                                        <?php 
                                        $class = null;
                                        foreach ($classes as $c) {
                                            if ($c['id'] == $teacher['class_id']) {
                                                $class = $c;
                                                break;
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $teacher['id'] ?></td>
                                            <td>
                                                <strong><?= esc($teacher['username']) ?></strong>
                                                <br>
                                                <small class="text-muted">Password: class123 (default)</small>
                                            </td>
                                            <td><?= esc($teacher['email']) ?></td>
                                            <td><?= $teacher['class_id'] ?></td>
                                            <td>
                                                <?php if ($class): ?>
                                                    <?= esc($class['name']) ?>
                                                    <?php if ($class['section']): ?>
                                                        <small class="text-muted">(<?= esc($class['section']) ?>)</small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-danger">Class Not Found</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?= $teacher['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $teacher['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td><?= date('M j, Y', strtotime($teacher['created_at'])) ?></td>
                                            <td>
                                                <?php if ($class): ?>
                                                    <a href="<?= base_url('admin/classes/manage-teacher/' . $class['id']) ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i> Manage
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-tie text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mb-3">No Class Teacher Accounts Found</h5>
                            <p class="text-muted">Click "Fix Missing Class Teachers" to create accounts for all classes.</p>
                            <a href="<?= base_url('admin/classes/fix-teachers') ?>" class="btn btn-warning">
                                <i class="fas fa-tools me-2"></i>Fix Missing Class Teachers
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Without Class Teachers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Classes Status</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Class ID</th>
                                    <th>Class Name</th>
                                    <th>Section</th>
                                    <th>Academic Year</th>
                                    <th>Class Teacher Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                    <?php 
                                    $hasClassTeacher = false;
                                    $classTeacher = null;
                                    foreach ($classTeachers as $teacher) {
                                        if ($teacher['class_id'] == $class['id']) {
                                            $hasClassTeacher = true;
                                            $classTeacher = $teacher;
                                            break;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $class['id'] ?></td>
                                        <td><?= esc($class['name']) ?></td>
                                        <td><?= esc($class['section']) ?></td>
                                        <td><?= esc($class['academic_year']) ?></td>
                                        <td>
                                            <?php if ($hasClassTeacher): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Has Class Teacher (<?= esc($classTeacher['username']) ?>)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>
                                                    No Class Teacher
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/classes/manage-teacher/' . $class['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-user-tie me-1"></i>
                                                <?= $hasClassTeacher ? 'Manage' : 'Create' ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Troubleshooting Class Teacher Login Issues
                </h6>
                <ul class="mb-0">
                    <li><strong>Default Credentials:</strong> Username is auto-generated (like SS-ONE), password is "class123"</li>
                    <li><strong>Missing Accounts:</strong> Click "Fix Missing Class Teachers" to create accounts for classes without them</li>
                    <li><strong>Login Issues:</strong> Use the "Manage" button to verify and update credentials</li>
                    <li><strong>Username Format:</strong> Class names are converted to usernames (e.g., "SS 1" becomes "SS-ONE")</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
