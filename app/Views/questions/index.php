<?= $this->extend($layout ?? 'layouts/dashboard') ?>

<?= $this->section('meta') ?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .question-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .question-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
    }
    .difficulty-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }
    .difficulty-easy { background: linear-gradient(135deg, #4caf50 0%, #45a049 100%); color: white; }
    .difficulty-medium { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; }
    .difficulty-hard { background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white; }
    .btn-action {
        border-radius: 8px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        margin: 0 0.125rem;
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: transparent;
    }
    .btn-outline-primary:hover {
        color: white;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-outline-info {
        color: #17a2b8;
        border-color: #17a2b8;
        background-color: transparent;
    }
    .btn-outline-info:hover {
        color: white;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-outline-warning {
        color: #ff9800;
        border-color: #ff9800;
        background-color: transparent;
    }
    .btn-outline-warning:hover {
        color: white;
        background-color: #ff9800;
        border-color: #ff9800;
    }
    .btn-outline-danger {
        color: #f44336;
        border-color: #f44336;
        background-color: transparent;
    }
    .btn-outline-danger:hover {
        color: white;
        background-color: #f44336;
        border-color: #f44336;
    }
    .btn-group {
        display: inline-flex;
        gap: 0.25rem;
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

    /* Ensure Material Icons display properly */
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
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.2);
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
    }

    /* Sophisticated Pagination Styles */
    .pagination-container {
        position: relative;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0 0 15px 15px;
    }

    .pagination-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1000;
        border-radius: 0 0 15px 15px;
    }

    .loading-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border-radius: 0 0 15px 15px;
    }

    .loading-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: var(--primary-color);
    }

    .pagination-info {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
    }

    .per-page-selector .form-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .per-page-selector .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .per-page-selector .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }

    .pagination {
        --bs-pagination-padding-x: 0.75rem;
        --bs-pagination-padding-y: 0.5rem;
        --bs-pagination-font-size: 0.875rem;
        --bs-pagination-border-width: 2px;
        --bs-pagination-border-color: #e9ecef;
        --bs-pagination-border-radius: 8px;
        --bs-pagination-hover-color: white;
        --bs-pagination-hover-bg: var(--primary-color);
        --bs-pagination-hover-border-color: var(--primary-color);
        --bs-pagination-focus-color: white;
        --bs-pagination-focus-bg: var(--primary-color);
        --bs-pagination-focus-border-color: var(--primary-color);
        --bs-pagination-active-color: white;
        --bs-pagination-active-bg: var(--primary-color);
        --bs-pagination-active-border-color: var(--primary-color);
        --bs-pagination-disabled-color: #adb5bd;
        --bs-pagination-disabled-bg: #f8f9fa;
        --bs-pagination-disabled-border-color: #e9ecef;
    }

    .pagination .page-link {
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .pagination .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.3);
    }

    .pagination .page-link.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.4);
    }

    .pagination .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .pagination .page-link:hover::before {
        left: 100%;
    }

    .page-jump .input-group {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .page-jump .input-group-text {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .page-jump .form-control {
        border: none;
        text-align: center;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .page-jump .form-control:focus {
        box-shadow: none;
        background: #f8f9fa;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .pagination-container .row {
            flex-direction: column;
            gap: 1rem;
        }

        .pagination-container .col-md-6 {
            text-align: center;
        }

        .pagination-container .d-flex {
            justify-content: center !important;
            flex-wrap: wrap;
            gap: 0.5rem !important;
        }

        .pagination {
            --bs-pagination-padding-x: 0.5rem;
            --bs-pagination-padding-y: 0.375rem;
            --bs-pagination-font-size: 0.8rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Question Bank</h4>
                <p class="text-muted mb-0">Manage questions for exams and assessments</p>
            </div>
            <div class="d-flex gap-2">
                <?php
                // Show AI Generate button if AI generation is enabled (API key validation happens during generation)
                $showAIGenerate = ($settings['ai_generation_enabled'] ?? false);
                if ($showAIGenerate): ?>
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#aiGenerateModal">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">psychology</i>AI Generate
                </button>
                <?php endif; ?>
                <a href="<?= base_url(($route_prefix ?? '') . 'questions/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add New Question
                </a>
                <a href="<?= base_url(($route_prefix ?? '') . 'questions/bulk-create') ?>" class="btn btn-outline-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">library_add</i>Bulk Create
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Question Statistics -->
<?php if (isset($stats) && !empty($stats)): ?>
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['total'] ?? 0 ?></h3>
            <p class="mb-0">Total Questions</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['mcq'] ?? 0 ?></h3>
            <p class="mb-0">MCQ Questions</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['easy'] ?? 0 ?></h3>
            <p class="mb-0">Easy Questions</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['hard'] ?? 0 ?></h3>
            <p class="mb-0">Hard Questions</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="<?= base_url(($route_prefix ?? '') . 'questions') ?>" id="filtersForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Subject</label>
                            <select class="form-select" name="subject">
                                <option value="">All Subjects</option>
                                <?php if (isset($subjects)): ?>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?= $subject['id'] ?>" <?= ($filters['subject_id'] ?? '') == $subject['id'] ? 'selected' : '' ?>>
                                            <?= esc($subject['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Question Type</label>
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <?php if (isset($question_types)): ?>
                                    <?php foreach ($question_types as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= ($filters['question_type'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= esc($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Difficulty</label>
                            <select class="form-select" name="difficulty">
                                <option value="">All Difficulties</option>
                                <?php if (isset($difficulties)): ?>
                                    <?php foreach ($difficulties as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= ($filters['difficulty'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= esc($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Academic Session</label>
                            <select class="form-select" name="session">
                                <option value="">All Sessions</option>
                                <?php if (isset($sessions)): ?>
                                    <?php foreach ($sessions as $session): ?>
                                        <option value="<?= $session['id'] ?>" <?= ($filters['session_id'] ?? '') == $session['id'] ? 'selected' : '' ?>>
                                            <?= esc($session['session_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Academic Term</label>
                            <select class="form-select" name="term">
                                <option value="">All Terms</option>
                                <?php if (isset($terms)): ?>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?= $term['id'] ?>" <?= ($filters['term_id'] ?? '') == $term['id'] ? 'selected' : '' ?>>
                                            <?= esc($term['term_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Class</label>
                            <select class="form-select" name="class">
                                <option value="">All Classes</option>
                                <?php if (isset($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= ($filters['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>>
                                            <?= esc($class['name']) ?><?= !empty($class['section']) ? ' - ' . esc($class['section']) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       value="<?= esc($filters['search'] ?? '') ?>" placeholder="Search questions...">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="material-symbols-rounded" style="font-size: 18px;">search</i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Context</label>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">
                                    <?= $current_session['session_name'] ?? 'No Session' ?>
                                </span>
                                <span class="badge bg-info">
                                    <?= $current_term['term_name'] ?? 'No Term' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2" id="applyFiltersBtn">
                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">filter_list</i>Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clearFiltersBtn">
                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">clear</i>Clear Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Questions Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">All Questions</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="showBulkActionsModal()">
                            <i class="material-symbols-rounded me-2" style="font-size: 16px;">checklist</i>Bulk Actions
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($questions)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="questionsTable">
                            <thead>
                                <tr>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Question</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Subject</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Class</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Type</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Teacher</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Session/Term</th>
                                    <th class="border-0 fw-semibold" style="background: var(--primary-color); color: white !important; padding: 12px;">Marks</th>
                                    <th class="border-0 fw-semibold text-center" style="background: var(--primary-color); color: white !important; padding: 12px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="questionsTableBody">
                                <!-- Questions will be loaded via AJAX for better performance and to avoid duplication -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Sophisticated AJAX Pagination -->
                    <?php if (!empty($questions)): ?>
                    <div class="card-footer bg-white border-top-0">
                        <div class="pagination-container">
                            <!-- Loading Overlay -->
                            <div class="pagination-loading" id="paginationLoading" style="display: none;">
                                <div class="loading-backdrop"></div>
                                <div class="loading-content">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0">Loading questions...</p>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="pagination-info">
                                        <span class="text-muted">
                                            Showing <span id="showingStart"><?= (($current_page - 1) * $per_page) + 1 ?></span>
                                            to <span id="showingEnd"><?= min($current_page * $per_page, $total_questions) ?></span>
                                            of <span id="totalRecords"><?= $total_questions ?></span> questions
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-center gap-3">
                                        <!-- Per Page Selector -->
                                        <div class="per-page-selector">
                                            <label class="form-label mb-0 me-2">Show:</label>
                                            <select class="form-select form-select-sm" id="perPageSelect" style="width: auto;">
                                                <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
                                                <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                                                <option value="15" <?= $per_page == 15 ? 'selected' : '' ?>>15</option>
                                                <option value="25" <?= $per_page == 25 ? 'selected' : '' ?>>25</option>
                                                <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
                                                <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
                                            </select>
                                        </div>

                                        <!-- Pagination Controls -->
                                        <nav aria-label="Questions pagination">
                                            <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                                <!-- Pagination will be generated by JavaScript -->
                                            </ul>
                                        </nav>

                                        <!-- Direct Page Input -->
                                        <div class="page-jump">
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <span class="input-group-text">Page</span>
                                                <input type="number" class="form-control" id="pageJumpInput"
                                                       min="1" max="<?= ceil($total_questions / $per_page) ?>"
                                                       value="<?= $current_page ?>" style="width: 60px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">quiz</i>
                        <h6 class="text-muted">No questions found</h6>
                        <p class="text-muted small">Start by creating your first question</p>
                        <a href="<?= base_url(($route_prefix ?? '') . 'questions/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add First Question
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">warning</i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this question?</p>
                <p class="text-muted small" id="deleteQuestionText"></p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Question
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Confirmation Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicateModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">content_copy</i>
                    Confirm Duplicate
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to duplicate this question?</p>
                <p class="text-info small">A copy of this question will be created with all its options and settings.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmDuplicate">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">content_copy</i>Duplicate Question
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionsModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">checklist</i>
                    Bulk Actions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionsForm" method="POST" action="<?= base_url(($route_prefix ?? '') . 'questions/bulk-actions') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="question_ids" id="selectedQuestionIds">

                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" name="action" required>
                            <option value="">Choose action...</option>
                            <option value="delete">Delete Selected</option>
                            <option value="change_difficulty">Change Difficulty</option>
                            <option value="change_subject">Change Subject</option>
                        </select>
                    </div>

                    <div id="additionalOptions" style="display: none;">
                        <!-- Additional options will be shown based on selected action -->
                    </div>

                    <div class="alert alert-info">
                        <small><span id="selectedCount">0</span> question(s) selected</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="bulkActionsForm" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">check</i>Apply Action
                </button>
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

    // Initialize pagination
    initializePagination();

    // Initialize checkbox functionality
    initializeCheckboxes();

    // Initialize filter form
    initializeFilterForm();

    // Load questions immediately on page load to prevent duplication
    loadQuestions();
});

// Pagination variables
let currentPage = <?= $current_page ?>;
let perPage = <?= $per_page ?>;
let totalQuestions = <?= $total_questions ?>;
let totalPages = Math.ceil(totalQuestions / perPage);
let isLoading = false;



function initializePagination() {
    // Generate initial pagination
    generatePaginationControls();

    // Per page selector
    const perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            perPage = parseInt(this.value);
            currentPage = 1; // Reset to first page
            loadQuestions();
        });
    }

    // Page jump input
    const pageJumpInput = document.getElementById('pageJumpInput');
    if (pageJumpInput) {
        pageJumpInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const page = parseInt(this.value);
                if (page >= 1 && page <= totalPages) {
                    currentPage = page;
                    loadQuestions();
                } else {
                    this.value = currentPage;
                    showAlert('Please enter a valid page number between 1 and ' + totalPages, 'warning');
                }
            }
        });

        pageJumpInput.addEventListener('blur', function() {
            const page = parseInt(this.value);
            if (isNaN(page) || page < 1 || page > totalPages) {
                this.value = currentPage;
            }
        });
    }
}

function initializeCheckboxes() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const questionCheckboxes = document.querySelectorAll('.question-checkbox');
            questionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    // Update checkbox event listeners after AJAX load
    updateCheckboxListeners();
}

function updateCheckboxListeners() {
    const questionCheckboxes = document.querySelectorAll('.question-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');

    questionCheckboxes.forEach(checkbox => {
        // Remove existing listeners to prevent duplicates
        checkbox.removeEventListener('change', handleCheckboxChange);
        checkbox.addEventListener('change', handleCheckboxChange);
    });

    function handleCheckboxChange() {
        updateSelectedCount();

        // Update select all checkbox state
        const checkedCount = document.querySelectorAll('.question-checkbox:checked').length;
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === questionCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < questionCheckboxes.length;
        }
    }
}

function initializeFilterForm() {
    const filtersForm = document.getElementById('filtersForm');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    if (filtersForm) {
        filtersForm.addEventListener('submit', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when applying filters
            loadQuestions();
        });
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Clear all form fields
            const form = document.getElementById('filtersForm');
            form.reset();

            // Reset pagination
            currentPage = 1;

            // Load questions with cleared filters
            loadQuestions();
        });
    }
}

function loadQuestions() {
    if (isLoading) return;

    isLoading = true;
    showLoading(true);

    // Get current filters
    const filters = getCurrentFilters();

    // Build query parameters with cache busting
    const params = new URLSearchParams({
        page: currentPage,
        per_page: perPage,
        _t: Date.now(), // Cache busting timestamp
        ...filters
    });

    // Make AJAX request
    const ajaxUrl = `<?= base_url(($route_prefix ?? '') . 'questions/load-questions') ?>`;
    console.log('AJAX URL:', ajaxUrl);
    console.log('Full URL with params:', `${ajaxUrl}?${params}`);

    fetch(`${ajaxUrl}?${params}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        },
        cache: 'no-cache'
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            // Check if response is HTML (login page redirect)
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('text/html')) {
                console.error('Received HTML response instead of JSON - likely session expired');
                throw new Error('Session expired. Please refresh the page and login again.');
            }

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update table content
                document.getElementById('questionsTableBody').innerHTML = data.html;

                // Update pagination info
                totalQuestions = data.pagination.total;
                totalPages = data.pagination.total_pages;

                // Update pagination controls
                generatePaginationControls();
                updatePaginationInfo();
                updatePageJumpMax();

                // Reinitialize checkbox listeners
                updateCheckboxListeners();

                // Update URL without page reload
                updateURL(filters);

                // Scroll to top of table
                document.getElementById('questionsTable').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

            } else {
                // Check for session expiry message
                if (data.message && data.message.includes('Session expired')) {
                    showAlert('Session expired. Please refresh the page and login again.', 'warning');
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    showAlert(data.message || 'Error loading questions', 'danger');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            console.error('Error details:', error.message);

            // Check if it's a session expiry error
            if (error.message.includes('Session expired')) {
                showAlert('Session expired. Refreshing page...', 'warning');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert('Network error occurred while loading questions: ' + error.message, 'danger');

                // If AJAX fails completely, fall back to page reload after 5 seconds
                console.log('AJAX failed, will reload page in 5 seconds');
                setTimeout(() => {
                    window.location.reload();
                }, 5000);
            }
        })
        .finally(() => {
            isLoading = false;
            showLoading(false);
        });
}

function getCurrentFilters() {
    const form = document.querySelector('form[action*="questions"]');
    const formData = new FormData(form);
    const filters = {};

    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            filters[key] = value;
        }
    }

    return filters;
}

function generatePaginationControls() {
    const paginationContainer = document.getElementById('paginationControls');
    if (!paginationContainer) return;

    let html = '';

    // Previous button
    html += `<li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage - 1})" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
             </li>`;

    // Page numbers with smart ellipsis
    const pageNumbers = generatePageNumbers();
    pageNumbers.forEach(page => {
        if (page === '...') {
            html += `<li class="page-item disabled">
                        <span class="page-link">...</span>
                     </li>`;
        } else {
            html += `<li class="page-item ${page === currentPage ? 'active' : ''}">
                        <a class="page-link ${page === currentPage ? 'active' : ''}" href="#" onclick="goToPage(${page})">${page}</a>
                     </li>`;
        }
    });

    // Next button
    html += `<li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage + 1})" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </a>
             </li>`;

    paginationContainer.innerHTML = html;
}

function generatePageNumbers() {
    const pages = [];
    const delta = 2; // Number of pages to show around current page

    if (totalPages <= 7) {
        // Show all pages if total is small
        for (let i = 1; i <= totalPages; i++) {
            pages.push(i);
        }
    } else {
        // Always show first page
        pages.push(1);

        // Calculate range around current page
        const start = Math.max(2, currentPage - delta);
        const end = Math.min(totalPages - 1, currentPage + delta);

        // Add ellipsis after first page if needed
        if (start > 2) {
            pages.push('...');
        }

        // Add pages around current page
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }

        // Add ellipsis before last page if needed
        if (end < totalPages - 1) {
            pages.push('...');
        }

        // Always show last page
        if (totalPages > 1) {
            pages.push(totalPages);
        }
    }

    return pages;
}

function goToPage(page) {
    if (page < 1 || page > totalPages || page === currentPage || isLoading) {
        return false;
    }

    currentPage = page;
    loadQuestions();
    return false;
}

function updatePaginationInfo() {
    const start = (currentPage - 1) * perPage + 1;
    const end = Math.min(currentPage * perPage, totalQuestions);

    document.getElementById('showingStart').textContent = start;
    document.getElementById('showingEnd').textContent = end;
    document.getElementById('totalRecords').textContent = totalQuestions;
}

function updatePageJumpMax() {
    const pageJumpInput = document.getElementById('pageJumpInput');
    if (pageJumpInput) {
        pageJumpInput.max = totalPages;
        pageJumpInput.value = currentPage;
    }
}

function updateURL(filters) {
    const url = new URL(window.location);
    url.searchParams.set('page', currentPage);
    url.searchParams.set('per_page', perPage);

    // Add filters to URL
    Object.keys(filters).forEach(key => {
        if (filters[key]) {
            url.searchParams.set(key, filters[key]);
        } else {
            url.searchParams.delete(key);
        }
    });

    window.history.replaceState({}, '', url);
}

function showLoading(show) {
    const loadingElement = document.getElementById('paginationLoading');
    if (loadingElement) {
        loadingElement.style.display = show ? 'block' : 'none';
    }
}



// Modal variables
let currentQuestionId = null;

// Show delete modal
function showDeleteModal(questionId, questionText) {
    currentQuestionId = questionId;
    document.getElementById('deleteQuestionText').textContent = questionText + '...';
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentQuestionId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = `<?= base_url(($route_prefix ?? '') . 'questions/delete/') ?>${currentQuestionId}`;
    }
});

// Handle duplicate confirmation
document.getElementById('confirmDuplicate').addEventListener('click', function() {
    if (currentQuestionId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Duplicating...';
        this.disabled = true;

        // Redirect to duplicate URL
        window.location.href = `<?= base_url(($route_prefix ?? '') . 'questions/duplicate/') ?>${currentQuestionId}`;
    }
});

// Duplicate question
function duplicateQuestion(questionId) {
    showDuplicateModal(questionId);
}

// Show duplicate modal
function showDuplicateModal(questionId) {
    currentQuestionId = questionId;
    const modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
    modal.show();
}

// Show bulk actions modal
function showBulkActionsModal() {
    const selectedCheckboxes = document.querySelectorAll('.question-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        showCustomAlert('Please select at least one question.', 'warning');
        return;
    }

    // Get selected question IDs
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    document.getElementById('selectedQuestionIds').value = selectedIds.join(',');

    updateSelectedCount();

    const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
    modal.show();
}

// Custom alert function
function showCustomAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

// Update selected count
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.question-checkbox:checked').length;
    const countElement = document.getElementById('selectedCount');
    if (countElement) {
        countElement.textContent = selectedCount;
    }
}

// Handle bulk action selection
document.querySelector('select[name="action"]').addEventListener('change', function() {
    const additionalOptions = document.getElementById('additionalOptions');
    const selectedAction = this.value;

    additionalOptions.innerHTML = '';
    additionalOptions.style.display = 'none';

    if (selectedAction === 'change_difficulty') {
        additionalOptions.innerHTML = `
            <div class="mb-3">
                <label class="form-label">New Difficulty</label>
                <select class="form-select" name="new_difficulty" required>
                    <option value="">Select difficulty...</option>
                    <?php if (isset($difficulties)): ?>
                        <?php foreach ($difficulties as $key => $label): ?>
                            <option value="<?= $key ?>"><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        `;
        additionalOptions.style.display = 'block';
    } else if (selectedAction === 'change_subject') {
        additionalOptions.innerHTML = `
            <div class="mb-3">
                <label class="form-label">New Subject</label>
                <select class="form-select" name="new_subject" required>
                    <option value="">Select subject...</option>
                    <?php if (isset($subjects)): ?>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        `;
        additionalOptions.style.display = 'block';
    }
});
</script>

<!-- AI Question Generation Modal -->
<?php
// Show AI Generate modal if AI generation is enabled
if (($settings['ai_generation_enabled'] ?? false)):
?>
<div class="modal fade" id="aiGenerateModal" tabindex="-1" aria-labelledby="aiGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="aiGenerateModalLabel">
                    <i class="material-symbols-rounded me-2">psychology</i>
                    AI Question Generation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="aiGenerateForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <select name="subject_id" class="form-select" required>
                                    <option value="">Select Subject</option>
                                    <?php if (isset($subjects)): ?>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Class <span class="text-danger">*</span></label>
                                <select name="class_id" class="form-select" required>
                                    <option value="">Select Class</option>
                                    <?php if (isset($classes)): ?>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Topics <span class="text-danger">*</span></label>
                        <input type="text" name="topics" class="form-control"
                               placeholder="e.g., Photosynthesis, Cell Division, Genetics" required>
                        <small class="text-muted">Separate multiple topics with commas</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub-topics (Optional)</label>
                        <input type="text" name="subtopics" class="form-control"
                               placeholder="e.g., Light reactions, Calvin cycle, Chloroplast structure">
                        <small class="text-muted">Separate multiple sub-topics with commas</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reference Links (Optional)</label>
                        <textarea name="reference_links" class="form-control" rows="2"
                                  placeholder="Paste any reference URLs or materials for AI to consider"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Question Types & Quantities</label>
                        <div class="row" id="questionTypesContainer">
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">MCQ</span>
                                    <input type="number" name="question_types[mcq]" class="form-control" min="0" max="20" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">True/False</span>
                                    <input type="number" name="question_types[true_false]" class="form-control" min="0" max="20" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">Yes/No</span>
                                    <input type="number" name="question_types[yes_no]" class="form-control" min="0" max="20" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">Short Answer</span>
                                    <input type="number" name="question_types[short_answer]" class="form-control" min="0" max="20" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">Essay</span>
                                    <input type="number" name="question_types[essay]" class="form-control" min="0" max="10" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">Fill Blank</span>
                                    <input type="number" name="question_types[fill_blank]" class="form-control" min="0" max="20" value="0">
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Specify how many questions of each type you want to generate</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Questions: <span id="totalQuestionsCount">0</span></label>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" id="totalQuestionsProgress" style="width: 0%"></div>
                        </div>
                        <small class="text-muted">Maximum 50 questions per generation</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="generateQuestionsBtn" disabled>
                    <i class="material-symbols-rounded me-2">auto_awesome</i>
                    Generate Questions
                </button>
            </div>
        </div>
    </div>
</div>

<!-- AI Questions Preview Modal -->
<div class="modal fade" id="aiPreviewModal" tabindex="-1" aria-labelledby="aiPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="aiPreviewModalLabel">
                    <i class="material-symbols-rounded me-2">preview</i>
                    Review AI Generated Questions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="aiPreviewContent">
                <!-- Generated questions will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="approveQuestionsBtn">
                    <i class="material-symbols-rounded me-2">check_circle</i>
                    Approve & Add to Question Bank
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// AI Question Generation functionality
document.addEventListener('DOMContentLoaded', function() {
    const questionTypeInputs = document.querySelectorAll('#questionTypesContainer input[type="number"]');
    const totalCountSpan = document.getElementById('totalQuestionsCount');
    const totalProgressBar = document.getElementById('totalQuestionsProgress');
    const generateBtn = document.getElementById('generateQuestionsBtn');

    // Update total questions count
    function updateTotalCount() {
        let total = 0;
        questionTypeInputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });

        totalCountSpan.textContent = total;
        totalProgressBar.style.width = Math.min((total / 50) * 100, 100) + '%';

        // Enable/disable generate button
        generateBtn.disabled = total === 0 || total > 50;

        if (total > 50) {
            totalProgressBar.classList.add('bg-danger');
            totalProgressBar.classList.remove('bg-success');
        } else {
            totalProgressBar.classList.add('bg-success');
            totalProgressBar.classList.remove('bg-danger');
        }
    }

    // Add event listeners to question type inputs
    questionTypeInputs.forEach(input => {
        input.addEventListener('input', updateTotalCount);
    });

    // Generate questions
    generateBtn.addEventListener('click', function() {
        const form = document.getElementById('aiGenerateForm');
        const formData = new FormData(form);

        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Show loading state
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';

        // Make API request
        fetch('<?= base_url(($route_prefix ?? '') . 'questions/ai-generate') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide generation modal
                bootstrap.Modal.getInstance(document.getElementById('aiGenerateModal')).hide();

                // Show preview modal with generated questions
                showPreviewModal(data.questions);
            } else {
                showAlert('Error: ' + (data.message || 'Failed to generate questions'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while generating questions', 'danger');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<i class="material-symbols-rounded me-2">auto_awesome</i>Generate Questions';
        });
    });

    function showPreviewModal(questions) {
        const previewContent = document.getElementById('aiPreviewContent');
        let html = '<div class="row">';

        questions.forEach((question, index) => {
            html += `
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Question ${index + 1}</h6>
                            <div>
                                <span class="badge bg-primary">${question.question_type.replace('_', ' ').toUpperCase()}</span>
                                <span class="badge bg-secondary">${question.difficulty.toUpperCase()}</span>
                                <span class="badge bg-info">${question.points} mks</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Question:</label>
                                <textarea class="form-control" rows="2" data-field="question_text" data-index="${index}">${question.question_text}</textarea>
                            </div>

                            ${question.options && question.options.length > 0 ? `
                            <div class="mb-3">
                                <label class="form-label fw-bold">Options:</label>
                                ${question.options.map((option, optIndex) => `
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">${String.fromCharCode(65 + optIndex)}</span>
                                        <input type="text" class="form-control" value="${option}"
                                               data-field="options" data-index="${index}" data-option="${optIndex}">
                                    </div>
                                `).join('')}
                            </div>
                            ` : ''}

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Correct Answer:</label>
                                    <input type="text" class="form-control" value="${question.correct_answer}"
                                           data-field="correct_answer" data-index="${index}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Points:</label>
                                    <input type="number" class="form-control" value="${question.points}" min="1" max="10"
                                           data-field="points" data-index="${index}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-bold">Explanation:</label>
                                <textarea class="form-control" rows="2" data-field="explanation" data-index="${index}">${question.explanation}</textarea>
                            </div>

                            ${question.hints ? `
                            <div class="mt-3">
                                <label class="form-label fw-bold">Hints:</label>
                                <textarea class="form-control" rows="1" data-field="hints" data-index="${index}">${question.hints}</textarea>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        previewContent.innerHTML = html;

        // Store questions data for approval
        window.aiGeneratedQuestions = questions;

        // Show preview modal
        new bootstrap.Modal(document.getElementById('aiPreviewModal')).show();
    }

    // Approve questions
    document.getElementById('approveQuestionsBtn').addEventListener('click', function() {
        // Collect updated question data from form fields
        const updatedQuestions = [];
        const questionCards = document.querySelectorAll('#aiPreviewContent .card');

        questionCards.forEach((card, index) => {
            const question = {...window.aiGeneratedQuestions[index]};

            // Update question text
            const questionText = card.querySelector('[data-field="question_text"]');
            if (questionText) question.question_text = questionText.value;

            // Update options
            const optionInputs = card.querySelectorAll('[data-field="options"]');
            if (optionInputs.length > 0) {
                question.options = Array.from(optionInputs).map(input => input.value);
            }

            // Update other fields
            ['correct_answer', 'points', 'explanation', 'hints'].forEach(field => {
                const input = card.querySelector(`[data-field="${field}"]`);
                if (input) {
                    question[field] = field === 'points' ? parseInt(input.value) : input.value;
                }
            });

            updatedQuestions.push(question);
        });

        // Get form data from generation form
        const generationForm = document.getElementById('aiGenerateForm');
        const formData = new FormData(generationForm);
        formData.append('questions', JSON.stringify(updatedQuestions));

        // Show loading state
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding to Question Bank...';

        // Submit to backend
        fetch('<?= base_url(($route_prefix ?? '') . 'questions/ai-approve') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide preview modal
                bootstrap.Modal.getInstance(document.getElementById('aiPreviewModal')).hide();

                // Show success message with nice alert instead of browser alert
                showAlert(`Success! ${data.added_count} questions have been added to the question bank.`, 'success');

                // Refresh questions list without full page reload to prevent duplication
                setTimeout(() => {
                    loadQuestions();
                }, 1000);
            } else {
                showAlert('Error: ' + (data.message || 'Failed to add questions'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while adding questions', 'danger');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<i class="material-symbols-rounded me-2">check_circle</i>Approve & Add to Question Bank';
        });
    });

    // Show alert function for better UX instead of browser alerts
    function showAlert(message, type = 'info') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'danger' ? 'danger' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
        alertDiv.innerHTML = `
            <i class="material-symbols-rounded me-2">${type === 'success' ? 'check_circle' : type === 'danger' ? 'error' : type === 'warning' ? 'warning' : 'info'}</i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(alertDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>