<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .report-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        color: white;
    }
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .stats-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin: 1rem 0;
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .rank-badge {
        width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item:last-child {
        border-bottom: none !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Reports & Analytics</h4>
                <p class="text-muted mb-0">View system performance and user analytics</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">download</i>Export Data
                </button>
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (isset($error_message)): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">warning</i>
    <strong>Notice:</strong> <?= esc($error_message) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?= esc($stats['total_exams'] ?? 0) ?></div>
            <div>Total Exams Conducted</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?= esc($stats['active_students'] ?? 0) ?></div>
            <div>Active Students</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?= esc($stats['total_teachers'] ?? 0) ?></div>
            <div>Total Teachers</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?= esc($stats['average_pass_rate'] ?? 0) ?>%</div>
            <div>Average Pass Rate</div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row">
    <!-- Exam Reports -->
    <div class="col-lg-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">quiz</i>
                    Exam Reports
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Generate detailed reports on exam performance and statistics</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('reports/exam-performance') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">assessment</i>
                        Exam Performance Report
                    </a>
                    <a href="<?= base_url('reports/grade-distribution') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">trending_up</i>
                        Grade Distribution
                    </a>
                    <a href="<?= base_url('reports/exam-schedule') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">schedule</i>
                        Exam Schedule Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Reports -->
    <div class="col-lg-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">school</i>
                    Student Reports
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Track student progress and performance metrics</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('reports/student-report') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">person</i>
                        Individual Student Report
                    </a>
                    <a href="<?= base_url('reports/class-performance') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">groups</i>
                        Class Performance Report
                    </a>
                    <a href="<?= base_url('reports/attendance') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">login</i>
                        Attendance Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Reports -->
    <div class="col-lg-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">computer</i>
                    System Reports
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Monitor system usage and performance</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('reports/usage-analytics') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">analytics</i>
                        Usage Analytics
                    </a>
                    <a href="<?= base_url('reports/security-audit') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">security</i>
                        Security Audit Log
                    </a>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card report-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">timeline</i>
                    Recent Exam Activity
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_activity)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Exam</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_activity as $activity): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    <?= strtoupper(substr($activity['first_name'], 0, 1)) ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium"><?= esc($activity['first_name'] . ' ' . $activity['last_name']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($activity['exam_title']) ?></td>
                                    <td>
                                        <span class="badge <?= $activity['percentage'] >= 50 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= esc($activity['percentage']) ?>%
                                        </span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($activity['created_at'])) ?></td>
                                    <td>
                                        <span class="badge <?= $activity['status'] === 'completed' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= ucfirst(esc($activity['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-muted">
                            <i class="material-symbols-rounded mb-3" style="font-size: 48px;">bar_chart</i>
                            <p>No exam data available yet</p>
                            <small>Recent exam activity will appear here once exams are conducted</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-lg-4 mb-4">
        <div class="card report-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">emoji_events</i>
                    Top Performers
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($top_performers)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($top_performers as $index => $performer): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="rank-badge me-3">
                                    <?php if ($index === 0): ?>
                                        <i class="material-symbols-rounded text-warning" style="font-size: 24px;">trophy</i>
                                    <?php elseif ($index === 1): ?>
                                        <i class="material-symbols-rounded text-secondary" style="font-size: 20px;">military_tech</i>
                                    <?php elseif ($index === 2): ?>
                                        <i class="material-symbols-rounded text-warning" style="font-size: 18px;">workspace_premium</i>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark"><?= $index + 1 ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-medium"><?= esc($performer['first_name'] . ' ' . $performer['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($performer['exam_title']) ?></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success"><?= esc($performer['percentage']) ?>%</div>
                                <small class="text-muted"><?= date('M j', strtotime($performer['created_at'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="material-symbols-rounded mb-3" style="font-size: 48px;">leaderboard</i>
                        <p>No performance data available</p>
                        <small>Top performers will be listed here</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row">
    <div class="col-12">
        <div class="card report-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">file_download</i>
                    Export Options
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-success w-100" onclick="exportReport('excel')">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">table_view</i>
                            Export to Excel
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-danger w-100" onclick="exportReport('pdf')">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">picture_as_pdf</i>
                            Export to PDF
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-info w-100" onclick="exportReport('csv')">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">code</i>
                            Export to CSV
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-warning w-100" onclick="printReport()">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">print</i>
                            Print Report
                        </button>
                    </div>
                </div>
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

    // Add loading states to report buttons
    const reportButtons = document.querySelectorAll('.btn-outline-primary');
    reportButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.tagName === 'A') {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="material-symbols-rounded me-2">hourglass_empty</i>Loading...';

                // Reset after 3 seconds (fallback)
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 3000);
            }
        });
    });

    console.log('Reports page loaded - all buttons are now functional');
});

// Export report function
function exportReport(format) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;

    // Show loading state
    button.innerHTML = '<i class="material-symbols-rounded me-2">hourglass_empty</i>Exporting...';
    button.disabled = true;

    // Simulate export process
    setTimeout(() => {
        // Create download link
        const link = document.createElement('a');
        link.href = `<?= base_url('reports/export/') ?>${format}/general`;
        link.download = `report_${new Date().toISOString().split('T')[0]}.${format}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;

        // Show success message
        showNotification('Export Started', `Your ${format.toUpperCase()} report is being prepared for download.`, 'success');
    }, 1500);
}

// Print report function
function printReport() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;

    // Show loading state
    button.innerHTML = '<i class="material-symbols-rounded me-2">hourglass_empty</i>Preparing...';
    button.disabled = true;

    // Open print dialog
    setTimeout(() => {
        window.print();

        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    }, 500);
}

// Notification function
function showNotification(title, message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <strong>${title}</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
<?= $this->endSection() ?>
