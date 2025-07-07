<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
            <p class="mb-0 text-muted"><?= $pageSubtitle ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('student/results') ?>" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-chart-line"></i> View Results
            </a>
            <a href="<?= base_url('student/academic-history') ?>" class="btn btn-outline-info btn-sm">
                <i class="fas fa-history"></i> Academic History
            </a>
        </div>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row mb-4">
        <!-- Exam Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Exams Taken
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $examStats['total_exams'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Score -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Average Score
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $examStats['average_score'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Score -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Best Score
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $examStats['best_score'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pass Rate -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pass Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $examStats['pass_rate'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Practice Statistics Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Practice Sessions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $practiceStats['total_practices'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumbbell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Practice Average
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $practiceStats['average_score'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Practice Best
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $practiceStats['best_score'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                This Week
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $practiceStats['this_week'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Performance Row -->
    <div class="row">
        <!-- Subject Performance -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Subject Performance</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($subjectPerformance)): ?>
                        <?php foreach ($subjectPerformance as $subject): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-sm font-weight-bold"><?= esc($subject['subject_name']) ?></span>
                                    <span class="text-sm text-muted"><?= $subject['avg_percentage'] ?>% (<?= $subject['exam_count'] ?> exams)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar 
                                        <?= $subject['avg_percentage'] >= 80 ? 'bg-success' : 
                                            ($subject['avg_percentage'] >= 60 ? 'bg-warning' : 'bg-danger') ?>" 
                                        role="progressbar" 
                                        style="width: <?= $subject['avg_percentage'] ?>%"
                                        aria-valuenow="<?= $subject['avg_percentage'] ?>" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No exam data available yet. Take some exams to see your subject performance.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Performance -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Performance</h6>
                </div>
                <div class="card-body">
                    <!-- Recent Exams -->
                    <h6 class="text-sm font-weight-bold text-gray-800 mb-2">Recent Exams</h6>
                    <?php if (!empty($recentPerformance['recent_exams'])): ?>
                        <?php foreach (array_slice($recentPerformance['recent_exams'], 0, 3) as $exam): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="text-sm font-weight-bold"><?= esc($exam['exam_title']) ?></div>
                                    <div class="text-xs text-muted"><?= date('M j, Y', strtotime($exam['created_at'])) ?></div>
                                </div>
                                <span class="badge badge-<?= $exam['percentage'] >= 60 ? 'success' : 'danger' ?> badge-pill">
                                    <?= $exam['percentage'] ?>%
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-sm">No recent exams</p>
                    <?php endif; ?>

                    <hr class="my-3">

                    <!-- Recent Practice -->
                    <h6 class="text-sm font-weight-bold text-gray-800 mb-2">Recent Practice</h6>
                    <?php if (!empty($recentPerformance['recent_practices'])): ?>
                        <?php foreach (array_slice($recentPerformance['recent_practices'], 0, 3) as $practice): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="text-sm font-weight-bold"><?= esc($practice['category']) ?></div>
                                    <div class="text-xs text-muted"><?= date('M j, Y', strtotime($practice['created_at'])) ?></div>
                                </div>
                                <span class="badge badge-<?= $practice['percentage'] >= 60 ? 'success' : 'warning' ?> badge-pill">
                                    <?= $practice['percentage'] ?>%
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-sm">No recent practice sessions</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Progress Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Progress Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="progressChart" width="100%" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="<?= base_url('assets/vendor/chartjs/chart.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Progress Chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    
    const examData = <?= json_encode($monthlyProgress['exam_progress']) ?>;
    const practiceData = <?= json_encode($monthlyProgress['practice_progress']) ?>;
    
    // Prepare chart data
    const months = [];
    const examScores = [];
    const practiceScores = [];
    
    // Get last 6 months
    for (let i = 5; i >= 0; i--) {
        const date = new Date();
        date.setMonth(date.getMonth() - i);
        const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
        const monthLabel = date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        
        months.push(monthLabel);
        
        // Find exam data for this month
        const examMonth = examData.find(item => item.month === monthKey);
        examScores.push(examMonth ? parseFloat(examMonth.avg_score) : 0);
        
        // Find practice data for this month
        const practiceMonth = practiceData.find(item => item.month === monthKey);
        practiceScores.push(practiceMonth ? parseFloat(practiceMonth.avg_score) : 0);
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Exam Average',
                data: examScores,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.1
            }, {
                label: 'Practice Average',
                data: practiceScores,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Performance Trend Over Time'
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
