<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .exam-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }
    .exam-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }
    .exam-meta {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 0.75rem;
        margin-top: 1rem;
    }
    .exam-meta .meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .exam-meta .meta-item:last-child {
        margin-bottom: 0;
    }
    .exam-meta .meta-item i {
        width: 20px;
        margin-right: 0.5rem;
    }
    .filter-tabs {
        background: white;
        border-radius: 15px;
        padding: 0.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .filter-tabs .nav-link {
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .filter-tabs .nav-link.active {
        background: var(--primary-color);
        color: white;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    .countdown-timer {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        margin-top: 1rem;
    }
    .countdown-timer .time-unit {
        display: inline-block;
        margin: 0 0.5rem;
        text-align: center;
    }
    .countdown-timer .time-value {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .countdown-timer .time-label {
        display: block;
        font-size: 0.75rem;
        opacity: 0.8;
    }
    .subjects-list {
        background: rgba(0,0,0,0.02);
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 0.5rem;
    }
    .subjects-list .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        margin: 0.1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="exam-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2 fw-bold"><?= $pageTitle ?></h2>
            <p class="mb-0 opacity-75"><?= $pageSubtitle ?></p>
            <div class="d-flex flex-wrap gap-3 mt-2">
                <small class="opacity-75">
                    <i class="fas fa-id-card me-1"></i>
                    Student ID: <?= $student['student_id'] ?? 'N/A' ?>
                </small>
                <small class="opacity-75">
                    <i class="fas fa-users me-1"></i>
                    Class: <?php if (!empty($student['class_name'])): ?>
                        <?= esc($student['class_name']) ?><?= !empty($student['class_section']) ? ' - ' . esc($student['class_section']) : '' ?>
                    <?php else: ?>
                        <span class="text-warning">Not Assigned</span>
                    <?php endif; ?>
                </small>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <i class="fas fa-clipboard-list" style="font-size: 4rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <ul class="nav nav-pills" id="examTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                <i class="fas fa-list me-2"></i>All Exams
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="active-tab" data-bs-toggle="pill" data-bs-target="#active" type="button" role="tab">
                <i class="fas fa-play-circle me-2"></i>Active
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="upcoming-tab" data-bs-toggle="pill" data-bs-target="#upcoming" type="button" role="tab">
                <i class="fas fa-clock me-2"></i>Upcoming
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed" type="button" role="tab">
                <i class="fas fa-check-circle me-2"></i>Completed
            </button>
        </li>
    </ul>
</div>

<!-- Exam Content -->
<div class="tab-content" id="examTabsContent">
    <!-- All Exams Tab -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        <?php if (empty($student['class_id'])): ?>
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle text-warning"></i>
                <h5>No Class Assigned</h5>
                <p>You haven't been assigned to a class yet. Please contact your administrator to assign you to a class to view available exams.</p>
            </div>
        <?php elseif (!empty($exams)): ?>
            <div class="row">
                <?php foreach ($exams as $exam): ?>
                    <?php
                    $now = date('Y-m-d H:i:s');
                    $canTake = $exam['can_take'] && $exam['status'] === 'active';
                    $isUpcoming = $exam['status'] === 'scheduled';
                    $isCompleted = $exam['status'] === 'completed' || (!empty($exam['attempt']) && !$canTake);
                    $isActive = $exam['status'] === 'active';
                    $attemptInfo = $exam['attempt_info'] ?? null;
                    ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="exam-card card h-100">
                            <div class="card-body p-4">
                                <!-- Exam Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-bold mb-0"><?= esc($exam['title']) ?></h5>
                                    <?php if ($isCompleted): ?>
                                        <span class="status-badge bg-success text-white">
                                            <i class="fas fa-check me-1"></i>Completed
                                        </span>
                                    <?php elseif ($canTake): ?>
                                        <span class="status-badge bg-primary text-white">
                                            <i class="fas fa-play me-1"></i>Active
                                        </span>
                                    <?php elseif ($isUpcoming): ?>
                                        <span class="status-badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>Upcoming
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge bg-secondary text-white">
                                            <i class="fas fa-times me-1"></i>Expired
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Exam Mode and Subject Information -->
                                <div class="mb-3">
                                    <?php if (($exam['exam_mode'] ?? 'single_subject') === 'multi_subject'): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info text-white me-2">
                                                <i class="fas fa-layer-group me-1"></i>Multi-Subject
                                            </span>
                                            <small class="text-muted"><?= count($exam['subjects'] ?? []) ?> subjects</small>
                                        </div>
                                        <?php if (!empty($exam['subjects'])): ?>
                                            <div class="subjects-list">
                                                <small class="text-muted d-block mb-1">Subjects included:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($exam['subjects'] as $subject): ?>
                                                        <span class="badge bg-light text-dark border">
                                                            <?= esc($subject['subject_name']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary text-white me-2">
                                                <i class="fas fa-book me-1"></i>Single Subject
                                            </span>
                                            <span class="text-primary fw-semibold">
                                                <?= esc($exam['subject_name']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Exam Description -->
                                <?php if (!empty($exam['description'])): ?>
                                    <p class="text-muted small mb-3"><?= esc($exam['description']) ?></p>
                                <?php endif; ?>

                                <!-- Exam Meta Information -->
                                <div class="exam-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock text-primary"></i>
                                        <span class="small">Duration: <?= $exam['duration_minutes'] ?> minutes</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar text-primary"></i>
                                        <span class="small">Start: <?= date('M j, Y g:i A', strtotime($exam['start_time'])) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-times text-primary"></i>
                                        <span class="small">End: <?= date('M j, Y g:i A', strtotime($exam['end_time'])) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-question-circle text-primary"></i>
                                        <span class="small">Questions: <?= $exam['total_questions'] ?? 'TBD' ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-trophy text-primary"></i>
                                        <span class="small">Passing: <?= $exam['passing_marks'] ?>%</span>
                                    </div>
                                </div>

                                <!-- Countdown Timer for Upcoming Exams -->
                                <?php if ($isUpcoming): ?>
                                    <div class="countdown-timer" data-start-time="<?= $exam['start_time'] ?>">
                                        <div class="small mb-2">Starts in:</div>
                                        <div class="countdown-display">
                                            <div class="time-unit">
                                                <span class="time-value days">00</span>
                                                <span class="time-label">Days</span>
                                            </div>
                                            <div class="time-unit">
                                                <span class="time-value hours">00</span>
                                                <span class="time-label">Hours</span>
                                            </div>
                                            <div class="time-unit">
                                                <span class="time-value minutes">00</span>
                                                <span class="time-label">Minutes</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Attempt Information -->
                                <?php if ($attemptInfo && $attemptInfo['max_attempts'] > 1): ?>
                                    <div class="attempt-info mt-3 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-muted">
                                                <i class="fas fa-redo me-1"></i>Attempts:
                                            </span>
                                            <span class="badge bg-primary">
                                                <?= $attemptInfo['attempts_used'] ?>/<?= $attemptInfo['max_attempts'] ?>
                                            </span>
                                        </div>
                                        <?php if ($attemptInfo['attempts_remaining'] > 0): ?>
                                            <div class="small text-success mt-1">
                                                <i class="fas fa-check-circle me-1"></i>
                                                <?= $attemptInfo['attempts_remaining'] ?> attempt(s) remaining
                                            </div>
                                        <?php else: ?>
                                            <div class="small text-danger mt-1">
                                                <i class="fas fa-times-circle me-1"></i>
                                                No attempts remaining
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    <?php if ($isCompleted && !empty($exam['attempt'])): ?>
                                        <a href="<?= base_url('student/examResult/' . $exam['attempt']['id']) ?>"
                                           class="btn btn-outline-success w-100">
                                            <i class="fas fa-chart-line me-2"></i>View Result
                                        </a>
                                    <?php elseif ($canTake): ?>
                                        <?php
                                        // Check if there's an active attempt for this exam
                                        $hasActiveAttempt = false;
                                        $activeAttemptId = null;
                                        if (!empty($exam['attempts'])) {
                                            foreach ($exam['attempts'] as $attempt) {
                                                if ($attempt['status'] === 'in_progress') {
                                                    $hasActiveAttempt = true;
                                                    $activeAttemptId = $attempt['id'];
                                                    break;
                                                }
                                            }
                                        }

                                        if ($hasActiveAttempt) {
                                            $buttonText = 'Resume Exam';
                                            $buttonIcon = 'fas fa-play-circle';
                                            $buttonAction = "window.location.href='" . base_url('student/takeExam/' . $activeAttemptId) . "'";
                                        } else {
                                            $buttonText = ($attemptInfo && $attemptInfo['attempts_used'] > 0) ? 'Retake Exam' : 'Start Exam';
                                            $buttonIcon = ($attemptInfo && $attemptInfo['attempts_used'] > 0) ? 'fas fa-redo' : 'fas fa-play';
                                            $buttonAction = "confirmStartExam(" . $exam['id'] . ", '" . esc($exam['title']) . "')";
                                        }
                                        ?>
                                        <button class="btn btn-primary w-100" onclick="<?= $buttonAction ?>">
                                            <i class="<?= $buttonIcon ?> me-2"></i><?= $buttonText ?>
                                        </button>
                                    <?php elseif ($isUpcoming): ?>
                                        <button class="btn btn-outline-warning w-100" disabled>
                                            <i class="fas fa-clock me-2"></i>Not Started Yet
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary w-100" disabled>
                                            <i class="fas fa-times me-2"></i>Not Available
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h5>No Exams Available</h5>
                <p>There are currently no exams available for your class. Check back later for new examinations.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Active Exams Tab -->
    <div class="tab-pane fade" id="active" role="tabpanel">
        <?php
        $activeExams = array_filter($exams, function($exam) {
            return $exam['status'] === 'active' && $exam['can_take'];
        });
        ?>
        <?php if (!empty($activeExams)): ?>
            <div class="row">
                <?php foreach ($activeExams as $exam): ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="exam-card card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-bold mb-0"><?= esc($exam['title']) ?></h5>
                                    <span class="status-badge bg-primary text-white">
                                        <i class="fas fa-play me-1"></i>Active
                                    </span>
                                </div>
                                <!-- Exam Mode and Subject Information -->
                                <div class="mb-3">
                                    <?php if (($exam['exam_mode'] ?? 'single_subject') === 'multi_subject'): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info text-white me-2">
                                                <i class="fas fa-layer-group me-1"></i>Multi-Subject
                                            </span>
                                            <small class="text-muted"><?= count($exam['subjects'] ?? []) ?> subjects</small>
                                        </div>
                                        <?php if (!empty($exam['subjects'])): ?>
                                            <div class="subjects-list">
                                                <small class="text-muted d-block mb-1">Subjects included:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($exam['subjects'] as $subject): ?>
                                                        <span class="badge bg-light text-dark border">
                                                            <?= esc($subject['subject_name']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary text-white me-2">
                                                <i class="fas fa-book me-1"></i>Single Subject
                                            </span>
                                            <span class="text-primary fw-semibold">
                                                <?= esc($exam['subject_name']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="exam-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock text-primary"></i>
                                        <span class="small">Duration: <?= $exam['duration_minutes'] ?> minutes</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-times text-primary"></i>
                                        <span class="small">Ends: <?= date('M j, Y g:i A', strtotime($exam['end_time'])) ?></span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-primary w-100" onclick="confirmStartExam(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                                        <i class="fas fa-play me-2"></i>Start Exam
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-play-circle"></i>
                <h5>No Active Exams</h5>
                <p>There are currently no active exams that you can take.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Upcoming Exams Tab -->
    <div class="tab-pane fade" id="upcoming" role="tabpanel">
        <?php
        $upcomingExams = array_filter($exams, function($exam) {
            return $exam['status'] === 'scheduled';
        });
        ?>
        <?php if (!empty($upcomingExams)): ?>
            <div class="row">
                <?php foreach ($upcomingExams as $exam): ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="exam-card card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-bold mb-0"><?= esc($exam['title']) ?></h5>
                                    <span class="status-badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Upcoming
                                    </span>
                                </div>
                                <!-- Exam Mode and Subject Information -->
                                <div class="mb-3">
                                    <?php if (($exam['exam_mode'] ?? 'single_subject') === 'multi_subject'): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info text-white me-2">
                                                <i class="fas fa-layer-group me-1"></i>Multi-Subject
                                            </span>
                                            <small class="text-muted"><?= count($exam['subjects'] ?? []) ?> subjects</small>
                                        </div>
                                        <?php if (!empty($exam['subjects'])): ?>
                                            <div class="subjects-list">
                                                <small class="text-muted d-block mb-1">Subjects included:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($exam['subjects'] as $subject): ?>
                                                        <span class="badge bg-light text-dark border">
                                                            <?= esc($subject['subject_name']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary text-white me-2">
                                                <i class="fas fa-book me-1"></i>Single Subject
                                            </span>
                                            <span class="text-primary fw-semibold">
                                                <?= esc($exam['subject_name']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="exam-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar text-primary"></i>
                                        <span class="small">Starts: <?= date('M j, Y g:i A', strtotime($exam['start_time'])) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock text-primary"></i>
                                        <span class="small">Duration: <?= $exam['duration_minutes'] ?> minutes</span>
                                    </div>
                                </div>
                                <div class="countdown-timer" data-start-time="<?= $exam['start_time'] ?>">
                                    <div class="small mb-2">Starts in:</div>
                                    <div class="countdown-display">
                                        <div class="time-unit">
                                            <span class="time-value days">00</span>
                                            <span class="time-label">Days</span>
                                        </div>
                                        <div class="time-unit">
                                            <span class="time-value hours">00</span>
                                            <span class="time-label">Hours</span>
                                        </div>
                                        <div class="time-unit">
                                            <span class="time-value minutes">00</span>
                                            <span class="time-label">Minutes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clock"></i>
                <h5>No Upcoming Exams</h5>
                <p>There are currently no upcoming exams scheduled for your class.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Completed Exams Tab -->
    <div class="tab-pane fade" id="completed" role="tabpanel">
        <?php
        $completedExams = array_filter($exams, function($exam) {
            return $exam['status'] === 'completed' || !empty($exam['attempt']);
        });
        ?>
        <?php if (!empty($completedExams)): ?>
            <div class="row">
                <?php foreach ($completedExams as $exam): ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="exam-card card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-bold mb-0"><?= esc($exam['title']) ?></h5>
                                    <span class="status-badge bg-success text-white">
                                        <i class="fas fa-check me-1"></i>Completed
                                    </span>
                                </div>
                                <!-- Exam Mode and Subject Information -->
                                <div class="mb-3">
                                    <?php if (($exam['exam_mode'] ?? 'single_subject') === 'multi_subject'): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info text-white me-2">
                                                <i class="fas fa-layer-group me-1"></i>Multi-Subject
                                            </span>
                                            <small class="text-muted"><?= count($exam['subjects'] ?? []) ?> subjects</small>
                                        </div>
                                        <?php if (!empty($exam['subjects'])): ?>
                                            <div class="subjects-list">
                                                <small class="text-muted d-block mb-1">Subjects included:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($exam['subjects'] as $subject): ?>
                                                        <span class="badge bg-light text-dark border">
                                                            <?= esc($subject['subject_name']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary text-white me-2">
                                                <i class="fas fa-book me-1"></i>Single Subject
                                            </span>
                                            <span class="text-primary fw-semibold">
                                                <?= esc($exam['subject_name']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($exam['attempt'])): ?>
                                    <div class="exam-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar text-primary"></i>
                                            <span class="small">Taken: <?= date('M j, Y g:i A', strtotime($exam['attempt']['submitted_at'])) ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-chart-line text-primary"></i>
                                            <span class="small">Mark: <?= $exam['attempt']['marks_obtained'] ?>/<?= $exam['attempt']['total_marks'] ?> (<?= $exam['attempt']['percentage'] ?>%)</span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="<?= base_url('student/examResult/' . $exam['attempt']['id']) ?>"
                                           class="btn btn-outline-success w-100">
                                            <i class="fas fa-chart-line me-2"></i>View Result
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h5>No Completed Exams</h5>
                <p>You haven't completed any exams yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Start Exam Confirmation Modal -->
<div class="modal fade" id="startExamModal" tabindex="-1" aria-labelledby="startExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title" id="startExamModalLabel">
                    <i class="fas fa-play-circle me-2"></i>Start Exam
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Are you ready to start the exam?</h5>
                    <p class="text-muted">Once you start, the timer will begin and you cannot pause the exam.</p>
                </div>
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Important Instructions:
                    </h6>
                    <ul class="mb-0 small">
                        <li>Make sure you have a stable internet connection</li>
                        <li>Do not refresh or close the browser during the exam</li>
                        <li>The exam will auto-submit when time expires</li>
                        <li>You can take this exam up to 5 times</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmStartBtn">
                    <i class="fas fa-play me-2"></i>Start Exam
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let selectedExamId = null;

// Confirm start exam
function confirmStartExam(examId, examTitle) {
    selectedExamId = examId;
    document.getElementById('startExamModalLabel').innerHTML =
        '<i class="fas fa-play-circle me-2"></i>Start: ' + examTitle;

    const modal = new bootstrap.Modal(document.getElementById('startExamModal'));
    modal.show();
}

// Handle exam start confirmation
document.getElementById('confirmStartBtn').addEventListener('click', function() {
    if (selectedExamId) {
        window.location.href = '<?= base_url('student/startExam/') ?>' + selectedExamId;
    }
});

// Countdown timers for upcoming exams
function updateCountdowns() {
    const countdownElements = document.querySelectorAll('.countdown-timer[data-start-time]');

    countdownElements.forEach(function(element) {
        const startTime = new Date(element.dataset.startTime).getTime();
        const now = new Date().getTime();
        const distance = startTime - now;

        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

            element.querySelector('.days').textContent = days.toString().padStart(2, '0');
            element.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
            element.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
        } else {
            element.innerHTML = '<div class="text-success"><i class="fas fa-play-circle me-2"></i>Exam has started!</div>';
        }
    });
}

// Update countdowns every minute
setInterval(updateCountdowns, 60000);
updateCountdowns(); // Initial call

// Auto-refresh page every 5 minutes to check for new exams
setInterval(function() {
    location.reload();
}, 300000);

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    // Show notification if there are active exams
    const activeExams = document.querySelectorAll('#active .exam-card').length;
    if (activeExams > 0) {
        CBT.showToast('You have ' + activeExams + ' active exam(s) available!', 'info');
    }
});
</script>
<?= $this->endSection() ?>