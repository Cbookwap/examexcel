<?= $this->extend('layouts/principal') ?>

<?= $this->section('css') ?>
<style>
    /* Override the main content background to off-white */
    .container-fluid {
        background-color: #f8f9fa !important;
        min-height: 100vh;
        padding: 20px;
        border-radius: 15px;
        margin-top: -20px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .question-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
    }

    .question-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15);
    }

    .question-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: black;"><?= $pageTitle ?></h1>
            <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
        </div>
        <div>
            <a href="<?= base_url('principal/exams/' . $exam['id'] . '/questions') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>
                Back to Exam
            </a>
        </div>
    </div>

    <!-- Subject Configuration Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold" style="color: var(--primary-color);"><?= $examSubject['question_count'] ?></div>
                            <small class="text-muted">Required Questions</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold" style="color: #28a745;"><?= $examSubject['total_marks'] ?></div>
                            <small class="text-muted">Total Marks</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold" style="color: #17a2b8;"><?= $examSubject['time_allocation'] ?></div>
                            <small class="text-muted">Time (minutes)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold" style="color: #ffc107;"><?= count($selectedQuestionIds) ?></div>
                            <small class="text-muted">Selected Questions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Selection Form -->
    <form method="POST" id="questionSelectionForm">
        <div class="info-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">
                    <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">quiz</i>
                    Available Questions for <?= esc($subject['name']) ?>
                </h5>
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="selectAll()">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">select_all</i>
                        Select All
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">clear</i>
                        Clear All
                    </button>
                </div>
            </div>
            
            <?php if (empty($availableQuestions)): ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">quiz</i>
                    <h5 class="text-muted">No Questions Available</h5>
                    <p class="text-muted">No questions found for this subject and class combination.</p>
                    <a href="<?= base_url('questions/create') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>
                        Create Questions
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php 
                    $groupedQuestions = [];
                    foreach ($availableQuestions as $question) {
                        $groupedQuestions[$question['id']]['question'] = $question;
                        if (!empty($question['option_text'])) {
                            $groupedQuestions[$question['id']]['options'][] = $question;
                        }
                    }
                    ?>
                    
                    <?php foreach ($groupedQuestions as $questionId => $questionData): ?>
                        <div class="col-md-6 mb-3">
                            <div class="question-card p-3" data-question-id="<?= $questionId ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input question-checkbox" type="checkbox"
                                               name="selected_questions[]" value="<?= $questionId ?>"
                                               id="question_<?= $questionId ?>"
                                               <?= in_array($questionId, $selectedQuestionIds) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold" for="question_<?= $questionId ?>">
                                            Question <?= $questionId ?>
                                        </label>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-<?= $questionData['question']['difficulty'] === 'easy' ? 'success' : ($questionData['question']['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($questionData['question']['difficulty']) ?>
                                        </span>
                                        <span class="badge bg-primary"><?= $questionData['question']['points'] ?> mks</span>
                                    </div>
                                </div>
                                
                                <div class="question-content">
                                    <p class="mb-2"><?= character_limiter(strip_tags($questionData['question']['question_text']), 100) ?></p>
                                    
                                    <?php if (!empty($questionData['options'])): ?>
                                        <div class="options-preview">
                                            <small class="text-muted">Options:</small>
                                            <ul class="list-unstyled ms-3 mb-0">
                                                <?php foreach (array_slice($questionData['options'], 0, 2) as $option): ?>
                                                    <li class="small text-muted">
                                                        <?= $option['is_correct'] ? '✓' : '•' ?> 
                                                        <?= character_limiter(strip_tags($option['option_text']), 50) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                                <?php if (count($questionData['options']) > 2): ?>
                                                    <li class="small text-muted">... and <?= count($questionData['options']) - 2 ?> more</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <span class="text-muted">Selected: </span>
                        <span id="selectedCount" class="fw-bold" style="color: var(--primary-color);"><?= count($selectedQuestionIds) ?></span>
                        <span class="text-muted"> / <?= $examSubject['question_count'] ?> required</span>
                    </div>
                    <button type="submit" class="btn" style="background-color: var(--primary-color); color: white; border: none;">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>
                        Save Question Selection
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxQuestions = <?= $examSubject['question_count'] ?>;
    
    // Handle question card clicks
    document.querySelectorAll('.question-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('.question-checkbox');
                if (checkbox && !checkbox.disabled) {
                    checkbox.checked = !checkbox.checked;
                    updateSelection();
                }
            }
        });
    });
    
    // Handle checkbox changes
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });
    
    function updateSelection() {
        const checkboxes = document.querySelectorAll('.question-checkbox');
        const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
        const selectedCount = checkedBoxes.length;
        
        // Update selected count display
        document.getElementById('selectedCount').textContent = selectedCount;
        
        // Disable unchecked boxes if max reached
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked && selectedCount >= maxQuestions) {
                checkbox.disabled = true;
                checkbox.closest('.question-card').style.opacity = '0.5';
            } else {
                checkbox.disabled = false;
                checkbox.closest('.question-card').style.opacity = '1';
            }
        });
        
        // Update card styling
        checkboxes.forEach(checkbox => {
            const card = checkbox.closest('.question-card');
            if (checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }
    
    // Initialize selection state
    updateSelection();
});

function selectAll() {
    const maxQuestions = <?= $examSubject['question_count'] ?>;
    const checkboxes = document.querySelectorAll('.question-checkbox:not(:disabled)');
    let selected = 0;
    
    checkboxes.forEach(checkbox => {
        if (selected < maxQuestions) {
            checkbox.checked = true;
            selected++;
        }
    });
    
    updateSelection();
}

function clearSelection() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.question-checkbox');
    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
    const selectedCount = checkedBoxes.length;
    const maxQuestions = <?= $examSubject['question_count'] ?>;
    
    // Update selected count display
    document.getElementById('selectedCount').textContent = selectedCount;
    
    // Disable unchecked boxes if max reached
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked && selectedCount >= maxQuestions) {
            checkbox.disabled = true;
            checkbox.closest('.question-card').style.opacity = '0.5';
        } else {
            checkbox.disabled = false;
            checkbox.closest('.question-card').style.opacity = '1';
        }
    });
    
    // Update card styling
    checkboxes.forEach(checkbox => {
        const card = checkbox.closest('.question-card');
        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
}

// Form validation
document.getElementById('questionSelectionForm').addEventListener('submit', function(e) {
    const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
    
    if (selectedQuestions.length === 0) {
        e.preventDefault();
        alert('Please select at least one question for this subject.');
        return false;
    }

    if (selectedQuestions.length > <?= $examSubject['question_count'] ?>) {
        e.preventDefault();
        alert('You can only select up to <?= $examSubject['question_count'] ?> questions for this subject.');
        return false;
    }
});
</script>
<?= $this->endSection() ?>
