<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .form-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .section-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .section-content {
        padding: 1.5rem;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--theme-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--theme-color-rgb), 0.25);
    }
    .form-check-input:checked {
        background-color: var(--theme-color);
        border-color: var(--theme-color);
    }
    .help-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    .exam-mode-display {
        background: rgba(var(--theme-color-rgb), 0.1);
        border: 2px solid var(--theme-color);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;"><?= $pageTitle ?></h4>
                <p class="text-light mb-0"><?= $pageSubtitle ?></p>
            </div>
            <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">visibility</i>View Exam
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">check_circle</i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">error</i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Validation Errors -->
<?php if (isset($validation) && $validation->getErrors()): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">error</i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        <?php foreach ($validation->getErrors() as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <form id="examForm" method="POST" action="<?= base_url('principal/exams/edit/' . $exam['id']) ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: var(--theme-color);">info</i>Basic Information
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label fw-semibold">Exam Title *</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= old('title', $exam['title']) ?>" required>
                            <div class="help-text">Enter a descriptive title for the exam</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration_minutes" class="form-label fw-semibold">Duration (Minutes) *</label>
                            <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                                   value="<?= old('duration_minutes', $exam['duration_minutes']) ?>" min="1" max="480" required>
                            <div class="help-text">Maximum 8 hours (480 minutes)</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Optional description for the exam"><?= old('description', $exam['description']) ?></textarea>
                        <div class="help-text">Provide additional details about the exam (optional)</div>
                    </div>
                </div>
            </div>

            <!-- Subject and Class -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: var(--theme-color);">school</i>Subject & Class Assignment
                    </h5>
                </div>
                <div class="section-content">
                    <!-- Exam Mode Display (Read-only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Exam Mode</label>
                            <div class="exam-mode-display">
                                <i class="material-symbols-rounded mb-2" style="font-size: 32px; color: var(--theme-color);">
                                    <?= $exam['exam_mode'] === 'single_subject' ? 'book' : 'library_books' ?>
                                </i>
                                <h6 class="fw-semibold mb-1">
                                    <?= $exam['exam_mode'] === 'single_subject' ? 'Single Subject' : 'Multi-Subject' ?>
                                </h6>
                                <small class="text-muted">
                                    <?= $exam['exam_mode'] === 'single_subject' ? 'Focused on one subject' : 'Multiple subjects exam' ?>
                                </small>
                                <div class="mt-2">
                                    <small class="text-muted">Note: Exam mode cannot be changed after creation</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if ($exam['exam_mode'] === 'single_subject'): ?>
                            <div class="col-md-4 mb-3">
                                <label for="subject_id" class="form-label fw-semibold">Subject *</label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?= $subject['id'] ?>" <?= old('subject_id', $exam['subject_id']) == $subject['id'] ? 'selected' : '' ?>>
                                            <?= esc($subject['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Subjects</label>
                                <div class="form-control" style="height: auto; min-height: 45px;">
                                    <?php if (!empty($examSubjects)): ?>
                                        <?php foreach ($examSubjects as $examSubject): ?>
                                            <span class="badge bg-primary me-1 mb-1"><?= esc($examSubject['subject_name']) ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No subjects configured</span>
                                    <?php endif; ?>
                                </div>
                                <div class="help-text">Configure subjects in question management</div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="col-md-4 mb-3">
                            <label for="class_id" class="form-label fw-semibold">Class *</label>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= old('class_id', $exam['class_id']) == $class['id'] ? 'selected' : '' ?>>
                                        <?= esc($class['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="exam_type" class="form-label fw-semibold">Exam Type *</label>
                            <select class="form-select" id="exam_type" name="exam_type" required>
                                <option value="">Select Type</option>
                                <?php if (!empty($examTypes)): ?>
                                    <?php foreach ($examTypes as $type): ?>
                                        <option value="<?= esc($type['code']) ?>" <?= old('exam_type', $exam['exam_type']) == $type['code'] ? 'selected' : '' ?>>
                                            <?= esc($type['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="midterm" <?= old('exam_type', $exam['exam_type']) == 'midterm' ? 'selected' : '' ?>>Mid-term Exam</option>
                                    <option value="final" <?= old('exam_type', $exam['exam_type']) == 'final' ? 'selected' : '' ?>>Final Exam</option>
                                    <option value="quiz" <?= old('exam_type', $exam['exam_type']) == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                    <option value="test" <?= old('exam_type', $exam['exam_type']) == 'test' ? 'selected' : '' ?>>Test</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scoring & Timing -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: var(--theme-color);">grade</i>Scoring & Timing
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="total_marks" class="form-label fw-semibold">Total Marks *</label>
                            <input type="number" class="form-control" id="total_marks" name="total_marks"
                                   value="<?= old('total_marks', $exam['total_marks']) ?>" min="1" max="1000" required>
                            <div class="help-text">Maximum marks for the exam</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="passing_marks" class="form-label fw-semibold">Passing Marks *</label>
                            <input type="number" class="form-control" id="passing_marks" name="passing_marks"
                                   value="<?= old('passing_marks', $exam['passing_marks']) ?>" min="1" required>
                            <div class="help-text">Minimum marks to pass</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="max_attempts" class="form-label fw-semibold">Max Attempts</label>
                            <input type="number" class="form-control" id="max_attempts" name="max_attempts"
                                   value="<?= old('max_attempts', $exam['max_attempts']) ?>" min="1" max="10">
                            <div class="help-text">Number of allowed attempts</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label fw-semibold">Start Time *</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                                   value="<?= old('start_time', date('Y-m-d\TH:i', strtotime($exam['start_time']))) ?>" required>
                            <div class="help-text">When the exam becomes available</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label fw-semibold">End Time *</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                                   value="<?= old('end_time', date('Y-m-d\TH:i', strtotime($exam['end_time']))) ?>" required>
                            <div class="help-text">When the exam closes</div>
                        </div>
                    </div>

                    <!-- Negative Marking -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="negative_marking" name="negative_marking"
                                       value="1" <?= old('negative_marking', $exam['negative_marking']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="negative_marking">
                                    Enable Negative Marking
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="negative_marks_per_question" class="form-label fw-semibold">Negative Marks per Question</label>
                            <input type="number" class="form-control" id="negative_marks_per_question" name="negative_marks_per_question"
                                   value="<?= old('negative_marks_per_question', $exam['negative_marks_per_question']) ?>" step="0.25" min="0" max="10"
                                   <?= !$exam['negative_marking'] ? 'disabled' : '' ?>>
                            <div class="help-text">Marks deducted for wrong answers</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-3 mb-4">
                <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Update Exam
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate passing marks based on total marks (only for new values, not during edit)
    let totalMarksInitialized = false;
    const totalMarksField = document.getElementById('total_marks');
    const passingMarksField = document.getElementById('passing_marks');

    // Store initial values
    const initialTotalMarks = parseInt(totalMarksField.value) || 0;
    const initialPassingMarks = parseInt(passingMarksField.value) || 0;

    totalMarksField.addEventListener('input', function() {
        const totalMarks = parseInt(this.value) || 0;
        
        // Only auto-calculate if this is a significant change and not the initial load
        if (totalMarksInitialized && totalMarks > 0 && Math.abs(totalMarks - initialTotalMarks) > 10) {
            const passingMarks = Math.ceil(totalMarks * 0.4); // 40% passing
            passingMarksField.value = passingMarks;
        }
        totalMarksInitialized = true;
    });

    // Validate end time is after start time
    function validateDateTime() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;

        if (startTime && endTime && new Date(endTime) <= new Date(startTime)) {
            document.getElementById('end_time').setCustomValidity('End time must be after start time');
        } else {
            document.getElementById('end_time').setCustomValidity('');
        }
    }

    document.getElementById('start_time').addEventListener('change', validateDateTime);
    document.getElementById('end_time').addEventListener('change', validateDateTime);

    // Form validation
    document.getElementById('examForm').addEventListener('submit', function(e) {
        const totalMarks = parseFloat(document.getElementById('total_marks').value) || 0;
        const passingMarks = parseFloat(document.getElementById('passing_marks').value) || 0;

        if (passingMarks > totalMarks) {
            e.preventDefault();
            alert('Passing marks cannot be greater than total marks');
            return false;
        }

        validateDateTime();
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }

        this.classList.add('was-validated');
    });

    // Enable/disable negative marks input based on checkbox
    document.getElementById('negative_marking').addEventListener('change', function() {
        const negativeMarksInput = document.getElementById('negative_marks_per_question');
        negativeMarksInput.disabled = !this.checked;
        if (!this.checked) {
            negativeMarksInput.value = 0;
        }
    });
});
</script>
<?= $this->endSection() ?>
