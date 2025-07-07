<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
            <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
        </div>
        <div>
            <a href="<?= base_url('admin/exam/' . $exam['id'] . '/questions') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>
                Back to Exam
            </a>
        </div>
    </div>

    <!-- Subject Configuration Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-primary fw-bold"><?= $examSubject['question_count'] ?></div>
                                <small class="text-muted">Required Questions</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-success fw-bold"><?= $examSubject['total_marks'] ?></div>
                                <small class="text-muted">Total Marks</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-info fw-bold"><?= $examSubject['time_allocation'] ?></div>
                                <small class="text-muted">Time (minutes)</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-6 text-warning fw-bold"><?= count($selectedQuestionIds) ?></div>
                                <small class="text-muted">Selected Questions</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Selection Form -->
    <form method="POST" id="questionSelectionForm">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">quiz</i>
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
            </div>
            <div class="card-body">
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
                                <div class="question-card p-3 border rounded" data-question-id="<?= $questionId ?>">
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
                                            <span class="badge bg-primary"><?= ucfirst($questionData['question']['difficulty']) ?></span>
                                            <span class="badge bg-success"><?= $questionData['question']['points'] ?> mks</span>
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
                            <span id="selectedCount" class="fw-bold text-primary"><?= count($selectedQuestionIds) ?></span>
                            <span class="text-muted"> / <?= $examSubject['question_count'] ?> required</span>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>
                            Save Question Selection
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" id="alertModalHeader">
                <h5 class="modal-title" id="alertModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;" id="alertModalIcon">info</i>
                    <span id="alertModalTitle">Information</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="alertMessage">Message content</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">check</i>OK
                </button>
            </div>
        </div>
    </div>
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
                card.classList.add('border-primary', 'bg-light');
            } else {
                card.classList.remove('border-primary', 'bg-light');
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
            card.classList.add('border-primary', 'bg-light');
        } else {
            card.classList.remove('border-primary', 'bg-light');
        }
    });
}

// Form validation
document.getElementById('questionSelectionForm').addEventListener('submit', function(e) {
    const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
    
    if (selectedQuestions.length === 0) {
        e.preventDefault();
        showAlert('Please select at least one question for this subject.', 'warning');
        return false;
    }

    if (selectedQuestions.length > <?= $examSubject['question_count'] ?>) {
        e.preventDefault();
        showAlert('You can only select up to <?= $examSubject['question_count'] ?> questions for this subject.', 'warning');
        return false;
    }
});

function showAlert(message, type = 'info') {
    const alertModal = document.getElementById('alertModal');
    const alertModalHeader = document.getElementById('alertModalHeader');
    const alertModalIcon = document.getElementById('alertModalIcon');
    const alertModalTitle = document.getElementById('alertModalTitle');
    const alertMessage = document.getElementById('alertMessage');

    // Set message
    alertMessage.textContent = message;

    // Configure modal based on type
    switch (type) {
        case 'warning':
            alertModalHeader.className = 'modal-header bg-warning text-dark';
            alertModalIcon.textContent = 'warning';
            alertModalTitle.textContent = 'Warning';
            break;
        case 'error':
            alertModalHeader.className = 'modal-header bg-danger text-white';
            alertModalIcon.textContent = 'error';
            alertModalTitle.textContent = 'Error';
            break;
        case 'success':
            alertModalHeader.className = 'modal-header bg-success text-white';
            alertModalIcon.textContent = 'check_circle';
            alertModalTitle.textContent = 'Success';
            break;
        default:
            alertModalHeader.className = 'modal-header bg-primary text-white';
            alertModalIcon.textContent = 'info';
            alertModalTitle.textContent = 'Information';
    }

    // Show modal
    const modal = new bootstrap.Modal(alertModal);
    modal.show();
}
</script>
<?= $this->endSection() ?>
