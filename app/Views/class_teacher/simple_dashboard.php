<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="page-content-wrapper">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                    <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                <h5 class="fw-bold mb-2">
                    <i class="fas fa-check-circle me-2"></i>
                    Class Teacher Dashboard is Working!
                </h5>
                <p class="mb-0">
                    This simple dashboard loaded successfully. The issue was likely with the complex data loading 
                    in the original dashboard or with the authentication filter.
                </p>
            </div>
        </div>
    </div>

    <!-- Test Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Test Results</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Class Teacher Controller</span>
                            <span class="badge bg-success">Working</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Class Teacher Routes</span>
                            <span class="badge bg-success">Working</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Class Teacher Views</span>
                            <span class="badge bg-success">Working</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Dashboard Layout</span>
                            <span class="badge bg-success">Working</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Next Steps
                </h6>
                <ol class="mb-0">
                    <li>Try the <a href="<?= base_url('class-teacher/dashboard') ?>">full dashboard</a> to see if it works now</li>
                    <li>Check the <a href="<?= base_url('class-teacher/debug') ?>">debug page</a> for session information</li>
                    <li>Test the <a href="<?= base_url('class-teacher/marksheet') ?>">marksheet functionality</a></li>
                    <li>If issues persist, check the browser console for JavaScript errors</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Navigation</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('class-teacher/dashboard') ?>" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Full Dashboard
                        </a>
                        <a href="<?= base_url('class-teacher/marksheet') ?>" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Marksheet
                        </a>
                        <a href="<?= base_url('class-teacher/debug') ?>" class="btn btn-warning">
                            <i class="fas fa-bug me-2"></i>Debug Info
                        </a>
                        <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
