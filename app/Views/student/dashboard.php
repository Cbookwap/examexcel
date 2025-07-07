<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .exam-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
    }
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .exam-reminder {
        animation: pulse-glow 2s infinite;
        border-radius: 15px !important;
    }
    @keyframes pulse-glow {
        0% { box-shadow: 0 4px 20px rgba(251, 191, 36, 0.3); }
        50% { box-shadow: 0 8px 30px rgba(251, 191, 36, 0.5); }
        100% { box-shadow: 0 4px 20px rgba(251, 191, 36, 0.3); }
    }
    .exam-reminder .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white;">
            <div class="card-body py-4">
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
                        <i class="fas fa-graduation-cap" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Exam Reminder -->
<?php if (!empty($activeAttempts)): ?>
    <?php foreach ($activeAttempts as $attempt): ?>
        <?php
        // Calculate time remaining for the exam
        $startTime = new DateTime($attempt['start_time']);
        $now = new DateTime();
        $examEndTime = new DateTime($attempt['exam_end_time']);
        $timeElapsed = $now->diff($startTime);
        $totalMinutes = $timeElapsed->h * 60 + $timeElapsed->i;
        $remainingMinutes = max(0, $attempt['duration_minutes'] - $totalMinutes);

        // Check if exam is still valid
        $isExpired = $now > $examEndTime || $remainingMinutes <= 0;
        ?>
        <?php if (!$isExpired): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning border-0 shadow-sm exam-reminder" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-left: 5px solid #d97706 !important;">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="text-white mb-1 fw-bold">
                                    <i class="fas fa-clock me-2"></i>Exam in Progress
                                </h5>
                                <p class="text-white mb-2 opacity-90">
                                    You have an active exam: <strong><?= esc($attempt['exam_title']) ?></strong>
                                    <?php if (!empty($attempt['subject_name'])): ?>
                                        (<?= esc($attempt['subject_name']) ?>)
                                    <?php endif; ?>
                                </p>
                                <div class="d-flex align-items-center gap-3 text-white opacity-75 small">
                                    <span>
                                        <i class="fas fa-hourglass-half me-1"></i>
                                        Time Remaining: <strong><?= floor($remainingMinutes / 60) ?>h <?= $remainingMinutes % 60 ?>m</strong>
                                    </span>
                                    <span>
                                        <i class="fas fa-play-circle me-1"></i>
                                        Started: <?= $startTime->format('M j, g:i A') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <a href="<?= base_url('student/takeExam/' . $attempt['id']) ?>"
                                   class="btn btn-light btn-lg fw-bold px-4 py-2 shadow-sm">
                                    <i class="fas fa-arrow-right me-2"></i>Continue Exam
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1"><?= count($availableExams) ?></h3>
            <p class="mb-0">Available Exams</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 class="mb-1"><?= count($recentAttempts) ?></h3>
            <p class="mb-0">Completed Exams</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <h3 class="mb-1"><?= count($upcomingExams) ?></h3>
            <p class="mb-0">Upcoming Exams</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <?php
            $avgScore = 0;
            if (!empty($recentAttempts)) {
                $totalScore = array_sum(array_column($recentAttempts, 'percentage'));
                $avgScore = round($totalScore / count($recentAttempts), 1);
            }
            ?>
            <h3 class="mb-1"><?= $avgScore ?>%</h3>
            <p class="mb-0">Average Mark</p>
        </div>
    </div>
</div>

