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

    <!-- Error Information -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <h5 class="fw-bold mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Dashboard Loading Error
                </h5>
                <p class="mb-2">
                    There was an error loading the class teacher dashboard. This helps us identify the exact issue.
                </p>
                <p class="mb-0">
                    <strong>Error:</strong> <?= esc($error ?? 'Unknown error') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Error Details -->
    <?php if (isset($trace)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Error Details</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded" style="font-size: 12px; max-height: 300px; overflow-y: auto;"><?= esc($trace) ?></pre>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('class-teacher/simple') ?>" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Simple Dashboard (Working)
                        </a>
                        <a href="<?= base_url('class-teacher/debug') ?>" class="btn btn-info">
                            <i class="fas fa-bug me-2"></i>Debug Information
                        </a>
                        <a href="<?= base_url('admin/classes/check-database') ?>" class="btn btn-warning">
                            <i class="fas fa-database me-2"></i>Check Database
                        </a>
                        <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Troubleshooting Steps
                </h6>
                <ol class="mb-0">
                    <li>The simple dashboard works, so the basic functionality is fine</li>
                    <li>This error helps identify which specific component is failing</li>
                    <li>Common issues: Missing database tables, model errors, or data loading problems</li>
                    <li>Check the error message above for specific details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
