<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>


<style>
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        border: none;
        box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(var(--primary-color-rgb), 0.4);
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

    .stats-card.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    .stats-card.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
    }

    .stats-card.info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .quick-action-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }

    .quick-action-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .activity-card, .status-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }

    .user-avatar-small {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
    }

    .progress-bar-custom {
        border-radius: 10px;
    }

    .theme-control {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row">
    <div class="ms-3">
        <h3 class="mb-0 font-weight-bolder">Admin Dashboard</h3>
        <p class="mb-4">Welcome back! Here's where your ExamExcel CBT system is controlled.</p>
    </div>
</div>

<!-- Material Dashboard Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Users</p>
                        <h4 class="mb-0"><?= $dashboard_stats['total_users'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="fas fa-users opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="<?= $dashboard_stats['user_growth'] >= 0 ? 'text-success' : 'text-danger' ?> font-weight-bolder">
                        <?= $dashboard_stats['user_growth'] >= 0 ? '+' : '' ?><?= $dashboard_stats['user_growth'] ?>%
                    </span> from last month
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Students</p>
                        <h4 class="mb-0"><?= $dashboard_stats['total_students'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="fas fa-graduation-cap opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-info font-weight-bolder"><?= $dashboard_stats['total_classes'] ?> classes</span> available
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Teachers</p>
                        <h4 class="mb-0"><?= $dashboard_stats['total_teachers'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="fas fa-chalkboard-teacher opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-info font-weight-bolder"><?= $dashboard_stats['total_subjects'] ?> subjects</span> assigned
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Active Exams</p>
                        <h4 class="mb-0"><?= $dashboard_stats['active_exams'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="fas fa-clipboard-list opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-warning font-weight-bolder"><?= $dashboard_stats['upcoming_exams'] ?> upcoming</span> scheduled
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Questions</p>
                        <h4 class="mb-0"><?= $dashboard_stats['total_questions'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-warning shadow-warning shadow text-center border-radius-lg">
                        <i class="fas fa-question-circle opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-success font-weight-bolder"><?= $dashboard_stats['active_questions'] ?> active</span> in bank
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Exam Attempts</p>
                        <h4 class="mb-0"><?= $dashboard_stats['total_attempts'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-success shadow-success shadow text-center border-radius-lg">
                        <i class="fas fa-chart-line opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-info font-weight-bolder"><?= $dashboard_stats['today_attempts'] ?> today</span> attempts
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Class Teachers</p>
                        <h4 class="mb-0"><?= $dashboard_stats['total_class_teachers'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-info shadow-info shadow text-center border-radius-lg">
                        <i class="fas fa-user-tie opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-success font-weight-bolder"><?= $dashboard_stats['active_classes'] ?> classes</span> managed
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Academic Session</p>
                        <h6 class="mb-0"><?= $current_session['session_name'] ?? 'Not Set' ?></h6>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-info shadow-info shadow text-center border-radius-lg">
                        <i class="fas fa-calendar-alt opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-primary font-weight-bolder"><?= $current_term['term_name'] ?? 'No Term' ?></span> active
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Quick Actions - Mobile First Responsive -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Quick Actions</h6>
                <p class="text-sm mb-0">Frequently used administrative tasks</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Add New User -->
                    <div class="col-12 col-sm-6 col-lg-3 mb-3">
                        <div class="card h-100 shadow-sm border-0 quick-action-card" style="background: var(--primary-color);">
                            <div class="card-body text-center text-white p-3">
                                <div class="icon icon-lg icon-shape bg-white shadow text-center border-radius-lg mb-3">
                                    <i class="fas fa-user-plus text-dark opacity-10"></i>
                                </div>
                                <h6 class="text-white mb-2">Add New User</h6>
                                <p class="text-white opacity-8 text-sm mb-3">Create accounts for students, teachers or admins</p>
                                <a href="<?= base_url('admin/users/create') ?>" class="btn btn-white btn-sm w-100">Create User</a>
                            </div>
                        </div>
                    </div>

                    <!-- Create Exam -->
                    <div class="col-12 col-sm-6 col-lg-3 mb-3">
                        <div class="card h-100 shadow-sm border-0 quick-action-card" style="background: var(--primary-color);">
                            <div class="card-body text-center text-white p-3">
                                <div class="icon icon-lg icon-shape bg-white shadow text-center border-radius-lg mb-3">
                                    <i class="fas fa-plus-square text-dark opacity-10"></i>
                                </div>
                                <h6 class="text-white mb-2">Create Exam</h6>
                                <p class="text-white opacity-8 text-sm mb-3">Set up new examinations and assessments</p>
                                <a href="<?= base_url('exam/create') ?>" class="btn btn-white btn-sm w-100">New Exam</a>
                            </div>
                        </div>
                    </div>

                    <!-- Add Questions -->
                    <div class="col-12 col-sm-6 col-lg-3 mb-3">
                        <div class="card h-100 shadow-sm border-0 quick-action-card" style="background: var(--primary-color);">
                            <div class="card-body text-center text-white p-3">
                                <div class="icon icon-lg icon-shape bg-white shadow text-center border-radius-lg mb-3">
                                    <i class="fas fa-question-circle text-dark opacity-10"></i>
                                </div>
                                <h6 class="text-white mb-2">Add Questions</h6>
                                <p class="text-white opacity-8 text-sm mb-3">Build your question bank with various types</p>
                                <a href="<?= base_url('questions/create') ?>" class="btn btn-white btn-sm w-100">Add Question</a>
                            </div>
                        </div>
                    </div>



                    <!-- View Reports -->
                    <div class="col-12 col-sm-6 col-lg-3 mb-3">
                        <div class="card h-100 shadow-sm border-0 quick-action-card" style="background: var(--primary-color);">
                            <div class="card-body text-center text-white p-3">
                                <div class="icon icon-lg icon-shape bg-white shadow text-center border-radius-lg mb-3">
                                    <i class="fas fa-chart-bar text-dark opacity-10"></i>
                                </div>
                                <h6 class="text-white mb-2">View Reports</h6>
                                <p class="text-white opacity-8 text-sm mb-3">Analyze performance and system metrics</p>
                                <a href="<?= base_url('admin/reports') ?>" class="btn btn-white btn-sm w-100">View Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Exams Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Exams</h6>
                    <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="border-0 fw-semibold">Exam Title</th>
                                <th class="border-0 fw-semibold">Subject</th>
                                <th class="border-0 fw-semibold">Students</th>
                                <th class="border-0 fw-semibold">Duration</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Created</th>
                                <th class="border-0 fw-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_exams)): ?>
                                <?php foreach ($recent_exams as $exam): ?>
                                    <?php
                                    // Use application timezone for consistent time comparison
                                    $timezone = new \DateTimeZone(config('App')->appTimezone);
                                    $now = new \DateTime('now', $timezone);
                                    $nowString = $now->format('Y-m-d H:i:s');
                                    $status = 'draft';
                                    $statusClass = 'bg-secondary';

                                    if ($exam['is_active']) {
                                        if ($exam['end_time'] < $nowString) {
                                            $status = 'completed';
                                            $statusClass = 'bg-secondary';
                                        } elseif ($exam['start_time'] <= $nowString && $exam['end_time'] >= $nowString) {
                                            $status = 'active';
                                            $statusClass = 'bg-success';
                                        } elseif ($exam['start_time'] > $nowString) {
                                            $status = 'scheduled';
                                            $statusClass = 'bg-warning';
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="fw-medium"><?= esc($exam['title']) ?></td>
                                        <td><?= esc($exam['subject_name'] ?? 'N/A') ?></td>
                                        <td><span class="badge bg-light text-dark">Class: <?= esc($exam['class_name'] ?? 'N/A') ?></span></td>
                                        <td><?= $exam['duration_minutes'] ?> min</td>
                                        <td><span class="badge <?= $statusClass ?>"><?= ucfirst($status) ?></span></td>
                                        <td><?= date('M j, Y', strtotime($exam['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('exam/view/' . $exam['id']) ?>" class="btn btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('exam/edit/' . $exam['id']) ?>" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <div class="text-muted">No exams found</div>
                                        <a href="<?= base_url('exam/create') ?>" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>Create First Exam
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comprehensive Statistics Section -->
<div class="row mb-4">
    <!-- Students Statistics Table -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);">
                <h6 class="mb-0 text-white fw-semibold">
                    <i class="fas fa-users me-2"></i>Students Statistics Table
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Class</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Students</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Active</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Pending</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Suspended</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($student_stats_by_class)): ?>
                                <?php foreach ($student_stats_by_class as $stat): ?>
                                    <tr>
                                        <td class="fw-medium" style="padding: 12px;"><?= esc($stat['class_name']) ?></td>
                                        <td style="padding: 12px;"><?= $stat['total_students'] ?></td>
                                        <td style="padding: 12px;"><?= $stat['active_students'] ?></td>
                                        <td style="padding: 12px; color: #f59e0b;"><?= $stat['pending_students'] ?></td>
                                        <td style="padding: 12px; color: #ef4444;"><?= $stat['suspended_students'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <div class="text-muted">No student data available</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Count Table -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);">
                <h6 class="mb-0 text-white fw-semibold">
                    <i class="fas fa-question-circle me-2"></i>Questions Count Table
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Subjects</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Questions</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Easy</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Normal</th>
                                <th class="border-0 fw-semibold" style="background: var(--primary-color); padding: 12px; color: white !important;">Total Difficult</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($subject_question_counts)): ?>
                                <?php foreach ($subject_question_counts as $subject): ?>
                                    <tr>
                                        <td class="fw-medium" style="padding: 12px;"><?= esc($subject['subject_name']) ?></td>
                                        <td style="padding: 12px;"><?= $subject['total_questions'] ?></td>
                                        <td style="padding: 12px; color: #10b981;"><?= $subject['easy_questions'] ?></td>
                                        <td style="padding: 12px; color: #f59e0b;"><?= $subject['normal_questions'] ?></td>
                                        <td style="padding: 12px; color: #ef4444;"><?= $subject['difficult_questions'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-question-circle fa-2x text-muted mb-2"></i>
                                        <div class="text-muted">No question data available</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top 10 Question Bank Subject Wise Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);">
                <h6 class="mb-0 text-white fw-semibold">
                    <i class="fas fa-chart-pie me-2"></i>Top 10 Question Bank Subject Wise
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-container" style="position: relative; height: 400px;">
                            <canvas id="questionSubjectChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-legend">
                            <h6 class="fw-semibold mb-3">Question Count</h6>
                            <div id="chartLegend">
                                <?php if (!empty($question_stats_by_subject)): ?>
                                    <?php foreach ($question_stats_by_subject as $index => $data): ?>
                                        <div class="legend-item d-flex align-items-center mb-2">
                                            <div class="legend-color me-2" style="width: 16px; height: 16px; background-color: <?= $data['color'] ?>; border-radius: 3px;"></div>
                                            <span class="legend-text"><?= esc($data['subject']) ?> (<?= $data['count'] ?>)</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted">No data available</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="activity-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="fas fa-clock me-2" style="color: var(--primary-color);"></i>
                    Recent Activity
                </h5>
                <p class="text-muted small mb-0">Latest system activities and user actions</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="border: none; padding: 1rem; color: #374151; font-weight: 600;">User</th>
                                <th style="border: none; padding: 1rem; color: #374151; font-weight: 600;">Action</th>
                                <th style="border: none; padding: 1rem; color: #374151; font-weight: 600;">Time</th>
                                <th style="border: none; padding: 1rem; color: #374151; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_users)): ?>
                                <?php foreach (array_slice($recent_users, 0, 5) as $user): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 1rem; border: none;">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-small me-3">
                                                <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: #1f2937;"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></div>
                                                <div class="text-muted small"><?= esc($user['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; border: none;">
                                        <span class="badge" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 15px;">
                                            User Registration
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; border: none;">
                                        <span class="text-muted small"><?= date('M j, Y g:i A', strtotime($user['created_at'])) ?></span>
                                    </td>
                                    <td style="padding: 1rem; border: none;">
                                        <?php if ($user['is_active']): ?>
                                            <span class="badge" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 15px;">
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background: #6b7280; color: white; padding: 0.25rem 0.75rem; border-radius: 15px;">
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5" style="border: none;">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <div class="text-muted">No recent activity</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center p-3" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <a href="<?= base_url('admin/activity-log') ?>" class="btn" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; border-radius: 25px; padding: 0.5rem 1.5rem; text-decoration: none;">
                        <i class="fas fa-eye me-2"></i>
                        View All Activity
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional CSS for Statistics -->
<style>
.chart-container {
    position: relative;
    height: 400px;
    width: 100%;
}

.legend-item {
    font-size: 0.875rem;
    padding: 0.25rem 0;
}

.legend-color {
    flex-shrink: 0;
}

.legend-text {
    color: #374151;
    font-weight: 500;
}

/* Custom table styling for statistics */
.table th {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.table td {
    font-size: 0.875rem;
    vertical-align: middle;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chart-container {
        height: 300px;
    }

    .table-responsive {
        font-size: 0.8rem;
    }

    .table th,
    .table td {
        padding: 8px;
        font-size: 0.8rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Question Subject Pie Chart
    const ctx = document.getElementById('questionSubjectChart');
    if (ctx) {
        const chartData = <?= json_encode($question_stats_by_subject ?? []) ?>;

        if (chartData && chartData.length > 0) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.map(item => item.subject),
                    datasets: [{
                        data: chartData.map(item => item.count),
                        backgroundColor: chartData.map(item => item.color),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverBorderWidth: 3,
                        hoverBorderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // We're using custom legend
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1000
                    }
                }
            });
        } else {
            // Show no data message
            ctx.getContext('2d').fillStyle = '#6b7280';
            ctx.getContext('2d').font = '16px Arial';
            ctx.getContext('2d').textAlign = 'center';
            ctx.getContext('2d').fillText('No data available', ctx.width / 2, ctx.height / 2);
        }
    }
});
</script>
<?= $this->endSection() ?>