<!-- Available Exams -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Available Exams</h5>
                    <a href="<?= base_url('student/exams') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($student['class_id'])): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="text-muted">No Class Assigned</h5>
                        <p class="text-muted">You haven't been assigned to a class yet. Please contact your administrator to assign you to a class to view available exams.</p>
                    </div>
                <?php elseif (!empty($availableExams)): ?>
                    <div class="row">
                        <?php foreach (array_slice($availableExams, 0, 3) as $exam): ?>
                            <?php
                            // Use application timezone for consistent time comparison
                            $timezone = new \DateTimeZone(config('App')->appTimezone);
                            $now = new \DateTime('now', $timezone);
                            $nowString = $now->format('Y-m-d H:i:s');
                            $canTake = $exam['start_time'] <= $nowString && $exam['end_time'] >= $nowString && $exam['is_active'];
                            $isUpcoming = $exam['start_time'] > $nowString;
                            $isExpired = $exam['end_time'] < $nowString;
                            ?>
                            <div class="col-md-4 mb-3">
                                <div class="exam-card card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title fw-semibold"><?= esc($exam['title']) ?></h6>
                                            <?php if ($canTake): ?>
                                                <span class="status-badge bg-success text-white">Active</span>
                                            <?php elseif ($isUpcoming): ?>
                                                <span class="status-badge bg-warning text-dark">Upcoming</span>
                                            <?php else: ?>
                                                <span class="status-badge bg-secondary text-white">Expired</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="card-text text-muted small mb-2"><?= esc($exam['subject_name']) ?></p>
                                        <div class="small text-muted mb-3">
                                            <div><i class="fas fa-clock me-1"></i><?= $exam['duration_minutes'] ?> minutes</div>
                                            <div><i class="fas fa-calendar me-1"></i><?= date('M j, Y g:i A', strtotime($exam['start_time'])) ?></div>
                                        </div>
                                        <?php if ($canTake): ?>
                                            <a href="<?= base_url('student/startExam/' . $exam['id']) ?>"
                                               class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-play me-1"></i>Start Exam
                                            </a>
                                        <?php elseif ($isUpcoming): ?>
                                            <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                                <i class="fas fa-clock me-1"></i>Starts Soon
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                                <i class="fas fa-times me-1"></i>Expired
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No exams available</h6>
                        <p class="text-muted small">Check back later for new examinations</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Results -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Recent Results</h5>
                    <a href="<?= base_url('student/results') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-chart-line me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentAttempts)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Exam</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Subject</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Mark</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Date</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentAttempts as $attempt): ?>
                                <tr>
                                    <td class="fw-medium"><?= esc($attempt['exam_title']) ?></td>
                                    <td><?= esc($attempt['subject_name']) ?></td>
                                    <td>
                                        <span class="fw-bold <?= $attempt['percentage'] >= 60 ? 'text-success' : 'text-danger' ?>">
                                            <?= $attempt['percentage'] ?>%
                                        </span>
                                    </td>
                                    <td class="text-muted small"><?= date('M j, Y', strtotime($attempt['submitted_at'])) ?></td>
                                    <td>
                                        <?php if ($attempt['percentage'] >= 60): ?>
                                            <span class="badge bg-success">Passed</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Failed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No results yet</h6>
                        <p class="text-muted small">Take an exam to see your results here</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">Upcoming Exams</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($upcomingExams)): ?>
                    <?php foreach (array_slice($upcomingExams, 0, 5) as $exam): ?>
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="fas fa-calendar text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold small"><?= esc($exam['title']) ?></h6>
                                <p class="mb-0 text-muted small"><?= esc($exam['subject_name']) ?></p>
                                <small class="text-muted"><?= date('M j, g:i A', strtotime($exam['start_time'])) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-0">No upcoming exams</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Auto-refresh dashboard every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

// Show notifications
document.addEventListener('DOMContentLoaded', function() {
    // Show notification for active exams
    <?php if (!empty($activeAttempts)): ?>
        <?php foreach ($activeAttempts as $attempt): ?>
            <?php
            $startTime = new DateTime($attempt['start_time']);
            $now = new DateTime();
            $examEndTime = new DateTime($attempt['exam_end_time']);
            $timeElapsed = $now->diff($startTime);
            $totalMinutes = $timeElapsed->h * 60 + $timeElapsed->i;
            $remainingMinutes = max(0, $attempt['duration_minutes'] - $totalMinutes);
            $isExpired = $now > $examEndTime || $remainingMinutes <= 0;
            ?>
            <?php if (!$isExpired): ?>
                // Show toast notification for active exam
                if (typeof CBT !== 'undefined' && CBT.showToast) {
                    CBT.showToast('You have an active exam: <?= esc($attempt['exam_title']) ?>. Click "Continue Exam" to resume.', 'warning', 8000);
                }
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    // Show notification for upcoming exams
    <?php if (!empty($upcomingExams)): ?>
        <?php foreach ($upcomingExams as $exam): ?>
            <?php
            $startTime = new DateTime($exam['start_time']);
            $now = new DateTime();
            $diff = $startTime->diff($now);
            $minutesUntilStart = ($diff->h * 60) + $diff->i;
            ?>
            <?php if ($minutesUntilStart <= 30 && $minutesUntilStart > 0): ?>
                if (typeof CBT !== 'undefined' && CBT.showToast) {
                    CBT.showToast('Exam starting soon: <?= esc($exam['title']) ?> in <?= $minutesUntilStart ?> minutes', 'info');
                }
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
