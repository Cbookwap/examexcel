<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1" style="color: #1f2937; font-weight: 600;"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="refreshSystemInfo()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- System Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">PHP Version</p>
                        <h5 class="mb-0 fw-bold"><?= $systemInfo['php_version'] ?></h5>
                    </div>
                    <div class="icon-shape bg-primary text-white rounded-circle">
                        <i class="fab fa-php"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">CodeIgniter</p>
                        <h5 class="mb-0 fw-bold"><?= $systemInfo['codeigniter_version'] ?></h5>
                    </div>
                    <div class="icon-shape bg-success text-white rounded-circle">
                        <i class="fas fa-fire"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Database Size</p>
                        <h5 class="mb-0 fw-bold"><?= $systemInfo['database_size'] ?></h5>
                    </div>
                    <div class="icon-shape bg-warning text-white rounded-circle">
                        <i class="fas fa-database"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Disk Usage</p>
                        <h5 class="mb-0 fw-bold"><?= $systemInfo['disk_space']['used_percentage'] ?>%</h5>
                    </div>
                    <div class="icon-shape bg-info text-white rounded-circle">
                        <i class="fas fa-hdd"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Information Details -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-server me-2"></i>Server Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Server Software:</span>
                            <span class="fw-medium"><?= esc($systemInfo['server_software']) ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Database Version:</span>
                            <span class="fw-medium"><?= esc($systemInfo['database_version']) ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Memory Limit:</span>
                            <span class="fw-medium"><?= $systemInfo['memory_limit'] ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Max Execution Time:</span>
                            <span class="fw-medium"><?= $systemInfo['max_execution_time'] ?>s</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Upload Max Size:</span>
                            <span class="fw-medium"><?= $systemInfo['upload_max_filesize'] ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Post Max Size:</span>
                            <span class="fw-medium"><?= $systemInfo['post_max_size'] ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Timezone:</span>
                            <span class="fw-medium"><?= $systemInfo['timezone'] ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Current Time:</span>
                            <span class="fw-medium"><?= $systemInfo['current_time'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-chart-bar me-2"></i>Performance Metrics
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Users:</span>
                            <span class="fw-medium"><?= number_format($performanceMetrics['total_users']) ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Exams:</span>
                            <span class="fw-medium"><?= number_format($performanceMetrics['total_exams']) ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Questions:</span>
                            <span class="fw-medium"><?= number_format($performanceMetrics['total_questions']) ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Attempts:</span>
                            <span class="fw-medium"><?= number_format($performanceMetrics['total_attempts']) ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Database Tables:</span>
                            <span class="fw-medium"><?= $performanceMetrics['database_tables'] ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Cache Status:</span>
                            <span class="fw-medium">
                                <span class="badge <?= $performanceMetrics['cache_status'] === 'Enabled' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= $performanceMetrics['cache_status'] ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Storage Information -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-hdd me-2"></i>Storage Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h5 class="mb-1"><?= $systemInfo['disk_space']['free'] ?></h5>
                            <p class="text-muted small mb-0">Free Space</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h5 class="mb-1"><?= $systemInfo['disk_space']['total'] ?></h5>
                            <p class="text-muted small mb-0">Total Space</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h5 class="mb-1"><?= $systemInfo['disk_space']['used_percentage'] ?>%</h5>
                            <p class="text-muted small mb-0">Used</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar <?= $systemInfo['disk_space']['used_percentage'] > 80 ? 'bg-danger' : ($systemInfo['disk_space']['used_percentage'] > 60 ? 'bg-warning' : 'bg-success') ?>"
                             role="progressbar"
                             style="width: <?= $systemInfo['disk_space']['used_percentage'] ?>%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
function refreshSystemInfo() {
    location.reload();
}
</script>

<?= $this->endSection() ?>
