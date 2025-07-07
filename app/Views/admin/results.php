<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        border: none;
        box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(var(--primary-color-rgb), 0.4);
    }

    .stats-card.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    .stats-card.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
    }

    .stats-card.info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    }

    .stats-card.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .filter-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .results-table-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .performance-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }

    .table th {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280 !important;
        padding: 0.75rem !important;
        border: none;
        background: #f8fafc;
    }

    .table td {
        font-size: 0.875rem !important;
        padding: 0.75rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .badge-grade {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-pass {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .badge-fail {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
    }

    .progress-bar-custom {
        border-radius: 10px;
    }

    .subject-performance-item {
        padding: 1rem;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .subject-performance-item:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .filter-form .form-control, .filter-form .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    .filter-form .form-control:focus, .filter-form .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-export {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-clear {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-clear:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(107, 114, 128, 0.3);
        color: white;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }

        .filter-form .row > div {
            margin-bottom: 1rem;
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        .stats-number {
            font-size: 2rem;
        }
    }

    /* Animation for loading states */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Pagination styling */
    .card-footer {
        border-radius: 0 0 15px 15px !important;
        background: #f8fafc !important;
        padding: 1rem 1.5rem;
    }

    .pagination-info {
        font-size: 0.875rem;
        color: #64748b;
    }

    /* Modern Pagination Styles */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.5rem 0;
    }

    .pagination-info .results-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .pagination-info .count-range {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: 0 2px 8px rgba(var(--primary-color-rgb), 0.3);
    }

    .pagination-info .total-count {
        color: var(--primary-color);
        font-weight: 700;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    /* Per page selector */
    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748b;
    }

    .per-page-selector .form-select {
        width: auto;
        min-width: 70px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .per-page-selector .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }

    /* Page navigation */
    .page-navigation {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .pagination-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s ease;
    }

    .pagination-btn:hover::before {
        left: 100%;
    }

    .pagination-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.2);
    }

    .pagination-btn.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.4);
        transform: translateY(-1px);
    }

    .pagination-btn.active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(var(--primary-color-rgb), 0.5);
    }

    .pagination-btn:disabled {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #cbd5e1;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .pagination-btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .pagination-btn i {
        font-size: 1.2rem;
    }

    .page-numbers {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .pagination-ellipsis {
        color: #94a3b8;
        font-weight: 600;
        padding: 0 0.5rem;
        font-size: 1.2rem;
    }

    /* Page input */
    .page-input {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748b;
    }

    .page-input .form-control {
        width: 60px;
        text-align: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .page-input .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }

    /* Loading overlay */
    .table-loading {
        position: relative;
    }

    .table-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f4f6;
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .pagination-controls {
            flex-direction: column;
            gap: 1rem;
        }

        .page-navigation {
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-btn {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }

        .per-page-selector,
        .page-input {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .pagination-btn {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        .pagination-btn i {
            font-size: 1rem;
        }

        .page-numbers {
            gap: 0.125rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row">
    <div class="ms-3">
        <h3 class="mb-0 font-weight-bolder"><?= $pageTitle ?></h3>
        <p class="mb-4"><?= $pageSubtitle ?></p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Total Attempts</div>
                    <div class="stats-number"><?= number_format($stats['total_attempts']) ?></div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">assignment_turned_in</i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Pass Rate</div>
                    <div class="stats-number"><?= $stats['pass_rate'] ?>%</div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">trending_up</i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Average Mark</div>
                    <div class="stats-number"><?= $stats['average_percentage'] ?>%</div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">analytics</i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-label">Total Students</div>
                    <div class="stats-number"><?= number_format($stats['total_students']) ?></div>
                </div>
                <div class="stats-icon">
                    <i class="material-symbols-rounded">school</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <div class="card-header-custom">
        <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
            <i class="material-symbols-rounded me-2" style="color: #667eea;">filter_list</i>
            Filter Results
        </h5>
        <p class="text-muted small mb-0">Filter exam results by various criteria</p>
    </div>
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $filters['class_id'] == $class['id'] ? 'selected' : '' ?>>
                                <?php
                                // Display class name with section if available
                                $displayName = esc($class['name']);
                                if (!empty($class['section']) && $class['section'] !== 'A') {
                                    $displayName .= ' - ' . esc($class['section']);
                                }
                                echo $displayName;
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select">
                        <option value="">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>" <?= $filters['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                                <?= esc($subject['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="<?= $filters['date_from'] ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="<?= $filters['date_to'] ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-filter">
                            <i class="material-symbols-rounded me-1">search</i>Filter
                        </button>
                    </div>
                </div>

                <!-- Hidden input to reset pagination when filtering -->
                <input type="hidden" name="page" value="1">
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('admin/results') ?>" class="btn btn-clear btn-sm">
                            <i class="material-symbols-rounded me-1">clear</i>Clear Filters
                        </a>
                        <button type="button" class="btn btn-export btn-sm" onclick="exportResults()">
                            <i class="material-symbols-rounded me-1">download</i>Export Results
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Performance Analytics -->
<div class="row mb-4">
    <!-- Subject Performance -->
    <div class="col-lg-6 mb-4">
        <div class="performance-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #667eea;">subject</i>
                    Subject Performance
                </h5>
                <p class="text-muted small mb-0">Performance breakdown by subject</p>
            </div>
            <div class="card-body">
                <?php if (!empty($subjectPerformance)): ?>
                    <?php foreach ($subjectPerformance as $subject => $performance): ?>
                        <?php
                        // Ensure all required keys exist with default values
                        $passRate = $performance['pass_rate'] ?? 0;
                        $totalAttempts = $performance['total_attempts'] ?? 0;
                        $averagePercentage = $performance['average_percentage'] ?? 0;
                        $passed = $performance['passed'] ?? 0;
                        ?>
                        <div class="subject-performance-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-semibold"><?= esc($subject) ?></h6>
                                <span class="badge <?= $passRate >= 70 ? 'badge-pass' : 'badge-fail' ?>">
                                    <?= $passRate ?>% Pass Rate
                                </span>
                            </div>
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Attempts</small>
                                    <div class="fw-semibold"><?= $totalAttempts ?></div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Avg Mark</small>
                                    <div class="fw-semibold"><?= $averagePercentage ?>%</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Passed</small>
                                    <div class="fw-semibold"><?= $passed ?></div>
                                </div>
                            </div>
                            <div class="progress progress-custom mt-2">
                                <div class="progress-bar progress-bar-custom <?= $passRate >= 70 ? 'bg-success' : 'bg-warning' ?>"
                                     style="width: <?= $passRate ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 3rem;">analytics</i>
                        <p class="text-muted">No performance data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-lg-6 mb-4">
        <div class="performance-card">
            <div class="card-header-custom">
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #667eea;">emoji_events</i>
                    Top Performers
                </h5>
                <p class="text-muted small mb-0">Highest scoring students</p>
            </div>
            <div class="card-body">
                <?php if (!empty($topPerformers)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <?php foreach (array_slice($topPerformers, 0, 10) as $index => $performer): ?>
                                    <tr>
                                        <td>
                                            <span class="badge <?= $index < 3 ? 'badge-pass' : 'bg-light text-dark' ?>">
                                                #<?= $index + 1 ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?= esc($performer['first_name'] . ' ' . $performer['last_name']) ?></div>
                                            <small class="text-muted"><?= esc($performer['student_id']) ?></small>
                                        </td>
                                        <td><?= esc($performer['subject_name'] ?? ($performer['exam_mode'] === 'multi_subject' ? 'Multi-Subject' : 'Unknown Subject')) ?></td>
                                        <td>
                                            <span class="fw-semibold text-success"><?= $performer['percentage'] ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 3rem;">emoji_events</i>
                        <p class="text-muted">No performance data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Results Table -->
<div class="results-table-card">
    <div class="card-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                    <i class="material-symbols-rounded me-2" style="color: #667eea;">table_view</i>
                    Exam Results
                </h5>
                <p class="text-muted small mb-0">
                    Showing <?= count($attempts) ?> of <?= $stats['total_attempts'] ?> results
                    <?php if (isset($pager)): ?>
                        (Page <?= $currentPage ?> of <?= $pager->getPageCount() ?>)
                    <?php endif; ?>
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" onclick="bulkDeleteResults()" style="display: none;">
                    <i class="material-symbols-rounded me-1">delete</i>Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <button class="btn btn-outline-primary btn-sm" onclick="refreshResults()">
                    <i class="material-symbols-rounded me-1">refresh</i>Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($attempts)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    <label class="form-check-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="mainResultsTableBody">
                        <?php foreach ($attempts as $attempt): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input result-checkbox" type="checkbox" value="<?= $attempt['id'] ?>" onchange="updateBulkDeleteButton()">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium"><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($attempt['student_id']) ?></small>
                                </td>
                                <td>
                                    <div class="fw-medium"><?= esc($attempt['exam_title']) ?></div>
                                    <small class="text-muted"><?= $attempt['total_questions'] ?> questions</small>
                                </td>
                                <td><?= esc($attempt['subject_name'] ?? ($attempt['exam_mode'] === 'multi_subject' ? 'Multi-Subject' : 'Unknown Subject')) ?></td>
                                <td><?= esc($attempt['class_name']) ?></td>
                                <td>
                                    <?php
                                    $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
                                    $totalMarks = $attempt['total_marks'] ?? $attempt['total_points'] ?? 0;
                                    ?>
                                    <span class="fw-semibold"><?= $studentScore ?></span>
                                    <small class="text-muted">/ <?= $totalMarks ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold me-2"><?= $attempt['percentage'] ?>%</span>
                                        <div class="progress progress-custom" style="width: 60px;">
                                            <div class="progress-bar progress-bar-custom <?= $attempt['percentage'] >= 70 ? 'bg-success' : ($attempt['percentage'] >= 50 ? 'bg-warning' : 'bg-danger') ?>"
                                                 style="width: <?= $attempt['percentage'] ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
                                    $passingThreshold = $attempt['passing_marks'] ?? $attempt['passing_score'] ?? 0;
                                    $isPassed = $studentScore >= $passingThreshold;
                                    ?>
                                    <span class="badge-grade <?= $isPassed ? 'badge-pass' : 'badge-fail' ?>">
                                        <?= $isPassed ? 'PASS' : 'FAIL' ?>
                                    </span>
                                </td>
                                <td>
                                    <div><?= date('M j, Y', strtotime($attempt['submitted_at'])) ?></div>
                                    <small class="text-muted"><?= date('g:i A', strtotime($attempt['submitted_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="View Details"
                                                onclick="viewAttemptDetails(<?= $attempt['id'] ?>)">
                                            <i class="material-symbols-rounded">visibility</i>
                                        </button>
                                        <button class="btn btn-outline-info" title="Download Report"
                                                onclick="downloadReport(<?= $attempt['id'] ?>)">
                                            <i class="material-symbols-rounded">download</i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Delete Result"
                                                onclick="deleteResult(<?= $attempt['id'] ?>, '<?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?>', '<?= esc($attempt['exam_title']) ?>')">
                                            <i class="material-symbols-rounded">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modern Pagination Controls -->
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                <div class="card-footer border-0 bg-transparent">
                    <div class="pagination-container">
                        <!-- Results Info -->
                        <div class="pagination-info">
                            <div class="results-count">
                                <span class="showing-text">Showing</span>
                                <span class="count-range">
                                    <?= ($currentPage - 1) * $perPage + 1 ?> - <?= min($currentPage * $perPage, $stats['total_attempts']) ?>
                                </span>
                                <span class="of-text">of</span>
                                <span class="total-count"><?= number_format($stats['total_attempts']) ?></span>
                                <span class="results-text">results</span>
                            </div>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="pagination-controls">
                            <!-- Items per page selector -->
                            <div class="per-page-selector">
                                <label for="perPageSelect">Show:</label>
                                <select id="perPageSelect" class="form-select form-select-sm" onchange="changePerPage(this.value)">
                                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                    <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
                                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                                    <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                                </select>
                            </div>

                            <!-- Page navigation -->
                            <div class="page-navigation">
                                <!-- First page -->
                                <button class="pagination-btn pagination-btn-first"
                                        onclick="loadPage(1)"
                                        <?= $currentPage <= 1 ? 'disabled' : '' ?>
                                        title="First page">
                                    <i class="bi bi-chevron-double-left"></i>
                                </button>

                                <!-- Previous page -->
                                <button class="pagination-btn pagination-btn-prev"
                                        onclick="loadPage(<?= max(1, $currentPage - 1) ?>)"
                                        <?= $currentPage <= 1 ? 'disabled' : '' ?>
                                        title="Previous page">
                                    <i class="bi bi-chevron-left"></i>
                                </button>

                                <!-- Page numbers -->
                                <div class="page-numbers">
                                    <?php
                                    $totalPages = $pager->getPageCount();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($totalPages, $currentPage + 2);

                                    // Show first page if not in range
                                    if ($start > 1): ?>
                                        <button class="pagination-btn page-number" onclick="loadPage(1)">1</button>
                                        <?php if ($start > 2): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <!-- Page number buttons -->
                                    <?php for ($i = $start; $i <= $end; $i++): ?>
                                        <button class="pagination-btn page-number <?= $i == $currentPage ? 'active' : '' ?>"
                                                onclick="loadPage(<?= $i ?>)"><?= $i ?></button>
                                    <?php endfor; ?>

                                    <!-- Show last page if not in range -->
                                    <?php if ($end < $totalPages): ?>
                                        <?php if ($end < $totalPages - 1): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                        <button class="pagination-btn page-number" onclick="loadPage(<?= $totalPages ?>)"><?= $totalPages ?></button>
                                    <?php endif; ?>
                                </div>

                                <!-- Next page -->
                                <button class="pagination-btn pagination-btn-next"
                                        onclick="loadPage(<?= min($totalPages, $currentPage + 1) ?>)"
                                        <?= $currentPage >= $totalPages ? 'disabled' : '' ?>
                                        title="Next page">
                                    <i class="bi bi-chevron-right"></i>
                                </button>

                                <!-- Last page -->
                                <button class="pagination-btn pagination-btn-last"
                                        onclick="loadPage(<?= $totalPages ?>)"
                                        <?= $currentPage >= $totalPages ? 'disabled' : '' ?>
                                        title="Last page">
                                    <i class="bi bi-chevron-double-right"></i>
                                </button>
                            </div>

                            <!-- Page input -->
                            <div class="page-input">
                                <span>Go to:</span>
                                <input type="number"
                                       id="pageInput"
                                       class="form-control form-control-sm"
                                       min="1"
                                       max="<?= $totalPages ?>"
                                       value="<?= $currentPage ?>"
                                       onkeypress="handlePageInput(event)">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="material-symbols-rounded text-muted mb-3" style="font-size: 4rem;">assignment</i>
                <h5 class="text-muted">No Results Found</h5>
                <p class="text-muted">No exam results match your current filters.</p>
                <a href="<?= base_url('admin/results') ?>" class="btn btn-outline-primary">
                    <i class="material-symbols-rounded me-1">refresh</i>Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Global variables for pagination
let currentPage = <?= $currentPage ?>;
let totalPages = <?= isset($pager) ? $pager->getPageCount() : 1 ?>;
let isLoading = false;

// AJAX Pagination Functions
function loadPage(page) {
    if (isLoading || page < 1 || page > totalPages) return;

    isLoading = true;
    showLoading();

    // Get current filter values
    const filters = getCurrentFilters();
    filters.page = page;

    // Build URL with filters
    const url = '<?= base_url('admin/results') ?>?' + new URLSearchParams(filters).toString();

    // Use AJAX for smooth loading
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        // Create a temporary container to parse the HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;

        // Find the new table body
        const newTableBody = tempDiv.querySelector('#mainResultsTableBody');
        const newPagination = tempDiv.querySelector('.pagination-container');

        // Update table content if found
        const currentTableBody = document.getElementById('mainResultsTableBody');
        if (newTableBody && currentTableBody) {
            currentTableBody.innerHTML = newTableBody.innerHTML;
            console.log('Table content updated successfully');
        } else {
            console.log('Table update failed - newTableBody:', !!newTableBody, 'currentTableBody:', !!currentTableBody);
        }

        // Update pagination if found
        const currentPagination = document.querySelector('.pagination-container');
        if (newPagination && currentPagination) {
            currentPagination.outerHTML = newPagination.outerHTML;
            console.log('Pagination updated successfully');
        } else {
            console.log('Pagination update failed - newPagination:', !!newPagination, 'currentPagination:', !!currentPagination);
        }

        // Update current page
        currentPage = page;

        // Update URL without page reload
        window.history.pushState({page: page}, '', url);

        // Scroll to top of table smoothly
        const tableCard = document.querySelector('.card');
        if (tableCard) {
            tableCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        hideLoading();
        isLoading = false;

        // Show brief success notification (optional - can be removed for cleaner UX)
        // showNotification('Page ' + page + ' loaded', 'success');
    })
    .catch(error => {
        console.error('Error loading page:', error);

        // Fallback to regular page navigation
        window.location.href = url;

        hideLoading();
        isLoading = false;
    });
}

function changePerPage(perPage) {
    if (isLoading) return;

    showLoading();

    const filters = getCurrentFilters();
    filters.per_page = perPage;
    filters.page = 1; // Reset to first page

    const url = '<?= base_url('admin/results') ?>?' + new URLSearchParams(filters).toString();
    window.location.href = url;
}

function handlePageInput(event) {
    if (event.key === 'Enter') {
        const page = parseInt(event.target.value);
        if (page >= 1 && page <= totalPages) {
            loadPage(page);
        } else {
            event.target.value = currentPage;
            showNotification('Please enter a valid page number (1-' + totalPages + ').', 'warning');
        }
    }
}

function getCurrentFilters() {
    return {
        class_id: document.getElementById('classFilter')?.value || '',
        subject_id: document.getElementById('subjectFilter')?.value || '',
        date_from: document.getElementById('dateFrom')?.value || '',
        date_to: document.getElementById('dateTo')?.value || '',
        status: document.getElementById('statusFilter')?.value || '',
        per_page: document.getElementById('perPageSelect')?.value || '20'
    };
}

function showLoading() {
    const tableContainer = document.querySelector('.table-responsive');
    if (!tableContainer) return;

    tableContainer.style.position = 'relative';

    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'loading-overlay';
    loadingDiv.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(2px);
    `;
    loadingDiv.innerHTML = `
        <div class="loading-spinner" style="
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        "></div>
    `;

    tableContainer.appendChild(loadingDiv);
}

function hideLoading() {
    const loadingOverlay = document.querySelector('.loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

// Enhanced filter function with AJAX
function applyFilters() {
    if (isLoading) return;

    const filters = getCurrentFilters();
    filters.page = 1; // Reset to first page when filtering

    loadPage(1);
}

// Refresh results function
function refreshResults() {
    window.location.reload();
}

// Export results function
function exportResults() {
    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    const exportUrl = '<?= base_url("admin/results/export") ?>?' + urlParams.toString();

    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'exam_results_' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Show success message
    showNotification('Export started', 'Your results export is being prepared for download.', 'success');
}

// View attempt details function
function viewAttemptDetails(attemptId) {
    // Create modal or redirect to details page
    window.open('<?= base_url("admin/results/view/") ?>' + attemptId, '_blank');
}

// Download individual report function
function downloadReport(attemptId) {
    const downloadUrl = '<?= base_url("admin/results/download/") ?>' + attemptId;

    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = 'exam_report_' + attemptId + '.pdf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showNotification('Download started', 'Individual exam report is being prepared.', 'info');
}

// Delete result function
function deleteResult(attemptId, studentName, examTitle) {
    // Create confirmation modal
    const modalHtml = `
        <div class="modal fade" id="deleteResultModal" tabindex="-1" aria-labelledby="deleteResultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteResultModalLabel">
                            <i class="material-symbols-rounded me-2">warning</i>
                            Confirm Delete Result
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="material-symbols-rounded text-danger mb-3" style="font-size: 4rem;">delete_forever</i>
                        </div>
                        <p class="text-center mb-3">
                            Are you sure you want to delete this exam result?
                        </p>
                        <div class="alert alert-warning">
                            <strong>Student:</strong> ${studentName}<br>
                            <strong>Exam:</strong> ${examTitle}<br>
                            <strong>Warning:</strong> This action cannot be undone!
                        </div>
                        <p class="text-muted small text-center">
                            Deleting this result will permanently remove all associated data including answers and performance metrics.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="material-symbols-rounded me-1">cancel</i>Cancel
                        </button>
                        <button type="button" class="btn btn-danger" onclick="confirmDeleteResult(${attemptId})">
                            <i class="material-symbols-rounded me-1">delete_forever</i>Delete Result
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('deleteResultModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteResultModal'));
    modal.show();

    // Clean up modal after it's hidden
    document.getElementById('deleteResultModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Confirm delete result function
function confirmDeleteResult(attemptId) {
    const deleteButton = document.querySelector('#deleteResultModal .btn-danger');
    const originalText = deleteButton.innerHTML;

    // Show loading state
    deleteButton.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Deleting...';
    deleteButton.disabled = true;

    // Send delete request
    fetch('<?= base_url("admin/results/delete/") ?>' + attemptId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            _token: '<?= csrf_token() ?>',
            confirm: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteResultModal'));
            modal.hide();

            // Show success message
            showNotification('Success', 'Exam result has been deleted successfully.', 'success');

            // Refresh the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error message
            showNotification('Error', data.message || 'Failed to delete exam result.', 'danger');

            // Reset button
            deleteButton.innerHTML = originalText;
            deleteButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error', 'An error occurred while deleting the result.', 'danger');

        // Reset button
        deleteButton.innerHTML = originalText;
        deleteButton.disabled = false;
    });
}

// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const resultCheckboxes = document.querySelectorAll('.result-checkbox');

    resultCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });

    updateBulkDeleteButton();
}

// Update bulk delete button visibility and count
function updateBulkDeleteButton() {
    const checkedBoxes = document.querySelectorAll('.result-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');

    if (checkedBoxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
        selectedCount.textContent = checkedBoxes.length;
    } else {
        bulkDeleteBtn.style.display = 'none';
    }

    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.result-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');

    if (checkedBoxes.length === allCheckboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else if (checkedBoxes.length > 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }
}

// Bulk delete results
function bulkDeleteResults() {
    const checkedBoxes = document.querySelectorAll('.result-checkbox:checked');
    const attemptIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (attemptIds.length === 0) {
        showNotification('Warning', 'Please select at least one result to delete.', 'warning');
        return;
    }

    // Create confirmation modal
    const modalHtml = `
        <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="bulkDeleteModalLabel">
                            <i class="material-symbols-rounded me-2">warning</i>
                            Confirm Bulk Delete
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="material-symbols-rounded text-danger mb-3" style="font-size: 4rem;">delete_forever</i>
                        </div>
                        <p class="text-center mb-3">
                            Are you sure you want to delete <strong>${attemptIds.length}</strong> exam results?
                        </p>
                        <div class="alert alert-danger">
                            <strong>Warning:</strong> This action cannot be undone!<br>
                            All selected results and their associated data will be permanently deleted.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="material-symbols-rounded me-1">cancel</i>Cancel
                        </button>
                        <button type="button" class="btn btn-danger" onclick="confirmBulkDelete([${attemptIds.join(',')}])">
                            <i class="material-symbols-rounded me-1">delete_forever</i>Delete ${attemptIds.length} Results
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('bulkDeleteModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
    modal.show();

    // Clean up modal after it's hidden
    document.getElementById('bulkDeleteModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Confirm bulk delete
function confirmBulkDelete(attemptIds) {
    const deleteButton = document.querySelector('#bulkDeleteModal .btn-danger');
    const originalText = deleteButton.innerHTML;

    // Show loading state
    deleteButton.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Deleting...';
    deleteButton.disabled = true;

    // Send bulk delete request
    fetch('<?= base_url("admin/results/bulk-delete") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            attempt_ids: attemptIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('bulkDeleteModal'));
            modal.hide();

            // Show success message
            showNotification('Success', `${data.deleted_count} exam results have been deleted successfully.`, 'success');

            // Refresh the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error message
            showNotification('Error', data.message || 'Failed to delete exam results.', 'danger');

            // Reset button
            deleteButton.innerHTML = originalText;
            deleteButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error', 'An error occurred while deleting the results.', 'danger');

        // Reset button
        deleteButton.innerHTML = originalText;
        deleteButton.disabled = false;
    });
}

// Show notification function (enhanced for pagination)
function showNotification(message, type = 'info', title = null) {
    // Handle different parameter formats
    if (typeof message === 'string' && typeof type === 'string' && typeof title === 'string') {
        // Old format: showNotification(title, message, type)
        const temp = message;
        message = type;
        type = title;
        title = temp;
    }

    // Create notification element
    const notification = document.createElement('div');
    const alertType = type === 'error' ? 'danger' : type;
    notification.className = `alert alert-${alertType} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';

    const content = title ? `<strong>${title}</strong><br>${message}` : message;
    notification.innerHTML = `
        ${content}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds for pagination, 5 seconds for others
    const timeout = type === 'info' ? 3000 : 5000;
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, timeout);
}

// Handle pagination with filters
function updatePaginationLinks() {
    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);

    // Update all pagination links to include current filters
    document.querySelectorAll('.pagination .page-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes('?')) {
            const url = new URL(href, window.location.origin);

            // Preserve current filters
            ['class_id', 'subject_id', 'exam_id', 'date_from', 'date_to'].forEach(param => {
                if (urlParams.has(param)) {
                    url.searchParams.set(param, urlParams.get(param));
                }
            });

            link.setAttribute('href', url.toString());
        }
    });
}

// Initialize tooltips and pagination
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Update pagination links with current filters
    updatePaginationLinks();

    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn-filter, .btn-export, .btn-clear');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Loading...';
                this.disabled = true;

                // Re-enable after 3 seconds (fallback)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });

    // Add smooth scrolling to anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation to stats cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe stats cards
    document.querySelectorAll('.stats-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Add real-time search functionality to tables
    const searchInput = document.querySelector('#tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.table tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

// Handle responsive table on mobile
function handleResponsiveTable() {
    const tables = document.querySelectorAll('.table-responsive table');

    tables.forEach(table => {
        if (window.innerWidth < 768) {
            // Add mobile-friendly classes
            table.classList.add('table-sm');
        } else {
            table.classList.remove('table-sm');
        }
    });
}

// Call on load and resize
window.addEventListener('load', handleResponsiveTable);
window.addEventListener('resize', handleResponsiveTable);

// Add print functionality
function printResults() {
    window.print();
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R for refresh
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshResults();
    }

    // Ctrl/Cmd + E for export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        exportResults();
    }

    // Ctrl/Cmd + P for print
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printResults();
    }
});
</script>
<?= $this->endSection() ?>
