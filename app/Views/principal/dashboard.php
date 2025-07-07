<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<style>
.stats-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border: none;
    border-radius: 15px;
    color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
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

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.stats-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.info-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.chart-container {
    position: relative;
    height: 300px;
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">check_circle</i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <?php
                $userTitle = session()->get('title') ?? 'Principal';
                $firstName = session()->get('first_name') ?? 'User';
                ?>
                <h4 class="fw-bold mb-1" style="color: white;">Welcome, <?= esc($userTitle) ?> <?= esc($firstName) ?></h4>
                <p class="text-light mb-0"><?= esc($userTitle) ?> Dashboard - ExamExcel CBT Overview</p>
            </div>
            <div class="text-light">
                <?php
date_default_timezone_set('Africa/Lagos');   // ensure correct zone
$serverTime = time();                        // Unix timestamp (seconds)
?>

<span id="clock"></span>

<script>
let serverNow = <?= $serverTime ?> * 1000;   // ms

function updateClock() {
  const now   = new Date(serverNow);
  const date  = now.toLocaleDateString('en-US', {
                weekday: 'long', month: 'long', day: 'numeric', year: 'numeric'
              });
  const time  = now.toLocaleTimeString('en-GB');
  document.getElementById('clock').textContent = `${date} ${time}`;
  serverNow += 1000;                         // advance 1 s
}

updateClock();
setInterval(updateClock, 1000);
</script>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Students</h6>
                    <h3 class="mb-0"><?= number_format($stats['total_students']) ?></h3>
                    <small class="opacity-75"><?= number_format($stats['active_students']) ?> Active</small>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">school</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Teachers</h6>
                    <h3 class="mb-0"><?= number_format($stats['total_teachers']) ?></h3>
                    <small class="opacity-75"><?= number_format($stats['active_teachers']) ?> Active</small>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">person</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Classes</h6>
                    <h3 class="mb-0"><?= number_format($stats['total_classes']) ?></h3>
                    <small class="opacity-75"><?= number_format($stats['active_classes']) ?> Active</small>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">class</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Question Bank</h6>
                    <h3 class="mb-0"><?= number_format($stats['total_questions']) ?></h3>
                    <small class="opacity-75"><?= number_format($stats['recent_questions']) ?> This Week</small>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">help</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card info-card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 fw-bold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    helper('settings');
                    $themeSettings = get_theme_settings();
                    ?>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?= base_url('principal/students/create') ?>" class="btn btn-primary w-100 py-3" style="background: <?= $themeSettings['primary_color'] ?>; border-color: <?= $themeSettings['primary_color'] ?>;">
                            <i class="material-symbols-rounded d-block mb-2" style="font-size: 2rem;">person_add</i>
                            Add Student
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?= base_url('principal/teachers/create') ?>" class="btn w-100 py-3" style="background: <?= $themeSettings['primary_light'] ?>; border-color: <?= $themeSettings['primary_light'] ?>; color: white;">
                            <i class="material-symbols-rounded d-block mb-2" style="font-size: 2rem;">person_add</i>
                            Add Teacher
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?= base_url('principal/classes/create') ?>" class="btn w-100 py-3" style="background: <?= $themeSettings['primary_dark'] ?>; border-color: <?= $themeSettings['primary_dark'] ?>; color: white;">
                            <i class="material-symbols-rounded d-block mb-2" style="font-size: 2rem;">add</i>
                            Create Class
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?= base_url('principal/reports') ?>" class="btn w-100 py-3" style="background: linear-gradient(135deg, <?= $themeSettings['primary_color'] ?>, <?= $themeSettings['primary_light'] ?>); border: none; color: white;">
                            <i class="material-symbols-rounded d-block mb-2" style="font-size: 2rem;">analytics</i>
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card info-card h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 fw-bold" style="color: <?= $themeSettings['primary_color'] ?>;">Recent Exams</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentExams)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentExams as $exam): ?>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?= esc($exam['title']) ?></h6>
                                    <p class="mb-1 text-muted"><?= esc($exam['subject_name'] ?? 'Multiple Subjects') ?></p>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($exam['created_at'])) ?></small>
                                </div>
                                <span class="badge" style="background: <?= $themeSettings['primary_color'] ?>; color: white;"><?= $exam['duration'] ?> min</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No recent exams found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card info-card h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 fw-bold" style="color: <?= $themeSettings['primary_color'] ?>;">Recent Students</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentStudents)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentStudents as $student): ?>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: <?= $themeSettings['primary_color'] ?>;">
                                        <?= strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h6>
                                    <small class="text-muted"><?= esc($student['class_name'] ?? 'No Class') ?> • <?= esc($student['student_id']) ?></small>
                                </div>
                                <small class="text-muted"><?= date('M d', strtotime($student['created_at'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No recent students found.</p>
                <?php endif; ?>
            </div>
        </div>
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

    // Add animation to stats cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    // Observe stats cards
    document.querySelectorAll('.stats-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });


});
</script>
<?= $this->endSection() ?>
