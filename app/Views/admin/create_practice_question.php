<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
    }
    .form-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px 15px 0 0;
        margin: -1rem -1rem 1.5rem -1rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }
    .btn-secondary {
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }
    .option-group {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .option-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    .correct-answer-hint {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #155724;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-2 fw-bold"><?= $pageTitle ?></h2>
        <p class="mb-0 text-muted"><?= $pageSubtitle ?></p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= base_url('admin/practice-questions') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Practice Questions
        </a>
    </div>
</div>

<!-- Create Form -->
<div class="row">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <div class="form-header">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-plus-circle me-2 text-white"></i>Create New Practice Question
                    </h5>
                </div>

                <?= form_open('admin/practice-questions/store', ['class' => 'needs-validation', 'novalidate' => true]) ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">
                                <i class="fas fa-tag me-1"></i>Category *
                            </label>
                            <select name="category" id="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="English Language" <?= old('category') === 'English Language' ? 'selected' : '' ?>>English Language</option>
                                <option value="Mathematics" <?= old('category') === 'Mathematics' ? 'selected' : '' ?>>Mathematics</option>
                                <option value="Civic Education" <?= old('category') === 'Civic Education' ? 'selected' : '' ?>>Civic Education</option>
                                <option value="Current Affairs" <?= old('category') === 'Current Affairs' ? 'selected' : '' ?>>Current Affairs</option>
                                <option value="French" <?= old('category') === 'French' ? 'selected' : '' ?>>French</option>
                                <option value="General Knowledge" <?= old('category') === 'General Knowledge' ? 'selected' : '' ?>>General Knowledge</option>
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="difficulty" class="form-label">
                                <i class="fas fa-chart-line me-1"></i>Difficulty *
                            </label>
                            <select name="difficulty" id="difficulty" class="form-select" required>
                                <option value="">Select Difficulty</option>
                                <option value="easy" <?= old('difficulty') === 'easy' ? 'selected' : '' ?>>Easy</option>
                                <option value="medium" <?= old('difficulty') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="hard" <?= old('difficulty') === 'hard' ? 'selected' : '' ?>>Hard</option>
                            </select>
                            <div class="invalid-feedback">Please select difficulty level.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="points" class="form-label">
                                <i class="fas fa-star me-1"></i>Mark
                            </label>
                            <input type="number" name="points" id="points" class="form-control" 
                                   value="<?= old('points', 1) ?>" min="1" max="10">
                            <small class="text-muted">Default: 1 Mark</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="question_text" class="form-label">
                        <i class="fas fa-question-circle me-1"></i>Question Text *
                    </label>
                    <textarea name="question_text" id="question_text" class="form-control" 
                              rows="3" required placeholder="Enter the question text..."><?= old('question_text') ?></textarea>
                    <div class="invalid-feedback">Please enter the question text.</div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="option-group">
                            <div class="option-label">Option A</div>
                            <input type="text" name="option_a" id="option_a" class="form-control" 
                                   value="<?= old('option_a') ?>" required placeholder="Enter option A...">
                            <div class="invalid-feedback">Please enter option A.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="option-group">
                            <div class="option-label">Option B</div>
                            <input type="text" name="option_b" id="option_b" class="form-control" 
                                   value="<?= old('option_b') ?>" required placeholder="Enter option B...">
                            <div class="invalid-feedback">Please enter option B.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="option-group">
                            <div class="option-label">Option C</div>
                            <input type="text" name="option_c" id="option_c" class="form-control" 
                                   value="<?= old('option_c') ?>" required placeholder="Enter option C...">
                            <div class="invalid-feedback">Please enter option C.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="option-group">
                            <div class="option-label">Option D</div>
                            <input type="text" name="option_d" id="option_d" class="form-control" 
                                   value="<?= old('option_d') ?>" required placeholder="Enter option D...">
                            <div class="invalid-feedback">Please enter option D.</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="correct_answer" class="form-label">
                        <i class="fas fa-check-circle me-1"></i>Correct Answer *
                    </label>
                    <select name="correct_answer" id="correct_answer" class="form-select" required>
                        <option value="">Select Correct Answer</option>
                        <option value="A" <?= old('correct_answer') === 'A' ? 'selected' : '' ?>>A</option>
                        <option value="B" <?= old('correct_answer') === 'B' ? 'selected' : '' ?>>B</option>
                        <option value="C" <?= old('correct_answer') === 'C' ? 'selected' : '' ?>>C</option>
                        <option value="D" <?= old('correct_answer') === 'D' ? 'selected' : '' ?>>D</option>
                    </select>
                    <div class="correct-answer-hint">
                        <i class="fas fa-info-circle me-1"></i>
                        Select which option (A, B, C, or D) is the correct answer for this question.
                    </div>
                    <div class="invalid-feedback">Please select the correct answer.</div>
                </div>

                <div class="form-group">
                    <label for="explanation" class="form-label">
                        <i class="fas fa-lightbulb me-1"></i>Explanation (Optional)
                    </label>
                    <textarea name="explanation" id="explanation" class="form-control" 
                              rows="2" placeholder="Provide an explanation for the correct answer..."><?= old('explanation') ?></textarea>
                    <small class="text-muted">This will help students understand why the answer is correct.</small>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <a href="<?= base_url('admin/practice-questions') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Question
                        </button>
                    </div>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Show preview of selected correct answer
document.getElementById('correct_answer').addEventListener('change', function() {
    const selectedOption = this.value;
    const hint = document.querySelector('.correct-answer-hint');
    
    if (selectedOption) {
        const optionText = document.getElementById('option_' + selectedOption.toLowerCase()).value;
        if (optionText) {
            hint.innerHTML = `<i class="fas fa-check-circle me-1"></i>Correct Answer: <strong>Option ${selectedOption}</strong> - ${optionText}`;
        }
    }
});

// Update hint when option text changes
['a', 'b', 'c', 'd'].forEach(option => {
    document.getElementById('option_' + option).addEventListener('input', function() {
        const correctAnswer = document.getElementById('correct_answer').value;
        if (correctAnswer && correctAnswer.toLowerCase() === option) {
            const hint = document.querySelector('.correct-answer-hint');
            hint.innerHTML = `<i class="fas fa-check-circle me-1"></i>Correct Answer: <strong>Option ${correctAnswer}</strong> - ${this.value}`;
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    CBT.showToast('Fill in all required fields to create a practice question.', 'info');
});
</script>
<?= $this->endSection() ?>
