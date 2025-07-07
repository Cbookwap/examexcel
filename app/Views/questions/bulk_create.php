<?= $this->extend($layout ?? 'layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .question-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        background: white;
    }
    .question-card.collapsed {
        border-color: var(--primary-color);
        box-shadow: 0 2px 10px rgba(var(--primary-color-rgb), 0.1);
    }
    .question-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 13px 13px 0 0;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    .question-header:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
    }
    .question-body {
        padding: 1.5rem;
        display: none;
    }
    .question-body.show {
        display: block;
    }
    .collapse-icon {
        transition: transform 0.3s ease;
        font-size: 24px;
    }
    .collapsed .collapse-icon {
        transform: rotate(180deg);
    }
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .question-type-specific {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }
    .bulk-controls {
        position: sticky;
        top: 20px;
        z-index: 100;
        background: white;
        padding: 1rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    /* Fix text visibility issues */
    .alert-info {
        background-color: #e7f3ff !important;
        border-color: #b3d9ff !important;
        color: #0c5460 !important;
    }

    .alert-info h6 {
        color: #0c5460 !important;
        font-weight: 600 !important;
    }

    .alert-info .text-muted {
        color: #495057 !important;
        font-weight: 500 !important;
    }

    .progress-circle {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        color: white !important;
        font-weight: bold !important;
        border: 2px solid white !important;
        box-shadow: 0 2px 8px rgba(var(--primary-color-rgb), 0.3) !important;
    }

    .badge.bg-primary {
        background-color: var(--primary-color) !important;
        color: white !important;
        font-weight: 600 !important;
    }

    .badge.bg-success {
        background-color: #28a745 !important;
        color: white !important;
        font-weight: 600 !important;
    }

    .badge.bg-secondary {
        background-color: #6c757d !important;
        color: white !important;
        font-weight: 600 !important;
    }

    /* Improve progress indicator text visibility */
    #bulkProgressIndicator h6 {
        color: #0c5460 !important;
        font-weight: 700 !important;
    }

    #bulkProgressIndicator .text-muted {
        color: #495057 !important;
        font-weight: 500 !important;
    }

    #bulkProgressIndicator .alert-info {
        border: 2px solid var(--primary-color) !important;
        background: linear-gradient(135deg, #f8f9ff 0%, #e7f3ff 100%) !important;
    }

    /* Fix overlapping issue with question headers and content */
    .question-card {
        margin-top: 1.5rem;
        position: relative;
    }

    .question-header {
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 5;
    }

    .question-body {
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        position: relative;
        z-index: 1;
    }

    /* Ensure proper spacing for form elements */
    .question-type-specific {
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
        border: 1px solid #e9ecef;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Bulk Create Questions</h4>
                <p class="text-muted mb-0">Create multiple questions at once with collapsible interface</p>
            </div>
            <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Questions
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2">check_circle</i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2">error</i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?= base_url(($route_prefix ?? '') . 'questions/create-bulk') ?>" id="bulkCreateForm">
    <?= csrf_field() ?>

    <!-- Bulk Controls -->
    <div class="bulk-controls">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Subject *</label>
                <select class="form-select" name="subject_id" id="bulkSubject" onchange="updateBulkProgress()" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Class</label>
                <select class="form-select" name="class_id" id="bulkClass" onchange="updateBulkProgress()">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>"><?= esc($class['display_name'] ?? $class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Exam Type</label>
                <select class="form-select" name="exam_type_id">
                    <option value="">Select Type</option>
                    <?php foreach ($examTypes as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <!-- Progress Indicator for Bulk -->
        <div id="bulkProgressIndicator" class="row mt-3" style="display: none;">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center">
                    <div class="me-3">
                        <div class="progress-circle" style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: bold; border: 2px solid white; box-shadow: 0 2px 8px rgba(var(--primary-color-rgb), 0.3);">
                            <span id="bulkQuestionCount">0</span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-semibold" style="color: #0c5460 !important; font-weight: 700;">Existing Questions</h6>
                        <small id="bulkProgressDetails" style="color: #495057 !important; font-weight: 500;">For this subject & class in current session/term</small>
                    </div>
                    <div class="ms-3">
                        <span class="badge bg-primary" id="newQuestionsCount" style="background-color: var(--primary-color) !important; color: white !important; font-weight: 600;">0 new questions to add</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" class="btn btn-primary" onclick="addQuestion()">
                        <i class="material-symbols-rounded me-2">add</i>Add Question
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="expandAll()">
                        <i class="material-symbols-rounded me-2">unfold_more</i>Expand All
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="collapseAll()">
                        <i class="material-symbols-rounded me-2">unfold_less</i>Collapse All
                    </button>
                    <span class="text-muted ms-3">
                        <i class="material-symbols-rounded me-1">info</i>
                        Click question headers to expand/collapse
                    </span>

                </div>
            </div>
        </div>
    </div>

    <!-- Questions Container -->
    <div id="questionsContainer">
        <!-- Questions will be added here dynamically -->
    </div>

    <!-- Submit Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="material-symbols-rounded me-2">save</i>Create All Questions
                    </button>
                    <p class="text-muted mt-2 mb-0">
                        <i class="material-symbols-rounded me-1">lightbulb</i>
                        Make sure to fill all required fields before submitting
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let questionCount = 0;

// Local icon conversion function as fallback
function localConvertMaterialIcons() {
    const iconMap = {
        'add': 'fas fa-plus',
        'unfold_more': 'fas fa-expand-alt',
        'unfold_less': 'fas fa-compress-alt',
        'expand_more': 'fas fa-chevron-down',
        'expand_less': 'fas fa-chevron-up',
        'quiz': 'fas fa-question-circle',
        'delete': 'fas fa-trash',
        'info': 'fas fa-info-circle',
        'save': 'fas fa-save',
        'lightbulb': 'fas fa-lightbulb',
        'radio_button_checked': 'fas fa-dot-circle',
        'toggle_on': 'fas fa-toggle-on',
        'help': 'fas fa-question',
        'calculate': 'fas fa-calculator',
        'text_fields': 'fas fa-text-width',
        'bookmark': 'fas fa-bookmark',
        'bookmark_add': 'fas fa-bookmark'
    };

    const materialIcons = document.querySelectorAll('.material-symbols-rounded');
    let converted = 0;

    materialIcons.forEach(icon => {
        const iconText = icon.textContent.trim();

        if (iconMap[iconText] && !icon.hasAttribute('data-converted')) {
            icon.className = icon.className.replace('material-symbols-rounded', iconMap[iconText]);
            icon.textContent = '';
            icon.setAttribute('data-converted', 'true');
            converted++;
            console.log(`Converted icon: ${iconText} -> ${iconMap[iconText]}`);
        }
    });

    console.log(`Local conversion: ${converted} icons converted`);
    return converted;
}

// Add initial question on page load
document.addEventListener('DOMContentLoaded', function() {
    addQuestion();

    // Ensure icons are converted after page load with multiple attempts
    setTimeout(() => {
        console.log('Attempting icon conversion...');
        if (typeof convertMaterialIcons === 'function') {
            convertMaterialIcons();
            console.log('Global icon conversion called');
        } else {
            console.log('convertMaterialIcons function not available');
        }
        localConvertMaterialIcons();
    }, 200);

    // Additional conversion attempts
    setTimeout(() => {
        if (typeof convertMaterialIcons === 'function') {
            convertMaterialIcons();
        }
        localConvertMaterialIcons();
    }, 500);

    setTimeout(() => {
        if (typeof convertMaterialIcons === 'function') {
            convertMaterialIcons();
        }
        localConvertMaterialIcons();
    }, 1000);
});

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');

    const questionHtml = `
        <div class="question-card" id="question-${questionCount}">
            <div class="question-header" onclick="toggleQuestion(${questionCount})">
                <div>
                    <h6 class="mb-0 text-white">
                        <i class="material-symbols-rounded me-2 text-white">quiz</i>
                        Question ${questionCount}
                    </h6>
                    <small class="opacity-75">Click to expand/collapse</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-light" onclick="event.stopPropagation(); removeQuestion(${questionCount})">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                    <i class="fas fa-chevron-down collapse-icon"></i>
                </div>
            </div>
            <div class="question-body" id="question-body-${questionCount}">
                ${getQuestionFormHtml(questionCount)}
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', questionHtml);

    // Convert any new Material Icons to FontAwesome
    setTimeout(() => {
        if (typeof convertMaterialIcons === 'function') {
            convertMaterialIcons();
        }
        localConvertMaterialIcons();
    }, 50);

    // Auto-expand the new question
    setTimeout(() => {
        toggleQuestion(questionCount);
    }, 100);

    // Update new questions count
    updateNewQuestionsCount();
}

function getQuestionFormHtml(index) {
    return `
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label text-white">Question Text *</label>
                <textarea class="form-control" name="questions[${index}][question_text]" rows="3"
                          placeholder="Enter your question here..." required
                          onblur="validateBulkQuestionText(this); checkBulkQuestionForDuplicates(this);"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Question Type *</label>
                <select class="form-select" name="questions[${index}][question_type]"
                        onchange="showQuestionTypeOptions(${index}, this.value)" required>
                    <option value="">Select Type</option>
                    <option value="mcq">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="yes_no">Yes/No</option>
                    <option value="fill_blank">Fill in the Blank</option>
                    <option value="short_answer">Short Answer</option>
                    <option value="essay">Essay</option>
                    <option value="math_equation">Math Equation</option>
                    <option value="image_based">Image Based</option>
                    <option value="drag_drop">Drag & Drop</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Difficulty *</label>
                <select class="form-select" name="questions[${index}][difficulty]" required>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Marks *</label>
                <input type="number" class="form-control" name="questions[${index}][points]"
                       value="1" min="1" max="100" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Time Limit (seconds)</label>
                <input type="number" class="form-control" name="questions[${index}][time_limit]"
                       placeholder="Optional time limit">
            </div>
            <div class="col-md-6">
                <label class="form-label">Explanation (Optional)</label>
                <textarea class="form-control" name="questions[${index}][explanation]" rows="2"
                          placeholder="Explanation shown to students after exam..."></textarea>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Custom Instruction (Optional)</label>
                <div class="input-group">
                    <textarea class="form-control" name="questions[${index}][custom_instruction]" rows="2"
                              placeholder="Specific instruction for this question..." id="instruction-${index}"></textarea>
                    <button type="button" class="btn btn-outline-primary" onclick="showInstructionTemplates(${index})" title="Load from template">
                        <i class="material-symbols-rounded">bookmark</i>
                    </button>
                </div>
                <div class="mt-1">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="saveAsTemplate(${index})" title="Save as template">
                        <i class="material-symbols-rounded me-1" style="font-size: 14px;">bookmark_add</i>Save as Template
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Hints (Optional)</label>
                <textarea class="form-control" name="questions[${index}][hints]" rows="2"
                          placeholder="Helpful hints for students during the exam..."></textarea>
            </div>
        </div>

        <!-- Question Type Specific Options -->
        <div id="question-type-options-${index}" class="question-type-specific">
            <!-- Dynamic content based on question type -->
        </div>
    `;
}

function toggleQuestion(index) {
    const card = document.getElementById(`question-${index}`);
    const body = document.getElementById(`question-body-${index}`);
    const icon = card.querySelector('.collapse-icon');

    if (body.classList.contains('show')) {
        body.classList.remove('show');
        card.classList.remove('collapsed');
        // Set FontAwesome class directly instead of text content
        icon.className = 'fas fa-chevron-down collapse-icon';
        icon.textContent = '';
    } else {
        body.classList.add('show');
        card.classList.add('collapsed');
        // Set FontAwesome class directly instead of text content
        icon.className = 'fas fa-chevron-up collapse-icon';
        icon.textContent = '';
    }
}

function removeQuestion(index) {
    if (confirm('Are you sure you want to remove this question?')) {
        const questionCard = document.getElementById(`question-${index}`);
        questionCard.remove();
        updateNewQuestionsCount();
    }
}

function expandAll() {
    const cards = document.querySelectorAll('.question-card');
    cards.forEach(card => {
        const body = card.querySelector('.question-body');
        const icon = card.querySelector('.collapse-icon');

        body.classList.add('show');
        card.classList.add('collapsed');
        // Set FontAwesome class directly instead of text content
        icon.className = 'fas fa-chevron-up collapse-icon';
        icon.textContent = '';
    });
}

function collapseAll() {
    const cards = document.querySelectorAll('.question-card');
    cards.forEach(card => {
        const body = card.querySelector('.question-body');
        const icon = card.querySelector('.collapse-icon');

        body.classList.remove('show');
        card.classList.remove('collapsed');
        // Set FontAwesome class directly instead of text content
        icon.className = 'fas fa-chevron-down collapse-icon';
        icon.textContent = '';
    });
}

function showQuestionTypeOptions(index, questionType) {
    const container = document.getElementById(`question-type-options-${index}`);

    if (!questionType) {
        container.style.display = 'none';
        return;
    }

    let optionsHtml = '';

    switch (questionType) {
        case 'mcq':
            optionsHtml = getMCQOptionsHtml(index);
            break;
        case 'true_false':
            optionsHtml = getTrueFalseOptionsHtml(index);
            break;
        case 'yes_no':
            optionsHtml = getYesNoOptionsHtml(index);
            break;
        case 'fill_blank':
            optionsHtml = getFillBlankOptionsHtml(index);
            break;
        case 'short_answer':
            optionsHtml = getShortAnswerOptionsHtml(index);
            break;
        case 'essay':
            optionsHtml = getEssayOptionsHtml(index);
            break;
        case 'math_equation':
            optionsHtml = getMathEquationOptionsHtml(index);
            break;
        case 'image_based':
            optionsHtml = getImageBasedOptionsHtml(index);
            break;
        case 'drag_drop':
            optionsHtml = getDragDropOptionsHtml(index);
            break;
    }

    container.innerHTML = optionsHtml;
    container.style.display = optionsHtml ? 'block' : 'none';

    // Convert any new Material Icons to FontAwesome
    setTimeout(() => {
        if (typeof convertMaterialIcons === 'function') {
            convertMaterialIcons();
        }
        localConvertMaterialIcons();
    }, 50);
}

function getMCQOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">radio_button_checked</i>
            Multiple Choice Options
        </h6>
        <div id="mcq-options-${index}">
            <div class="option-item mb-2">
                <div class="row g-2">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="questions[${index}][options][0][option_text]"
                               placeholder="Option A" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="questions[${index}][options][0][is_correct]" value="1">
                            <label class="form-check-label">Correct Answer</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="option-item mb-2">
                <div class="row g-2">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="questions[${index}][options][1][option_text]"
                               placeholder="Option B" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="questions[${index}][options][1][is_correct]" value="1">
                            <label class="form-check-label">Correct Answer</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMCQOption(${index})">
            <i class="material-symbols-rounded me-1">add</i>Add Option
        </button>
    `;
}

function getTrueFalseOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">toggle_on</i>
            True/False Options
        </h6>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="questions[${index}][correct_option]" value="0" id="true-${index}">
                    <label class="form-check-label" for="true-${index}">True is correct</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="questions[${index}][correct_option]" value="1" id="false-${index}">
                    <label class="form-check-label" for="false-${index}">False is correct</label>
                </div>
            </div>
        </div>
        <input type="hidden" name="questions[${index}][options][0][option_text]" value="True">
        <input type="hidden" name="questions[${index}][options][1][option_text]" value="False">
    `;
}

function getYesNoOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">help</i>
            Yes/No Options
        </h6>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="questions[${index}][correct_option]" value="0" id="yes-${index}">
                    <label class="form-check-label" for="yes-${index}">Yes is correct</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="questions[${index}][correct_option]" value="1" id="no-${index}">
                    <label class="form-check-label" for="no-${index}">No is correct</label>
                </div>
            </div>
        </div>
        <input type="hidden" name="questions[${index}][options][0][option_text]" value="Yes">
        <input type="hidden" name="questions[${index}][options][1][option_text]" value="No">
    `;
}

function getMathEquationOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">calculate</i>
            Math Equation Configuration
        </h6>
        <div class="alert alert-info">
            <i class="material-symbols-rounded me-2">info</i>
            <strong>For Teachers:</strong> Use LaTeX/MathML to create beautifully formatted questions<br>
            <strong>For Students:</strong> Type simple text answers (no complex formatting needed)<br>
            <strong>Key Point:</strong> Students never need to learn LaTeX/MathML - they just type normal answers!
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="questions[${index}][allow_calculator]">
                    <label class="form-check-label">Allow calculator during exam</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-control" name="questions[${index}][equation_format]">
                        <option value="text">Plain Text Questions (Basic)</option>
                        <option value="latex">LaTeX Questions (Beautiful Math Formatting)</option>
                        <option value="mathml">MathML Questions (Advanced Formatting)</option>
                    </select>
                    <label>Question Format (How YOU write the question)</label>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="number" class="form-control" name="questions[${index}][decimal_places]" value="2" min="0" max="10">
                    <label>Decimal Places</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="number" class="form-control" name="questions[${index}][tolerance]" value="0.01" step="0.001" min="0">
                    <label>Tolerance (±)</label>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Correct Answers (Simple text answers students will type):</label>
            <div class="alert alert-success mb-3">
                <i class="material-symbols-rounded me-2">lightbulb</i>
                <strong>Remember:</strong> No matter how complex your question formatting is (LaTeX/MathML),
                students always type simple answers like "x=5", "3/4", "28.27", etc.
            </div>
            <div id="math-answers-${index}">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="questions[${index}][math_answers][]"
                           placeholder="e.g., x=5, 3/4, 28.27" required>
                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMathAnswer(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Acceptable Answer
            </button>
        </div>
    `;
}

function getFillBlankOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">text_fields</i>
            Fill in the Blank Configuration
        </h6>
        <div class="alert alert-info">
            <i class="material-symbols-rounded me-2">info</i>
            <strong>How to use:</strong> Use [BLANK] in your question text where students should fill answers.
            <br><br><strong>Examples:</strong>
            <br>• Geography: "The capital of [BLANK] is [BLANK] and it is located in the [BLANK] region."
            <br>• Math: "If x = 5, then 2x = [BLANK] and x² = [BLANK]."
            <br>• Science: "Water boils at [BLANK]°C and freezes at [BLANK]°C."
            <br><br><strong>Scoring:</strong> If you use multiple [BLANK] markers, the total mark will be divided equally among all blanks.
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="questions[${index}][case_sensitive]">
                    <label class="form-check-label">Case sensitive answers</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="questions[${index}][exact_match]">
                    <label class="form-check-label">Require exact match (no partial credit)</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="detectBlanks(${index})">
                <i class="material-symbols-rounded me-1">search</i>Detect Blanks in Question
            </button>
            <small class="text-muted d-block mt-1">Click this after writing your question text to automatically detect [BLANK] markers.</small>
        </div>

        <div class="mt-3">
            <label class="form-label">Answers for each blank:</label>
            <div id="blank-answers-${index}">
                <div class="alert alert-secondary text-center">
                    <i class="material-symbols-rounded me-2">info</i>
                    Write your question text with [BLANK] markers first, then click "Detect Blanks" to configure answers.
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h6 class="mb-2">Manual Blank Configuration</h6>
            <div class="alert alert-warning">
                <i class="material-symbols-rounded me-2">warning</i>
                Use this if automatic detection doesn't work or you want to add blanks manually.
            </div>
            <div id="manual-blank-answers-${index}">
                <div class="blank-group mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Blank Number</label>
                            <input type="number" class="form-control" value="1" min="1" readonly>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Acceptable Answers (comma-separated)</label>
                            <input type="text" class="form-control" name="questions[${index}][manual_blank_answers][1]"
                                   placeholder="answer1, answer2, answer3">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.blank-group').remove()">
                                <i class="material-symbols-rounded">delete</i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">Enter multiple acceptable answers separated by commas. Example: "Nigeria, nigeria, NIGERIA"</small>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addManualBlankGroup(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Manual Blank
            </button>
        </div>
    `;
}

function getEssayOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">article</i>
            Essay Configuration
        </h6>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="number" class="form-control" name="questions[${index}][min_words]" value="100" min="1">
                    <label>Minimum Words</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="number" class="form-control" name="questions[${index}][max_words_essay]" value="1000" min="1">
                    <label>Maximum Words</label>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="questions[${index}][enable_rubric]"
                       onchange="toggleRubricOptions(${index}, this.checked)">
                <label class="form-check-label">Enable AI-Assisted Grading with Rubric</label>
            </div>

            <div id="rubric-options-${index}" style="display: none;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-control" name="questions[${index}][rubric_type]">
                                <option value="content_only">Content Quality Only</option>
                                <option value="comprehensive">Comprehensive (Content + Organization + Grammar)</option>
                                <option value="custom">Custom Criteria</option>
                            </select>
                            <label>Rubric Type</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="questions[${index}][rubric_max_score]" value="10" min="1" max="100">
                            <label>Maximum Mark</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Model Answer (Key points AI should look for):</label>
                    <textarea class="form-control" name="questions[${index}][model_answer]" rows="3"
                              placeholder="Provide key points that should be covered in a good answer..."></textarea>
                </div>
            </div>
        </div>
    `;
}

// Helper functions
function addMCQOption(index) {
    const container = document.getElementById(`mcq-options-${index}`);
    const optionCount = container.children.length;

    const optionHtml = `
        <div class="option-item mb-2">
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="questions[${index}][options][${optionCount}][option_text]"
                           placeholder="Option ${String.fromCharCode(65 + optionCount)}" required>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="questions[${index}][options][${optionCount}][is_correct]" value="1">
                        <label class="form-check-label">Correct Answer</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
}

