<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.schedule-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.schedule-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1.5rem;
}

.schedule-body {
    padding: 1.5rem;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 500;
}

.exam-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.exam-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.exam-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-exam {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
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

.filter-tabs {
    margin-bottom: 2rem;
}

.nav-pills .nav-link {
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    margin-right: 0.5rem;
    color: #6c757d;
    background: #f8f9fa;
    border: none;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.schedule-stats {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
    display: block;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

@media (max-width: 768px) {
    .exam-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .exam-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .btn-exam {
        flex: 1;
        text-align: center;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><?= esc($pageTitle) ?></h1>
            <p class="text-muted mb-0"><?= esc($pageSubtitle) ?></p>
        </div>
    </div>

    <?php if (empty($student['class_id'])): ?>
        <div class="empty-state">
            <i class="fas fa-exclamation-triangle text-warning"></i>
            <h5>No Class Assigned</h5>
            <p>You haven't been assigned to a class yet. Please contact your administrator to assign you to a class to view your exam schedule.</p>
        </div>
    <?php else: ?>
        <!-- Schedule Statistics -->
        <div class="schedule-stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number"><?= !empty($schedules) ? count(array_filter($schedules, function($s) { return isset($s['status']) && $s['status'] === 'upcoming'; })) : 0 ?></span>
                        <div class="stat-label">Upcoming Exams</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number"><?= !empty($schedules) ? count(array_filter($schedules, function($s) { return isset($s['status']) && $s['status'] === 'active'; })) : 0 ?></span>
                        <div class="stat-label">Active Exams</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number"><?= !empty($schedules) ? count(array_filter($schedules, function($s) { return isset($s['status']) && $s['status'] === 'completed'; })) : 0 ?></span>
                        <div class="stat-label">Completed Exams</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number"><?= !empty($schedules) ? array_sum(array_column($schedules, 'attempt_count')) : 0 ?></span>
                        <div class="stat-label">Total Attempts</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <ul class="nav nav-pills" id="scheduleFilter" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                        All Exams
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="upcoming-tab" data-bs-toggle="pill" data-bs-target="#upcoming" type="button" role="tab">
                        Upcoming
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tab" data-bs-toggle="pill" data-bs-target="#active" type="button" role="tab">
                        Active
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed" type="button" role="tab">
                        Completed
                    </button>
                </li>
            </ul>
        </div>

        <!-- Schedule Content -->
        <div class="tab-content" id="scheduleTabsContent">
            <!-- All Exams Tab -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <?php if (!empty($schedules)): ?>
                    <?php foreach ($schedules as $schedule): ?>
                        <?php if (isset($schedule) && is_array($schedule) && !empty($schedule)): ?>
                            <?= $this->include('student/partials/schedule_card', ['schedule' => $schedule]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <h5>No Exams Scheduled</h5>
                        <p>There are no exams scheduled for your class at this time.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Upcoming Exams Tab -->
            <div class="tab-pane fade" id="upcoming" role="tabpanel">
                <?php
                $upcomingExams = !empty($schedules) ? array_filter($schedules, function($s) { return isset($s['status']) && $s['status'] === 'upcoming'; }) : [];
                if (!empty($upcomingExams)): ?>
                    <?php foreach ($upcomingExams as $schedule): ?>
                        <?php if (isset($schedule) && is_array($schedule) && !empty($schedule)): ?>
                            <?= $this->include('student/partials/schedule_card', ['schedule' => $schedule]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-clock"></i>
                        <h5>No Upcoming Exams</h5>
                        <p>You don't have any upcoming exams scheduled.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Active Exams Tab -->
            <div class="tab-pane fade" id="active" role="tabpanel">
                <?php
                $activeExams = !empty($schedules) ? array_filter($schedules, function($s) { return isset($s['status']) && $s['status'] === 'active'; }) : [];
                if (!empty($activeExams)): ?>
                    <?php foreach ($activeExams as $schedule): ?>
                        <?php if (isset($schedule) && is_array($schedule) && !empty($schedule)): ?>
                            <?= $this->include('student/partials/schedule_card', ['schedule' => $schedule]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-play-circle"></i>
                        <h5>No Active Exams</h5>
                        <p>You don't have any active exams at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Completed Exams Tab -->
            <div class="tab-pane fade" id="completed" role="tabpanel">
                <?php
                $completedExams = !empty($schedules) ? array_filter($schedules, function($s) { return isset($s['status']) && $s['status'] === 'completed'; }) : [];
                if (!empty($completedExams)): ?>
                    <?php foreach ($completedExams as $schedule): ?>
                        <?php if (isset($schedule) && is_array($schedule) && !empty($schedule)): ?>
                            <?= $this->include('student/partials/schedule_card', ['schedule' => $schedule]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h5>No Completed Exams</h5>
                        <p>You haven't completed any exams yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Schedule page loaded');
    
    // Add smooth scrolling for tab changes
    const tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});
</script>
<?= $this->endSection() ?>
