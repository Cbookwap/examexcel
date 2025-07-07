<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .result-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    .student-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--theme-color), var(--theme-color-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.875rem;
    }
    .score-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
    }
    .score-excellent { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .score-good { background: linear-gradient(135deg, #17a2b8, #6f42c1); color: white; }
    .score-average { background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; }
    .score-poor { background: linear-gradient(135deg, #dc3545, #e83e8c); color: white; }
    
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }
    
    .stats-card {
        background: linear-gradient(135deg, var(--theme-color), var(--theme-color-dark));
        color: white;
        border-radius: 12px;
        border: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;"><?= $pageTitle ?></h4>
                <p class="text-light mb-0"><?= $pageSubtitle ?></p>
            </div>
            <a href="<?= base_url('principal/dashboard') ?>" class="btn btn-outline-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 32px;">assessment</i>
                <h3 class="fw-bold mb-1"><?= count($attempts) ?></h3>
                <p class="mb-0 opacity-75">Total Results</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 32px;">trending_up</i>
                <h3 class="fw-bold mb-1">
                    <?php
                    $completedAttempts = array_filter($attempts, function($attempt) { 
                        return ($attempt['status'] === 'completed' || $attempt['status'] === 'submitted') && $attempt['percentage'] !== null; 
                    });
                    echo count($completedAttempts) > 0 ? round(array_sum(array_column($completedAttempts, 'percentage')) / count($completedAttempts), 1) : 0;
                    ?>%
                </h3>
                <p class="mb-0 opacity-75">Average Mark</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 32px;">check_circle</i>
                <h3 class="fw-bold mb-1">
                    <?= count(array_filter($attempts, function($attempt) { 
                        return ($attempt['status'] === 'completed' || $attempt['status'] === 'submitted'); 
                    })) ?>
                </h3>
                <p class="mb-0 opacity-75">Completed</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 32px;">school</i>
                <h3 class="fw-bold mb-1">
                    <?= count(array_unique(array_column($attempts, 'student_id'))) ?>
                </h3>
                <p class="mb-0 opacity-75">Students</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= base_url('principal/results') ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filter by Class</label>
                    <select class="form-select" name="class_id">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= ($filters['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                <?= esc($class['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Subject</label>
                    <select class="form-select" name="subject_id">
                        <option value="">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>" <?= ($filters['subject_id'] == $subject['id']) ? 'selected' : '' ?>>
                                <?= esc($subject['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Exam</label>
                    <select class="form-select" name="exam_id">
                        <option value="">All Exams</option>
                        <?php foreach ($exams as $exam): ?>
                            <option value="<?= $exam['id'] ?>" <?= ($filters['exam_id'] == $exam['id']) ? 'selected' : '' ?>>
                                <?= esc($exam['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">filter_list</i>Filter
                        </button>
                        <a href="<?= base_url('principal/results') ?>" class="btn btn-outline-secondary">
                            <i class="material-symbols-rounded" style="font-size: 16px;">clear</i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Results List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 fw-semibold">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">list</i>
            Exam Results (<?= count($attempts) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($attempts)): ?>
            <div class="text-center py-5">
                <i class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">assessment</i>
                <h5 class="text-muted">No Results Found</h5>
                <p class="text-muted">No exam results match your current filters.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Student</th>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Exam</th>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Subject</th>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Mark</th>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Status</th>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Date</th>
                            <th class="fw-semibold" style="color: white; background-color: var(--theme-color);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attempts as $attempt): ?>
                        <?php
                        $percentage = $attempt['percentage'] ?? 0;
                        $scoreClass = 'score-poor';
                        if ($percentage >= 90) $scoreClass = 'score-excellent';
                        elseif ($percentage >= 75) $scoreClass = 'score-good';
                        elseif ($percentage >= 60) $scoreClass = 'score-average';
                        
                        $statusClass = 'secondary';
                        if ($attempt['status'] === 'completed' || $attempt['status'] === 'submitted') {
                            $statusClass = 'success';
                        } elseif ($attempt['status'] === 'in_progress') {
                            $statusClass = 'warning';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="student-avatar me-3">
                                        <?= strtoupper(substr($attempt['first_name'], 0, 1) . substr($attempt['last_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></div>
                                        <small class="text-muted"><?= esc($attempt['student_id']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium"><?= esc($attempt['exam_title']) ?></div>
                                    <small class="text-muted"><?= esc($attempt['class_name']) ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= esc($attempt['subject_name'] ?? 'Multiple') ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="score-circle <?= $scoreClass ?> me-2">
                                        <?= round($percentage) ?>%
                                    </div>
                                    <div class="small text-muted">
                                        <?= $attempt['score'] ?>/<?= $attempt['total_marks'] ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?= $statusClass ?> px-3 py-2">
                                    <?= ucfirst(str_replace('_', ' ', $attempt['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <div><?= date('M j, Y', strtotime($attempt['submitted_at'] ?? $attempt['created_at'])) ?></div>
                                    <div class="text-muted"><?= date('g:i A', strtotime($attempt['submitted_at'] ?? $attempt['created_at'])) ?></div>
                                </div>
                            </td>
                            <td>
                                <a href="<?= base_url('principal/results/view/' . $attempt['id']) ?>" 
                                   class="btn btn-outline-primary btn-sm" title="View Details">
                                    <i class="material-symbols-rounded" style="font-size: 16px;">visibility</i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
});
</script>
<?= $this->endSection() ?>