function addMathAnswer(index) {
    const container = document.getElementById(`math-answers-${index}`);
    const answerHtml = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="questions[${index}][math_answers][]"
                   placeholder="e.g., x=5, 3/4, 28.27" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', answerHtml);
}

function toggleRubricOptions(index, enabled) {
    const container = document.getElementById(`rubric-options-${index}`);
    container.style.display = enabled ? 'block' : 'none';
}

// Placeholder functions for other question types
function getShortAnswerOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">short_text</i>
            Short Answer Configuration
        </h6>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="questions[${index}][max_words]" value="50" min="1">
            <label>Maximum Words</label>
        </div>
        <div class="mt-3">
            <label class="form-label">Acceptable Answers:</label>
            <div id="short-answers-${index}">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="questions[${index}][short_answers][]"
                           placeholder="Acceptable answer" required>
                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addShortAnswer(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Answer
            </button>
        </div>
    `;
}

function getImageBasedOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">image</i>
            Image Based Configuration
        </h6>
        <div class="alert alert-info">
            <i class="material-symbols-rounded me-2">info</i>
            <strong>How Image Questions Work:</strong>
            <br>• <strong>Image Options:</strong> Students choose from multiple images as answers
            <br>• <strong>Clickable Areas:</strong> Students click on specific areas of an image
            <br>• <strong>Image Labeling:</strong> Students identify parts of an image
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Question Image</label>
                <input type="file" class="form-control" name="questions[${index}][question_image]"
                       accept="image/*" onchange="previewQuestionImage(${index}, this)">
                <small class="text-muted">Upload the main image for the question</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Image Question Type</label>
                <select class="form-select" name="questions[${index}][image_question_type]"
                        onchange="toggleImageQuestionType(${index}, this.value)">
                    <option value="image_options">Image Options (Multiple images to choose from)</option>
                    <option value="clickable_areas">Clickable Areas (Click on correct areas)</option>
                    <option value="image_labeling">Image Labeling (Identify parts)</option>
                </select>
            </div>
        </div>

        <div class="mt-3" id="image-preview-${index}" style="display: none;">
            <label class="form-label">Image Preview:</label>
            <div class="border rounded p-2">
                <img id="preview-img-${index}" src="" alt="Preview" style="max-width: 100%; max-height: 200px;">
            </div>
        </div>

        <!-- Image Options Type -->
        <div id="image-options-config-${index}" class="mt-3">
            <h6 class="mb-3">Image Options Configuration</h6>
            <div class="alert alert-info">
                <i class="material-symbols-rounded me-2">lightbulb</i>
                Students will choose from multiple images. Upload option images below.
            </div>
            <div id="image-options-list-${index}">
                <div class="image-option-item mb-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Option Image 1</label>
                            <input type="file" class="form-control" name="questions[${index}][option_images][]"
                                   accept="image/*" onchange="previewOptionImage(${index}, 0, this)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Option Label</label>
                            <input type="text" class="form-control" name="questions[${index}][option_labels][]"
                                   placeholder="Option A" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Correct?</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox"
                                       name="questions[${index}][correct_image_options][]" value="0">
                                <label class="form-check-label">Correct</label>
                            </div>
                        </div>
                    </div>
                    <div id="option-preview-${index}-0" class="mt-2" style="display: none;">
                        <img src="" alt="Option Preview" style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addImageOption(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Image Option
            </button>
        </div>

        <!-- Clickable Areas Type -->
        <div id="clickable-areas-config-${index}" class="mt-3" style="display: none;">
            <h6 class="mb-3">Clickable Areas Configuration</h6>
            <div class="alert alert-info">
                <i class="material-symbols-rounded me-2">info</i>
                Define clickable areas on the image. Students will click on correct areas.
            </div>
            <div id="clickable-areas-list-${index}">
                <div class="clickable-area-item mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Area Name</label>
                            <input type="text" class="form-control" name="questions[${index}][area_names][]"
                                   placeholder="e.g., Heart" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">X Position (%)</label>
                            <input type="number" class="form-control" name="questions[${index}][area_x][]"
                                   min="0" max="100" placeholder="50">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Y Position (%)</label>
                            <input type="number" class="form-control" name="questions[${index}][area_y][]"
                                   min="0" max="100" placeholder="50">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Width (%)</label>
                            <input type="number" class="form-control" name="questions[${index}][area_width][]"
                                   min="1" max="50" placeholder="10">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Height (%)</label>
                            <input type="number" class="form-control" name="questions[${index}][area_height][]"
                                   min="1" max="50" placeholder="10">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Correct?</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox"
                                       name="questions[${index}][correct_areas][]" value="0">
                                <label class="form-check-label">✓</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addClickableArea(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Clickable Area
            </button>
        </div>

        <!-- Image Labeling Type -->
        <div id="image-labeling-config-${index}" class="mt-3" style="display: none;">
            <h6 class="mb-3">Image Labeling Configuration</h6>
            <div class="alert alert-info">
                <i class="material-symbols-rounded me-2">info</i>
                Students will identify and label parts of the image.
            </div>
            <div id="image-labels-list-${index}">
                <div class="image-label-item mb-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Label Text</label>
                            <input type="text" class="form-control" name="questions[${index}][label_texts][]"
                                   placeholder="e.g., Mitochondria" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">X Position (%)</label>
                            <input type="number" class="form-control" name="questions[${index}][label_x][]"
                                   min="0" max="100" placeholder="50">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Y Position (%)</label>
                            <input type="number" class="form-control" name="questions[${index}][label_y][]"
                                   min="0" max="100" placeholder="50">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Acceptable Answers</label>
                            <input type="text" class="form-control" name="questions[${index}][acceptable_labels][]"
                                   placeholder="mitochondria,powerhouse" title="Comma-separated acceptable answers">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm mt-4"
                                    onclick="this.closest('.image-label-item').remove()">
                                <i class="material-symbols-rounded">delete</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addImageLabel(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Label Point
            </button>
        </div>
    `;
}

