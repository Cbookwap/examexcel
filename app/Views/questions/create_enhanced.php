<?= $this->extend($layout ?? 'layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .question-creation-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .creation-mode-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        cursor: pointer;
    }

    .creation-mode-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .mode-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .mode-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .mode-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .mode-description {
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .question-form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .form-body {
        padding: 2rem;
    }

    .session-term-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 2px solid rgba(var(--primary-color-rgb), 0.1);
    }

    .info-badge {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin: 0.25rem;
    }

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-floating > .form-control {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 1rem 0.75rem;
        height: auto;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }

    .form-floating > label {
        color: #6c757d;
        font-weight: 500;
    }

    .question-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
    }

    .question-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(var(--primary-color-rgb), 0.1);
    }

    .question-number {
        position: absolute;
        top: -10px;
        left: 20px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        z-index: 10;
    }

    /* Fix overlapping issue with question number and field borders */
    .question-item {
        padding-top: 2rem !important;
        margin-top: 1rem;
        position: relative;
    }

    .question-item .question-number {
        position: absolute;
        top: -10px;
        left: 20px;
        z-index: 10;
    }

    .option-item {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .option-item:hover {
        border-color: var(--primary-color);
    }

    .option-item.correct {
        border-color: #28a745;
        background: #f8fff9;
    }

    .btn-action {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(var(--primary-color-rgb), 0.3);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        border: none;
    }

    .floating-action-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        box-shadow: 0 5px 20px rgba(var(--primary-color-rgb), 0.4);
        font-size: 1.5rem;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .floating-action-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.6);
    }

    .progress-indicator {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .progress {
        height: 8px;
        border-radius: 10px;
        background: #e9ecef;
    }

    .progress-bar {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 10px;
    }

    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    @media (max-width: 768px) {
        .question-creation-container {
            padding: 1rem 0;
        }

        .form-body {
            padding: 1.5rem;
        }

        .mode-header {
            padding: 1.5rem;
        }

        .floating-action-btn {
            bottom: 1rem;
            right: 1rem;
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="question-creation-container">
    <div class="container-fluid">
        <!-- Mode Selection -->
        <div id="modeSelection" class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-outline-secondary">
                        <i class="material-symbols-rounded me-2">arrow_back</i>
                        Back to Question Bank
                    </a>
                    <h2 class="text-center mb-0" style="color: var(--primary-color); font-weight: 700;">
                        <i class="material-symbols-rounded me-2" style="font-size: 2rem;">quiz</i>
                        Create Questions
                    </h2>
                    <div style="width: 180px;"></div> <!-- Spacer for centering -->
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="creation-mode-card" onclick="selectMode('single')">
                    <div class="mode-header">
                        <i class="material-symbols-rounded mode-icon">edit_note</i>
                        <div class="mode-title">Single Question</div>
                        <div class="mode-description">Create one question at a time with detailed options</div>
                    </div>
                    <div class="p-4 text-center">
                        <button class="btn btn-primary btn-action">
                            <i class="material-symbols-rounded me-2">add</i>Start Creating
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="creation-mode-card" onclick="selectMode('bulk')">
                    <div class="mode-header">
                        <i class="material-symbols-rounded mode-icon">library_add</i>
                        <div class="mode-title">Bulk Questions</div>
                        <div class="mode-description">Create multiple questions quickly in one session</div>
                    </div>
                    <div class="p-4 text-center">
                        <button class="btn btn-primary btn-action">
                            <i class="material-symbols-rounded me-2">playlist_add</i>Start Bulk Creation
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session and Term Information -->
        <div id="sessionTermInfo" class="row" style="display: none;">
            <div class="col-12">
                <div class="session-term-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2" style="color: var(--primary-color);">
                                <i class="material-symbols-rounded me-2">school</i>
                                Current Academic Context
                            </h5>
                            <p class="mb-2 text-muted">Questions will be created for the current academic session and term</p>
                            <div>
                                <span class="info-badge">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">calendar_today</i>
                                    <?= $current_session['session_name'] ?? 'No Active Session' ?>
                                </span>
                                <span class="info-badge">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">schedule</i>
                                    <?= $current_term['term_name'] ?? 'No Active Term' ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">arrow_back</i>
                                    Back to Question Bank
                                </a>
                                <button class="btn btn-outline-primary btn-sm" onclick="showModeSelection()">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">refresh</i>
                                    Change Mode
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div id="progressIndicator" class="row" style="display: none;">
            <div class="col-12">
                <div class="progress-indicator">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="progress-circle" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold;">
                                        <span id="questionCount">0</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold" style="color: var(--primary-color);">Questions Created</h6>
                                    <small class="text-muted" id="progressDetails">For this subject & class in current session/term</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">arrow_back</i>
                                    Back to Question Bank
                                </a>
                                <div id="duplicateAlert" class="alert alert-warning p-2 mb-0" style="display: none;">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">warning</i>
                                    <small>Checking for duplicates...</small>
                                </div>
                                <div id="successAlert" class="alert alert-success p-2 mb-0" style="display: none;">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">check_circle</i>
                                    <small>Question created!</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Question Form -->
        <div id="singleQuestionForm" style="display: none;">
            <div class="row">
                <div class="col-12">
                    <div class="question-form-card">
                        <div class="form-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 text-white">
                                    <i class="material-symbols-rounded me-2 text-white">edit_note</i>
                                    Create Single Question
                                </h4>
                                <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-outline-light btn-sm">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">arrow_back</i>
                                    Back to Question Bank
                                </a>
                            </div>
                        </div>
                        <div class="form-body">
                            <form id="singleQuestionFormElement" method="POST" action="<?= base_url(($route_prefix ?? '') . 'questions/create') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="session_id" value="<?= $current_session['id'] ?? '' ?>">
                                <input type="hidden" name="term_id" value="<?= $current_term['id'] ?? '' ?>">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-control" name="subject_id" id="singleSubject" required>
                                                <option value="">Choose Subject</option>
                                                <?php foreach ($subjects as $subject): ?>
                                                    <option value="<?= $subject['id'] ?>">
                                                        <?= esc($subject['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="singleSubject">Subject *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-control" name="class_id" id="singleClass" required>
                                                <option value="">Choose Class</option>
                                                <?php foreach ($classes as $class): ?>
                                                    <option value="<?= $class['id'] ?>">
                                                        <?= esc($class['display_name'] ?? $class['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="singleClass">Class *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-control" name="exam_type_id" id="singleExamType">
                                                <option value="">Choose Exam Type</option>
                                                <?php foreach ($examTypes as $examType): ?>
                                                    <option value="<?= $examType['id'] ?>">
                                                        <?= esc($examType['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="singleExamType">Exam Type</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-control" name="question_type" id="singleQuestionType" required>
                                                <option value="">Choose Type</option>
                                                <?php foreach ($question_types as $key => $label): ?>
                                                    <option value="<?= $key ?>"><?= esc($label) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="singleQuestionType">Question Type *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-control" name="difficulty" required>
                                                <option value="">Choose Difficulty</option>
                                                <?php foreach ($difficulties as $key => $label): ?>
                                                    <option value="<?= $key ?>"><?= esc($label) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label>Difficulty *</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating">
                                    <textarea class="form-control" name="question_text" id="singleQuestionText"
                                              style="height: 120px;" required placeholder="Enter your question here..."></textarea>
                                    <label for="singleQuestionText">Question Text *</label>
                                </div>

                                <!-- Custom Instruction Section -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <small class="text-muted mb-2 d-block">
                                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">info</i>
                                            Instructions appear at the top of the question for students.
                                        </small>
                                        <div class="form-floating">
                                            <textarea class="form-control" name="custom_instruction" id="customInstruction"
                                                      style="height: 80px;" placeholder="Write custom instruction for this question..."></textarea>
                                            <label for="customInstruction">Custom Instruction (Optional)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-control" id="instructionTemplate" onchange="loadInstructionTemplate()">
                                                <option value="">Load from Template</option>
                                                <?php foreach ($instructions as $instruction): ?>
                                                    <option value="<?= esc($instruction['instruction_text']) ?>">
                                                        <?= esc($instruction['title']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="instructionTemplate">Instruction Templates</label>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="saveInstructionTemplate()">
                                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">bookmark_add</i>
                                                Save as Template
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="points" value="1" min="1" max="100" required>
                                            <label>Mark *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted mb-2 d-block">
                                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">info</i>
                                            Leave empty for no time limit. Use seconds.
                                        </small>
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="time_limit" min="0" placeholder="Optional">
                                            <label>Time Limit in Seconds (Optional)</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Options Section -->
                                <div id="singleOptionsSection" style="display: none;">
                                    <h6 class="mb-3" style="color: var(--primary-color);">
                                        <i class="material-symbols-rounded me-2">radio_button_checked</i>
                                        Answer Options
                                    </h6>
                                    <div id="singleOptionsList"></div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSingleOption()">
                                        <i class="material-symbols-rounded me-1">add</i>Add Option
                                    </button>
                                </div>

                                <!-- Fill in the Blank Section -->
                                <div id="singleFillBlankSection" style="display: none;">
                                    <h6 class="mb-3" style="color: var(--primary-color);">
                                        <i class="material-symbols-rounded me-2">edit</i>
                                        Fill in the Blank Configuration
                                    </h6>
                                    <div class="alert alert-info">
                                        <i class="material-symbols-rounded me-2">info</i>
                                        Use <code>[BLANK]</code> in your question text to mark where students should fill in answers.
                                        <br><br><strong>Examples:</strong>
                                        <br>• Geography: "The capital of Nigeria is [BLANK] and it is located in the [BLANK] region."
                                        <br>• Math: "If x = 5, then 2x = [BLANK] and x² = [BLANK]."
                                        <br>• Science: "Water boils at [BLANK]°C and freezes at [BLANK]°C."
                                        <br><br><strong>Scoring:</strong> If you use multiple [BLANK] markers, the total mark will be divided equally among all blanks.
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="detectBlanks('single')">
                                            <i class="material-symbols-rounded me-1">search</i>Detect Blanks in Question
                                        </button>
                                        <small class="text-muted d-block mt-1">Click this after writing your question text to automatically detect [BLANK] markers.</small>
                                    </div>
                                    <div id="singleBlankAnswers">
                                        <div id="singleBlankAnswersList">
                                            <div class="alert alert-secondary text-center">
                                                <i class="material-symbols-rounded me-2">info</i>
                                                Write your question text with [BLANK] markers first, then click "Detect Blanks" to configure answers.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Short Answer Section -->
                                <div id="singleShortAnswerSection" style="display: none;">
                                    <h6 class="mb-3" style="color: var(--primary-color);">
                                        <i class="material-symbols-rounded me-2">short_text</i>
                                        Short Answer Configuration
                                    </h6>
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="max_words" min="1" max="500" value="50">
                                        <label>Maximum Words Allowed</label>
                                    </div>
                                    <div id="singleShortAnswers">
                                        <label class="form-label">Acceptable Answers/Keywords:</label>
                                        <div id="singleShortAnswersList"></div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addShortAnswer('single')">
                                            <i class="material-symbols-rounded me-1">add</i>Add Acceptable Answer
                                        </button>
                                    </div>
                                </div>

                                <!-- Essay Section -->
                                <div id="singleEssaySection" style="display: none;">
                                    <h6 class="mb-3" style="color: var(--primary-color);">
                                        <i class="material-symbols-rounded me-2">article</i>
                                        Essay Configuration
                                    </h6>
                                    <div class="alert alert-info">
                                        <i class="material-symbols-rounded me-2">info</i>
                                        <strong>AI-Assisted Grading:</strong> Enable rubrics for AI to suggest marks. Teachers review and approve before students see results.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" name="min_words" min="1" value="100">
                                                <label>Minimum Words Required</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" name="max_words_essay" min="1" value="1000">
                                                <label>Maximum Words Allowed</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grading Rubric Section -->
                                    <div class="mt-4">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_rubric" id="enableRubric" onchange="toggleRubricSection()">
                                            <label class="form-check-label" for="enableRubric">
                                                <strong>Enable AI-Assisted Grading with Rubric</strong>
                                            </label>
                                        </div>

                                        <div id="rubricConfiguration" style="display: none;">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <select class="form-control" name="rubric_type" id="rubricType" onchange="updateRubricTemplate()">
                                                            <option value="content_quality">Content Quality Only</option>
                                                            <option value="comprehensive">Comprehensive (Content + Organization + Grammar)</option>
                                                            <option value="custom">Custom Criteria</option>
                                                        </select>
                                                        <label>Rubric Type</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number" class="form-control" name="rubric_max_score" id="rubricMaxScore" min="1" max="100" value="10">
                                                        <label>Maximum Rubric Mark</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="rubricCriteria">
                                                <!-- Rubric criteria will be populated here -->
                                            </div>

                                            <div class="mt-3">
                                                <div class="form-floating">
                                                    <textarea class="form-control" name="model_answer" id="modelAnswer" style="height: 100px;"
                                                              placeholder="Provide a model answer or key points that should be covered..."></textarea>
                                                    <label>Model Answer / Key Points (for AI reference)</label>
                                                </div>
                                                <small class="text-muted">This helps AI evaluate student responses more accurately.</small>
                                            </div>
                                        </div>

                                        <!-- Traditional Rubric (fallback) -->
                                        <div id="traditionalRubric">
                                            <div class="form-floating">
                                                <textarea class="form-control" name="grading_rubric" style="height: 100px;"
                                                          placeholder="Define grading criteria for manual teacher evaluation..."></textarea>
                                                <label>Manual Grading Rubric (Optional)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Based Section -->
                                <div id="singleImageBasedSection" style="display: none;">
                                    <h6 class="mb-3" style="color: var(--primary-color);">
                                        <i class="material-symbols-rounded me-2">image</i>
                                        Image Based Question Configuration
                                    </h6>
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" name="question_image" accept="image/*">
                                        <label>Upload Question Image *</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="image_question_type" value="clickable_areas" id="clickableAreas" checked>
                                        <label class="form-check-label" for="clickableAreas">
                                            Clickable Areas (students click on correct areas)
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="image_question_type" value="image_options" id="imageOptions">
                                        <label class="form-check-label" for="imageOptions">
                                            Image Options (students choose from multiple images)
                                        </label>
                                    </div>
                                    <div id="imageAreasConfig" style="display: block;">
                                        <label class="form-label">Define Clickable Areas (after image upload):</label>
                                        <div class="alert alert-info">
                                            <i class="material-symbols-rounded me-2">info</i>
                                            After uploading an image, you can define clickable areas by coordinates.
                                        </div>
                                    </div>
                                </div>

                                <!-- Math Equation Section -->
                                <div id="singleMathEquationSection" style="display: none;">
                                    <h6 class="mb-3" style="color: var(--primary-color);">
                                        <i class="material-symbols-rounded me-2">calculate</i>
                                        Math Equation Configuration
                                    </h6>
                                    <div class="alert alert-info">
                                        <i class="material-symbols-rounded me-2">info</i>
                                        <strong>How Math Equations Work:</strong>
                                        <br><br><strong>For Teachers:</strong> Use LaTeX/MathML to create beautifully formatted questions
                                        <br>• Write: <code>\frac{x^2 + 3x}{2x - 1} = 5</code> → Students see: proper fraction format
                                        <br>• Write: <code>x^{2} + 5x - 6 = 0</code> → Students see: x² + 5x - 6 = 0
                                        <br><br><strong>For Students:</strong> Type simple text answers (no complex formatting needed)
                                        <br>• <strong>Algebra:</strong> "Solve for x: 2x + 5 = 15" → Student types "x=5" or "5"
                                        <br>• <strong>Fractions:</strong> "Simplify: 6/8" → Student types "3/4" or "0.75"
                                        <br>• <strong>Geometry:</strong> "Area of circle with radius 3" → Student types "28.27" or "9π"
                                        <br><br><strong>Key Point:</strong> Students never need to learn LaTeX/MathML - they just type normal answers!
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="allow_calculator" id="allowCalculator">
                                                <label class="form-check-label" for="allowCalculator">
                                                    Allow calculator during exam
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-control" name="equation_format" id="equationFormat" onchange="updateMathExamples()">
                                                    <option value="text">Plain Text Questions (Basic)</option>
                                                    <option value="latex">LaTeX Questions (Beautiful Math Formatting)</option>
                                                    <option value="mathml">MathML Questions (Advanced Formatting)</option>
                                                </select>
                                                <label>Question Format (How YOU write the question)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="mathFormatExamples" class="mb-3">
                                        <!-- Format examples will be shown here -->
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" name="decimal_places" min="0" max="10" value="2">
                                                <label>Decimal Places (for rounding)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" name="tolerance" min="0" step="0.01" value="0.01">
                                                <label>Tolerance (±)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="singleMathAnswers">
                                        <label class="form-label">Correct Answers (Simple text answers students will type):</label>
                                        <div class="alert alert-success mb-3">
                                            <i class="material-symbols-rounded me-2">lightbulb</i>
                                            <strong>Remember:</strong> No matter how complex your question formatting is (LaTeX/MathML),
                                            students always type simple answers like "x=5", "3/4", "28.27", etc.
                                        </div>
                                        <div id="singleMathAnswersList"></div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMathAnswer('single')">
                                            <i class="material-symbols-rounded me-1">add</i>Add Acceptable Answer
                                        </button>
                                    </div>
                                </div>

                                <!-- Drag & Drop Section -->
                                <div id="singleDragDropSection" style="display: none;">
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
                                        <br><br><strong>Setup:</strong> Create draggable items below. Students will drag these to drop zones you define.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Draggable Items</h6>
                                            <div id="singleDragDropItems">
                                                <div id="singleDragDropItemsList"></div>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addDragDropItem('single')">
                                                    <i class="material-symbols-rounded me-1">add</i>Add Draggable Item
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Drop Zones</h6>
                                            <div id="singleDropZones">
                                                <div id="singleDropZonesList"></div>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addDropZone('single')">
                                                    <i class="material-symbols-rounded me-1">add</i>Add Drop Zone
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <h6 class="mb-3">Correct Matches</h6>
                                        <div id="singleDragDropMatches">
                                            <div class="alert alert-secondary text-center">
                                                <i class="material-symbols-rounded me-2">info</i>
                                                Add draggable items and drop zones first, then define correct matches below.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <small class="text-muted mb-2 d-block">
                                    <i class="material-symbols-rounded me-1" style="font-size: 14px;">info</i>
                                    <strong>Explanation:</strong> Shown to students AFTER they complete the exam to explain why the answer is correct.
                                </small>
                                <div class="form-floating">
                                    <textarea class="form-control" name="explanation" style="height: 80px;"
                                              placeholder="Explain the correct answer..."></textarea>
                                    <label>Explanation (Optional)</label>
                                </div>

                                <small class="text-muted mb-2 d-block">
                                    <i class="material-symbols-rounded me-1" style="font-size: 14px;">info</i>
                                    <strong>Hints:</strong> Shown to students DURING the exam to help them answer the question.
                                </small>
                                <div class="form-floating">
                                    <textarea class="form-control" name="hints" style="height: 60px;"
                                              placeholder="Provide hints for students..."></textarea>
                                    <label>Hints (Optional)</label>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary btn-action" onclick="showModeSelection()">
                                        <i class="material-symbols-rounded me-2">arrow_back</i>Back
                                    </button>

                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-action me-2" onclick="previewSingleQuestion()">
                                            <i class="material-symbols-rounded me-2">visibility</i>Preview
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-action">
                                            <i class="material-symbols-rounded me-2">save</i>Create Question
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Questions Form -->
        <div id="bulkQuestionsForm" style="display: none;">
            <div class="row">
                <div class="col-12">
                    <div class="question-form-card">
                        <div class="form-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 text-white">
                                    <i class="material-symbols-rounded me-2 text-white">library_add</i>
                                    Create Multiple Questions
                                </h4>
                                <a href="<?= base_url(($route_prefix ?? '') . 'questions') ?>" class="btn btn-outline-light btn-sm">
                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">arrow_back</i>
                                    Back to Question Bank
                                </a>
                            </div>
                        </div>
                        <div class="form-body">
                            <!-- Progress Indicator -->
                            <div class="progress-indicator">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Progress</span>
                                    <span id="progressText">0 of 0 questions</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                                </div>
                            </div>

                            <form id="bulkQuestionsFormElement" method="POST" action="<?= base_url(($route_prefix ?? '') . 'questions/create-bulk') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="session_id" value="<?= $current_session['id'] ?? '' ?>">
                                <input type="hidden" name="term_id" value="<?= $current_term['id'] ?? '' ?>">

                                <!-- Bulk Mode Information -->
                                <div class="alert alert-info mb-4">
                                    <div class="d-flex align-items-start">
                                        <i class="material-symbols-rounded me-2 mt-1">info</i>
                                        <div>
                                            <h6 class="mb-2">Bulk Question Creation - Supported Types</h6>
                                            <p class="mb-2">This bulk mode supports the following question types:</p>
                                            <ul class="mb-2">
                                                <li><strong>Multiple Choice (MCQ)</strong> - Questions with multiple options</li>
                                                <li><strong>True/False</strong> - Simple true or false questions</li>
                                                <li><strong>Yes/No</strong> - Simple yes or no questions</li>
                                                <li><strong>Fill in the Blank</strong> - Questions with [BLANK] markers</li>
                                                <li><strong>Short Answer</strong> - Questions requiring brief text responses</li>
                                                <li><strong>Essay</strong> - Questions requiring longer written responses</li>
                                            </ul>
                                            <p class="mb-0">
                                                <strong>Need advanced question types?</strong> For Math Equations, Image-based questions, or complex Drag & Drop questions,
                                                please use the <a href="<?= base_url(($route_prefix ?? '') . 'questions/bulk-create') ?>" class="alert-link">dedicated bulk creation page</a>
                                                or create them individually in single mode.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Common Settings -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <select class="form-control" name="subject_id" id="bulkSubject" required>
                                                <option value="">Choose Subject</option>
                                                <?php foreach ($subjects as $subject): ?>
                                                    <option value="<?= $subject['id'] ?>">
                                                        <?= esc($subject['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="bulkSubject">Subject (for all questions) *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <select class="form-control" name="class_id" id="bulkClass" required>
                                                <option value="">Choose Class</option>
                                                <?php foreach ($classes as $class): ?>
                                                    <option value="<?= $class['id'] ?>">
                                                        <?= esc($class['display_name'] ?? $class['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="bulkClass">Class (for all questions) *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <select class="form-control" name="exam_type_id" id="bulkExamType">
                                                <option value="">Choose Exam Type</option>
                                                <?php foreach ($examTypes as $examType): ?>
                                                    <option value="<?= $examType['id'] ?>">
                                                        <?= esc($examType['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="bulkExamType">Exam Type (for all questions)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="questionCount" min="1" max="50" value="5">
                                            <label for="questionCount">Number of Questions</label>
                                        </div>
                                    </div>
                                </div>



                                <!-- Questions Container -->
                                <div id="bulkQuestionsContainer">
                                    <!-- Questions will be added here dynamically -->
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary btn-action" onclick="showModeSelection()">
                                        <i class="material-symbols-rounded me-2">arrow_back</i>Back
                                    </button>

                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-action me-2" onclick="generateQuestions()">
                                            <i class="material-symbols-rounded me-2">refresh</i>Generate Questions
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-action">
                                            <i class="material-symbols-rounded me-2">save</i>Create All Questions
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="floating-action-btn" onclick="addNewQuestion()" style="display: none;" title="Add Question">
            <i class="material-symbols-rounded">add</i>
        </button>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white;">
                <h5 class="modal-title text-white" id="previewModalLabel">
                    <i class="material-symbols-rounded me-2 text-white">visibility</i>
                    Question Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <!-- Preview content will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitAfterPreview()">
                    <i class="material-symbols-rounded me-2">save</i>
                    Create Question
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Save Template Modal -->
<div class="modal fade" id="saveTemplateModal" tabindex="-1" aria-labelledby="saveTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white;">
                <h5 class="modal-title" id="saveTemplateModalLabel">
                    <i class="material-symbols-rounded me-2">bookmark_add</i>
                    Save Instruction Template
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="templateTitle" placeholder="Enter template title">
                    <label for="templateTitle">Template Title *</label>
                </div>
                <div class="mt-3">
                    <label class="form-label">Instruction Text:</label>
                    <div id="templatePreview" class="p-3 bg-light rounded small"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmSaveTemplate()">
                    <i class="material-symbols-rounded me-2">save</i>
                    Save Template
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let currentMode = '';
let singleOptionCount = 0;
let bulkQuestionCount = 0;
let bulkOptionCounts = {};

// Mode Selection
function selectMode(mode) {
    currentMode = mode;
    document.getElementById('modeSelection').style.display = 'none';
    document.getElementById('sessionTermInfo').style.display = 'block';

    if (mode === 'single') {
        document.getElementById('singleQuestionForm').style.display = 'block';
        document.getElementById('bulkQuestionsForm').style.display = 'none';
    } else if (mode === 'bulk') {
        document.getElementById('singleQuestionForm').style.display = 'none';
        document.getElementById('bulkQuestionsForm').style.display = 'block';
        document.querySelector('.floating-action-btn').style.display = 'block';
        generateQuestions();
    }
}

function showModeSelection() {
    document.getElementById('modeSelection').style.display = 'block';
    document.getElementById('sessionTermInfo').style.display = 'none';
    document.getElementById('singleQuestionForm').style.display = 'none';
    document.getElementById('bulkQuestionsForm').style.display = 'none';
    document.querySelector('.floating-action-btn').style.display = 'none';
    currentMode = '';
}

// Instruction Template Functions
function loadInstructionTemplate() {
    const select = document.getElementById('instructionTemplate');
    const textarea = document.getElementById('customInstruction');

    if (select.value) {
        textarea.value = select.value;
        select.selectedIndex = 0; // Reset dropdown
    }
}

function loadBulkInstructionTemplate() {
    const select = document.getElementById('bulkInstructionTemplate');
    const textarea = document.getElementById('bulkCustomInstruction');

    if (select.value) {
        textarea.value = select.value;
        select.selectedIndex = 0; // Reset dropdown
    }
}

let currentInstructionText = '';

function saveInstructionTemplate() {
    const textarea = document.getElementById('customInstruction');
    const instructionText = textarea.value.trim();

    if (!instructionText) {
        showAlert('Please write an instruction first before saving as template.', 'warning');
        return;
    }

    currentInstructionText = instructionText;
    document.getElementById('templatePreview').textContent = instructionText;
    document.getElementById('templateTitle').value = '';

    const modal = new bootstrap.Modal(document.getElementById('saveTemplateModal'));
    modal.show();
}

function saveBulkInstructionTemplate() {
    const textarea = document.getElementById('bulkCustomInstruction');
    const instructionText = textarea.value.trim();

    if (!instructionText) {
        showAlert('Please write an instruction first before saving as template.', 'warning');
        return;
    }

    currentInstructionText = instructionText;
    document.getElementById('templatePreview').textContent = instructionText;
    document.getElementById('templateTitle').value = '';

    const modal = new bootstrap.Modal(document.getElementById('saveTemplateModal'));
    modal.show();
}

function confirmSaveTemplate() {
    const title = document.getElementById('templateTitle').value.trim();

    if (!title) {
        showAlert('Please enter a title for the template.', 'warning');
        return;
    }

    // Save via AJAX
    saveInstructionToDatabase(title, currentInstructionText);

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('saveTemplateModal'));
    modal.hide();
}

function saveInstructionToDatabase(title, instructionText) {
    fetch('<?= base_url(($route_prefix ?? '') . 'questions/save-instruction-template') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            title: title,
            instruction_text: instructionText,
            csrf_token: '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Instruction template saved successfully!', 'success');
            // Reload the page to update dropdowns
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('Error saving template: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error saving template. Please try again.', 'error');
    });
}

function showAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Single Question Functions
document.getElementById('singleQuestionType').addEventListener('change', function() {
    const type = this.value;

    // Hide all question type specific sections
    hideAllQuestionTypeSections('single');

    // Show appropriate section based on question type
    if (['mcq', 'true_false', 'yes_no', 'drag_drop'].includes(type)) {
        const optionsSection = document.getElementById('singleOptionsSection');
        const optionsList = document.getElementById('singleOptionsList');

        optionsList.innerHTML = '';
        singleOptionCount = 0;
        optionsSection.style.display = 'block';

        if (type === 'true_false') {
            addSingleOption('True', type);
            addSingleOption('False', type);
            hideAddOptionButton('single');
        } else if (type === 'yes_no') {
            addSingleOption('Yes', type);
            addSingleOption('No', type);
            hideAddOptionButton('single');
        } else if (type === 'mcq') {
            addSingleOption('', type);
            addSingleOption('', type);
            showAddOptionButton('single');
        } else if (type === 'drag_drop') {
            document.getElementById('singleDragDropSection').style.display = 'block';
            initializeDragDropItems('single');
        }
    } else if (type === 'fill_blank') {
        document.getElementById('singleFillBlankSection').style.display = 'block';
        initializeBlankAnswers('single');
    } else if (type === 'short_answer') {
        document.getElementById('singleShortAnswerSection').style.display = 'block';
        initializeShortAnswers('single');
    } else if (type === 'essay') {
        document.getElementById('singleEssaySection').style.display = 'block';
    } else if (type === 'image_based') {
        document.getElementById('singleImageBasedSection').style.display = 'block';
    } else if (type === 'math_equation') {
        document.getElementById('singleMathEquationSection').style.display = 'block';
        initializeMathAnswers('single');
    }
});

function hideAllQuestionTypeSections(formType) {
    const sections = [
        'OptionsSection',
        'FillBlankSection',
        'ShortAnswerSection',
        'EssaySection',
        'ImageBasedSection',
        'MathEquationSection',
        'DragDropSection'
    ];

    sections.forEach(section => {
        const element = document.getElementById(formType + section);
        if (element) {
            element.style.display = 'none';
        }
    });
}

function addSingleOption(defaultText = '', questionType = '') {
    const optionsList = document.getElementById('singleOptionsList');
    const optionDiv = document.createElement('div');
    optionDiv.className = 'option-item';

    // Determine input type and behavior based on question type
    const isSingleAnswer = ['true_false', 'yes_no'].includes(questionType);
    const inputType = isSingleAnswer ? 'radio' : 'checkbox';
    const inputName = isSingleAnswer ? 'single_correct_option' : `options[${singleOptionCount}][is_correct]`;
    const inputValue = isSingleAnswer ? singleOptionCount : '1';
    const showDeleteButton = !isSingleAnswer; // Don't show delete for true/false and yes/no

    optionDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="form-check me-3">
                <input class="form-check-input" type="${inputType}" name="${inputName}" value="${inputValue}"
                       ${isSingleAnswer ? 'required' : ''}>
                <label class="form-check-label">Correct</label>
            </div>
            <input type="text" class="form-control me-2" name="options[${singleOptionCount}][option_text]"
                   placeholder="Option ${singleOptionCount + 1}" value="${defaultText}" required
                   ${isSingleAnswer ? 'readonly' : ''}>
            ${showDeleteButton ? `
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSingleOption(this)">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            ` : ''}
        </div>
    `;

    optionsList.appendChild(optionDiv);
    singleOptionCount++;
}

function removeSingleOption(button) {
    button.closest('.option-item').remove();
}

function hideAddOptionButton(formType) {
    const selector = formType === 'single' ? '#singleOptionsSection button[onclick*="addSingleOption"]' :
                     '#bulkOptionsSection button[onclick*="addBulkOption"]';
    const button = document.querySelector(selector);
    if (button) {
        button.style.display = 'none';
    }
}

function showAddOptionButton(formType) {
    const selector = formType === 'single' ? '#singleOptionsSection button[onclick*="addSingleOption"]' :
                     '#bulkOptionsSection button[onclick*="addBulkOption"]';
    const button = document.querySelector(selector);
    if (button) {
        button.style.display = 'inline-block';
    }
}

// Fill in the Blank Functions
function initializeBlankAnswers(formType) {
    // Don't auto-initialize - wait for user to detect blanks
    const listId = formType + 'BlankAnswersList';
    const list = document.getElementById(listId);
    if (list) {
        list.innerHTML = `
            <div class="alert alert-secondary text-center">
                <i class="material-symbols-rounded me-2">info</i>
                Write your question text with [BLANK] markers first, then click "Detect Blanks" to configure answers.
            </div>
        `;
    }
}

function detectBlanks(formType) {
    const questionTextId = formType === 'single' ? 'singleQuestionText' : `bulkQuestionText_${formType}`;
    const questionText = document.getElementById(questionTextId).value;
    const blankMatches = questionText.match(/\[BLANK\]/g);

    if (!blankMatches || blankMatches.length === 0) {
        showCustomAlert('No [BLANK] markers found in the question text. Please add [BLANK] where you want students to fill in answers.', 'warning');
        return;
    }

    const blankCount = blankMatches.length;
    const listId = formType + 'BlankAnswersList';
    const list = document.getElementById(listId);

    // Clear existing content
    list.innerHTML = '';

    // Add header
    const headerDiv = document.createElement('div');
    headerDiv.className = 'mb-3';
    headerDiv.innerHTML = `
        <h6 class="text-success">
            <i class="material-symbols-rounded me-2">check_circle</i>
            Found ${blankCount} blank(s) in your question
        </h6>
        <small class="text-muted">Configure acceptable answers for each blank below. Each blank will be worth ${Math.round(100/blankCount)}% of the total mark.</small>
    `;
    list.appendChild(headerDiv);

    // Create answer sections for each blank
    for (let i = 1; i <= blankCount; i++) {
        addBlankAnswerSection(formType, i, blankCount);
    }
}

function addBlankAnswerSection(formType, blankNumber, totalBlanks) {
    const listId = formType + 'BlankAnswersList';
    const list = document.getElementById(listId);

    const blankSection = document.createElement('div');
    blankSection.className = 'blank-section mb-4 p-3 border rounded';
    blankSection.innerHTML = `
        <h6 class="mb-3" style="color: var(--primary-color);">
            <i class="material-symbols-rounded me-2">edit</i>
            Blank ${blankNumber} of ${totalBlanks}
        </h6>
        <div class="blank-answers-list" id="${formType}BlankAnswers_${blankNumber}">
            <div class="input-group mb-2">
                <span class="input-group-text">Answer ${blankNumber}.1</span>
                <input type="text" class="form-control" name="blank_answers[${blankNumber}][]"
                       placeholder="First acceptable answer for blank ${blankNumber}" required>
                <button type="button" class="btn btn-outline-danger" onclick="removeBlankAnswer(this)">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            </div>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAlternativeAnswer('${formType}', ${blankNumber})">
            <i class="material-symbols-rounded me-1">add</i>Add Alternative Answer for Blank ${blankNumber}
        </button>
    `;

    list.appendChild(blankSection);
}

function addAlternativeAnswer(formType, blankNumber) {
    const containerId = `${formType}BlankAnswers_${blankNumber}`;
    const container = document.getElementById(containerId);
    const answerCount = container.children.length + 1;

    const answerDiv = document.createElement('div');
    answerDiv.className = 'input-group mb-2';
    answerDiv.innerHTML = `
        <span class="input-group-text">Answer ${blankNumber}.${answerCount}</span>
        <input type="text" class="form-control" name="blank_answers[${blankNumber}][]"
               placeholder="Alternative answer for blank ${blankNumber}" required>
        <button type="button" class="btn btn-outline-danger" onclick="removeBlankAnswer(this)">
            <i class="material-symbols-rounded">delete</i>
        </button>
    `;

    container.appendChild(answerDiv);
}

function removeBlankAnswer(button) {
    const answerDiv = button.closest('.input-group');
    const container = answerDiv.parentNode;

    // Don't remove if it's the only answer for this blank
    if (container.children.length > 1) {
        answerDiv.remove();

        // Renumber remaining answers
        const blankSection = container.closest('.blank-section');
        const blankNumber = blankSection.querySelector('h6').textContent.match(/Blank (\d+)/)[1];
        const answers = container.querySelectorAll('.input-group');

        answers.forEach((answer, index) => {
            const label = answer.querySelector('.input-group-text');
            label.textContent = `Answer ${blankNumber}.${index + 1}`;
        });
    } else {
        showCustomAlert('Each blank must have at least one acceptable answer.', 'warning');
    }
}

// Short Answer Functions
function initializeShortAnswers(formType) {
    const listId = formType + 'ShortAnswersList';
    const list = document.getElementById(listId);
    if (list) {
        list.innerHTML = '';
        addShortAnswer(formType);
    }
}

function addShortAnswer(formType) {
    const listId = formType + 'ShortAnswersList';
    const list = document.getElementById(listId);
    const answerDiv = document.createElement('div');
    answerDiv.className = 'input-group mb-2';

    const answerCount = list.children.length;
    answerDiv.innerHTML = `
        <input type="text" class="form-control" name="short_answers[]"
               placeholder="Acceptable answer/keyword ${answerCount + 1}" required>
        <button type="button" class="btn btn-outline-danger" onclick="removeShortAnswer(this)">
            <i class="material-symbols-rounded">delete</i>
        </button>
    `;

    list.appendChild(answerDiv);
}

function removeShortAnswer(button) {
    const answerDiv = button.closest('.input-group');
    if (answerDiv.parentNode.children.length > 1) {
        answerDiv.remove();
    }
}

// Math Equation Functions
function initializeMathAnswers(formType) {
    const listId = formType + 'MathAnswersList';
    const list = document.getElementById(listId);
    if (list) {
        list.innerHTML = '';
        addMathAnswer(formType);
    }
}

function addMathAnswer(formType) {
    const listId = formType + 'MathAnswersList';
    const list = document.getElementById(listId);
    const answerDiv = document.createElement('div');
    answerDiv.className = 'input-group mb-2';

    const answerCount = list.children.length;
    answerDiv.innerHTML = `
        <input type="text" class="form-control" name="math_answers[]"
               placeholder="Correct answer ${answerCount + 1} (e.g., x=5, 2.5, etc.)" required>
        <button type="button" class="btn btn-outline-danger" onclick="removeMathAnswer(this)">
            <i class="material-symbols-rounded">delete</i>
        </button>
    `;

    list.appendChild(answerDiv);
}

function removeMathAnswer(button) {
    const answerDiv = button.closest('.input-group');
    if (answerDiv.parentNode.children.length > 1) {
        answerDiv.remove();
    }
}

// Drag & Drop Functions
function initializeDragDropItems(formType) {
    const itemsList = document.getElementById(formType + 'DragDropItemsList');
    const zonesList = document.getElementById(formType + 'DropZonesList');
    const matchesList = document.getElementById(formType + 'DragDropMatches');

    if (itemsList) itemsList.innerHTML = '';
    if (zonesList) zonesList.innerHTML = '';
    if (matchesList) {
        matchesList.innerHTML = `
            <div class="alert alert-secondary text-center">
                <i class="material-symbols-rounded me-2">info</i>
                Add draggable items and drop zones first, then define correct matches below.
            </div>
        `;
    }

    // Add initial items
    addDragDropItem(formType);
    addDragDropItem(formType);
    addDropZone(formType);
    addDropZone(formType);
}

function addDragDropItem(formType) {
    const itemsList = document.getElementById(formType + 'DragDropItemsList');
    const itemCount = itemsList.children.length;

    const itemDiv = document.createElement('div');
    itemDiv.className = 'input-group mb-2';
    itemDiv.innerHTML = `
        <span class="input-group-text">Item ${itemCount + 1}</span>
        <input type="text" class="form-control drag-item-input" name="drag_items[]"
               placeholder="Draggable item ${itemCount + 1}" required data-item-id="${itemCount + 1}">
        <button type="button" class="btn btn-outline-danger" onclick="removeDragDropItem(this, '${formType}')">
            <i class="material-symbols-rounded">delete</i>
        </button>
    `;

    itemsList.appendChild(itemDiv);
    updateDragDropMatches(formType);
}

function addDropZone(formType) {
    const zonesList = document.getElementById(formType + 'DropZonesList');
    const zoneCount = zonesList.children.length;

    const zoneDiv = document.createElement('div');
    zoneDiv.className = 'input-group mb-2';
    zoneDiv.innerHTML = `
        <span class="input-group-text">Zone ${zoneCount + 1}</span>
        <input type="text" class="form-control drop-zone-input" name="drop_zones[]"
               placeholder="Drop zone ${zoneCount + 1}" required data-zone-id="${zoneCount + 1}">
        <button type="button" class="btn btn-outline-danger" onclick="removeDropZone(this, '${formType}')">
            <i class="material-symbols-rounded">delete</i>
        </button>
    `;

    zonesList.appendChild(zoneDiv);
    updateDragDropMatches(formType);
}

function removeDragDropItem(button, formType) {
    const itemDiv = button.closest('.input-group');
    const itemsList = itemDiv.parentNode;

    if (itemsList.children.length > 1) {
        itemDiv.remove();
        renumberDragDropItems(formType);
        updateDragDropMatches(formType);
    } else {
        showCustomAlert('You must have at least one draggable item.', 'warning');
    }
}

function removeDropZone(button, formType) {
    const zoneDiv = button.closest('.input-group');
    const zonesList = zoneDiv.parentNode;

    if (zonesList.children.length > 1) {
        zoneDiv.remove();
        renumberDropZones(formType);
        updateDragDropMatches(formType);
    } else {
        showCustomAlert('You must have at least one drop zone.', 'warning');
    }
}

function renumberDragDropItems(formType) {
    const itemsList = document.getElementById(formType + 'DragDropItemsList');
    const items = itemsList.querySelectorAll('.input-group');

    items.forEach((item, index) => {
        const label = item.querySelector('.input-group-text');
        const input = item.querySelector('.drag-item-input');

        label.textContent = `Item ${index + 1}`;
        input.placeholder = `Draggable item ${index + 1}`;
        input.dataset.itemId = index + 1;
    });
}

function renumberDropZones(formType) {
    const zonesList = document.getElementById(formType + 'DropZonesList');
    const zones = zonesList.querySelectorAll('.input-group');

    zones.forEach((zone, index) => {
        const label = zone.querySelector('.input-group-text');
        const input = zone.querySelector('.drop-zone-input');

        label.textContent = `Zone ${index + 1}`;
        input.placeholder = `Drop zone ${index + 1}`;
        input.dataset.zoneId = index + 1;
    });
}

function updateDragDropMatches(formType) {
    const itemsList = document.getElementById(formType + 'DragDropItemsList');
    const zonesList = document.getElementById(formType + 'DropZonesList');
    const matchesList = document.getElementById(formType + 'DragDropMatches');

    const items = itemsList.querySelectorAll('.drag-item-input');
    const zones = zonesList.querySelectorAll('.drop-zone-input');

    if (items.length === 0 || zones.length === 0) {
        matchesList.innerHTML = `
            <div class="alert alert-secondary text-center">
                <i class="material-symbols-rounded me-2">info</i>
                Add draggable items and drop zones first, then define correct matches below.
            </div>
        `;
        return;
    }

    matchesList.innerHTML = '<h6 class="mb-3">Define Correct Matches:</h6>';

    items.forEach((item, itemIndex) => {
        const itemText = item.value || `Item ${itemIndex + 1}`;

        const matchDiv = document.createElement('div');
        matchDiv.className = 'mb-3 p-3 border rounded';
        matchDiv.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <strong>${itemText}</strong>
                    <small class="text-muted d-block">Draggable Item</small>
                </div>
                <div class="col-md-1 text-center">
                    <i class="material-symbols-rounded">arrow_forward</i>
                </div>
                <div class="col-md-7">
                    <select class="form-control" name="drag_matches[${itemIndex}]" required>
                        <option value="">Select correct drop zone</option>
                        ${Array.from(zones).map((zone, zoneIndex) => {
                            const zoneText = zone.value || `Zone ${zoneIndex + 1}`;
                            return `<option value="${zoneIndex}">${zoneText}</option>`;
                        }).join('')}
                    </select>
                </div>
            </div>
        `;

        matchesList.appendChild(matchDiv);
    });
}

// Add event listeners for real-time updates
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('drag-item-input') || e.target.classList.contains('drop-zone-input')) {
        const formType = e.target.closest('[id*="DragDrop"]').id.includes('single') ? 'single' : 'bulk';
        updateDragDropMatches(formType);
    }
});

// Essay Rubric Functions
function toggleRubricSection() {
    const checkbox = document.getElementById('enableRubric');
    const rubricConfig = document.getElementById('rubricConfiguration');
    const traditionalRubric = document.getElementById('traditionalRubric');

    if (checkbox.checked) {
        rubricConfig.style.display = 'block';
        traditionalRubric.style.display = 'none';
        updateRubricTemplate(); // Initialize with default template
    } else {
        rubricConfig.style.display = 'none';
        traditionalRubric.style.display = 'block';
    }
}

function updateRubricTemplate() {
    const rubricType = document.getElementById('rubricType').value;
    const maxScore = parseInt(document.getElementById('rubricMaxScore').value) || 10;
    const criteriaContainer = document.getElementById('rubricCriteria');

    let criteriaHTML = '<h6 class="mb-3">Grading Criteria:</h6>';

    if (rubricType === 'content_quality') {
        criteriaHTML += generateContentQualityRubric(maxScore);
    } else if (rubricType === 'comprehensive') {
        criteriaHTML += generateComprehensiveRubric(maxScore);
    } else if (rubricType === 'custom') {
        criteriaHTML += generateCustomRubric(maxScore);
    }

    criteriaContainer.innerHTML = criteriaHTML;
}

function generateContentQualityRubric(maxScore) {
    const levels = [
        { name: 'Excellent', percentage: 90, description: 'Comprehensive, accurate, well-researched content' },
        { name: 'Good', percentage: 75, description: 'Adequate content with minor gaps' },
        { name: 'Fair', percentage: 60, description: 'Basic content with some inaccuracies' },
        { name: 'Poor', percentage: 40, description: 'Insufficient or mostly incorrect content' }
    ];

    let html = `
        <div class="rubric-criterion mb-4 p-3 border rounded">
            <h6 class="text-primary">Content Quality (${maxScore} marks)</h6>
            <input type="hidden" name="rubric_criteria[0][name]" value="Content Quality">
            <input type="hidden" name="rubric_criteria[0][weight]" value="100">
            <div class="row">
    `;

    levels.forEach((level, index) => {
        const points = Math.round((level.percentage / 100) * maxScore);
        html += `
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <h6 class="card-title">${level.name} (${points}/${maxScore})</h6>
                        <input type="hidden" name="rubric_criteria[0][levels][${index}][name]" value="${level.name}">
                        <input type="hidden" name="rubric_criteria[0][levels][${index}][points]" value="${points}">
                        <textarea class="form-control" name="rubric_criteria[0][levels][${index}][description]"
                                  rows="2" placeholder="Description for ${level.name} level">${level.description}</textarea>
                    </div>
                </div>
            </div>
        `;
    });

    html += '</div></div>';
    return html;
}

function generateComprehensiveRubric(maxScore) {
    const criteria = [
        { name: 'Content Quality', weight: 50 },
        { name: 'Organization', weight: 30 },
        { name: 'Grammar & Style', weight: 20 }
    ];

    let html = '';

    criteria.forEach((criterion, criterionIndex) => {
        const criterionMaxScore = Math.round((criterion.weight / 100) * maxScore);

        html += `
            <div class="rubric-criterion mb-4 p-3 border rounded">
                <h6 class="text-primary">${criterion.name} (${criterionMaxScore} points - ${criterion.weight}%)</h6>
                <input type="hidden" name="rubric_criteria[${criterionIndex}][name]" value="${criterion.name}">
                <input type="hidden" name="rubric_criteria[${criterionIndex}][weight]" value="${criterion.weight}">
                <div class="row">
        `;

        const levels = [
            { name: 'Excellent', percentage: 90 },
            { name: 'Good', percentage: 75 },
            { name: 'Fair', percentage: 60 },
            { name: 'Poor', percentage: 40 }
        ];

        levels.forEach((level, levelIndex) => {
            const points = Math.round((level.percentage / 100) * criterionMaxScore);
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <h6 class="card-title">${level.name} (${points}/${criterionMaxScore})</h6>
                            <input type="hidden" name="rubric_criteria[${criterionIndex}][levels][${levelIndex}][name]" value="${level.name}">
                            <input type="hidden" name="rubric_criteria[${criterionIndex}][levels][${levelIndex}][points]" value="${points}">
                            <textarea class="form-control" name="rubric_criteria[${criterionIndex}][levels][${levelIndex}][description]"
                                      rows="2" placeholder="Description for ${level.name} ${criterion.name}"></textarea>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div></div>';
    });

    return html;
}

function generateCustomRubric(maxScore) {
    return `
        <div class="alert alert-info">
            <i class="material-symbols-rounded me-2">info</i>
            Custom rubric builder - Add your own criteria and performance levels.
        </div>
        <div id="customCriteriaList">
            <!-- Custom criteria will be added here -->
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomCriterion()">
            <i class="material-symbols-rounded me-1">add</i>Add Criterion
        </button>
    `;
}

let customCriterionCount = 0;

function addCustomCriterion() {
    const container = document.getElementById('customCriteriaList');
    const maxScore = parseInt(document.getElementById('rubricMaxScore').value) || 10;

    const criterionDiv = document.createElement('div');
    criterionDiv.className = 'rubric-criterion mb-4 p-3 border rounded';
    criterionDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-primary mb-0">Custom Criterion ${customCriterionCount + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomCriterion(this)">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control" name="rubric_criteria[${customCriterionCount}][name]"
                           placeholder="Criterion name" required>
                    <label>Criterion Name</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="number" class="form-control" name="rubric_criteria[${customCriterionCount}][weight]"
                           min="1" max="100" value="25" placeholder="Weight percentage">
                    <label>Weight (%)</label>
                </div>
            </div>
        </div>
        <div class="custom-levels" id="customLevels_${customCriterionCount}">
            <!-- Performance levels will be added here -->
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCustomLevel(${customCriterionCount})">
            <i class="material-symbols-rounded me-1">add</i>Add Performance Level
        </button>
    `;

    container.appendChild(criterionDiv);

    // Add default levels
    addCustomLevel(customCriterionCount);
    addCustomLevel(customCriterionCount);

    customCriterionCount++;
}

function addCustomLevel(criterionIndex) {
    const levelsContainer = document.getElementById(`customLevels_${criterionIndex}`);
    const levelCount = levelsContainer.children.length;

    const levelDiv = document.createElement('div');
    levelDiv.className = 'row mb-2';
    levelDiv.innerHTML = `
        <div class="col-md-3">
            <input type="text" class="form-control" name="rubric_criteria[${criterionIndex}][levels][${levelCount}][name]"
                   placeholder="Level name" required>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="rubric_criteria[${criterionIndex}][levels][${levelCount}][points]"
                   min="0" placeholder="Points" required>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" name="rubric_criteria[${criterionIndex}][levels][${levelCount}][description]"
                   placeholder="Description">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomLevel(this)">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
    `;

    levelsContainer.appendChild(levelDiv);
}

function removeCustomCriterion(button) {
    button.closest('.rubric-criterion').remove();
}

function removeCustomLevel(button) {
    const levelsContainer = button.closest('.custom-levels');
    if (levelsContainer.children.length > 1) {
        button.closest('.row').remove();
    } else {
        showCustomAlert('Each criterion must have at least one performance level.', 'warning');
    }
}

// Math Equation Functions
function updateMathExamples() {
    const format = document.getElementById('equationFormat').value;
    const examplesContainer = document.getElementById('mathFormatExamples');

    let examplesHTML = '';

    if (format === 'text') {
        examplesHTML = `
            <div class="alert alert-success">
                <h6>Plain Text Questions (What YOU type as teacher):</h6>
                <ul class="mb-0">
                    <li><strong>Simple:</strong> "Solve: 2x + 5 = 15"</li>
                    <li><strong>Fractions:</strong> "Simplify: 6/8"</li>
                    <li><strong>Geometry:</strong> "Area of circle with radius 3"</li>
                </ul>
                <h6 class="mt-3">Students Will Type Simple Answers:</h6>
                <ul class="mb-0">
                    <li><strong>Numbers:</strong> 5, 3.14, -2.5</li>
                    <li><strong>Fractions:</strong> 3/4, 0.75</li>
                    <li><strong>Variables:</strong> x=5, y=-3</li>
                </ul>
            </div>
        `;
    } else if (format === 'latex') {
        examplesHTML = `
            <div class="alert alert-warning">
                <h6>LaTeX Questions (What YOU type as teacher):</h6>
                <ul class="mb-0">
                    <li><strong>Fractions:</strong> "Solve: \\frac{x+3}{2} = 5" → Students see beautiful fraction</li>
                    <li><strong>Powers:</strong> "Simplify: x^{2} + 3x^{2}" → Students see: x² + 3x²</li>
                    <li><strong>Roots:</strong> "Calculate: \\sqrt{16} + \\sqrt{25}" → Students see: √16 + √25</li>
                    <li><strong>Complex:</strong> "Solve: \\frac{x^2 - 4}{x + 2} = 0" → Students see formatted equation</li>
                </ul>
                <h6 class="mt-3 text-success">Students Still Type Simple Answers:</h6>
                <ul class="mb-0 text-success">
                    <li><strong>For fraction question:</strong> Student types "x=7" (not LaTeX!)</li>
                    <li><strong>For power question:</strong> Student types "4x^2" or "4x²"</li>
                    <li><strong>For root question:</strong> Student types "9" or "3"</li>
                </ul>
            </div>
        `;
    } else if (format === 'mathml') {
        examplesHTML = `
            <div class="alert alert-info">
                <h6>MathML Questions (What YOU type as teacher):</h6>
                <ul class="mb-0">
                    <li><strong>Fraction:</strong> &lt;mfrac&gt;&lt;mn&gt;x+3&lt;/mn&gt;&lt;mn&gt;2&lt;/mn&gt;&lt;/mfrac&gt; = 5 → Students see beautiful fraction</li>
                    <li><strong>Power:</strong> &lt;msup&gt;&lt;mi&gt;x&lt;/mi&gt;&lt;mn&gt;2&lt;/mn&gt;&lt;/msup&gt; + 3x = 0 → Students see: x² + 3x = 0</li>
                    <li><strong>Root:</strong> &lt;msqrt&gt;&lt;mn&gt;16&lt;/mn&gt;&lt;/msqrt&gt; + 5 = ? → Students see: √16 + 5 = ?</li>
                </ul>
                <h6 class="mt-3 text-success">Students Still Type Simple Answers:</h6>
                <ul class="mb-0 text-success">
                    <li><strong>For any MathML question:</strong> Student types "x=7", "9", "3/4" etc. (not MathML!)</li>
                </ul>
            </div>
        `;
    }

    examplesContainer.innerHTML = examplesHTML;
}

// Initialize math examples on page load
document.addEventListener('DOMContentLoaded', function() {
    updateMathExamples();
});

function previewSingleQuestion() {
    const form = document.getElementById('singleQuestionFormElement');
    const formData = new FormData(form);

    // Get form values
    const questionText = formData.get('question_text');
    const questionType = formData.get('question_type');
    const difficulty = formData.get('difficulty');
    const points = formData.get('points');
    const timeLimit = formData.get('time_limit');
    const explanation = formData.get('explanation');
    const hints = formData.get('hints');

    // Get subject and class names
    const subjectSelect = document.getElementById('singleSubject');
    const classSelect = document.getElementById('singleClass');
    const subjectName = subjectSelect.options[subjectSelect.selectedIndex]?.text || 'Not selected';
    const className = classSelect.options[classSelect.selectedIndex]?.text || 'Not selected';

    // Get question type label
    const questionTypeLabels = {
        'mcq': 'Multiple Choice',
        'true_false': 'True/False',
        'yes_no': 'Yes/No',
        'fill_blank': 'Fill in the Blank',
        'short_answer': 'Short Answer',
        'essay': 'Essay',
        'drag_drop': 'Drag & Drop',
        'image_based': 'Image Based',
        'math_equation': 'Math Equation'
    };

    // Build preview content
    let previewHTML = `
        <div class="question-preview">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Subject:</strong> ${subjectName}
                </div>
                <div class="col-md-4">
                    <strong>Class:</strong> ${className}
                </div>
                <div class="col-md-4">
                    <strong>Type:</strong> ${questionTypeLabels[questionType] || questionType}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Difficulty:</strong> <span class="badge bg-${getDifficultyColor(difficulty)}">${difficulty}</span>
                </div>
                <div class="col-md-4">
                    <strong>Mark:</strong> ${points || 1} points
                </div>
                <div class="col-md-4">
                    <strong>Time Limit:</strong> ${timeLimit ? timeLimit + ' seconds' : 'No limit'}
                </div>
            </div>

            <div class="question-content">
                <h5>Question:</h5>
                <div class="p-3 bg-light rounded">${questionText || 'No question text entered'}</div>
            </div>
    `;

    // Add options if applicable
    if (['mcq', 'true_false', 'yes_no', 'drag_drop'].includes(questionType)) {
        const options = form.querySelectorAll('input[name*="[option_text]"]');
        if (options.length > 0) {
            previewHTML += `
                <div class="mt-3">
                    <h6>Answer Options:</h6>
                    <div class="options-preview">
            `;

            options.forEach((option, index) => {
                const isCorrect = form.querySelector(`input[name*="[${index}][is_correct]"]`)?.checked;
                previewHTML += `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" disabled>
                        <label class="form-check-label ${isCorrect ? 'text-success fw-bold' : ''}">
                            ${option.value || `Option ${index + 1}`}
                            ${isCorrect ? ' ✓' : ''}
                        </label>
                    </div>
                `;
            });

            previewHTML += `
                    </div>
                </div>
            `;
        }
    }

    // Add hints if provided
    if (hints) {
        previewHTML += `
            <div class="mt-3">
                <h6>Hints for Students:</h6>
                <div class="p-2 bg-info bg-opacity-10 rounded">${hints}</div>
            </div>
        `;
    }

    // Add explanation if provided
    if (explanation) {
        previewHTML += `
            <div class="mt-3">
                <h6>Explanation (shown after exam):</h6>
                <div class="p-2 bg-success bg-opacity-10 rounded">${explanation}</div>
            </div>
        `;
    }

    previewHTML += `</div>`;

    // Show preview modal
    document.getElementById('previewContent').innerHTML = previewHTML;
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

function getDifficultyColor(difficulty) {
    const colors = {
        'easy': 'success',
        'medium': 'warning',
        'hard': 'danger'
    };
    return colors[difficulty] || 'secondary';
}

function submitAfterPreview() {
    // Close modal and submit form
    const modal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
    modal.hide();
    document.getElementById('singleQuestionFormElement').submit();
}

// Bulk Questions Functions
function generateQuestions() {
    const count = parseInt(document.getElementById('questionCount').value) || 5;
    const container = document.getElementById('bulkQuestionsContainer');

    container.innerHTML = '';
    bulkQuestionCount = count;
    bulkOptionCounts = {};

    for (let i = 0; i < count; i++) {
        addBulkQuestion(i + 1);
    }

    updateProgress();
}

function addBulkQuestion(questionNumber) {
    const container = document.getElementById('bulkQuestionsContainer');
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item';
    questionDiv.innerHTML = `
        <div class="question-number">Question ${questionNumber}</div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-control" name="questions[${questionNumber - 1}][question_type]"
                            onchange="handleBulkQuestionTypeChange(${questionNumber - 1}, this.value)" required>
                        <option value="">Choose Type</option>
                        <?php
                        // Only show question types that work well in bulk mode
                        $bulk_supported_types = ['mcq', 'true_false', 'yes_no', 'fill_blank', 'short_answer', 'essay'];
                        foreach ($question_types as $key => $label):
                            if (in_array($key, $bulk_supported_types)):
                        ?>
                            <option value="<?= $key ?>"><?= esc($label) ?></option>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                    <label>Question Type *</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <select class="form-control" name="questions[${questionNumber - 1}][difficulty]" required>
                        <?php foreach ($difficulties as $key => $label): ?>
                            <option value="<?= $key ?>"><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Difficulty *</label>
                </div>
            </div>
        </div>

        <div class="form-floating mb-3">
            <textarea class="form-control" name="questions[${questionNumber - 1}][question_text]"
                      style="height: 100px;" required placeholder="Enter question ${questionNumber}..."
                      onblur="validateBulkQuestionText(this, ${questionNumber - 1})"></textarea>
            <label>Question Text *</label>
        </div>

        <!-- Duplicate Alert for Bulk Questions -->
        <div id="bulkDuplicateAlert_${questionNumber - 1}" class="alert alert-warning p-2 mb-3" style="display: none;">
            <i class="material-symbols-rounded me-1" style="font-size: 16px;">warning</i>
            <small>Checking for duplicates...</small>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="questions[${questionNumber - 1}][points]"
                           value="1" min="1" max="100" required>
                    <label>Mark *</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="questions[${questionNumber - 1}][time_limit]"
                           min="0" placeholder="Optional">
                    <label>Time Limit (Optional)</label>
                </div>
            </div>
        </div>

        <!-- Question Type Specific Sections -->
        <div id="bulkOptionsSection_${questionNumber - 1}" style="display: none;">
            <h6 class="mb-3" style="color: var(--primary-color);">
                <i class="material-symbols-rounded me-2">radio_button_checked</i>
                Answer Options
            </h6>
            <div id="bulkOptionsList_${questionNumber - 1}"></div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addBulkOption(${questionNumber - 1})">
                <i class="material-symbols-rounded me-1">add</i>Add Option
            </button>
        </div>

        <!-- Fill in the Blank Section -->
        <div id="bulkFillBlankSection_${questionNumber - 1}" style="display: none;">
            <h6 class="mb-3" style="color: var(--primary-color);">
                <i class="material-symbols-rounded me-2">edit</i>
                Fill in the Blank Configuration
            </h6>
            <div class="alert alert-info">
                <i class="material-symbols-rounded me-2">info</i>
                Use <code>[BLANK]</code> in your question text to mark where students should fill in answers.
                <br><br><strong>Examples:</strong>
                <br>• Geography: "The capital of [BLANK] is [BLANK] and it is located in the [BLANK] region."
                <br>• Math: "If x = 5, then 2x = [BLANK] and x² = [BLANK]."
                <br>• Science: "Water boils at [BLANK]°C and freezes at [BLANK]°C."
            </div>
            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="detectBulkBlanks(${questionNumber - 1})">
                    <i class="material-symbols-rounded me-1">search</i>Detect Blanks in Question
                </button>
                <small class="text-muted d-block mt-1">Click this after writing your question text to automatically detect [BLANK] markers.</small>
            </div>
            <div id="bulkBlankAnswers_${questionNumber - 1}">
                <div id="bulkBlankAnswersList_${questionNumber - 1}">
                    <div class="alert alert-secondary text-center">
                        <i class="material-symbols-rounded me-2">info</i>
                        Write your question text with [BLANK] markers first, then click "Detect Blanks" to configure answers.
                    </div>
                </div>
            </div>
        </div>

        <!-- Short Answer Section -->
        <div id="bulkShortAnswerSection_${questionNumber - 1}" style="display: none;">
            <h6 class="mb-3" style="color: var(--primary-color);">
                <i class="material-symbols-rounded me-2">short_text</i>
                Short Answer Configuration
            </h6>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="questions[${questionNumber - 1}][max_words]" min="1" max="500" value="50">
                <label>Maximum Words Allowed</label>
            </div>
            <div id="bulkShortAnswers_${questionNumber - 1}">
                <label class="form-label">Acceptable Answers/Keywords:</label>
                <div id="bulkShortAnswersList_${questionNumber - 1}"></div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addBulkShortAnswer(${questionNumber - 1})">
                    <i class="material-symbols-rounded me-1">add</i>Add Acceptable Answer
                </button>
            </div>
        </div>

        <!-- Essay Section -->
        <div id="bulkEssaySection_${questionNumber - 1}" style="display: none;">
            <h6 class="mb-3" style="color: var(--primary-color);">
                <i class="material-symbols-rounded me-2">article</i>
                Essay Configuration
            </h6>
            <div class="alert alert-info">
                <i class="material-symbols-rounded me-2">info</i>
                <strong>AI-Assisted Grading:</strong> Enable rubrics for AI to suggest marks. Teachers review and approve before students see results.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="questions[${questionNumber - 1}][min_words]" min="1" value="100">
                        <label>Minimum Words Required</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="questions[${questionNumber - 1}][max_words_essay]" min="1" value="1000">
                        <label>Maximum Words Allowed</label>
                    </div>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="questions[${questionNumber - 1}][enable_rubric]" id="bulkEnableRubric_${questionNumber - 1}" onchange="toggleBulkRubricSection(${questionNumber - 1})">
                <label class="form-check-label" for="bulkEnableRubric_${questionNumber - 1}">
                    <strong>Enable AI-Assisted Grading with Rubric</strong>
                </label>
            </div>
            <div id="bulkRubricConfiguration_${questionNumber - 1}" style="display: none;">
                <div class="form-floating">
                    <textarea class="form-control" name="questions[${questionNumber - 1}][model_answer]" style="height: 100px;"
                              placeholder="Provide a model answer or key points that should be covered..."></textarea>
                    <label>Model Answer / Key Points (for AI reference)</label>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="questions[${questionNumber - 1}][explanation]"
                              style="height: 60px;" placeholder="Explain the answer..."></textarea>
                    <label>Explanation (Optional)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="questions[${questionNumber - 1}][hints]"
                              style="height: 60px;" placeholder="Provide hints..."></textarea>
                    <label>Hints (Optional)</label>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBulkQuestion(this)">
                <i class="material-symbols-rounded me-1">delete</i>Remove Question
            </button>
        </div>
    `;

    container.appendChild(questionDiv);
    bulkOptionCounts[questionNumber - 1] = 0;
}

function handleBulkQuestionTypeChange(questionIndex, type) {
    // Hide all sections first
    const sections = [
        'bulkOptionsSection',
        'bulkFillBlankSection',
        'bulkShortAnswerSection',
        'bulkEssaySection'
    ];

    sections.forEach(sectionName => {
        const section = document.getElementById(`${sectionName}_${questionIndex}`);
        if (section) {
            section.style.display = 'none';
        }
    });

    // Clear options list
    const optionsList = document.getElementById(`bulkOptionsList_${questionIndex}`);
    if (optionsList) {
        optionsList.innerHTML = '';
        bulkOptionCounts[questionIndex] = 0;
    }

    // Show appropriate section based on question type
    switch (type) {
        case 'mcq':
            document.getElementById(`bulkOptionsSection_${questionIndex}`).style.display = 'block';
            addBulkOption(questionIndex, '', type);
            addBulkOption(questionIndex, '', type);
            showBulkAddOptionButton(questionIndex);
            break;

        case 'true_false':
            document.getElementById(`bulkOptionsSection_${questionIndex}`).style.display = 'block';
            addBulkOption(questionIndex, 'True', type);
            addBulkOption(questionIndex, 'False', type);
            hideBulkAddOptionButton(questionIndex);
            break;

        case 'yes_no':
            document.getElementById(`bulkOptionsSection_${questionIndex}`).style.display = 'block';
            addBulkOption(questionIndex, 'Yes', type);
            addBulkOption(questionIndex, 'No', type);
            hideBulkAddOptionButton(questionIndex);
            break;

        case 'fill_blank':
            document.getElementById(`bulkFillBlankSection_${questionIndex}`).style.display = 'block';
            break;

        case 'short_answer':
            document.getElementById(`bulkShortAnswerSection_${questionIndex}`).style.display = 'block';
            // Add initial short answer field
            addBulkShortAnswer(questionIndex);
            break;

        case 'essay':
            document.getElementById(`bulkEssaySection_${questionIndex}`).style.display = 'block';
            break;

        default:
            // For question types not supported in bulk mode, show a helpful message
            if (['math_equation', 'image_based', 'drag_drop'].includes(type)) {
                showCustomAlert(`${type.replace('_', ' ').toUpperCase()} questions are not available in bulk mode. Please use the dedicated bulk creation page or single question mode for these question types.`, 'info');
                // Reset the select to empty
                const questionCard = document.querySelector(`#bulkQuestionsContainer .question-item:nth-child(${questionIndex + 1})`);
                const questionTypeSelect = questionCard.querySelector('select[name*="[question_type]"]');
                questionTypeSelect.value = '';
            }
            break;
    }
}

function addBulkOption(questionIndex, defaultText = '', questionType = '') {
    const optionsList = document.getElementById(`bulkOptionsList_${questionIndex}`);
    const optionCount = bulkOptionCounts[questionIndex];
    const optionDiv = document.createElement('div');
    optionDiv.className = 'option-item';

    // Determine input type and behavior based on question type
    const isSingleAnswer = ['true_false', 'yes_no'].includes(questionType);
    const inputType = isSingleAnswer ? 'radio' : 'checkbox';
    const inputName = isSingleAnswer ? `questions[${questionIndex}][correct_option]` : `questions[${questionIndex}][options][${optionCount}][is_correct]`;
    const inputValue = isSingleAnswer ? optionCount : '1';
    const showDeleteButton = !isSingleAnswer; // Don't show delete for true/false and yes/no

    optionDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="form-check me-3">
                <input class="form-check-input" type="${inputType}"
                       name="${inputName}" value="${inputValue}"
                       ${isSingleAnswer ? 'required' : ''}>
                <label class="form-check-label">Correct</label>
            </div>
            <input type="text" class="form-control me-2"
                   name="questions[${questionIndex}][options][${optionCount}][option_text]"
                   placeholder="Option ${optionCount + 1}" value="${defaultText}" required
                   ${isSingleAnswer ? 'readonly' : ''}>
            ${showDeleteButton ? `
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBulkOption(this)">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            ` : ''}
        </div>
    `;

    optionsList.appendChild(optionDiv);
    bulkOptionCounts[questionIndex]++;
}

function removeBulkOption(button) {
    button.closest('.option-item').remove();
}

function hideBulkAddOptionButton(questionIndex) {
    const button = document.querySelector(`#bulkOptionsSection_${questionIndex} button[onclick*="addBulkOption"]`);
    if (button) {
        button.style.display = 'none';
    }
}

function showBulkAddOptionButton(questionIndex) {
    const button = document.querySelector(`#bulkOptionsSection_${questionIndex} button[onclick*="addBulkOption"]`);
    if (button) {
        button.style.display = 'inline-block';
    }
}

function removeBulkQuestion(button) {
    button.closest('.question-item').remove();
    bulkQuestionCount--;
    updateProgress();
}

function addNewQuestion() {
    bulkQuestionCount++;
    addBulkQuestion(bulkQuestionCount);
    updateProgress();
}

function updateProgress() {
    const total = bulkQuestionCount;
    const completed = document.querySelectorAll('#bulkQuestionsContainer .question-item').length;
    const percentage = total > 0 ? (completed / total) * 100 : 0;

    document.getElementById('progressText').textContent = `${completed} of ${total} questions`;
    document.getElementById('progressBar').style.width = `${percentage}%`;
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

// Progress tracking variables
let currentQuestionCount = 0;
let duplicateCheckTimeout = null;

// Bulk question type specific functions
function addBulkShortAnswer(questionIndex) {
    const container = document.getElementById(`bulkShortAnswersList_${questionIndex}`);
    const answerDiv = document.createElement('div');
    answerDiv.className = 'input-group mb-2';
    answerDiv.innerHTML = `
        <input type="text" class="form-control" name="questions[${questionIndex}][short_answers][]"
               placeholder="Acceptable answer or keyword" required>
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="material-symbols-rounded">delete</i>
        </button>
    `;
    container.appendChild(answerDiv);
}



function toggleBulkRubricSection(questionIndex) {
    const checkbox = document.getElementById(`bulkEnableRubric_${questionIndex}`);
    const section = document.getElementById(`bulkRubricConfiguration_${questionIndex}`);
    section.style.display = checkbox.checked ? 'block' : 'none';
}

function detectBulkBlanks(questionIndex) {
    const questionCard = document.querySelector(`#bulkQuestionsContainer .question-item:nth-child(${questionIndex + 1})`);
    const questionTextarea = questionCard.querySelector('textarea[name*="[question_text]"]');
    const questionText = questionTextarea.value;

    // Find all [BLANK] markers
    const blankMatches = questionText.match(/\[BLANK\]/g);
    const blankCount = blankMatches ? blankMatches.length : 0;

    const container = document.getElementById(`bulkBlankAnswersList_${questionIndex}`);

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
                        <input type="text" class="form-control" name="questions[${questionIndex}][blank_answers][${i}][]"
                               placeholder="Acceptable answer for blank ${i}" required>
                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                            <i class="material-symbols-rounded">delete</i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addBulkBlankAnswer(${questionIndex}, ${i})">
                    <i class="material-symbols-rounded me-1">add</i>Add Answer for Blank ${i}
                </button>
            </div>
        `;
    }

    container.innerHTML = blanksHtml;
    showCustomAlert(`Detected ${blankCount} blank${blankCount > 1 ? 's' : ''} in the question.`, 'success');
}

function addBulkBlankAnswer(questionIndex, blankNumber) {
    const container = document.querySelector(`#bulkBlankAnswersList_${questionIndex} .blank-group:nth-child(${blankNumber}) .blank-answers-list`);

    const answerHtml = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="questions[${questionIndex}][blank_answers][${blankNumber}][]"
                   placeholder="Acceptable answer for blank ${blankNumber}" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', answerHtml);
}

// Bulk question validation and duplicate checking
function validateBulkQuestionText(input, questionIndex) {
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
        // Check for duplicates
        checkBulkQuestionForDuplicates(input, questionIndex);
        return true;
    }

    return true; // Empty field is handled by required validation
}

function checkBulkQuestionForDuplicates(textarea, questionIndex) {
    const questionText = textarea.value.trim();
    const questionCard = textarea.closest('.question-item');
    const questionTypeSelect = questionCard.querySelector('select[name*="[question_type]"]');
    const subjectId = document.getElementById('bulkSubject').value;
    const classId = document.getElementById('bulkClass').value;
    const examTypeSelect = document.getElementById('bulkExamType');
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

    // Add term_id for duplicate checking
    const termId = '<?= $current_term['id'] ?? '' ?>';
    formData.append('term_id', termId);

    // Debug logging
    console.log('Bulk duplicate check data:', {
        question_text: questionText,
        question_type: questionType,
        subject_id: subjectId,
        class_id: classId,
        exam_type_id: examTypeId,
        term_id: termId
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

        // Get the duplicate alert for this specific question
        const duplicateAlert = document.getElementById(`bulkDuplicateAlert_${questionIndex}`);

        if (data.is_duplicate) {
            // Show duplicate warning
            duplicateAlert.className = 'alert alert-danger p-2 mb-3';
            duplicateAlert.style.display = 'block';
            duplicateAlert.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">warning</i><small>' + (data.message || 'A similar question already exists!') + '</small>';
        } else {
            // Hide duplicate alert
            duplicateAlert.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error checking duplicates:', error);
        // Hide duplicate alert on error
        const duplicateAlert = document.getElementById(`bulkDuplicateAlert_${questionIndex}`);
        duplicateAlert.style.display = 'none';
    });
}

// Initialize progress tracking
function initializeProgressTracking() {
    const subjectSelect = document.getElementById('singleSubject');
    const classSelect = document.getElementById('singleClass');

    if (subjectSelect && classSelect) {
        subjectSelect.addEventListener('change', function() {
            loadClassesForSubject(this.value, 'singleClass');
            updateQuestionCount();
        });
        classSelect.addEventListener('change', updateQuestionCount);
    }

    // Also add for bulk form
    const bulkSubjectSelect = document.getElementById('bulkSubject');
    const bulkClassSelect = document.getElementById('bulkClass');

    if (bulkSubjectSelect && bulkClassSelect) {
        bulkSubjectSelect.addEventListener('change', function() {
            loadClassesForSubject(this.value, 'bulkClass');
        });
    }
}

// Load classes for selected subject
function loadClassesForSubject(subjectId, classSelectId) {
    const classSelect = document.getElementById(classSelectId);

    if (!subjectId) {
        // Clear class options if no subject selected
        classSelect.innerHTML = '<option value="">Choose Class</option>';
        return;
    }

    // Show loading state
    classSelect.innerHTML = '<option value="">Loading classes...</option>';
    classSelect.disabled = true;

    // Fetch classes for the selected subject
    fetch(`<?= base_url(($route_prefix ?? '') . 'questions/get-classes-for-subject/') ?>${subjectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear existing options
                classSelect.innerHTML = '<option value="">Choose Class</option>';

                // Add new options
                data.classes.forEach(classItem => {
                    const option = document.createElement('option');
                    option.value = classItem.id;
                    option.textContent = classItem.display_name || classItem.name;
                    classSelect.appendChild(option);
                });
            } else {
                classSelect.innerHTML = '<option value="">No classes available</option>';
            }
        })
        .catch(error => {
            console.error('Error loading classes:', error);
            classSelect.innerHTML = '<option value="">Error loading classes</option>';
        })
        .finally(() => {
            classSelect.disabled = false;
        });
}

// Update question count for progress indicator
function updateQuestionCount() {
    const subjectId = document.getElementById('singleSubject').value;
    const classId = document.getElementById('singleClass').value;

    if (!subjectId || !classId) {
        document.getElementById('progressIndicator').style.display = 'none';
        return;
    }

    // Show progress indicator
    document.getElementById('progressIndicator').style.display = 'block';

    // Fetch current count
    fetch(`<?= base_url(($route_prefix ?? '') . 'questions/get-question-count') ?>?subject_id=${subjectId}&class_id=${classId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentQuestionCount = data.count;
                document.getElementById('questionCount').textContent = currentQuestionCount;
                updateProgressDetails();
            }
        })
        .catch(error => {
            console.error('Error fetching question count:', error);
        });
}

// Update progress details text
function updateProgressDetails() {
    const subjectSelect = document.getElementById('singleSubject');
    const classSelect = document.getElementById('singleClass');

    const subjectText = subjectSelect.options[subjectSelect.selectedIndex]?.text || 'Selected Subject';
    const classText = classSelect.options[classSelect.selectedIndex]?.text || 'Selected Class';

    document.getElementById('progressDetails').textContent =
        `${subjectText} - ${classText} (Current Session/Term)`;
}

// Check for duplicate questions
function checkForDuplicates() {
    console.log('checkForDuplicates function called');

    const questionText = document.getElementById('singleQuestionText').value.trim();
    const questionType = document.getElementById('singleQuestionType').value;
    const subjectId = document.getElementById('singleSubject').value;
    const classId = document.getElementById('singleClass').value;
    const examTypeId = document.getElementById('singleExamType').value;

    console.log('Initial values:', {
        questionText: questionText,
        questionType: questionType,
        subjectId: subjectId,
        classId: classId,
        examTypeId: examTypeId
    });

    if (!questionText || !questionType || !subjectId) {
        console.log('Missing required fields, returning early');
        const duplicateAlert = document.getElementById('duplicateAlert');
        duplicateAlert.style.display = 'none';
        return;
    }

    // Only check for exact text matches, not partial matches
    if (questionText.length < 6) {
        const duplicateAlert = document.getElementById('duplicateAlert');
        duplicateAlert.style.display = 'none';
        return; // Too short to check for duplicates
    }

    // Clear previous timeout
    if (duplicateCheckTimeout) {
        clearTimeout(duplicateCheckTimeout);
    }

    // Show checking indicator
    const duplicateAlert = document.getElementById('duplicateAlert');
    duplicateAlert.className = 'alert alert-info p-2 mb-0';
    duplicateAlert.style.display = 'block';
    duplicateAlert.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">hourglass_empty</i><small>Checking for duplicates...</small>';

    // Debounce the check
    duplicateCheckTimeout = setTimeout(() => {
        const formData = new FormData();
        formData.append('question_text', questionText);
        formData.append('question_type', questionType);
        formData.append('subject_id', subjectId);

        if (classId) {
            formData.append('class_id', classId);
        }

        // Add term_id and exam_type_id for duplicate checking
        const termId = '<?= $current_term['id'] ?? '' ?>';
        formData.append('term_id', termId);
        if (examTypeId) {
            formData.append('exam_type_id', examTypeId);
        }

        // Debug logging
        console.log('Duplicate check data:', {
            question_text: questionText,
            question_type: questionType,
            subject_id: subjectId,
            class_id: classId,
            term_id: termId,
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
            console.log('Duplicate check response:', data);
            if (data.is_duplicate) {
                duplicateAlert.className = 'alert alert-danger p-2 mb-0';
                duplicateAlert.style.display = 'block';
                duplicateAlert.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">warning</i><small>' + (data.message || 'A similar question already exists!') + '</small>';
            } else {
                duplicateAlert.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error checking duplicates:', error);
            duplicateAlert.style.display = 'none';
        });
    }, 1000); // Wait 1 second after user stops typing
}

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

// AJAX Form Submission
document.getElementById('singleQuestionFormElement').addEventListener('submit', function(e) {
    e.preventDefault();

    // Check for duplicates before submission
    const duplicateAlert = document.getElementById('duplicateAlert');
    if (duplicateAlert.style.display !== 'none' && duplicateAlert.classList.contains('alert-danger')) {
        showCustomAlert('Cannot submit: A duplicate question exists. Please modify your question or check the existing question.', 'danger');
        return;
    }

    // Validate True/False and Yes/No questions
    const questionType = document.getElementById('singleQuestionType').value;
    if (['true_false', 'yes_no'].includes(questionType)) {
        const radioButtons = this.querySelectorAll('input[name="single_correct_option"]:checked');
        if (radioButtons.length === 0) {
            showCustomAlert('Please select the correct answer for ' + (questionType === 'true_false' ? 'True/False' : 'Yes/No') + ' question.', 'warning');
            return;
        }
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
    submitBtn.disabled = true;

    // Hide alerts
    document.getElementById('duplicateAlert').style.display = 'none';
    document.getElementById('successAlert').style.display = 'none';

    // Prepare form data
    const formData = new FormData(this);

    // Submit via AJAX
    fetch('<?= base_url(($route_prefix ?? '') . 'questions/create-ajax') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successAlert = document.getElementById('successAlert');
            successAlert.style.display = 'block';
            successAlert.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">check_circle</i><small>Question created successfully!</small>';

            // Update question count
            currentQuestionCount = data.question_count;
            document.getElementById('questionCount').textContent = currentQuestionCount;

            // Reset form but keep subject/class selection
            const subjectId = document.getElementById('singleSubject').value;
            const classId = document.getElementById('singleClass').value;
            const examTypeId = document.getElementById('singleExamType').value;

            this.reset();

            // Restore selections
            document.getElementById('singleSubject').value = subjectId;
            document.getElementById('singleClass').value = classId;
            document.getElementById('singleExamType').value = examTypeId;

            // Clear dynamic sections
            document.getElementById('singleOptionsSection').style.display = 'none';
            document.getElementById('singleFillBlankSection').style.display = 'none';
            document.getElementById('singleShortAnswerSection').style.display = 'none';
            document.getElementById('singleEssaySection').style.display = 'none';
            document.getElementById('singleMathEquationSection').style.display = 'none';
            document.getElementById('singleImageBasedSection').style.display = 'none';
            document.getElementById('singleDragDropSection').style.display = 'none';

            // Clear option lists
            document.getElementById('singleOptionsList').innerHTML = '';
            document.getElementById('singleShortAnswersList').innerHTML = '';
            document.getElementById('singleMathAnswersList').innerHTML = '';

            // Hide success alert after 3 seconds
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 3000);

            showCustomAlert('Question created successfully! You can continue adding more questions.', 'success');
        } else {
            showCustomAlert(data.message || 'Failed to create question. Please try again.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCustomAlert('An error occurred. Please try again.', 'danger');
    })
    .finally(() => {
        // Restore button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('bulkQuestionsFormElement').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate True/False and Yes/No questions in bulk
    const questionItems = document.querySelectorAll('.question-item');
    let validationErrors = [];

    questionItems.forEach((item, index) => {
        const questionTypeSelect = item.querySelector('select[name*="[question_type]"]');
        if (questionTypeSelect) {
            const questionType = questionTypeSelect.value;
            if (['true_false', 'yes_no'].includes(questionType)) {
                const radioButtons = item.querySelectorAll('input[type="radio"]:checked');
                if (radioButtons.length === 0) {
                    validationErrors.push(`Question ${index + 1}: Please select the correct answer for ${questionType === 'true_false' ? 'True/False' : 'Yes/No'} question.`);
                }
            }
        }
    });

    // Check for duplicate alerts before submission
    const duplicateAlerts = document.querySelectorAll('[id^="bulkDuplicateAlert_"]');
    let duplicateErrors = [];

    duplicateAlerts.forEach((alert, index) => {
        if (alert.style.display !== 'none' && alert.classList.contains('alert-danger')) {
            duplicateErrors.push(`Question ${index + 1}: Contains a duplicate question`);
        }
    });

    if (duplicateErrors.length > 0) {
        validationErrors = validationErrors.concat(duplicateErrors);
        validationErrors.push('Please modify or remove duplicate questions before submitting.');
    }

    if (validationErrors.length > 0) {
        showCustomAlert(validationErrors.join('<br>'), 'warning');
        return;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Questions...';
    submitBtn.disabled = true;

    // Submit form
    this.submit();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on mode selection
    document.querySelector('.creation-mode-card').focus();

    // Initialize progress tracking
    initializeProgressTracking();

    // Add duplicate checking and validation to question text input
    const questionTextInput = document.getElementById('singleQuestionText');
    if (questionTextInput) {
        questionTextInput.addEventListener('input', checkForDuplicates);
        questionTextInput.addEventListener('blur', function() {
            validateQuestionText(this);
            checkForDuplicates();
        });
    }

    // Add duplicate checking to question type change
    const questionTypeSelect = document.getElementById('singleQuestionType');
    if (questionTypeSelect) {
        questionTypeSelect.addEventListener('change', function() {
            // Clear duplicate alert when question type changes
            document.getElementById('duplicateAlert').style.display = 'none';
            // Check for duplicates after a short delay
            setTimeout(checkForDuplicates, 500);
        });
    }

    // Add duplicate checking to exam type change
    const examTypeSelect = document.getElementById('singleExamType');
    if (examTypeSelect) {
        examTypeSelect.addEventListener('change', function() {
            // Clear duplicate alert when exam type changes
            document.getElementById('duplicateAlert').style.display = 'none';
            // Check for duplicates after a short delay
            setTimeout(checkForDuplicates, 500);
        });
    }
});
</script>
<?= $this->endSection() ?>