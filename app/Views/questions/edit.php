<?= $this->extend($layout ?? 'layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0">
        <h5 class="card-title mb-0">
            <i class="fas fa-edit text-primary me-2"></i>
            Edit Question
        </h5>
    </div>

    <div class="card-body">
        <form method="POST" action="<?= base_url(($route_prefix ?? '') . 'questions/edit/' . $question['id']) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Subject *</label>
                        <select class="form-control <?= $validation->hasError('subject_id') ? 'is-invalid' : '' ?>"
                                name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>" <?= (old('subject_id', $question['subject_id']) == $subject['id']) ? 'selected' : '' ?>>
                                    <?= esc($subject['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('subject_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('subject_id') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Question Type *</label>
                        <select class="form-control <?= $validation->hasError('question_type') ? 'is-invalid' : '' ?>"
                                name="question_type" id="questionType" required>
                            <option value="">Select Type</option>
                            <?php foreach ($question_types as $key => $label): ?>
                                <option value="<?= $key ?>" <?= (old('question_type', $question['question_type']) == $key) ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('question_type')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('question_type') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Difficulty *</label>
                        <select class="form-control <?= $validation->hasError('difficulty') ? 'is-invalid' : '' ?>"
                                name="difficulty" required>
                            <?php foreach ($difficulties as $key => $label): ?>
                                <option value="<?= $key ?>" <?= (old('difficulty', $question['difficulty']) == $key) ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('difficulty')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('difficulty') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Question Text *</label>
                <textarea class="form-control <?= $validation->hasError('question_text') ? 'is-invalid' : '' ?>"
                          name="question_text" id="questionText" rows="4" required
                          placeholder="Enter your question here..."
                          onblur="validateQuestionText(this)"><?= old('question_text', $question['question_text']) ?></textarea>
                <?php if ($validation->hasError('question_text')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('question_text') ?></div>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Marks *</label>
                        <input type="number" class="form-control <?= $validation->hasError('points') ? 'is-invalid' : '' ?>"
                               name="points" value="<?= old('points', $question['points']) ?>" min="1" max="100" required>
                        <?php if ($validation->hasError('points')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('points') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" class="form-control" name="time_limit"
                               value="<?= old('time_limit', $question['time_limit']) ?>" min="0" placeholder="Optional">
                    </div>
                </div>
            </div>

            <!-- Options Section (for MCQ, True/False, etc.) -->
            <div id="optionsSection" style="display: none;">
                <h6 class="mb-3">Answer Options</h6>
                <div id="optionsList">
                    <!-- Existing options will be loaded here -->
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addOption()">
                    <i class="fas fa-plus me-1"></i>
                    Add Option
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label">Explanation (Optional)</label>
                <textarea class="form-control" name="explanation" rows="3"
                          placeholder="Explain the correct answer..."><?= old('explanation', $question['explanation']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Hints (Optional)</label>
                <textarea class="form-control" name="hints" rows="2"
                          placeholder="Provide hints for students..."><?= old('hints', $question['hints']) ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Questions
                </a>

                <div>
                    <a href="<?= base_url(($route_prefix ?? '') . 'questions/preview/' . $question['id']) ?>"
                       class="btn btn-outline-info me-2" target="_blank">
                        <i class="fas fa-eye me-2"></i>
                        Preview
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update Question
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let optionCount = 0;
const existingOptions = <?= json_encode($question['options'] ?? []) ?>;

// Question text validation function
function validateQuestionText(input) {
    const text = input.value.trim();
    const minLength = 6;

    // Remove any existing validation feedback
    const existingFeedback = input.parentElement.querySelector('.validation-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }

    // Remove validation classes
    input.classList.remove('is-invalid', 'is-valid');

    if (text.length > 0 && text.length < minLength) {
        // Show error
        input.classList.add('is-invalid');
        const feedback = document.createElement('div');
        feedback.className = 'validation-feedback invalid-feedback';
        feedback.style.display = 'block';
        feedback.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>Question must be at least ${minLength} characters long. Current: ${text.length}`;
        input.parentElement.appendChild(feedback);
        return false;
    } else if (text.length >= minLength) {
        // Show success
        input.classList.add('is-valid');
        return true;
    }

    return true; // Empty field is handled by required validation
}

// Check for duplicate questions (excluding current question)
function checkForDuplicates() {
    const questionText = document.getElementById('questionText').value.trim();
    const questionType = document.getElementById('questionType').value;
    const subjectId = document.getElementById('subject').value;
    const currentQuestionId = '<?= $question['id'] ?>';

    if (!questionText || !questionType || !subjectId) {
        return;
    }

    const formData = new FormData();
    formData.append('question_text', questionText);
    formData.append('question_type', questionType);
    formData.append('subject_id', subjectId);
    formData.append('exclude_id', currentQuestionId); // Exclude current question from duplicate check

    // Add options if available
    const options = [];
    const optionInputs = document.querySelectorAll('#optionsList input[name*="option_text"]');
    optionInputs.forEach(input => {
        if (input.value.trim()) {
            options.push({ option_text: input.value.trim() });
        }
    });
    formData.append('options', JSON.stringify(options));

    fetch('<?= base_url(($route_prefix ?? '') . 'questions/check-duplicate') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Remove any existing duplicate alert
        const existingAlert = document.querySelector('.duplicate-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        if (data.is_duplicate) {
            // Show duplicate warning
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning duplicate-alert mt-2';
            alertDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>' + (data.message || 'A similar question already exists!');
            document.getElementById('questionText').parentElement.appendChild(alertDiv);
        }
    })
    .catch(error => {
        console.error('Error checking duplicates:', error);
    });
}

document.getElementById('questionType').addEventListener('change', function() {
    const type = this.value;
    const optionsSection = document.getElementById('optionsSection');
    const optionsList = document.getElementById('optionsList');

    // Clear existing options
    optionsList.innerHTML = '';
    optionCount = 0;

    // Show/hide options section based on question type
    if (['mcq', 'true_false', 'yes_no', 'drag_drop'].includes(type)) {
        optionsSection.style.display = 'block';

        // Load existing options or add defaults
        if (existingOptions && existingOptions.length > 0) {
            existingOptions.forEach(option => {
                addOption(option.option_text, option.is_correct);
            });
        } else {
            // Add default options for specific types
            if (type === 'true_false') {
                addOption('True');
                addOption('False');
            } else if (type === 'yes_no') {
                addOption('Yes');
                addOption('No');
            } else if (type === 'mcq') {
                addOption();
                addOption();
            }
        }
    } else {
        optionsSection.style.display = 'none';
    }
});

function addOption(defaultText = '', isCorrect = false) {
    const optionsList = document.getElementById('optionsList');
    const optionDiv = document.createElement('div');
    optionDiv.className = 'mb-2 d-flex align-items-center';
    optionDiv.innerHTML = `
        <div class="form-check me-3">
            <input class="form-check-input" type="checkbox" name="options[${optionCount}][is_correct]" value="1" ${isCorrect ? 'checked' : ''}>
            <label class="form-check-label">Correct</label>
        </div>
        <input type="text" class="form-control me-2" name="options[${optionCount}][option_text]"
               placeholder="Option ${optionCount + 1}" value="${defaultText}" required>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOption(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;

    optionsList.appendChild(optionDiv);
    optionCount++;
}

function removeOption(button) {
    button.parentElement.remove();
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    const questionType = document.getElementById('questionType').value;
    if (questionType) {
        document.getElementById('questionType').dispatchEvent(new Event('change'));
    }

    // Add duplicate checking to question text blur
    const questionTextInput = document.getElementById('questionText');
    if (questionTextInput) {
        questionTextInput.addEventListener('blur', function() {
            validateQuestionText(this);
            checkForDuplicates();
        });
    }
});
</script>
<?= $this->endSection() ?>