function getDragDropOptionsHtml(index) {
    return `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">drag_indicator</i>
            Drag & Drop Configuration
        </h6>
        <div class="alert alert-info">
            <i class="material-symbols-rounded me-2">info</i>
            <strong>How Drag & Drop Works:</strong>
            <br>• <strong>Matching:</strong> "Drag countries to their capitals" - Students drag "Nigeria" to "Abuja"
            <br>• <strong>Categorization:</strong> "Drag animals into groups" - Students drag "Lion" to "Mammals"
            <br>• <strong>Sequencing:</strong> "Arrange events in order" - Students drag events to timeline positions
            <br>• <strong>Labeling:</strong> "Label diagram parts" - Students drag labels to correct positions
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3">Draggable Items</h6>
                <div class="alert alert-success">
                    <i class="material-symbols-rounded me-2">lightbulb</i>
                    <strong>Tip:</strong> These are the items students will drag. Make them clear and concise.
                </div>
                <div id="drag-items-list-${index}">
                    <div class="drag-item mb-2">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label">Draggable Item 1</label>
                                <input type="text" class="form-control" name="questions[${index}][drag_items][]"
                                       placeholder="e.g., Nigeria, Lion, 1914" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Item Type</label>
                                <select class="form-select" name="questions[${index}][drag_item_types][]">
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2" style="display: none;" id="drag-item-image-${index}-0">
                            <input type="file" class="form-control" name="questions[${index}][drag_item_images][]"
                                   accept="image/*" onchange="previewDragItemImage(${index}, 0, this)">
                            <div class="mt-2" id="drag-item-preview-${index}-0" style="display: none;">
                                <img src="" alt="Drag Item Preview" style="max-width: 100px; max-height: 60px; border: 1px solid #ddd; border-radius: 3px;">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addDragItem(${index})">
                    <i class="material-symbols-rounded me-1">add</i>Add Draggable Item
                </button>
            </div>

            <div class="col-md-6">
                <h6 class="mb-3">Drop Zones</h6>
                <div class="alert alert-warning">
                    <i class="material-symbols-rounded me-2">target</i>
                    <strong>Tip:</strong> These are the areas where students drop items. Be specific about what goes where.
                </div>
                <div id="drop-zones-list-${index}">
                    <div class="drop-zone mb-2">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label">Drop Zone 1</label>
                                <input type="text" class="form-control" name="questions[${index}][drop_zones][]"
                                       placeholder="e.g., Capitals, Mammals, Timeline 1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Items</label>
                                <input type="number" class="form-control" name="questions[${index}][drop_zone_limits][]"
                                       value="1" min="1" max="10">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Zone Description (Optional)</label>
                            <input type="text" class="form-control" name="questions[${index}][drop_zone_descriptions][]"
                                   placeholder="Brief description of what should be dropped here">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addDropZone(${index})">
                    <i class="material-symbols-rounded me-1">add</i>Add Drop Zone
                </button>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Correct Matches</h6>
            <div class="alert alert-info">
                <i class="material-symbols-rounded me-2">info</i>
                Define which draggable items belong in which drop zones. Add drag items and drop zones first.
            </div>
            <div id="drag-drop-matches-${index}">
                <div class="match-item mb-2">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <label class="form-label">Draggable Item</label>
                            <select class="form-select" name="questions[${index}][match_drag_items][]" required>
                                <option value="">Select draggable item...</option>
                            </select>
                        </div>
                        <div class="col-md-1 text-center">
                            <label class="form-label">&nbsp;</label>
                            <div class="mt-2">
                                <i class="material-symbols-rounded" style="color: var(--primary-color);">arrow_forward</i>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Drop Zone</label>
                            <select class="form-select" name="questions[${index}][match_drop_zones][]" required>
                                <option value="">Select drop zone...</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-sm mt-2"
                                    onclick="this.closest('.match-item').remove()">
                                <i class="material-symbols-rounded">delete</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success btn-sm" onclick="addDragDropMatch(${index})">
                <i class="material-symbols-rounded me-1">add</i>Add Match
            </button>
            <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="updateMatchOptions(${index})">
                <i class="material-symbols-rounded me-1">refresh</i>Update Match Options
            </button>
        </div>

        <div class="mt-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="questions[${index}][allow_partial_credit]" checked>
                        <label class="form-check-label">Allow partial credit for partially correct answers</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="questions[${index}][randomize_items]">
                        <label class="form-check-label">Randomize order of draggable items</label>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function addShortAnswer(index) {
    const container = document.getElementById(`short-answers-${index}`);
    const answerHtml = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="questions[${index}][short_answers][]"
                   placeholder="Acceptable answer" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', answerHtml);
}

// Bulk Progress Tracking Functions
function updateBulkProgress() {
    const subjectId = document.getElementById('bulkSubject').value;
    const classId = document.getElementById('bulkClass').value;

    if (!subjectId || !classId) {
        document.getElementById('bulkProgressIndicator').style.display = 'none';
        return;
    }

    // Show progress indicator
    document.getElementById('bulkProgressIndicator').style.display = 'block';

    // Fetch current count
    fetch(`<?= base_url(($route_prefix ?? '') . 'questions/get-question-count') ?>?subject_id=${subjectId}&class_id=${classId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('bulkQuestionCount').textContent = data.count;
                updateBulkProgressDetails();
                updateNewQuestionsCount();
            }
        })
        .catch(error => {
            console.error('Error fetching question count:', error);
        });
}

