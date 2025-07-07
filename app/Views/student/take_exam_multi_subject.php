<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Exam Header */
        .exam-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #e9ecef;
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .exam-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .student-info {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .timer-container {
            background: linear-gradient(135deg, #e91e63, #ad1457);
            border-radius: 25px;
            padding: 0.8rem 1.5rem;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .timer-display {
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-top: 120px;
            padding: 2rem 0;
            min-height: calc(100vh - 120px);
        }

        /* Subject Tabs */
        .subject-tabs-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .subject-tabs-wrapper {
            overflow-x: auto;
            padding: 0.5rem 0;
        }

        .subject-tabs-nav {
            display: flex;
            gap: 0.5rem;
            min-width: max-content;
            padding: 0.5rem;
        }

        .subject-tab-btn {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 25px;
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            min-width: 120px;
            position: relative;
            font-size: 0.9rem;
        }

        .subject-tab-btn:hover {
            background: #e3f2fd;
            border-color: #2196f3;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2);
        }

        .subject-tab-btn.active {
            background: linear-gradient(135deg, #e91e63, #ad1457);
            border-color: #e91e63;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.3);
        }

        .subject-tab-btn.completed {
            background: linear-gradient(135deg, #28a745, #20c997);
            border-color: #28a745;
            color: white;
        }

        .subject-tab-btn.in-progress {
            background: linear-gradient(135deg, #ffc107, #ff8f00);
            border-color: #ffc107;
            color: white;
        }

        .subject-name {
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
        }

        .subject-progress {
            font-size: 0.8rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .subject-status {
            position: absolute;
            top: 5px;
            right: 8px;
            font-size: 0.7rem;
        }

        .subject-status .fa-circle {
            color: #6c757d;
        }

        .subject-tab-btn.active .subject-status .fa-circle,
        .subject-tab-btn.completed .subject-status .fa-circle {
            color: #fff;
        }

        .subject-tab-btn.in-progress .subject-status .fa-circle {
            color: #fff;
            animation: pulse 1.5s infinite;
        }

        /* Question Container */
        .question-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .question-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .question-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e91e63;
            margin-bottom: 0.5rem;
        }

        .question-text {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #2c3e50;
            margin-bottom: 2rem;
        }

        /* Options */
        .option-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .option-item:hover {
            background: #e3f2fd;
            border-color: #2196f3;
            transform: translateX(5px);
        }

        .option-item.selected {
            background: linear-gradient(135deg, #e91e63, #ad1457);
            border-color: #e91e63;
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .form-check-input {
            margin-right: 1rem;
            transform: scale(1.2);
        }

        /* Navigation Controls */
        .nav-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-top: 2px solid #e9ecef;
            border-radius: 0 0 15px 15px;
        }

        .nav-btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-previous {
            background: #6c757d;
            color: white;
        }

        .btn-previous:hover:not(:disabled) {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-next {
            background: #e91e63;
            color: white;
        }

        .btn-next:hover:not(:disabled) {
            background: #ad1457;
            transform: translateY(-2px);
        }

        .btn-submit {
            background: #28a745;
            color: white;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }

        .btn-submit:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(40, 167, 69, 0.3);
        }

        /* Navigation Panel */
        .navigation-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 140px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
        }

        .nav-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .question-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 0.8rem;
            margin-bottom: 2rem;
        }

        .question-nav-btn {
            width: 50px;
            height: 50px;
            border: 2px solid #e9ecef;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .question-nav-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .question-nav-btn.answered {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .question-nav-btn.current {
            background: #e91e63;
            border-color: #e91e63;
            color: white;
        }

        .question-nav-btn.answered.current {
            background: #e91e63;
            border-color: #e91e63;
        }

        /* Legend Styles */
        .nav-legend {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px solid #e9ecef;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            font-size: 0.9rem;
        }

        .legend-icon {
            width: 30px;
            height: 30px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.8rem;
            font-size: 0.8rem;
        }

        .legend-answered {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .legend-current {
            background: #e91e63;
            border-color: #e91e63;
            color: white;
        }

        .legend-unanswered {
            background: white;
            border-color: #e9ecef;
            color: #6c757d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-top: 100px;
                padding: 1rem 0;
            }

            .subject-tabs {
                padding: 1rem;
            }

            .subject-tab {
                margin: 0.25rem;
                padding: 0.8rem 1rem;
            }

            .question-container {
                padding: 1.5rem;
            }

            .navigation-panel {
                margin-top: 2rem;
                position: static;
                max-height: none;
            }

            .question-grid {
                grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            }

            .question-nav-btn {
                width: 40px;
                height: 40px;
            }

            .nav-controls {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .question-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Timer Warning States */
        .timer-warning {
            animation: pulse 1s infinite;
        }

        .timer-critical {
            animation: pulse 0.5s infinite;
            background: rgba(220, 53, 69, 0.2) !important;
            border-color: #dc3545 !important;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <!-- Exam Header -->
    <div class="exam-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="exam-title"><?= esc($exam['title']) ?></div>
                    <div class="student-info">
                        <span class="me-4">
                            <i class="fas fa-user me-1"></i>
                            <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
                        </span>
                        <span class="me-4">
                            <i class="fas fa-id-card me-1"></i>
                            ID: <?= esc($student['student_id'] ?? 'N/A') ?>
                        </span>
                        <span class="me-4">
                            <i class="fas fa-users me-1"></i>
                            Class: <?= esc($student['class_name'] ?? 'N/A') ?><?= !empty($student['class_section']) ? ' - ' . esc($student['class_section']) : '' ?>
                        </span>
                        <span id="currentSubjectDisplay">
                            <i class="fas fa-book me-1"></i>
                            Subject: <span id="currentSubjectName">Select a subject</span>
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <!-- Exam Tools -->
                        <?php if ($exam['calculator_enabled'] || $exam['exam_pause_enabled']): ?>
                            <div class="d-flex gap-2">
                                <?php if ($exam['calculator_enabled']): ?>
                                    <button type="button" class="btn btn-light btn-sm" onclick="openCalculator()" title="Calculator">
                                        <i class="fas fa-calculator"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($exam['exam_pause_enabled']): ?>
                                    <button type="button" class="btn btn-warning btn-sm" id="pauseBtn" onclick="pauseExam()" title="Pause Exam">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Timer -->
                        <div class="timer-container" id="timerContainer">
                            <div class="timer-display">
                                <i class="fas fa-clock me-2"></i>
                                <span id="timeRemaining"><?= sprintf('%02d:%02d:00', floor($timeRemaining / 60), $timeRemaining % 60) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Subject Tabs -->
            <?php if ($isMultiSubject && !empty($questionsBySubject)): ?>
            <div class="subject-tabs-container mb-4">
                <div class="subject-tabs-wrapper">
                    <div class="subject-tabs-nav">
                        <?php foreach ($questionsBySubject as $subjectId => $subjectData): ?>
                        <?php
                        // Convert subject data to array if needed
                        if (is_object($subjectData)) {
                            $subjectData = (array) $subjectData;
                        }
                        ?>
                        <button class="subject-tab-btn"
                                data-subject-id="<?= $subjectId ?>"
                                onclick="selectSubject(<?= $subjectId ?>)"
                                id="subject-tab-<?= $subjectId ?>">
                            <span class="subject-name"><?= esc($subjectData['subject_name']) ?></span>
                            <span class="subject-progress">
                                <span id="subject-progress-<?= $subjectId ?>">0</span>/<span id="subject-total-<?= $subjectId ?>"><?= count($subjectData['questions']) ?></span>
                            </span>
                            <span class="subject-status" id="subject-status-<?= $subjectId ?>">
                                <i class="fas fa-circle"></i>
                            </span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <!-- Questions Section -->
                <div class="col-lg-8">
                    <!-- Subject Selection Message -->
                    <div class="question-container" id="subjectSelectionMessage" style="<?= $isMultiSubject ? '' : 'display: none;' ?>">
                        <div class="text-center py-5">
                            <i class="fas fa-hand-pointer fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">Select a Subject to Begin</h4>
                            <p class="text-muted">Choose a subject from the tabs above to start answering questions.</p>
                        </div>
                    </div>

                    <!-- Question Container -->
                    <div class="question-container" id="questionContainer" style="<?= $isMultiSubject ? 'display: none;' : '' ?>">
                        <!-- Questions will be dynamically loaded here -->
                    </div>
                </div>

                <!-- Navigation Panel -->
                <div class="col-lg-4">
                    <div class="navigation-panel">
                        <div class="nav-title">Question Navigation</div>
                        <div class="question-grid" id="questionGrid">
                            <!-- Question navigation buttons will be populated by JavaScript -->
                        </div>

                        <!-- Subject Progress (for multi-subject) -->
                        <?php if ($isMultiSubject): ?>
                        <div id="subjectProgressPanel" style="display: none;">
                            <h6 class="text-center mb-3">Subject Progress</h6>
                            <div id="subjectProgressList">
                                <!-- Subject progress will be populated by JavaScript -->
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Legend -->
                        <div class="nav-legend">
                            <div class="legend-item">
                                <div class="legend-icon legend-answered">✓</div>
                                <span>Answered</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-icon legend-current">1</div>
                                <span>Current</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-icon legend-unanswered">2</div>
                                <span>Not Answered</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submission -->
    <form id="examForm" action="<?= base_url('student/submitExam/' . $attempt['id']) ?>" method="POST" style="display: none;">
        <?= csrf_field() ?>
    </form>

    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Global variables
        let currentSubjectId = null;
        let currentQuestionIndex = 0;
        let currentSubjectQuestions = [];
        const attemptId = <?= $attempt['id'] ?>;
        let timeRemaining = <?= $timeRemaining * 60 ?>; // Convert to seconds
        let timerInterval = null;
        let answers = {};

        // Questions data
        const questionsBySubject = <?= json_encode($questionsBySubject) ?>;
        const isMultiSubject = <?= $isMultiSubject ? 'true' : 'false' ?>;
        const flatQuestions = <?= json_encode($questions) ?>;

        // Debug logging
        console.log('=== EXAM INITIALIZATION ===');
        console.log('Is Multi Subject:', isMultiSubject);
        console.log('Questions by Subject:', questionsBySubject);
        console.log('Flat Questions:', flatQuestions);

        // Initialize exam interface
        document.addEventListener('DOMContentLoaded', function() {
            if (isMultiSubject) {
                initializeMultiSubjectExam();
            } else {
                initializeSingleSubjectExam();
            }
            
            startTimer();
            loadSavedAnswers();
        });

        // Initialize multi-subject exam
        function initializeMultiSubjectExam() {
            console.log('Initializing multi-subject exam');
            console.log('Subjects available:', Object.keys(questionsBySubject));
            
            // Show subject selection message
            document.getElementById('subjectSelectionMessage').style.display = 'block';
            document.getElementById('questionContainer').style.display = 'none';
            
            // Update subject progress
            updateSubjectProgress();
        }

        // Initialize single-subject exam
        function initializeSingleSubjectExam() {
            console.log('Initializing single-subject exam');
            currentSubjectQuestions = flatQuestions;
            loadQuestion(0);
            updateQuestionNavigation();
        }

        // Select subject for multi-subject exam
        function selectSubject(subjectId) {
            console.log('Selecting subject:', subjectId);
            
            if (!questionsBySubject[subjectId]) {
                console.error('Subject not found:', subjectId);
                return;
            }

            // Update current subject
            currentSubjectId = subjectId;
            currentSubjectQuestions = questionsBySubject[subjectId].questions;
            currentQuestionIndex = 0;

            // Update UI
            updateSubjectTabs();
            updateCurrentSubjectDisplay();
            
            // Show question container and load first question
            document.getElementById('subjectSelectionMessage').style.display = 'none';
            document.getElementById('questionContainer').style.display = 'block';
            
            loadQuestion(0);
            updateQuestionNavigation();
            
            console.log('Subject selected successfully. Questions:', currentSubjectQuestions.length);
        }

        // Update subject tabs appearance
        function updateSubjectTabs() {
            document.querySelectorAll('.subject-tab-btn').forEach(tab => {
                tab.classList.remove('active');
            });

            const activeTab = document.querySelector(`[data-subject-id="${currentSubjectId}"]`);
            if (activeTab) {
                activeTab.classList.add('active', 'in-progress');
            }
        }

        // Update current subject display
        function updateCurrentSubjectDisplay() {
            const subjectNameElement = document.getElementById('currentSubjectName');
            if (subjectNameElement && questionsBySubject[currentSubjectId]) {
                subjectNameElement.textContent = questionsBySubject[currentSubjectId].subject_name;
            }
        }

        // Load question
        function loadQuestion(index) {
            if (!currentSubjectQuestions || index >= currentSubjectQuestions.length) {
                console.error('Invalid question index or no questions available');
                return;
            }

            const question = currentSubjectQuestions[index];
            currentQuestionIndex = index;

            console.log('Loading question:', index + 1, 'of', currentSubjectQuestions.length);

            // Build question HTML
            let questionHTML = `
                <div class="question-header">
                    <div class="question-number">Question ${index + 1}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Question ${index + 1} of ${currentSubjectQuestions.length}</span>
                        <span class="badge bg-primary">${question.points} mark(s)</span>
                    </div>
                </div>
                <div class="question-content">
                    <div class="question-text">
                        ${question.question_text.replace(/\n/g, '<br>')}
                        ${question.image_url ? `<img src="${question.image_url}" class="img-fluid rounded mt-3" alt="Question Image">` : ''}
                    </div>
                    <div class="options-container">
            `;

            // Add options based on question type
            if (question.question_type === 'mcq') {
                question.options.forEach(option => {
                    const isChecked = answers[question.id] == option.id ? 'checked' : '';
                    const isSelected = answers[question.id] == option.id ? 'selected' : '';
                    questionHTML += `
                        <div class="option-item ${isSelected}" onclick="selectOption(this, '${question.id}', '${option.id}')">
                            <input class="form-check-input" type="radio" name="question_${question.id}" value="${option.id}" id="option_${option.id}" ${isChecked}>
                            <label class="form-check-label w-100" for="option_${option.id}">
                                ${option.option_text}
                            </label>
                        </div>
                    `;
                });
            } else if (question.question_type === 'true_false') {
                const isTrueChecked = answers[question.id] == 'true' ? 'checked' : '';
                const isFalseChecked = answers[question.id] == 'false' ? 'checked' : '';
                const isTrueSelected = answers[question.id] == 'true' ? 'selected' : '';
                const isFalseSelected = answers[question.id] == 'false' ? 'selected' : '';
                
                questionHTML += `
                    <div class="option-item ${isTrueSelected}" onclick="selectOption(this, '${question.id}', 'true')">
                        <input class="form-check-input" type="radio" name="question_${question.id}" value="true" id="true_${question.id}" ${isTrueChecked}>
                        <label class="form-check-label w-100" for="true_${question.id}">True</label>
                    </div>
                    <div class="option-item ${isFalseSelected}" onclick="selectOption(this, '${question.id}', 'false')">
                        <input class="form-check-input" type="radio" name="question_${question.id}" value="false" id="false_${question.id}" ${isFalseChecked}>
                        <label class="form-check-label w-100" for="false_${question.id}">False</label>
                    </div>
                `;
            } else if (question.question_type === 'math_equation') {
                const metadata = question.metadata ? JSON.parse(question.metadata) : {};
                const allowCalculator = metadata.allow_calculator || false;
                const currentAnswer = answers[question.id] || '';

                if (allowCalculator) {
                    questionHTML += `
                        <div class="alert alert-info">
                            <i class="fas fa-calculator me-2"></i>
                            Calculator is allowed for this question.
                            <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="openCalculator()">
                                Open Calculator
                            </button>
                        </div>
                    `;
                }

                questionHTML += `
                    <div class="mb-3">
                        <input type="text" class="form-control" name="question_${question.id}"
                               placeholder="Enter your answer (e.g., x=5, 2.5, etc.)"
                               value="${currentAnswer}"
                               onchange="handleAnswerChange('${question.id}', this.value)">
                    </div>
                `;
            }

            questionHTML += `
                    </div>
                </div>
                <div class="nav-controls">
                    <button type="button" class="nav-btn btn-previous" onclick="previousQuestion()" ${index === 0 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <div class="question-counter">
                        ${index + 1} of ${currentSubjectQuestions.length}
                    </div>
                    ${index === currentSubjectQuestions.length - 1 ?
                        (isMultiSubject ?
                            `<button type="button" class="nav-btn btn-next" onclick="completeSubject()">
                                <i class="fas fa-arrow-right"></i> Complete Subject
                            </button>` :
                            `<button type="button" class="nav-btn btn-submit" onclick="showSubmitConfirmation()">
                                <i class="fas fa-check"></i> Submit Exam
                            </button>`
                        ) :
                        `<button type="button" class="nav-btn btn-next" onclick="nextQuestion()">
                            <i class="fas fa-save me-1"></i> Save & Next
                        </button>`
                    }
                </div>
            `;

            document.getElementById('questionContainer').innerHTML = questionHTML;
            updateQuestionNavigation();
        }

        // Navigation functions
        function nextQuestion() {
            saveCurrentAnswer();
            if (currentQuestionIndex < currentSubjectQuestions.length - 1) {
                loadQuestion(currentQuestionIndex + 1);
            }
        }

        function previousQuestion() {
            saveCurrentAnswer();
            if (currentQuestionIndex > 0) {
                loadQuestion(currentQuestionIndex - 1);
            }
        }

        function goToQuestion(index) {
            saveCurrentAnswer();
            loadQuestion(index);
        }

        // Complete current subject and return to subject selection
        function completeSubject() {
            saveCurrentAnswer();

            // Mark current subject as completed
            const currentTab = document.getElementById(`subject-tab-${currentSubjectId}`);
            if (currentTab) {
                currentTab.classList.add('completed');
                currentTab.classList.remove('active', 'in-progress');
            }

            // Update subject progress
            updateSubjectProgress();

            // Check if all subjects are completed
            const allSubjectsCompleted = Object.keys(questionsBySubject).every(subjectId => {
                const subjectData = questionsBySubject[subjectId];
                const answeredCount = subjectData.questions.filter(q => answers[q.id]).length;
                return answeredCount === subjectData.questions.length;
            });

            if (allSubjectsCompleted) {
                // All subjects completed, show final submit
                showFinalSubmitConfirmation();
            } else {
                // Return to subject selection
                currentSubjectId = null;
                currentSubjectQuestions = [];
                currentQuestionIndex = 0;

                document.getElementById('subjectSelectionMessage').style.display = 'block';
                document.getElementById('questionContainer').style.display = 'none';
                document.getElementById('currentSubjectName').textContent = 'Select a subject';

                // Clear navigation
                document.getElementById('questionGrid').innerHTML = '';
            }
        }

        // Answer selection
        function selectOption(element, questionId, optionValue) {
            console.log('=== OPTION SELECTED ===');
            console.log('Question ID:', questionId);
            console.log('Option Value:', optionValue);

            // Remove selected class from all options in this question
            const container = element.closest('.options-container');
            container.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });

            // Add selected class to clicked option
            element.classList.add('selected');

            // Check the radio button
            const radio = element.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                console.log('Radio button checked:', radio.name, '=', radio.value);
            }

            // Immediately save the answer to server
            console.log('Immediately saving selected answer...');
            saveAnswerToServer(questionId, optionValue);

            // Update local answers object
            answers[questionId] = optionValue;

            // Mark question as answered in navigation
            updateQuestionNavigation();

            // Update subject progress
            if (isMultiSubject) {
                updateSubjectProgress();
            }
        }

        // Handle answer change for text inputs (math equations, etc.)
        function handleAnswerChange(questionId, value) {
            console.log('=== ANSWER CHANGED ===');
            console.log('Question ID:', questionId);
            console.log('Answer Value:', value);

            // Update local answers object
            answers[questionId] = value;

            // Immediately save the answer to server
            console.log('Immediately saving text answer...');
            saveAnswerToServer(questionId, value);

            // Mark question as answered in navigation
            updateQuestionNavigation();

            // Update subject progress
            if (isMultiSubject) {
                updateSubjectProgress();
            }
        }

        // Save current answer
        function saveCurrentAnswer() {
            if (!currentSubjectQuestions || currentQuestionIndex >= currentSubjectQuestions.length) {
                return;
            }

            const question = currentSubjectQuestions[currentQuestionIndex];
            const questionId = question.id;

            // Get selected answer
            const selectedRadio = document.querySelector(`input[name="question_${questionId}"]:checked`);
            const answer = selectedRadio ? selectedRadio.value : '';

            if (answer) {
                console.log('Saving current answer:', questionId, '=', answer);
                saveAnswerToServer(questionId, answer);
                answers[questionId] = answer;
            }
        }

        // Save answer to server
        function saveAnswerToServer(questionId, answer) {
            console.log('=== SAVING ANSWER TO SERVER ===');
            console.log('Question ID:', questionId);
            console.log('Answer:', answer);
            console.log('Attempt ID:', attemptId);

            // Use URLSearchParams instead of FormData for better compatibility
            const params = new URLSearchParams();
            params.append('attempt_id', attemptId);
            params.append('question_id', questionId);
            params.append('answer', answer);
            params.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('student/saveAnswer') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: params.toString()
            })
            .then(response => {
                console.log('Server response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('✅ Answer saved successfully for question', questionId);
                } else {
                    console.error('❌ Failed to save answer:', data.message);
                }
            })
            .catch(error => {
                console.error('❌ Error saving answer:', error);
            });
        }



        // Update question navigation
        function updateQuestionNavigation() {
            if (!currentSubjectQuestions) return;

            const grid = document.getElementById('questionGrid');
            grid.innerHTML = '';

            currentSubjectQuestions.forEach((question, index) => {
                const btn = document.createElement('div');
                btn.className = 'question-nav-btn';
                btn.textContent = index + 1;
                btn.onclick = () => goToQuestion(index);

                // Add classes based on state
                if (index === currentQuestionIndex) {
                    btn.classList.add('current');
                }
                if (answers[question.id]) {
                    btn.classList.add('answered');
                }

                grid.appendChild(btn);
            });
        }

        // Update subject progress
        function updateSubjectProgress() {
            if (!isMultiSubject) return;

            Object.keys(questionsBySubject).forEach(subjectId => {
                const subjectData = questionsBySubject[subjectId];
                const answeredCount = subjectData.questions.filter(q => answers[q.id]).length;
                const totalCount = subjectData.questions.length;

                // Update progress text
                const progressElement = document.getElementById(`subject-progress-${subjectId}`);
                if (progressElement) {
                    progressElement.textContent = answeredCount;
                }

                // Update tab status
                const tabElement = document.querySelector(`[data-subject-id="${subjectId}"]`);
                if (tabElement) {
                    if (answeredCount === totalCount && totalCount > 0) {
                        tabElement.classList.add('completed');
                        tabElement.classList.remove('in-progress');
                    } else if (answeredCount > 0) {
                        tabElement.classList.add('in-progress');
                        tabElement.classList.remove('completed');
                    } else {
                        tabElement.classList.remove('completed', 'in-progress');
                    }
                }
            });
        }

        // Show final submit confirmation for multi-subject exam
        function showFinalSubmitConfirmation() {
            const totalQuestions = Object.values(questionsBySubject).reduce((sum, subject) => sum + subject.questions.length, 0);
            const answeredCount = Object.keys(answers).length;

            // Update modal content
            document.getElementById('finalSubmitModalTitle').innerHTML = '<i class="fas fa-trophy text-warning me-2"></i>Congratulations!';
            document.getElementById('finalSubmitModalBody').innerHTML = `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-success mb-3">You have completed all subjects!</h5>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1 text-primary">${totalQuestions}</h6>
                                <small class="text-muted">Total Questions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1 text-success">${answeredCount}</h6>
                                <small class="text-muted">Answered</small>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Are you ready to submit your exam?</p>
                    <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone.</small>
                </div>
            `;

            const modal = new bootstrap.Modal(document.getElementById('finalSubmitModal'));
            modal.show();
        }

        // Timer functions
        function startTimer() {
            timerInterval = setInterval(updateTimer, 1000);
        }

        function updateTimer() {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                autoSubmitExam();
                return;
            }

            timeRemaining--;

            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;

            const timeDisplay = hours > 0
                ? `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                : `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            document.getElementById('timeRemaining').textContent = timeDisplay;

            // Add warning classes
            const timerContainer = document.getElementById('timerContainer');
            if (timeRemaining <= 300) { // 5 minutes
                timerContainer.classList.add('timer-critical');
            } else if (timeRemaining <= 600) { // 10 minutes
                timerContainer.classList.add('timer-warning');
            }
        }

        // Submission functions
        function showSubmitConfirmation() {
            if (isMultiSubject) {
                showSubjectSubmitConfirmation();
            } else {
                showExamSubmitConfirmation();
            }
        }

        function showSubjectSubmitConfirmation() {
            const subjectName = questionsBySubject[currentSubjectId].subject_name;
            const answeredCount = currentSubjectQuestions.filter(q => answers[q.id]).length;
            const totalCount = currentSubjectQuestions.length;

            // Update modal content
            document.getElementById('subjectSubmitModalTitle').innerHTML = `<i class="fas fa-check-circle me-2"></i>Finish ${subjectName}`;
            document.getElementById('subjectSubmitModalBody').innerHTML = `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-question-circle text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="mb-3">Are you sure you want to finish ${subjectName}?</h6>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1 text-success">${answeredCount}</h6>
                                <small class="text-muted">Answered</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1 text-primary">${totalCount}</h6>
                                <small class="text-muted">Total Questions</small>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-0">You can select another subject to continue or submit the entire exam.</p>
                </div>
            `;

            const modal = new bootstrap.Modal(document.getElementById('subjectSubmitModal'));
            modal.show();
        }

        function confirmSubjectFinish() {
            // Return to subject selection
            currentSubjectId = null;
            currentSubjectQuestions = [];
            document.getElementById('subjectSelectionMessage').style.display = 'block';
            document.getElementById('questionContainer').style.display = 'none';
            updateSubjectTabs();
            document.getElementById('currentSubjectName').textContent = 'Select a subject';

            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('subjectSubmitModal')).hide();
        }

        function showExamSubmitConfirmation() {
            const totalQuestions = isMultiSubject
                ? Object.values(questionsBySubject).reduce((sum, subject) => sum + subject.questions.length, 0)
                : currentSubjectQuestions.length;
            const answeredCount = Object.keys(answers).length;

            // Update modal content
            document.getElementById('examSubmitModalTitle').innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Exam';
            document.getElementById('examSubmitModalBody').innerHTML = `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-question-circle text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="mb-3">Are you sure you want to submit your exam?</h6>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1 text-success">${answeredCount}</h6>
                                <small class="text-muted">Answered</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1 text-primary">${totalQuestions}</h6>
                                <small class="text-muted">Total Questions</small>
                            </div>
                        </div>
                    </div>
                    <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone.</p>
                </div>
            `;

            const modal = new bootstrap.Modal(document.getElementById('examSubmitModal'));
            modal.show();
        }

        function submitExam() {
            saveCurrentAnswer();
            document.getElementById('examForm').submit();
        }

        function autoSubmitExam() {
            // Update modal content
            document.getElementById('timeUpModalTitle').innerHTML = '<i class="fas fa-clock text-danger me-2"></i>Time is Up!';
            document.getElementById('timeUpModalBody').innerHTML = `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-hourglass-end text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-danger mb-3">Time is up!</h5>
                    <p class="text-muted mb-0">Your exam will be submitted automatically.</p>
                </div>
            `;

            const modal = new bootstrap.Modal(document.getElementById('timeUpModal'));
            modal.show();

            // Auto-submit after 3 seconds
            setTimeout(() => {
                submitExam();
            }, 3000);
        }

        // Load saved answers
        function loadSavedAnswers() {
            const savedAnswers = <?= json_encode($currentAnswers) ?>;
            if (savedAnswers && typeof savedAnswers === 'object') {
                answers = savedAnswers;
                console.log('Loaded saved answers:', answers);

                if (isMultiSubject) {
                    updateSubjectProgress();
                } else {
                    updateQuestionNavigation();
                }
            }
        }

        // Auto-save functionality
        setInterval(() => {
            if (currentSubjectQuestions && currentQuestionIndex < currentSubjectQuestions.length) {
                saveCurrentAnswer();
            }
        }, 30000); // Auto-save every 30 seconds

        // Save on page unload
        window.addEventListener('beforeunload', function(e) {
            saveCurrentAnswer();
        });

        // Add final submit button for multi-subject exams
        if (isMultiSubject) {
            const submitButton = document.createElement('button');
            submitButton.className = 'btn btn-success btn-lg position-fixed';
            submitButton.style.cssText = 'bottom: 20px; right: 20px; z-index: 1000; border-radius: 25px; padding: 1rem 2rem;';
            submitButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Entire Exam';
            submitButton.onclick = showExamSubmitConfirmation;
            document.body.appendChild(submitButton);
        }

        // Initialize security features based on settings
        <?php if ($settings['browser_lockdown'] ?? false): ?>
        console.log('Browser lockdown enabled - forcing fullscreen');
        requestFullscreenImmediate();
        <?php endif; ?>

        // Security features initialization
        <?php if ($settings['prevent_copy_paste'] ?? false): ?>
        console.log('Copy/Paste prevention enabled');

        // Prevent copy/paste/cut
        ['copy', 'paste', 'cut'].forEach(function(event) {
            document.addEventListener(event, function(e) {
                e.preventDefault();
                e.stopPropagation();
                showSecurityAlert(event.charAt(0).toUpperCase() + event.slice(1) + ' is not allowed during the exam');
                return false;
            });
        });

        // Prevent text selection
        document.addEventListener('selectstart', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return true;
            }
            e.preventDefault();
            return false;
        });
        <?php endif; ?>

        <?php if ($settings['disable_right_click'] ?? false): ?>
        console.log('Right-click disabled');
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showSecurityAlert('Right-click is disabled during the exam');
            return false;
        });
        <?php endif; ?>

        <?php if ($settings['browser_lockdown'] ?? false): ?>
        console.log('Browser lockdown enabled');

        // Browser lockdown features
        document.addEventListener('keydown', function(e) {
            // Prevent developer tools and other shortcuts
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
                (e.ctrlKey && e.key === 'u') ||
                (e.ctrlKey && e.key === 's') ||
                (e.ctrlKey && e.key === 'a') ||
                (e.ctrlKey && e.key === 'p') ||
                (e.ctrlKey && e.key === 'r') ||
                (e.ctrlKey && e.shiftKey && e.key === 'Delete') ||
                e.key === 'F5') {
                e.preventDefault();
                e.stopPropagation();
                showSecurityAlert('This action is not allowed during the exam');
                return false;
            }
        });

        // Enhanced tab switch/window focus loss detection
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                showSecurityAlert('Warning: Tab switching detected! This violation has been logged.');
            }
        });

        // Enhanced window blur (focus loss) detection
        window.addEventListener('blur', function() {
            showSecurityAlert('Please keep focus on the exam window');
        });
        <?php endif; ?>

        // Security functions
        function requestFullscreenImmediate() {
            console.log('Enforcing fullscreen mode...');
            showFullscreenModal();
        }

        function showFullscreenModal() {
            const modal = document.createElement('div');
            modal.id = 'fullscreen-modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-family: Arial, sans-serif;
            `;

            modal.innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <h2 style="margin-bottom: 1rem;">Fullscreen Required</h2>
                    <p style="margin-bottom: 2rem;">This exam requires fullscreen mode for security purposes.</p>
                    <button onclick="enterFullscreen()" style="
                        background: #007bff;
                        color: white;
                        border: none;
                        padding: 1rem 2rem;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 1rem;
                    ">Enter Fullscreen</button>
                </div>
            `;

            document.body.appendChild(modal);

            modal.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        }

        function enterFullscreen() {
            const elem = document.documentElement;

            if (elem.requestFullscreen) {
                elem.requestFullscreen().then(() => {
                    removeFullscreenModal();
                    setupFullscreenMonitoring();
                }).catch(err => {
                    console.error('Error attempting to enable fullscreen:', err);
                    showSecurityAlert('Please allow fullscreen mode to continue the exam');
                });
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
                removeFullscreenModal();
                setupFullscreenMonitoring();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
                removeFullscreenModal();
                setupFullscreenMonitoring();
            } else {
                console.log('Fullscreen API not supported');
                removeFullscreenModal();
                showSecurityAlert('Your browser does not support fullscreen mode');
            }
        }

        function removeFullscreenModal() {
            const modal = document.getElementById('fullscreen-modal');
            if (modal) {
                modal.remove();
            }
        }

        function setupFullscreenMonitoring() {
            console.log('Setting up fullscreen monitoring...');

            // Monitor fullscreen exit attempts
            document.addEventListener('fullscreenchange', handleFullscreenChange);
            document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
            document.addEventListener('msfullscreenchange', handleFullscreenChange);

            function handleFullscreenChange() {
                if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                    console.log('Fullscreen exited - showing modal again');
                    // Immediately show the modal again
                    setTimeout(() => {
                        showFullscreenModal();
                    }, 100);
                }
            }
        }

        function showSecurityAlert(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'security-alert';
            alertDiv.innerHTML = `
                <div class="security-alert-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>${message}</span>
                </div>
            `;

            alertDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #dc3545;
                color: white;
                padding: 1rem;
                border-radius: 5px;
                z-index: 9999;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                max-width: 300px;
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Calculator functionality
        function openCalculator() {
            // Create calculator modal if it doesn't exist
            if (!document.getElementById('calculatorModal')) {
                const calculatorHTML = `
                    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="calculatorModalLabel">
                                        <i class="fas fa-calculator me-2"></i>Calculator
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-2">
                                    <div class="calculator">
                                        <input type="text" id="calcDisplay" class="form-control mb-2" readonly>
                                        <div class="calc-buttons">
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100" onclick="clearCalc()">C</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100" onclick="deleteLast()">⌫</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100" onclick="calcInput('/')">/</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100" onclick="calcInput('×')">×</button></div>
                                            </div>
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('7')">7</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('8')">8</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('9')">9</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100" onclick="calcInput('-')">-</button></div>
                                            </div>
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('4')">4</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('5')">5</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('6')">6</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100" onclick="calcInput('+')">+</button></div>
                                            </div>
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('1')">1</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('2')">2</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('3')">3</button></div>
                                                <div class="col-3 row-span-2"><button type="button" class="btn btn-primary w-100 h-100" onclick="calculate()" style="height: 76px;">=</button></div>
                                            </div>
                                            <div class="row g-1">
                                                <div class="col-6"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('0')">0</button></div>
                                                <div class="col-3"><button type="button" class="btn btn-outline-dark w-100" onclick="calcInput('.')">.</button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', calculatorHTML);
            }

            const modal = new bootstrap.Modal(document.getElementById('calculatorModal'));
            modal.show();
        }

        // Calculator helper functions
        let calcExpression = '';

        function calcInput(value) {
            calcExpression += value;
            document.getElementById('calcDisplay').value = calcExpression;
        }

        function clearCalc() {
            calcExpression = '';
            document.getElementById('calcDisplay').value = '';
        }

        function deleteLast() {
            calcExpression = calcExpression.slice(0, -1);
            document.getElementById('calcDisplay').value = calcExpression;
        }

        function calculate() {
            try {
                const result = eval(calcExpression.replace('×', '*'));
                document.getElementById('calcDisplay').value = result;
                calcExpression = result.toString();
            } catch (error) {
                document.getElementById('calcDisplay').value = 'Error';
                calcExpression = '';
            }
        }

        // Pause functionality
        let isPaused = false;

        function pauseExam() {
            if (isPaused) {
                resumeExam();
                return;
            }

            isPaused = true;

            // Stop the timer
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }

            // Update pause button
            const pauseBtn = document.getElementById('pauseBtn');
            if (pauseBtn) {
                pauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseBtn.title = 'Resume Exam';
                pauseBtn.classList.remove('btn-warning');
                pauseBtn.classList.add('btn-success');
            }

            // Show pause overlay
            showPauseOverlay();

            // Save current state
            saveCurrentAnswer();
        }

        function resumeExam() {
            isPaused = false;

            // Restart the timer
            startTimer();

            // Update pause button
            const pauseBtn = document.getElementById('pauseBtn');
            if (pauseBtn) {
                pauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                pauseBtn.title = 'Pause Exam';
                pauseBtn.classList.remove('btn-success');
                pauseBtn.classList.add('btn-warning');
            }

            // Hide pause overlay
            hidePauseOverlay();
        }

        function showPauseOverlay() {
            // Create pause overlay if it doesn't exist
            if (!document.getElementById('pauseOverlay')) {
                const overlayHTML = `
                    <div id="pauseOverlay" style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.8);
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 2rem;
                        text-align: center;
                    ">
                        <div>
                            <i class="fas fa-pause mb-3" style="font-size: 4rem;"></i>
                            <div>Exam Paused</div>
                            <div style="font-size: 1rem; margin-top: 1rem;">
                                Click the resume button to continue
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', overlayHTML);
            } else {
                document.getElementById('pauseOverlay').style.display = 'flex';
            }
        }

        function hidePauseOverlay() {
            const overlay = document.getElementById('pauseOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }
    </script>

    <!-- Bootstrap Modals for Confirmations -->

    <!-- Final Submit Modal -->
    <div class="modal fade" id="finalSubmitModal" tabindex="-1" aria-labelledby="finalSubmitModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="finalSubmitModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="finalSubmitModalBody">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="submitExam()">
                        <i class="fas fa-paper-plane me-2"></i>Submit Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Submit Modal -->
    <div class="modal fade" id="subjectSubmitModal" tabindex="-1" aria-labelledby="subjectSubmitModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="subjectSubmitModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="subjectSubmitModalBody">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubjectFinish()">
                        <i class="fas fa-check me-2"></i>Finish Subject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Submit Modal -->
    <div class="modal fade" id="examSubmitModal" tabindex="-1" aria-labelledby="examSubmitModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="examSubmitModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="examSubmitModalBody">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submitExam()">
                        <i class="fas fa-paper-plane me-2"></i>Submit Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Up Modal -->
    <div class="modal fade" id="timeUpModal" tabindex="-1" aria-labelledby="timeUpModalTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="timeUpModalTitle"></h5>
                </div>
                <div class="modal-body" id="timeUpModalBody">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary" onclick="submitExam()">
                        <i class="fas fa-paper-plane me-2"></i>Submit Now
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
