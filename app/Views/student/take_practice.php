<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .practice-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .practice-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }
    .question-card {
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        border-radius: 15px;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .question-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    .question-number {
        background: var(--primary-color);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
    }
    .option-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .option-card:hover {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }
    .option-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.1);
    }
    .option-label {
        background: var(--primary-color);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .navigation-buttons {
        position: sticky;
        bottom: 20px;
        background: white;
        padding: 1rem;
        border-radius: 15px;
        box-shadow: 0 -2px 15px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }
    .progress-indicator {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .progress-bar-custom {
        background: rgba(255,255,255,0.3);
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
    }
    .progress-fill {
        background: white;
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="practice-container">
    <!-- Practice Header -->
    <div class="practice-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-2 fw-bold">
                    <i class="fas fa-dumbbell me-2"></i>
                    Practice: <?= esc($category) ?>
                </h3>
                <p class="mb-0 opacity-75">Take your time and think through each question carefully</p>
            </div>
            <div class="col-md-4">
                <div class="progress-indicator">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="opacity-75">Progress</small>
                        <small class="opacity-75">
                            <span id="currentQuestion">1</span> of <?= count($questions) ?>
                        </small>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill" id="progressFill" style="width: <?= (1/count($questions)) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions -->
    <?php foreach ($questions as $index => $question): ?>
        <div class="question-card" id="question-<?= $index + 1 ?>" style="<?= $index > 0 ? 'display: none;' : '' ?>">
            <div class="question-header">
                <div class="d-flex align-items-center">
                    <div class="question-number"><?= $index + 1 ?></div>
                    <div>
                        <h5 class="mb-1 fw-semibold">Question <?= $index + 1 ?></h5>
                        <small class="text-muted">
                            <i class="fas fa-star me-1"></i><?= $question['points'] ?> mark(s)
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Question Text -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3"><?= nl2br(esc($question['question_text'])) ?></h6>

                    <!-- Question Image if exists -->
                    <?php if (!empty($question['question_image'])): ?>
                        <div class="text-center mb-3">
                            <img src="<?= base_url('uploads/questions/' . $question['question_image']) ?>"
                                 alt="Question Image" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Answer Options -->
                <div class="options-container">
                    <?php
                    $options = ['A', 'B', 'C', 'D'];
                    $optionFields = ['option_a', 'option_b', 'option_c', 'option_d'];
                    ?>

                    <?php foreach ($options as $i => $option): ?>
                        <?php if (!empty($question[$optionFields[$i]])): ?>
                            <div class="option-card" onclick="selectOption(<?= $question['id'] ?>, '<?= $option ?>', this)">
                                <div class="d-flex align-items-center">
                                    <div class="option-label"><?= $option ?></div>
                                    <div class="flex-grow-1">
                                        <?= nl2br(esc($question[$optionFields[$i]])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>


            </div>
        </div>
    <?php endforeach; ?>

    <!-- Navigation Buttons -->
    <div class="navigation-buttons">
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-secondary" id="prevBtn" onclick="previousQuestion()" disabled>
                <i class="fas fa-chevron-left me-2"></i>Previous
            </button>

            <div class="text-center">
                <small class="text-muted">
                    Question <span id="currentQuestionNav">1</span> of <?= count($questions) ?>
                </small>
            </div>

            <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextQuestion()">
                Next<i class="fas fa-chevron-right ms-2"></i>
            </button>

            <button type="button" class="btn btn-success" id="submitBtn" onclick="submitPractice()" style="display: none;">
                <i class="fas fa-check me-2"></i>Submit Practice
            </button>
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitPracticeModal" tabindex="-1" aria-labelledby="submitPracticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-success text-white">
                <h5 class="modal-title" id="submitPracticeModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Submit Practice Test
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-question-circle fa-3x text-warning mb-3"></i>
                    <h5>Ready to submit your practice test?</h5>
                    <p class="text-muted">You can review your answers and see detailed explanations after submission.</p>
                </div>
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>What happens next:
                    </h6>
                    <ul class="mb-0 small">
                        <li>Your answers will be automatically graded</li>
                        <li>You'll see your score and detailed explanations</li>
                        <li>You can retake this practice anytime</li>
                        <li>Your practice history will be saved</li>
                    </ul>
                </div>
                <div class="text-center">
                    <small class="text-muted">
                        <span id="answeredCount">0</span> of <?= count($questions) ?> questions answered
                    </small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Continue Practicing
                </button>
                <button type="button" class="btn btn-success" id="confirmSubmitBtn">
                    <i class="fas fa-check me-2"></i>Submit Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for submission -->
<form id="practiceForm" method="POST" action="<?= base_url('student/submitPractice/' . $practice['id']) ?>" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let currentQuestionIndex = 0;
const totalQuestions = <?= count($questions) ?>;
const practiceId = <?= $practice['id'] ?>;
let answers = <?= json_encode($currentAnswers) ?>;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateNavigationButtons();
    updateAnsweredCount();
});

// Select option
function selectOption(questionId, option, element) {
    // Remove selection from other options in this question
    const questionCard = element.closest('.question-card');
    questionCard.querySelectorAll('.option-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Select this option
    element.classList.add('selected');

    // Save answer
    answers[questionId] = option;
    saveAnswer(questionId, option);

    // Update answered count
    updateAnsweredCount();
}

// Save answer via AJAX
function saveAnswer(questionId, answer) {
    fetch('<?= base_url('student/savePracticeAnswer') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'practice_id': practiceId,
            'question_id': questionId,
            'answer': answer,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Failed to save answer');
        }
    })
    .catch(error => {
        console.error('Error saving answer:', error);
    });
}

// Navigation functions
function nextQuestion() {
    if (currentQuestionIndex < totalQuestions - 1) {
        document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'none';
        currentQuestionIndex++;
        document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'block';
        updateNavigationButtons();
        updateProgress();
    }
}

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'none';
        currentQuestionIndex--;
        document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'block';
        updateNavigationButtons();
        updateProgress();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Previous button
    prevBtn.disabled = currentQuestionIndex === 0;

    // Next/Submit buttons
    if (currentQuestionIndex === totalQuestions - 1) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'inline-block';
    } else {
        nextBtn.style.display = 'inline-block';
        submitBtn.style.display = 'none';
    }

    // Update current question display
    document.getElementById('currentQuestion').textContent = currentQuestionIndex + 1;
    document.getElementById('currentQuestionNav').textContent = currentQuestionIndex + 1;
}

function updateProgress() {
    const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
    document.getElementById('progressFill').style.width = progress + '%';
}

function updateAnsweredCount() {
    const answeredCount = Object.keys(answers).length;
    document.getElementById('answeredCount').textContent = answeredCount;
}

// Submit practice
function submitPractice() {
    updateAnsweredCount();
    const modal = new bootstrap.Modal(document.getElementById('submitPracticeModal'));
    modal.show();
}

// Confirm submission
document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
    document.getElementById('practiceForm').submit();
});

// Restore selected answers on page load
document.addEventListener('DOMContentLoaded', function() {
    Object.keys(answers).forEach(questionId => {
        const answer = answers[questionId];
        const questionCards = document.querySelectorAll('.question-card');

        questionCards.forEach(card => {
            const options = card.querySelectorAll('.option-card');
            options.forEach(option => {
                const optionText = option.querySelector('.option-label').textContent;
                if (optionText === answer) {
                    option.classList.add('selected');
                }
            });
        });
    });
});

// Auto-save on page unload
window.addEventListener('beforeunload', function() {
    // Final save of any unsaved answers
    // This is handled by the individual saveAnswer calls
});
</script>
<?= $this->endSection() ?>
