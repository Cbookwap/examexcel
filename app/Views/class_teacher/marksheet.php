<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .marksheet-table {
        font-size: 0.875rem;
    }
    .marksheet-table th {
        background-color: var(--primary-color);
        color: white !important;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        padding: 0.75rem 0.5rem;
    }
    /* Ensure ALL header rows have white text */
    .marksheet-table thead tr th {
        color: white !important;
        background-color: var(--primary-color) !important;
    }
    .marksheet-table td {
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        padding: 0.5rem;
        color: #333;
        background-color: #fff;
    }
    .student-name {
        text-align: left !important;
        font-weight: 500;
        color: #333;
    }
    .marksheet-table td .text-muted {
        color: #6c757d !important;
    }
    .marksheet-table td strong {
        color: #333;
    }
    .marksheet-table td small {
        color: #6c757d;
    }
    .position-badge {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #333;
        font-weight: bold;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
    }
    .grade-A { background: #28a745; color: white; }
    .grade-B { background: #17a2b8; color: white; }
    .grade-C { background: #ffc107; color: #333; }
    .grade-D { background: #fd7e14; color: white; }
    .grade-F { background: #dc3545; color: white; }
    .filter-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
    }
    .filter-card .form-label {
        color: white !important;
    }
    .filter-card .opacity-75 {
        color: rgba(255, 255, 255, 0.75) !important;
    }
    .print-area {
        background: white;
    }
    @media print {
        .no-print { display: none !important; }
        .print-area { 
            margin: 0 !important; 
            padding: 20px !important;
        }
        .marksheet-table { font-size: 0.75rem; }
        .marksheet-table th, .marksheet-table td { 
            padding: 0.25rem !important; 
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="page-content-wrapper">
    <!-- Page Header -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold"><?= $pageTitle ?></h4>
                    <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-primary me-2">
                        <i class="fas fa-print me-2"></i>Print Marksheet
                    </button>
                    <a href="<?= base_url('class-teacher/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card filter-card">
                <div class="card-body">
                    <form method="GET" action="<?= base_url('class-teacher/marksheet') ?>">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3">
                                <label class="form-label opacity-75">Academic Session</label>
                                <select name="session_id" class="form-select">
                                    <option value="">All Sessions</option>
                                    <?php foreach ($sessions as $session): ?>
                                        <option value="<?= $session['id'] ?>" <?= $selectedSession == $session['id'] ? 'selected' : '' ?>>
                                            <?= esc($session['session_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label opacity-75">Term</label>
                                <select name="term_id" class="form-select">
                                    <option value="">All Terms</option>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?= $term['id'] ?>" <?= $selectedTerm == $term['id'] ? 'selected' : '' ?>>
                                            <?= esc($term['term_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label opacity-75">Exam Type</label>
                                <select name="exam_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="mid_term" <?= $selectedExamType == 'mid_term' ? 'selected' : '' ?>>Mid Term</option>
                                    <option value="final" <?= $selectedExamType == 'final' ? 'selected' : '' ?>>Final</option>
                                    <option value="quiz" <?= $selectedExamType == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                    <option value="assignment" <?= $selectedExamType == 'assignment' ? 'selected' : '' ?>>Assignment</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-light w-100">
                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Marksheet -->
    <div class="print-area">
        <!-- Print Header -->
        <div class="text-center mb-4 d-none d-print-block">
            <h3><?= get_app_name() ?></h3>
            <h4><?= esc($class['name']) ?> - Class Marksheet</h4>
            <p>Academic Year: <?= esc($class['academic_year']) ?></p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <?php if (!empty($marksheetData)): ?>
                            <div class="table-responsive">
                                <table class="table marksheet-table mb-0">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Position</th>
                                            <th rowspan="2">Student Name</th>
                                            <th rowspan="2">Student ID</th>
                                            <?php foreach ($subjects as $subject): ?>
                                                <th colspan="2"><?= esc($subject['name']) ?></th>
                                            <?php endforeach; ?>
                                            <th rowspan="2">Total</th>
                                            <th rowspan="2">Percentage</th>
                                            <th rowspan="2">Grade</th>
                                        </tr>
                                        <tr>
                                            <?php foreach ($subjects as $subject): ?>
                                                <th>Mark</th>
                                                <th>Grade</th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($marksheetData as $studentData): ?>
                                            <tr>
                                                <td>
                                                    <span class="position-badge"><?= $studentData['position'] ?></span>
                                                </td>
                                                <td class="student-name">
                                                    <strong><?= esc($studentData['student']['first_name'] . ' ' . $studentData['student']['last_name']) ?></strong>
                                                </td>
                                                <td><?= esc($studentData['student']['student_id']) ?></td>
                                                
                                                <?php foreach ($studentData['subjects'] as $subjectData): ?>
                                                    <td>
                                                        <?php if ($subjectData['total_marks'] > 0): ?>
                                                            <?= number_format($subjectData['marks_obtained'], 1) ?>/<?= $subjectData['total_marks'] ?>
                                                            <br>
                                                            <small class="text-muted"><?= number_format($subjectData['percentage'], 1) ?>%</small>
                                                        <?php else: ?>
                                                            <span class="text-muted">No Exam</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($subjectData['total_marks'] > 0): ?>
                                                            <span class="badge grade-<?= $subjectData['grade'] ?>"><?= $subjectData['grade'] ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                                
                                                <td>
                                                    <strong><?= number_format($studentData['total_marks'], 1) ?>/<?= $studentData['total_possible'] ?></strong>
                                                </td>
                                                <td>
                                                    <strong><?= number_format($studentData['percentage'], 1) ?>%</strong>
                                                </td>
                                                <td>
                                                    <span class="badge grade-<?= $studentData['grade'] ?>"><?= $studentData['grade'] ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-table text-muted mb-3" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mb-3">No Marksheet Data Available</h5>
                                <p class="text-muted">No exam results found for the selected criteria. Students need to complete exams to generate marksheet data.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <?php if (!empty($marksheetData)): ?>
            <div class="row mt-4 no-print">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="text-primary"><?= count($marksheetData) ?></h5>
                            <p class="mb-0 text-muted">Total Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <?php 
                            $classAverage = count($marksheetData) > 0 ? 
                                array_sum(array_column($marksheetData, 'percentage')) / count($marksheetData) : 0;
                            ?>
                            <h5 class="text-success"><?= number_format($classAverage, 1) ?>%</h5>
                            <p class="mb-0 text-muted">Class Average</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <?php 
                            $passCount = count(array_filter($marksheetData, function($student) {
                                return $student['percentage'] >= 40;
                            }));
                            $passRate = count($marksheetData) > 0 ? ($passCount / count($marksheetData)) * 100 : 0;
                            ?>
                            <h5 class="text-info"><?= number_format($passRate, 1) ?>%</h5>
                            <p class="mb-0 text-muted">Pass Rate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <?php 
                            $highestScore = count($marksheetData) > 0 ? 
                                max(array_column($marksheetData, 'percentage')) : 0;
                            ?>
                            <h5 class="text-warning"><?= number_format($highestScore, 1) ?>%</h5>
                            <p class="mb-0 text-muted">Highest Mark</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
