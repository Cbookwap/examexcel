<?= $this->extend('layouts/dashboard') ?>

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
        border-color: #A05AFF;
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
    }
    .form-check-input:checked {
        background-color: #A05AFF;
        border-color: #A05AFF;
    }
    .help-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    .btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #A05AFF 0%, #8B47E6 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #8B47E6 0%, #7C3AED 100%);
        transform: translateY(-1px);
    }
    .btn-outline-secondary {
        border: 2px solid #e2e8f0;
        color: #6b7280;
    }
    .btn-outline-secondary:hover {
        background-color: #f8fafc;
        border-color: #d1d5db;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <a href="<?= base_url('exam/view/' . $exam['id']) ?>" class="btn btn-outline-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">visibility</i>View Exam
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <form id="examForm" method="POST" action="<?= base_url('exam/edit/' . $exam['id']) ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">info</i>Basic Information
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
                                   value="<?= old('duration_minutes', $exam['duration_minutes']) ?>" min="1" max="600" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description', $exam['description']) ?></textarea>
                            <div class="help-text">Provide a detailed description of the exam</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject and Class -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">school</i>Subject & Class Assignment
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label for="class_id" class="form-label fw-semibold">Class *</label>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= old('class_id', $exam['class_id']) == $class['id'] ? 'selected' : '' ?>>
                                        <?= esc($class['name']) ?> <?= esc($class['section']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Configuration -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">settings</i>Exam Configuration
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="total_marks" class="form-label fw-semibold">Total Marks *</label>
                            <input type="number" class="form-control" id="total_marks" name="total_marks"
                                   value="<?= old('total_marks', $exam['total_marks']) ?>" min="1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="passing_marks" class="form-label fw-semibold">Passing Marks *</label>
                            <input type="number" class="form-control" id="passing_marks" name="passing_marks"
                                   value="<?= old('passing_marks', $exam['passing_marks']) ?>" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="question_count" class="form-label fw-semibold">Number of Questions *</label>
                            <input type="number" class="form-control" id="question_count" name="question_count"
                                   value="<?= old('question_count', $exam['question_count']) ?>" min="1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="negative_marks_per_question" class="form-label fw-semibold">Negative Marks</label>
                            <input type="number" class="form-control" id="negative_marks_per_question" name="negative_marks_per_question"
                                   value="<?= old('negative_marks_per_question', $exam['negative_marks_per_question']) ?>" min="0" step="0.25"
                                   <?= !$exam['negative_marking'] ? 'disabled' : '' ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label fw-semibold">Start Time *</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                                   value="<?= old('start_time', date('Y-m-d\TH:i', strtotime($exam['start_time']))) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label fw-semibold">End Time *</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                                   value="<?= old('end_time', date('Y-m-d\TH:i', strtotime($exam['end_time']))) ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Settings -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">quiz</i>Question Settings
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Question Behavior</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="negative_marking" name="negative_marking"
                                       value="1" <?= old('negative_marking', $exam['negative_marking']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="negative_marking">
                                    Enable Negative Marking
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="randomize_questions" name="randomize_questions"
                                       value="1" <?= old('randomize_questions', $exam['randomize_questions']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="randomize_questions">
                                    Randomize Question Order
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="randomize_options" name="randomize_options"
                                       value="1" <?= old('randomize_options', $exam['randomize_options']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="randomize_options">
                                    Randomize Answer Options
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Result Settings</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="show_result_immediately" name="show_result_immediately"
                                       value="1" <?= old('show_result_immediately', $exam['show_result_immediately']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="show_result_immediately">
                                    Show Results Immediately
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allow_review" name="allow_review"
                                       value="1" <?= old('allow_review', $exam['allow_review']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="allow_review">
                                    Allow Answer Review
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security & Monitoring -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">security</i>Security & Monitoring
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Security Features</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="browser_lockdown" name="browser_lockdown"
                                       value="1" <?= old('browser_lockdown', $exam['browser_lockdown']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="browser_lockdown">
                                    Enable Browser Lockdown
                                </label>
                                <div class="help-text">Prevents students from switching tabs or using developer tools</div>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="prevent_copy_paste" name="prevent_copy_paste"
                                       value="1" <?= old('prevent_copy_paste', $exam['prevent_copy_paste']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="prevent_copy_paste">
                                    Disable Copy/Paste
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="disable_right_click" name="disable_right_click"
                                       value="1" <?= old('disable_right_click', $exam['disable_right_click']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="disable_right_click">
                                    Disable Right Click
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Monitoring</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="require_proctoring" name="require_proctoring"
                                       value="1" <?= old('require_proctoring', $exam['require_proctoring']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="require_proctoring">
                                    Enable AI Proctoring
                                </label>
                                <div class="help-text">Uses webcam to monitor student behavior</div>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="auto_submit" name="auto_submit"
                                       value="1" <?= old('auto_submit', $exam['settings']['auto_submit'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="auto_submit">
                                    Auto-Submit on Time Up
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="show_timer" name="show_timer"
                                       value="1" <?= old('show_timer', $exam['settings']['show_timer'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="show_timer">
                                    Show Timer to Students
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Student Tools Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Student Tools & Features</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="calculator_enabled" name="calculator_enabled"
                                       value="1" <?= old('calculator_enabled', $exam['calculator_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="calculator_enabled">
                                    Enable Calculator
                                </label>
                                <div class="help-text">Provides a scientific calculator for students during the exam</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="exam_pause_enabled" name="exam_pause_enabled"
                                       value="1" <?= old('exam_pause_enabled', $exam['exam_pause_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="exam_pause_enabled">
                                    Allow Exam Pause
                                </label>
                                <div class="help-text">Students can pause and resume the exam (timer stops during pause)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">list</i>Exam Instructions
                    </h5>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instructions_general" class="form-label fw-semibold">General Instructions</label>
                            <textarea class="form-control" id="instructions_general" name="instructions_general" rows="4"><?= old('instructions_general', $exam['instructions']['general'] ?? 'Read all questions carefully before answering.') ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="instructions_technical" class="form-label fw-semibold">Technical Instructions</label>
                            <textarea class="form-control" id="instructions_technical" name="instructions_technical" rows="4"><?= old('instructions_technical', $exam['instructions']['technical'] ?? 'Ensure stable internet connection. Do not refresh the page.') ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="instructions_rules" class="form-label fw-semibold">Rules & Regulations</label>
                            <textarea class="form-control" id="instructions_rules" name="instructions_rules" rows="4"><?= old('instructions_rules', $exam['instructions']['rules'] ?? 'No external help allowed. Maintain academic integrity.') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-3 mb-4">
                <a href="<?= base_url('exam/view/' . $exam['id']) ?>" class="btn btn-outline-secondary">
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

        // Only auto-calculate if:
        // 1. This is not the initial load
        // 2. The total marks has actually changed from the initial value
        // 3. The user hasn't manually set a custom passing marks
        if (totalMarksInitialized && totalMarks !== initialTotalMarks && totalMarks > 0) {
            const currentPassingMarks = parseInt(passingMarksField.value) || 0;

            // Only auto-calculate if passing marks is empty or still at the calculated value
            if (currentPassingMarks === 0 || currentPassingMarks === Math.ceil(initialTotalMarks * 0.4)) {
                const passingMarks = Math.ceil(totalMarks * 0.4); // 40% passing
                passingMarksField.value = passingMarks;
            }
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