function updateBulkProgressDetails() {
    const subjectSelect = document.getElementById('bulkSubject');
    const classSelect = document.getElementById('bulkClass');

    const subjectText = subjectSelect.options[subjectSelect.selectedIndex]?.text || 'Selected Subject';
    const classText = classSelect.options[classSelect.selectedIndex]?.text || 'Selected Class';

    document.getElementById('bulkProgressDetails').textContent =
        `${subjectText} - ${classText} (Current Session/Term)`;
}

function updateNewQuestionsCount() {
    const newQuestionsCount = document.querySelectorAll('.question-card').length;
    const badge = document.getElementById('newQuestionsCount');

    if (newQuestionsCount === 0) {
        badge.textContent = '0 new questions to add';
        badge.className = 'badge bg-secondary';
    } else if (newQuestionsCount === 1) {
        badge.textContent = '1 new question to add';
        badge.className = 'badge bg-primary';
    } else {
        badge.textContent = `${newQuestionsCount} new questions to add`;
        badge.className = 'badge bg-success';
    }
}

// Custom alert function for bulk creation
function showBulkAlert(message, type = 'info') {
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

// Question text validation function for bulk questions
function validateBulkQuestionText(input) {
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
        feedback.innerHTML = `<i class="material-symbols-rounded me-1" style="font-size: 14px;">error</i>Question must be at least ${minLength} characters long. Current: ${text.length}`;
        input.parentElement.appendChild(feedback);
        return false;
    } else if (text.length >= minLength) {
        // Show success
        input.classList.add('is-valid');
        return true;
    }

    return true; // Empty field is handled by required validation
}

// Check for duplicate questions in bulk form
function checkBulkQuestionForDuplicates(textarea) {
    const questionText = textarea.value.trim();
    const questionCard = textarea.closest('.question-card');
    const questionTypeSelect = questionCard.querySelector('select[name*="[question_type]"]');
    const subjectId = document.getElementById('bulkSubject').value;
    const classId = document.getElementById('bulkClass').value;
    const examTypeSelect = document.querySelector('select[name="exam_type_id"]');
    const examTypeId = examTypeSelect ? examTypeSelect.value : '';

    if (!questionText || !questionTypeSelect || !subjectId) {
        return;
    }

    const questionType = questionTypeSelect.value;
    if (!questionType) {
        return;
    }

    // Only check for exact text matches, not partial matches
    if (questionText.length < 6) {
        return; // Too short to check for duplicates
    }

    const formData = new FormData();
    formData.append('question_text', questionText);
    formData.append('question_type', questionType);
    formData.append('subject_id', subjectId);

    if (classId) {
        formData.append('class_id', classId);
    }

    if (examTypeId) {
        formData.append('exam_type_id', examTypeId);
    }

    // Debug logging
    console.log('Bulk duplicate check data:', {
        question_text: questionText,
        question_type: questionType,
        subject_id: subjectId,
        class_id: classId,
        exam_type_id: examTypeId
    });

    fetch('<?= base_url(($route_prefix ?? '') . 'questions/check-duplicate') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Bulk duplicate check response:', data);

        // Remove any existing duplicate alert for this question
        const existingAlert = questionCard.querySelector('.duplicate-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        if (data.is_duplicate) {
            // Show duplicate warning
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning duplicate-alert mt-2';
            alertDiv.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 14px;">warning</i>' + (data.message || 'A similar question already exists!');
            textarea.parentElement.appendChild(alertDiv);
        }
    })
    .catch(error => {
        console.error('Error checking duplicates:', error);
    });
}

// Enhanced form submission with progress tracking
document.getElementById('bulkCreateForm').addEventListener('submit', function(e) {
    const newQuestionsCount = document.querySelectorAll('.question-card').length;

    if (newQuestionsCount === 0) {
        e.preventDefault();
        showBulkAlert('Please add at least one question before submitting.', 'warning');
        return;
    }

    // Check for duplicate alerts before submission
    const duplicateAlerts = document.querySelectorAll('.duplicate-alert');
    if (duplicateAlerts.length > 0) {
        e.preventDefault();
        showBulkAlert('Cannot submit: Some questions are duplicates. Please modify or remove duplicate questions before submitting.', 'danger');
        return;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Questions...';
    submitBtn.disabled = true;

    // Show progress message
    showBulkAlert(`Creating ${newQuestionsCount} question${newQuestionsCount > 1 ? 's' : ''}...`, 'info');
});

// Instruction template functions
function showInstructionTemplates(questionIndex) {
    const instructionTextarea = document.getElementById(`instruction-${questionIndex}`);

    // Create modal for template selection
    const modalHtml = `
        <div class="modal fade" id="templateModal-${questionIndex}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Instruction Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating">
                            <select class="form-select" id="templateSelect-${questionIndex}" onchange="previewTemplate(${questionIndex})">
                                <option value="">Choose a template...</option>
                                <?php foreach ($instructions as $instruction): ?>
                                    <option value="<?= esc($instruction['instruction_text']) ?>">
                                        <?= esc($instruction['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label>Available Templates</label>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Preview:</label>
                            <div id="templatePreview-${questionIndex}" class="p-3 bg-light rounded small" style="min-height: 60px;">
                                Select a template to preview...
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="applyTemplate(${questionIndex})">
                            Apply Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById(`templateModal-${questionIndex}`);
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById(`templateModal-${questionIndex}`));
    modal.show();
}

function previewTemplate(questionIndex) {
    const select = document.getElementById(`templateSelect-${questionIndex}`);
    const preview = document.getElementById(`templatePreview-${questionIndex}`);

    if (select.value) {
        preview.textContent = select.value;
    } else {
        preview.textContent = 'Select a template to preview...';
    }
}

function applyTemplate(questionIndex) {
    const select = document.getElementById(`templateSelect-${questionIndex}`);
    const instructionTextarea = document.getElementById(`instruction-${questionIndex}`);

    if (select.value) {
        instructionTextarea.value = select.value;

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById(`templateModal-${questionIndex}`));
        modal.hide();

        showBulkAlert('Template applied successfully!', 'success');
    } else {
        showBulkAlert('Please select a template first.', 'warning');
    }
}

function saveAsTemplate(questionIndex) {
    const instructionTextarea = document.getElementById(`instruction-${questionIndex}`);
    const instructionText = instructionTextarea.value.trim();

    if (!instructionText) {
        showBulkAlert('Please write an instruction first before saving as template.', 'warning');
        return;
    }

    // Create modal for saving template
    const modalHtml = `
        <div class="modal fade" id="saveTemplateModal-${questionIndex}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Save Instruction Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="templateTitle-${questionIndex}" placeholder="Template Title">
                            <label>Template Title *</label>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Instruction Text:</label>
                            <div id="saveTemplatePreview-${questionIndex}" class="p-3 bg-light rounded small">${instructionText}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="confirmSaveTemplate(${questionIndex})">
                            <i class="material-symbols-rounded me-2">save</i>Save Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById(`saveTemplateModal-${questionIndex}`);
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById(`saveTemplateModal-${questionIndex}`));
    modal.show();
}

