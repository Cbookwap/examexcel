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

.report-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    overflow: hidden;
    transition: all 0.3s ease;
}

.report-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
    background-color: transparent;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    color: white;
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    transform: translateY(-1px);
}

.chart-placeholder {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    color: #6c757d;
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .report-card {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;">Reports & Analytics</h4>
                <p class="text-light mb-0">View school performance and analytics</p>
            </div>
            <a href="<?= base_url('principal/dashboard') ?>" class="btn btn-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Exams</h6>
                    <h3 class="mb-0"><?= isset($examStats['total_exams']) ? number_format($examStats['total_exams']) : '0' ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">quiz</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Total Attempts</h6>
                    <h3 class="mb-0"><?= isset($examStats['total_attempts']) ? number_format($examStats['total_attempts']) : '0' ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">assignment</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Average Mark</h6>
                    <h3 class="mb-0"><?= isset($examStats['average_score']) ? number_format($examStats['average_score'], 1) . '%' : '0%' ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">trending_up</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1">Active Classes</h6>
                    <h3 class="mb-0"><?= isset($classPerformance) ? count($classPerformance) : '0' ?></h3>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">school</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row">
    <!-- Student Performance -->
    <div class="col-lg-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">school</i>
                    Student Performance
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Track student progress and academic performance</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('reports/class-performance') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">groups</i>
                        Class Performance Report
                    </a>
                    <a href="<?= base_url('reports/student-report') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">person</i>
                        Individual Student Report
                    </a>
                    <a href="<?= base_url('reports/grade-distribution') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">bar_chart</i>
                        Grade Distribution
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Analytics -->
    <div class="col-lg-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">quiz</i>
                    Exam Analytics
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Analyze exam performance and statistics</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('reports/exam-performance') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">assessment</i>
                        Exam Performance Report
                    </a>
                    <a href="<?= base_url('reports/exam-schedule') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">schedule</i>
                        Exam Schedule Report
                    </a>
                    <a href="<?= base_url('reports/usage-analytics') ?>" class="btn btn-outline-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">analytics</i>
                        System Usage Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Overview -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card report-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">timeline</i>
                    Performance Trends
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-placeholder">
                    <i class="material-symbols-rounded mb-3" style="font-size: 48px;">trending_up</i>
                    <h6>Performance Analytics</h6>
                    <p class="mb-0">Performance trends and analytics will be displayed here once sufficient exam data is available.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Classes -->
    <div class="col-lg-4 mb-4">
        <div class="card report-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">emoji_events</i>
                    Top Performing Classes
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($classPerformance)): ?>
                    <?php foreach (array_slice($classPerformance, 0, 5) as $class): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium"><?= esc($class['class_name']) ?></span>
                            <span class="badge bg-primary"><?= number_format($class['avg_score'], 1) ?>%</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="material-symbols-rounded mb-2" style="font-size: 32px;">leaderboard</i>
                        <p class="mb-0">No performance data available yet</p>
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
                    Export Reports
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
        link.href = `<?= base_url('reports/export/') ?>${format}/principal`;
        link.download = `principal_report_${new Date().toISOString().split('T')[0]}.${format}`;
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