function confirmSaveTemplate(questionIndex) {
    const titleInput = document.getElementById(`templateTitle-${questionIndex}`);
    const instructionTextarea = document.getElementById(`instruction-${questionIndex}`);

    const title = titleInput.value.trim();
    const instructionText = instructionTextarea.value.trim();

    if (!title) {
        showBulkAlert('Please enter a template title.', 'warning');
        return;
    }

    // Save to database
    fetch('<?= base_url(($route_prefix ?? '') . 'questions/save-instruction-template') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            title: title,
            instruction_text: instructionText
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showBulkAlert('Instruction template saved successfully!', 'success');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById(`saveTemplateModal-${questionIndex}`));
            modal.hide();
        } else {
            showBulkAlert('Error saving template: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error saving template:', error);
        showBulkAlert('Error saving template. Please try again.', 'error');
    });
}

// Image Based Question Functions
function previewQuestionImage(index, input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(`preview-img-${index}`);
            const container = document.getElementById(`image-preview-${index}`);
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleImageQuestionType(index, type) {
    const imageOptionsConfig = document.getElementById(`image-options-config-${index}`);
    const clickableAreasConfig = document.getElementById(`clickable-areas-config-${index}`);
    const imageLabelingConfig = document.getElementById(`image-labeling-config-${index}`);

    // Hide all configs
    imageOptionsConfig.style.display = 'none';
    clickableAreasConfig.style.display = 'none';
    imageLabelingConfig.style.display = 'none';

    // Show selected config
    switch(type) {
        case 'image_options':
            imageOptionsConfig.style.display = 'block';
            break;
        case 'clickable_areas':
            clickableAreasConfig.style.display = 'block';
            break;
        case 'image_labeling':
            imageLabelingConfig.style.display = 'block';
            break;
    }
}

function addImageOption(index) {
    const container = document.getElementById(`image-options-list-${index}`);
    const optionCount = container.children.length;

    const optionHtml = `
        <div class="image-option-item mb-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Option Image ${optionCount + 1}</label>
                    <input type="file" class="form-control" name="questions[${index}][option_images][]"
                           accept="image/*" onchange="previewOptionImage(${index}, ${optionCount}, this)">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Option Label</label>
                    <input type="text" class="form-control" name="questions[${index}][option_labels][]"
                           placeholder="Option ${String.fromCharCode(65 + optionCount)}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Correct?</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox"
                               name="questions[${index}][correct_image_options][]" value="${optionCount}">
                        <label class="form-check-label">Correct</label>
                    </div>
                </div>
            </div>
            <div id="option-preview-${index}-${optionCount}" class="mt-2" style="display: none;">
                <img src="" alt="Option Preview" style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="this.closest('.image-option-item').remove()">
                <i class="material-symbols-rounded me-1">delete</i>Remove Option
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
}

function previewOptionImage(index, optionIndex, input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector(`#option-preview-${index}-${optionIndex} img`);
            const container = document.getElementById(`option-preview-${index}-${optionIndex}`);
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function addClickableArea(index) {
    const container = document.getElementById(`clickable-areas-list-${index}`);
    const areaCount = container.children.length;

    const areaHtml = `
        <div class="clickable-area-item mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Area Name</label>
                    <input type="text" class="form-control" name="questions[${index}][area_names][]"
                           placeholder="e.g., Area ${areaCount + 1}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">X Position (%)</label>
                    <input type="number" class="form-control" name="questions[${index}][area_x][]"
                           min="0" max="100" placeholder="50">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Y Position (%)</label>
                    <input type="number" class="form-control" name="questions[${index}][area_y][]"
                           min="0" max="100" placeholder="50">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Width (%)</label>
                    <input type="number" class="form-control" name="questions[${index}][area_width][]"
                           min="1" max="50" placeholder="10">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Height (%)</label>
                    <input type="number" class="form-control" name="questions[${index}][area_height][]"
                           min="1" max="50" placeholder="10">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Correct?</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox"
                               name="questions[${index}][correct_areas][]" value="${areaCount}">
                        <label class="form-check-label">✓</label>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="this.closest('.clickable-area-item').remove()">
                <i class="material-symbols-rounded me-1">delete</i>Remove Area
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', areaHtml);
}

function addImageLabel(index) {
    const container = document.getElementById(`image-labels-list-${index}`);
    const labelCount = container.children.length;

    const labelHtml = `
        <div class="image-label-item mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Label Text</label>
                    <input type="text" class="form-control" name="questions[${index}][label_texts][]"
                           placeholder="e.g., Label ${labelCount + 1}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">X Position (%)</label>
                    <input type="number" class="form-control" name="questions[${index}][label_x][]"
                           min="0" max="100" placeholder="50">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Y Position (%)</label>
                    <input type="number" class="form-control" name="questions[${index}][label_y][]"
                           min="0" max="100" placeholder="50">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Acceptable Answers</label>
                    <input type="text" class="form-control" name="questions[${index}][acceptable_labels][]"
                           placeholder="answer1,answer2" title="Comma-separated acceptable answers">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm mt-4"
                            onclick="this.closest('.image-label-item').remove()">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', labelHtml);
}

// Drag & Drop Question Functions
function addDragItem(index) {
    const container = document.getElementById(`drag-items-list-${index}`);
    const itemCount = container.children.length;

    const itemHtml = `
        <div class="drag-item mb-2">
            <div class="row g-2">
                <div class="col-md-8">
                    <label class="form-label">Draggable Item ${itemCount + 1}</label>
                    <input type="text" class="form-control" name="questions[${index}][drag_items][]"
                           placeholder="e.g., Item ${itemCount + 1}" required onchange="updateMatchOptions(${index})">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Item Type</label>
                    <select class="form-select" name="questions[${index}][drag_item_types][]"
                            onchange="toggleDragItemImage(${index}, ${itemCount}, this.value)">
                        <option value="text">Text</option>
                        <option value="image">Image</option>
                    </select>
                </div>
            </div>
            <div class="mt-2" style="display: none;" id="drag-item-image-${index}-${itemCount}">
                <input type="file" class="form-control" name="questions[${index}][drag_item_images][]"
                       accept="image/*" onchange="previewDragItemImage(${index}, ${itemCount}, this)">
                <div class="mt-2" id="drag-item-preview-${index}-${itemCount}" style="display: none;">
                    <img src="" alt="Drag Item Preview" style="max-width: 100px; max-height: 60px; border: 1px solid #ddd; border-radius: 3px;">
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="removeDragItem(this, ${index})">
                <i class="material-symbols-rounded me-1">delete</i>Remove Item
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
}

function addDropZone(index) {
    const container = document.getElementById(`drop-zones-list-${index}`);
    const zoneCount = container.children.length;

    const zoneHtml = `
        <div class="drop-zone mb-2">
            <div class="row g-2">
                <div class="col-md-8">
                    <label class="form-label">Drop Zone ${zoneCount + 1}</label>
                    <input type="text" class="form-control" name="questions[${index}][drop_zones][]"
                           placeholder="e.g., Zone ${zoneCount + 1}" required onchange="updateMatchOptions(${index})">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Max Items</label>
                    <input type="number" class="form-control" name="questions[${index}][drop_zone_limits][]"
                           value="1" min="1" max="10">
                </div>
            </div>
            <div class="mt-2">
                <label class="form-label">Zone Description (Optional)</label>
                <input type="text" class="form-control" name="questions[${index}][drop_zone_descriptions][]"
                       placeholder="Brief description of what should be dropped here">
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="removeDropZone(this, ${index})">
                <i class="material-symbols-rounded me-1">delete</i>Remove Zone
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', zoneHtml);
}

function addDragDropMatch(index) {
    const container = document.getElementById(`drag-drop-matches-${index}`);

    const matchHtml = `
        <div class="match-item mb-2">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">Draggable Item</label>
                    <select class="form-select" name="questions[${index}][match_drag_items][]" required>
                        <option value="">Select draggable item...</option>
                    </select>
                </div>
                <div class="col-md-1 text-center">
                    <label class="form-label">&nbsp;</label>
                    <div class="mt-2">
                        <i class="material-symbols-rounded" style="color: var(--primary-color);">arrow_forward</i>
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Drop Zone</label>
                    <select class="form-select" name="questions[${index}][match_drop_zones][]" required>
                        <option value="">Select drop zone...</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm mt-2"
                            onclick="this.closest('.match-item').remove()">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', matchHtml);
    updateMatchOptions(index);
}

function updateMatchOptions(index) {
    // Get all drag items
    const dragItems = document.querySelectorAll(`input[name="questions[${index}][drag_items][]"]`);
    const dropZones = document.querySelectorAll(`input[name="questions[${index}][drop_zones][]"]`);

    // Update all match selects
    const dragSelects = document.querySelectorAll(`select[name="questions[${index}][match_drag_items][]"]`);
    const dropSelects = document.querySelectorAll(`select[name="questions[${index}][match_drop_zones][]"]`);

    // Update drag item options
    dragSelects.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Select draggable item...</option>';
        dragItems.forEach((item, itemIndex) => {
            if (item.value.trim()) {
                const option = document.createElement('option');
                option.value = itemIndex;
                option.textContent = item.value;
                if (currentValue == itemIndex) option.selected = true;
                select.appendChild(option);
            }
        });
    });

    // Update drop zone options
    dropSelects.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Select drop zone...</option>';
        dropZones.forEach((zone, zoneIndex) => {
            if (zone.value.trim()) {
                const option = document.createElement('option');
                option.value = zoneIndex;
                option.textContent = zone.value;
                if (currentValue == zoneIndex) option.selected = true;
                select.appendChild(option);
            }
        });
    });
}

function toggleDragItemImage(index, itemIndex, type) {
    const imageContainer = document.getElementById(`drag-item-image-${index}-${itemIndex}`);
    if (type === 'image') {
        imageContainer.style.display = 'block';
    } else {
        imageContainer.style.display = 'none';
    }
}

function previewDragItemImage(index, itemIndex, input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector(`#drag-item-preview-${index}-${itemIndex} img`);
            const container = document.getElementById(`drag-item-preview-${index}-${itemIndex}`);
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeDragItem(button, index) {
    button.closest('.drag-item').remove();
    updateMatchOptions(index);
}

function removeDropZone(button, index) {
    button.closest('.drop-zone').remove();
    updateMatchOptions(index);
}

// Fill Blank Question Functions
function detectBlanks(index) {
    const questionCard = document.querySelector(`#question-${index}`);
    const questionTextarea = questionCard.querySelector('textarea[name*="[question_text]"]');
    const questionText = questionTextarea.value;

    // Find all [BLANK] markers
    const blankMatches = questionText.match(/\[BLANK\]/g);
    const blankCount = blankMatches ? blankMatches.length : 0;

    const container = document.getElementById(`blank-answers-${index}`);

    if (blankCount === 0) {
        container.innerHTML = `
            <div class="alert alert-warning text-center">
                <i class="material-symbols-rounded me-2">warning</i>
                No [BLANK] markers found in the question text. Please add [BLANK] where students should fill answers.
            </div>
        `;
        return;
    }

    // Generate blank answer fields
    let blanksHtml = '';
    for (let i = 1; i <= blankCount; i++) {
        blanksHtml += `
            <div class="blank-group mb-3">
                <h6>Blank ${i} Answers:</h6>
                <div class="blank-answers-list">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="questions[${index}][blank_answers][${i}][]"
                               placeholder="Acceptable answer for blank ${i}" required>
                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                            <i class="material-symbols-rounded">delete</i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addBlankAnswer(${index}, ${i})">
                    <i class="material-symbols-rounded me-1">add</i>Add Answer for Blank ${i}
                </button>
            </div>
        `;
    }

    container.innerHTML = blanksHtml;
    showBulkAlert(`Detected ${blankCount} blank${blankCount > 1 ? 's' : ''} in the question.`, 'success');
}

function addBlankAnswer(index, blankNumber) {
    const container = document.querySelector(`#blank-answers-${index} .blank-group:nth-child(${blankNumber}) .blank-answers-list`);

    const answerHtml = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="questions[${index}][blank_answers][${blankNumber}][]"
                   placeholder="Acceptable answer for blank ${blankNumber}" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', answerHtml);
}

function addManualBlankGroup(index) {
    const container = document.getElementById(`manual-blank-answers-${index}`);
    const blankCount = container.children.length + 1;

    const blankHtml = `
        <div class="blank-group mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Blank Number</label>
                    <input type="number" class="form-control" value="${blankCount}" min="1" readonly>
                </div>
                <div class="col-md-7">
                    <label class="form-label">Acceptable Answers (comma-separated)</label>
                    <input type="text" class="form-control" name="questions[${index}][manual_blank_answers][${blankCount}]"
                           placeholder="answer1, answer2, answer3">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.blank-group').remove()">
                        <i class="material-symbols-rounded">delete</i>
                    </button>
                </div>
            </div>
            <small class="text-muted">Enter multiple acceptable answers separated by commas. Example: "Nigeria, nigeria, NIGERIA"</small>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', blankHtml);
}
</script>
<?= $this->endSection() ?>
