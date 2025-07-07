<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Favicon -->
    <?php $favicon = get_app_favicon(); ?>
    <?php if ($favicon): ?>
        <link rel="icon" type="image/png" href="<?= $favicon ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= $favicon ?>">
    <?php else: ?>
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <?php endif; ?>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* Header Styles - Inspired by Brainteaser */
        .exam-header {
            background: linear-gradient(135deg, #e91e63 0%, #ad1457 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(233, 30, 99, 0.3);
        }

        .exam-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .student-info {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .timer-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .timer-display {
            font-size: 1.4rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
        }

        /* Main Content */
        .main-content {
            margin-top: 120px;
            padding: 2rem 0;
            min-height: calc(100vh - 120px);
        }

        /* Question Area */
        .question-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .question-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem 2rem;
            border-bottom: 2px solid #e9ecef;
        }

        .question-number {
            background: #e91e63;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .question-content {
            padding: 2rem;
        }

        .question-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: #2d3748;
        }

        /* Options Styling */
        .option-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .option-item:hover {
            border-color: #e91e63;
            background: #fff5f8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(233, 30, 99, 0.15);
        }

        .option-item.selected {
            border-color: #e91e63;
            background: linear-gradient(135deg, #e91e63 0%, #ad1457 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(233, 30, 99, 0.3);
        }

        .option-item input[type="radio"] {
            margin-right: 1rem;
            transform: scale(1.2);
        }

        /* Navigation Panel */
        .navigation-panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            position: sticky;
            top: 140px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
        }

        .nav-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #2d3748;
        }

        .question-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .question-nav-btn {
            width: 45px;
            height: 45px;
            border: 2px solid #e9ecef;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .question-nav-btn:hover {
            border-color: #e91e63;
            background: #fff5f8;
        }

        .question-nav-btn.current {
            background: #e91e63;
            border-color: #e91e63;
            color: white;
        }

        .question-nav-btn.answered {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .question-nav-btn.answered.current {
            background: #e91e63;
            border-color: #e91e63;
        }

        /* Navigation Controls */
        .nav-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-top: 2px solid #e9ecef;
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

        /* Legend */
        .nav-legend {
            border-top: 1px solid #e9ecef;
            padding-top: 1.5rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .legend-icon {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .legend-answered {
            background: #28a745;
            color: white;
        }

        .legend-current {
            background: #e91e63;
            color: white;
        }

        .legend-unanswered {
            background: white;
            border: 2px solid #e9ecef;
            color: #6c757d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-top: 100px;
                padding: 1rem 0;
            }

            .exam-header {
                padding: 0.8rem 0;
            }

            .exam-title {
                font-size: 1.2rem;
            }

            .timer-display {
                font-size: 1.1rem;
            }

            .question-content {
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

        /* Security Features */
        <?php if ($settings['prevent_copy_paste'] ?? false): ?>
        .no-select {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        body.security-enabled {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        body.security-enabled input,
        body.security-enabled textarea,
        body.security-enabled select {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
        <?php endif; ?>

        /* Browser Lockdown Security Features */
        <?php if ($settings['browser_lockdown'] ?? false): ?>
        body {
            overflow: hidden; /* Prevent scrolling to hide content */
        }

        /* Hide browser UI elements */
        body::-webkit-scrollbar {
            display: none;
        }

        /* Prevent text selection globally in lockdown mode */
        * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Allow selection only in form inputs */
        input[type="text"], input[type="radio"], textarea, select {
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            user-select: text !important;
        }

        /* Disable image dragging */
        img {
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
            pointer-events: none;
        }
        <?php endif; ?>

        /* Enhanced Security Alert Styles */
        .security-alert {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10000;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: securityAlertIn 0.3s ease-out;
        }

        .security-alert-content {
            padding: 2rem;
            text-align: center;
            color: white;
            max-width: 400px;
        }

        .security-alert-content i {
            font-size: 3rem;
            color: #ff6b6b;
            margin-bottom: 1rem;
            animation: pulse 1s infinite;
        }

        .security-alert-content h4 {
            color: #ff6b6b;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .security-alert-content p {
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .security-warning {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(255, 152, 0, 0.3);
            animation: securityWarningIn 0.3s ease-out;
            max-width: 350px;
        }

        .security-warning-content {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
        }

        .security-warning-content i {
            font-size: 1.5rem;
            color: #fff3e0;
        }

        .security-warning-content span {
            flex: 1;
            font-weight: 500;
            line-height: 1.4;
        }

        .btn-close-warning {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .btn-close-warning:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        @keyframes securityAlertIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes securityWarningIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Fullscreen enforcement styles */
        .fullscreen-prompt {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 10001;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .fullscreen-prompt-content {
            max-width: 500px;
            padding: 2rem;
        }

        .fullscreen-prompt-content i {
            font-size: 4rem;
            color: #e91e63;
            margin-bottom: 1rem;
        }

        .fullscreen-prompt-content h3 {
            margin-bottom: 1rem;
            color: #e91e63;
        }

        /* Security status indicator */
        .security-status {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .security-status.active {
            background: rgba(40, 167, 69, 0.9);
        }

        .security-status.warning {
            background: rgba(255, 152, 0, 0.9);
        }

        .security-status.alert {
            background: rgba(220, 53, 69, 0.9);
            animation: pulse 1s infinite;
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
                        <?php if ($exam['exam_mode'] === 'multi_subject' && !empty($exam['subjects'])): ?>
                            <span id="currentSubjectDisplay">
                                <i class="fas fa-book me-1"></i>
                                Subject: <span id="currentSubjectName">Loading...</span>
                            </span>
                        <?php else: ?>
                            <span>
                                <i class="fas fa-book me-1"></i>
                                Subject: <?= esc($exam['subject_name'] ?? 'N/A') ?>
                            </span>
                        <?php endif; ?>
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
            <div class="row">
                <!-- Questions Section -->
                <div class="col-lg-8">
                    <!-- Anti-Cheating Warning -->
                    <?php if ($exam['browser_lockdown'] || $exam['require_proctoring']): ?>
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Anti-Cheating Measures Active</h6>
                                <small>This exam is being monitored. Do not switch tabs, copy/paste, or use external resources.</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Question Container -->
                    <div class="question-container" id="questionContainer">
                        <!-- Questions will be dynamically loaded here -->
                        <?php foreach ($questions as $index => $question): ?>
                        <?php
                        // Convert question object to array if needed
                        if (is_object($question)) {
                            $question = (array) $question;
                        }

                        // Convert options objects to arrays if needed
                        if (isset($question['options']) && is_array($question['options'])) {
                            foreach ($question['options'] as $key => $option) {
                                if (is_object($option)) {
                                    $question['options'][$key] = (array) $option;
                                }
                            }
                        }
                        ?>
                        <div class="question-item" id="question-<?= $index + 1 ?>" style="<?= $index > 0 ? 'display: none;' : '' ?>">
                            <div class="question-header">
                                <div class="question-number">Question <?= $index + 1 ?></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Question <?= $index + 1 ?> of <?= count($questions) ?></span>
                                    <span class="badge bg-primary"><?= $question['points'] ?> mark(s)</span>
                                </div>
                            </div>
                            <div class="question-content">
                                <div class="question-text">
                                    <?= nl2br(esc($question['question_text'])) ?>
                                    <?php if ($question['image_url']): ?>
                                        <img src="<?= base_url('uploads/questions/' . $question['image_url']) ?>"
                                             class="img-fluid rounded mt-3" alt="Question Image">
                                    <?php endif; ?>
                                </div>

                                <!-- Options based on question type -->
                                <div class="options-container">
                                    <?php if ($question['question_type'] === 'mcq'): ?>
                                        <?php foreach ($question['options'] as $option): ?>
                                        <div class="option-item" onclick="selectOption(this, '<?= $question['id'] ?>', '<?= $option['id'] ?>')">
                                            <input class="form-check-input" type="radio"
                                                   name="question_<?= $question['id'] ?>"
                                                   value="<?= $option['id'] ?>"
                                                   id="option_<?= $option['id'] ?>"
                                                   <?= isset($currentAnswers[$question['id']]) && $currentAnswers[$question['id']] == $option['id'] ? 'checked' : '' ?>>
                                            <label class="form-check-label w-100" for="option_<?= $option['id'] ?>">
                                                <?= esc($option['option_text']) ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>

                                    <?php elseif ($question['question_type'] === 'true_false'): ?>
                                        <div class="option-item" onclick="selectOption(this, '<?= $question['id'] ?>', 'true')">
                                            <input class="form-check-input" type="radio"
                                                   name="question_<?= $question['id'] ?>"
                                                   value="true" id="true_<?= $question['id'] ?>"
                                                   <?= isset($currentAnswers[$question['id']]) && $currentAnswers[$question['id']] == 'true' ? 'checked' : '' ?>>
                                            <label class="form-check-label w-100" for="true_<?= $question['id'] ?>">
                                                True
                                            </label>
                                        </div>
                                        <div class="option-item" onclick="selectOption(this, '<?= $question['id'] ?>', 'false')">
                                            <input class="form-check-input" type="radio"
                                                   name="question_<?= $question['id'] ?>"
                                                   value="false" id="false_<?= $question['id'] ?>"
                                                   <?= isset($currentAnswers[$question['id']]) && $currentAnswers[$question['id']] == 'false' ? 'checked' : '' ?>>
                                            <label class="form-check-label w-100" for="false_<?= $question['id'] ?>">
                                                False
                                            </label>
                                        </div>

                                <?php elseif ($question['question_type'] === 'fill_blank'): ?>
                                    <div class="mb-3">
                                        <?php
                                        $questionText = $question['question_text'];
                                        $blankCount = 0;
                                        $savedAnswers = [];

                                        // Parse saved answers if they exist
                                        if (isset($currentAnswers[$question['id']])) {
                                            $savedAnswersJson = $currentAnswers[$question['id']];
                                            $savedAnswers = json_decode($savedAnswersJson, true) ?: [];
                                        }

                                        $processedText = preg_replace_callback('/\[BLANK\]/', function($matches) use (&$blankCount, $question, $savedAnswers) {
                                            $blankCount++;
                                            $fieldName = "question_{$question['id']}_blank_{$blankCount}";
                                            $value = isset($savedAnswers[$blankCount]) ? esc($savedAnswers[$blankCount]) : '';
                                            return '<input type="text" class="form-control d-inline-block blank-input" style="width: 150px; display: inline !important; margin: 0 5px;" name="' . $fieldName . '" value="' . $value . '" placeholder="Blank ' . $blankCount . '" data-blank-number="' . $blankCount . '" data-question-id="' . $question['id'] . '">';
                                        }, $questionText);
                                        echo $processedText;
                                        ?>
                                        <input type="hidden" name="question_<?= $question['id'] ?>" value="" class="blank-answers-hidden">

                                        <?php
                                        $metadata = json_decode($question['metadata'] ?? '{}', true);
                                        $blankCount = $metadata['blank_count'] ?? substr_count($questionText, '[BLANK]');
                                        if ($blankCount > 1): ?>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="material-symbols-rounded me-1" style="font-size: 14px;">info</i>
                                                    This question has <?= $blankCount ?> blanks. Each blank is worth <?= round(100/$blankCount, 1) ?>% of the total score.
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                <?php elseif ($question['question_type'] === 'short_answer'): ?>
                                    <div class="mb-3">
                                        <?php
                                        $metadata = json_decode($question['metadata'] ?? '{}', true);
                                        $maxWords = $metadata['max_words'] ?? 50;
                                        ?>
                                        <textarea class="form-control" name="question_<?= $question['id'] ?>"
                                                  rows="3" placeholder="Enter your answer here (max <?= $maxWords ?> words)..."
                                                  data-max-words="<?= $maxWords ?>"><?= isset($currentAnswers[$question['id']]) ? esc($currentAnswers[$question['id']]) : '' ?></textarea>
                                        <small class="text-muted">Word count: <span class="word-count">0</span> / <?= $maxWords ?></small>
                                    </div>

                                <?php elseif ($question['question_type'] === 'essay'): ?>
                                    <div class="mb-3">
                                        <?php
                                        $metadata = json_decode($question['metadata'] ?? '{}', true);
                                        $minWords = $metadata['min_words'] ?? 100;
                                        $maxWords = $metadata['max_words_essay'] ?? 1000;
                                        ?>
                                        <textarea class="form-control" name="question_<?= $question['id'] ?>"
                                                  rows="10" placeholder="Write your essay here (minimum <?= $minWords ?> words, maximum <?= $maxWords ?> words)..."
                                                  data-min-words="<?= $minWords ?>" data-max-words="<?= $maxWords ?>"><?= isset($currentAnswers[$question['id']]) ? esc($currentAnswers[$question['id']]) : '' ?></textarea>
                                        <small class="text-muted">Word count: <span class="word-count">0</span> (Min: <?= $minWords ?>, Max: <?= $maxWords ?>)</small>
                                    </div>

                                <?php elseif ($question['question_type'] === 'math_equation'): ?>
                                    <div class="mb-3">
                                        <?php
                                        $metadata = json_decode($question['metadata'] ?? '{}', true);
                                        $allowCalculator = $metadata['allow_calculator'] ?? false;
                                        $equationFormat = $metadata['equation_format'] ?? 'text';
                                        ?>
                                        <?php if ($allowCalculator): ?>
                                            <div class="alert alert-info">
                                                <i class="material-symbols-rounded me-2">calculate</i>
                                                Calculator is allowed for this question.
                                                <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="openCalculator()">
                                                    Open Calculator
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                        <input type="text" class="form-control" name="question_<?= $question['id'] ?>"
                                               placeholder="Enter your answer (e.g., x=5, 2.5, etc.)"
                                               value="<?= isset($currentAnswers[$question['id']]) ? esc($currentAnswers[$question['id']]) : '' ?>">
                                        <small class="text-muted">Format: <?= ucfirst($equationFormat) ?></small>
                                    </div>

                                <?php elseif ($question['question_type'] === 'image_based'): ?>
                                    <div class="mb-3">
                                        <?php if ($question['image_url']): ?>
                                            <div class="image-question-container">
                                                <img src="<?= base_url($question['image_url']) ?>"
                                                     class="img-fluid rounded clickable-image"
                                                     alt="Question Image"
                                                     data-question-id="<?= $question['id'] ?>"
                                                     style="max-width: 100%; cursor: pointer;">
                                                <input type="hidden" name="question_<?= $question['id'] ?>" value="">
                                            </div>
                                            <small class="text-muted">Click on the correct area in the image above.</small>
                                        <?php endif; ?>
                                    </div>

                                <?php elseif ($question['question_type'] === 'drag_drop'): ?>
                                    <div class="mb-3">
                                        <div class="drag-drop-container">
                                            <div class="draggable-items mb-3">
                                                <h6>Drag these items:</h6>
                                                <?php foreach ($question['options'] as $option): ?>
                                                    <div class="draggable-item" draggable="true" data-option-id="<?= $option['id'] ?>">
                                                        <?= esc($option['option_text']) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="drop-zones">
                                                <h6>Drop zones:</h6>
                                                <div class="drop-zone" data-question-id="<?= $question['id'] ?>">
                                                    Drop items here
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="question_<?= $question['id'] ?>" value="">
                                    </div>
                                <?php endif; ?>
                                </div>
                            </div>

                            <!-- Navigation Controls -->
                            <div class="nav-controls">
                                <button type="button" class="nav-btn btn-previous" id="prevBtn" onclick="previousQuestion()" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </button>
                                <div class="question-counter">
                                    <span id="currentQuestionNum">1</span> of <span id="totalQuestionNum"><?= count($questions) ?></span>
                                </div>
                                <button type="button" class="nav-btn btn-next" id="nextBtn" onclick="nextQuestion()">
                                    <i class="fas fa-save me-1"></i>
                                    Save & Next
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <!-- Submit Section -->
                        <div class="question-item" id="submitSection" style="display: none;">
                            <div class="question-header">
                                <div class="question-number">Review & Submit</div>
                            </div>
                            <div class="question-content text-center">
                                <h4 class="mb-4">Ready to Submit Your Exam?</h4>
                                <p class="text-muted mb-4">Please review your answers before submitting. Once submitted, you cannot make changes.</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <?php if ($exam['allow_review']): ?>
                                        <button type="button" class="btn btn-info nav-btn" onclick="showReviewModal()">
                                            <i class="fas fa-eye"></i>
                                            Review Answers
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn-submit nav-btn" onclick="submitExam()">
                                        <i class="fas fa-check"></i>
                                        Submit Exam
                                    </button>
                                </div>
                            </div>
                            <div class="nav-controls">
                                <button type="button" class="nav-btn btn-previous" onclick="previousQuestion()">
                                    <i class="fas fa-chevron-left"></i>
                                    Back to Questions
                                </button>
                                <div class="question-counter">Review</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Panel -->
                <div class="col-lg-4">
                    <div class="navigation-panel">
                        <div class="nav-title">Question Navigation</div>
                        <div class="question-grid" id="questionGrid">
                            <?php foreach ($questions as $index => $question): ?>
                            <?php
                            // Convert question object to array if needed
                            if (is_object($question)) {
                                $question = (array) $question;
                            }
                            ?>
                            <div class="question-nav-btn <?= $index === 0 ? 'current' : '' ?>"
                                 data-question-index="<?= $index ?>"
                                 onclick="goToQuestion(<?= $index ?>)">
                                <?= $index + 1 ?>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Legend -->
                        <div class="nav-legend">
                            <div class="legend-item">
                                <div class="legend-icon legend-answered">✓</div>
                                <span>Answered</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-icon legend-current"><?= count($questions) > 0 ? '1' : '' ?></div>
                                <span>Current</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-icon legend-unanswered"><?= count($questions) > 1 ? '2' : '' ?></div>
                                <span>Not Answered</span>
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

    <!-- Pause Modal -->
    <div class="modal fade" id="pauseModal" tabindex="-1" aria-labelledby="pauseModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="pauseModalLabel">
                        <i class="fas fa-pause me-2"></i>Exam Paused
                    </h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="fas fa-pause-circle fa-4x text-warning mb-3"></i>
                        <h5>Your exam is currently paused</h5>
                        <p class="text-muted">The timer has been stopped. Click "Resume" when you're ready to continue.</p>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your progress has been automatically saved.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-lg" onclick="resumeExam()">
                        <i class="fas fa-play me-2"></i>Resume Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Warning Modal -->
    <div class="modal fade" id="timeWarningModal" tabindex="-1" aria-labelledby="timeWarningModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="timeWarningModalLabel">
                        <i class="fas fa-clock me-2"></i>Time Warning
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                        <h5 id="timeWarningMessage">Warning: Only 5 minutes remaining!</h5>
                        <p class="text-muted">Please manage your time wisely and review your answers.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Continue Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="submitConfirmModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Submit Exam
                    </h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="fas fa-question-circle fa-4x text-success mb-3"></i>
                        <h5>Are you sure you want to submit your exam?</h5>
                        <p class="text-muted">This action cannot be undone. Please review your answers before submitting.</p>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Once submitted, you will not be able to make any changes.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="confirmSubmitExam()">
                        <i class="fas fa-check me-2"></i>Yes, Submit Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Up Modal -->
    <div class="modal fade" id="timeUpModal" tabindex="-1" aria-labelledby="timeUpModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="timeUpModalLabel">
                        <i class="fas fa-clock me-2"></i>Time's Up!
                    </h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="fas fa-hourglass-end fa-4x text-danger mb-3"></i>
                        <h5>Your exam time has expired!</h5>
                        <p class="text-muted">Your exam will be submitted automatically in a few seconds.</p>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your answers have been automatically saved.
                    </div>
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Submitting...</span>
                        </div>
                        <p class="mt-2 text-muted">Submitting your exam...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Answer Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-eye me-2"></i>Review Your Answers
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div id="reviewContent">
                        <!-- Review content will be populated by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close Review
                    </button>
                    <button type="button" class="btn btn-success" onclick="submitExam()" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Submit Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Status Indicator -->
    <div class="security-status active" id="securityStatus">
        <i class="fas fa-shield-alt"></i>
        <span>Security Active</span>
    </div>

    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Simple exam interface - copied from working practice test pattern
        let currentQuestionIndex = 0;
        const totalQuestions = <?= count($questions) ?>;
        const attemptId = <?= $attempt['id'] ?>;
        let timeRemaining = <?= $timeRemaining * 60 ?>; // Convert to seconds
        let timerInterval = null;
        let answers = {};

        // Questions data for subject tracking
        const questionsData = <?= json_encode($questions) ?>;
        const isMultiSubject = <?= $exam['exam_mode'] === 'multi_subject' ? 'true' : 'false' ?>;

        // Security alert function
        function showSecurityAlert(message) {
            document.getElementById('securityAlertMessage').textContent = message;
            const modal = new bootstrap.Modal(document.getElementById('securityAlertModal'));
            modal.show();
        }

        console.log('Exam initialized with:', totalQuestions, 'questions');
        console.log('Time remaining:', timeRemaining, 'seconds');

        // Update current subject on page load
        updateCurrentSubject();

        // Function to update current subject display for multi-subject exams
        function updateCurrentSubject() {
            if (isMultiSubject && questionsData.length > 0) {
                const currentQuestion = questionsData[currentQuestionIndex];
                const currentSubjectElement = document.getElementById('currentSubjectName');

                if (currentQuestion && currentQuestion.subject_name && currentSubjectElement) {
                    currentSubjectElement.textContent = currentQuestion.subject_name;
                }
            }
        }

        // Timer functionality
        function startTimer() {
            console.log('Starting timer with', timeRemaining, 'seconds');
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
        }

        function updateTimer() {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);

                // Auto-save current answer before time expires
                saveCurrentAnswer();

                // Show time up modal and auto-submit
                const modal = new bootstrap.Modal(document.getElementById('timeUpModal'));
                modal.show();

                // Auto-submit after 3 seconds
                setTimeout(() => {
                    document.getElementById('examForm').submit();
                }, 3000);

                return;
            }

            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;

            const timerElement = document.getElementById('timeRemaining');
            if (timerElement) {
                timerElement.textContent =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                // Add warning classes for low time
                const timerContainer = timerElement.closest('.timer-container') || timerElement.parentElement;
                if (timeRemaining <= 300) { // 5 minutes
                    timerContainer.classList.add('timer-warning');
                }
                if (timeRemaining <= 60) { // 1 minute
                    timerContainer.classList.add('timer-critical');
                }
            }

            timeRemaining--;
        }

        // Navigation functions with auto-save and submit functionality
        function nextQuestion() {
            console.log('Next question clicked, current:', currentQuestionIndex);

            // Auto-save current answer before moving
            saveCurrentAnswer();

            if (currentQuestionIndex < totalQuestions - 1) {
                document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'none';
                currentQuestionIndex++;
                document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'block';
                updateNavigationButtons();
                updateCurrentSubject();
            } else {
                // On last question, show submit option
                submitExam();
            }
        }

        function previousQuestion() {
            console.log('Previous question clicked, current:', currentQuestionIndex);

            // Auto-save current answer before moving
            saveCurrentAnswer();

            if (currentQuestionIndex > 0) {
                document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'none';
                currentQuestionIndex--;
                document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'block';
                updateNavigationButtons();
                updateCurrentSubject();
            }
        }

        function goToQuestion(index) {
            console.log('Go to question:', index);

            // Auto-save current answer before moving
            saveCurrentAnswer();

            if (index >= 0 && index < totalQuestions) {
                document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'none';
                currentQuestionIndex = index;
                document.getElementById('question-' + (currentQuestionIndex + 1)).style.display = 'block';
                updateNavigationButtons();
                updateCurrentSubject();
            }
        }

        function updateNavigationButtons() {
            // Update ALL previous buttons (since each question has its own)
            const prevBtns = document.querySelectorAll('#prevBtn, .btn-previous');
            prevBtns.forEach(btn => {
                btn.disabled = currentQuestionIndex === 0;
            });

            // Update ALL next buttons (since each question has its own)
            const nextBtns = document.querySelectorAll('#nextBtn, .btn-next');
            nextBtns.forEach(btn => {
                if (currentQuestionIndex === totalQuestions - 1) {
                    btn.innerHTML = '<i class="fas fa-save me-1"></i>Submit Exam';
                    btn.className = 'nav-btn btn-submit';
                } else {
                    btn.innerHTML = '<i class="fas fa-save me-1"></i>Save & Next';
                    btn.className = 'nav-btn btn-next';
                }
            });

            // Update ALL current question displays
            const currentQuestionNums = document.querySelectorAll('#currentQuestionNum');
            currentQuestionNums.forEach(element => {
                element.textContent = currentQuestionIndex + 1;
            });

            // Update navigation grid
            const navButtons = document.querySelectorAll('.question-nav-btn');
            navButtons.forEach((btn, index) => {
                btn.classList.remove('current');
                if (index === currentQuestionIndex) {
                    btn.classList.add('current');
                }
            });

            console.log('Updated navigation buttons. Current question:', currentQuestionIndex + 1, 'Previous disabled:', currentQuestionIndex === 0);
        }

        // Auto-save functionality
        function saveCurrentAnswer() {
            console.log('Saving current answer for question index:', currentQuestionIndex);

            // Get the current question element
            const currentQuestionElement = document.getElementById('question-' + (currentQuestionIndex + 1));
            if (!currentQuestionElement) {
                console.log('No current question element found');
                return;
            }

            // Find the question ID from the form elements
            const radioInputs = currentQuestionElement.querySelectorAll('input[type="radio"]');
            const textInputs = currentQuestionElement.querySelectorAll('input[type="text"]');
            const textareas = currentQuestionElement.querySelectorAll('textarea');

            let questionId = null;
            let answer = null;

            // Try to get question ID and answer from radio buttons
            if (radioInputs.length > 0) {
                const firstRadio = radioInputs[0];
                const nameAttr = firstRadio.getAttribute('name');
                if (nameAttr && nameAttr.startsWith('question_')) {
                    questionId = nameAttr.replace('question_', '');

                    // Get selected radio value
                    const checkedRadio = currentQuestionElement.querySelector('input[type="radio"]:checked');
                    if (checkedRadio) {
                        answer = checkedRadio.value;
                    }
                }
            }
            // Try text inputs
            else if (textInputs.length > 0) {
                const firstInput = textInputs[0];
                const nameAttr = firstInput.getAttribute('name');
                if (nameAttr && nameAttr.startsWith('question_')) {
                    questionId = nameAttr.replace('question_', '');
                    answer = firstInput.value;
                }
            }
            // Try textareas
            else if (textareas.length > 0) {
                const firstTextarea = textareas[0];
                const nameAttr = firstTextarea.getAttribute('name');
                if (nameAttr && nameAttr.startsWith('question_')) {
                    questionId = nameAttr.replace('question_', '');
                    answer = firstTextarea.value;
                }
            }

            // Save answer if we have question ID (even if answer is empty)
            if (questionId) {
                // Ensure answer is a string (empty string for unanswered questions)
                if (answer === null || answer === undefined) {
                    answer = '';
                }

                console.log('Saving answer:', questionId, '=', answer);
                saveAnswerToServer(questionId, answer);

                // Mark question as answered in navigation only if there's an actual answer
                const navBtn = document.querySelector(`[data-question-index="${currentQuestionIndex}"]`);
                if (navBtn) {
                    if (answer && answer !== '') {
                        navBtn.classList.add('answered');
                    } else {
                        navBtn.classList.remove('answered');
                    }
                }
            } else {
                console.log('No question ID found for current question');
            }
        }

        function saveAnswerToServer(questionId, answer) {
            console.log('Saving answer for question:', questionId);



            // Use URLSearchParams instead of FormData for better compatibility
            const params = new URLSearchParams();
            params.append('attempt_id', attemptId);
            params.append('question_id', questionId);
            params.append('answer', answer);
            params.append('csrf_test_name', '<?= csrf_hash() ?>');

            fetch('<?= base_url('student/saveAnswer') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: params.toString()
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('✅ Answer saved for question', questionId);
                    // Store answer locally for immediate feedback
                    answers[questionId] = answer;
                } else {
                    console.error('❌ Failed to save answer:', data.message);
                }
            })
            .catch(error => {
                console.error('❌ Error saving answer:', error.message);
            });
        }



        function submitExam() {
            // Save current answer before submitting
            saveCurrentAnswer();

            const modal = new bootstrap.Modal(document.getElementById('submitConfirmModal'));
            modal.show();
        }

        function confirmSubmitExam() {
            // Final save before submission
            saveCurrentAnswer();
            document.getElementById('examForm').submit();
        }

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
            const navBtn = document.querySelector(`[data-question-index="${currentQuestionIndex}"]`);
            if (navBtn) {
                navBtn.classList.add('answered');
                console.log('Question marked as answered in navigation');
            }
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
                                                <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="clearCalc()">C</button></div>
                                                <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('/')">/</button></div>
                                                <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('*')">×</button></div>
                                                <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="deleteLast()">⌫</button></div>
                                            </div>
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('7')">7</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('8')">8</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('9')">9</button></div>
                                                <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('-')">-</button></div>
                                            </div>
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('4')">4</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('5')">5</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('6')">6</button></div>
                                                <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('+')">+</button></div>
                                            </div>
                                            <div class="row g-1 mb-1">
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('1')">1</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('2')">2</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('3')">3</button></div>
                                                <div class="col-3 row-span-2"><button class="btn btn-success w-100 h-100" onclick="calculate()">=</button></div>
                                            </div>
                                            <div class="row g-1">
                                                <div class="col-6"><button class="btn btn-outline-primary w-100" onclick="calcInput('0')">0</button></div>
                                                <div class="col-3"><button class="btn btn-outline-primary w-100" onclick="calcInput('.')">.</button></div>
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
            clearInterval(timerInterval);

            // Save current answer before pausing
            saveCurrentAnswer();

            // Update pause button
            const pauseBtn = document.getElementById('pauseBtn');
            pauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            pauseBtn.title = 'Resume Exam';
            pauseBtn.classList.remove('btn-warning');
            pauseBtn.classList.add('btn-success');

            // Show pause modal
            const modal = new bootstrap.Modal(document.getElementById('pauseModal'));
            modal.show();

            // Record pause event
            fetch('<?= base_url('student/recordPauseEvent') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `attempt_id=${attemptId}&action=pause&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            });
        }

        function resumeExam() {
            isPaused = false;
            startTimer();

            // Update pause button
            const pauseBtn = document.getElementById('pauseBtn');
            pauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
            pauseBtn.title = 'Pause Exam';
            pauseBtn.classList.remove('btn-success');
            pauseBtn.classList.add('btn-warning');

            // Hide pause modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('pauseModal'));
            if (modal) {
                modal.hide();
            }

            // Record resume event
            fetch('<?= base_url('student/recordPauseEvent') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `attempt_id=${attemptId}&action=resume&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            });
        }

        // Show review modal function
        function showReviewModal() {
            const reviewContent = document.getElementById('reviewContent');
            let reviewHTML = '';

            // Get all questions and their answers
            const questions = <?= json_encode($questions) ?>;
            const currentAnswers = <?= json_encode($currentAnswers) ?>;

            reviewHTML += '<div class="row">';

            questions.forEach((question, index) => {
                const questionId = question.id;
                const userAnswer = currentAnswers[questionId] || null;
                let answerText = 'Not answered';
                let answerClass = 'text-danger';

                if (userAnswer) {
                    answerClass = 'text-success';

                    // Find the answer text based on question type
                    if (question.question_type === 'mcq' && question.options) {
                        const selectedOption = question.options.find(opt => opt.id == userAnswer);
                        answerText = selectedOption ? selectedOption.option_text : 'Invalid answer';
                    } else if (question.question_type === 'true_false') {
                        const selectedOption = question.options.find(opt => opt.id == userAnswer);
                        answerText = selectedOption ? selectedOption.option_text : 'Invalid answer';
                    } else {
                        answerText = userAnswer;
                    }
                }

                reviewHTML += `
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-question-circle me-2"></i>Question ${index + 1}
                                    <span class="badge bg-primary ms-2">${question.points} mks</span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text mb-3">${question.question_text}</p>
                                <div class="answer-review">
                                    <strong>Your Answer:</strong>
                                    <span class="${answerClass} ms-2">${answerText}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="goToQuestionFromReview(${index})">
                                    <i class="fas fa-edit me-1"></i>Edit Answer
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            reviewHTML += '</div>';

            // Add summary
            const totalQuestions = questions.length;
            const answeredQuestions = Object.keys(currentAnswers).length;
            const unansweredQuestions = totalQuestions - answeredQuestions;

            reviewHTML = `
                <div class="alert alert-info mb-4">
                    <h5><i class="fas fa-chart-pie me-2"></i>Answer Summary</h5>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="h4 text-primary">${totalQuestions}</div>
                            <div>Total Questions</div>
                        </div>
                        <div class="col-md-4">
                            <div class="h4 text-success">${answeredQuestions}</div>
                            <div>Answered</div>
                        </div>
                        <div class="col-md-4">
                            <div class="h4 text-danger">${unansweredQuestions}</div>
                            <div>Not Answered</div>
                        </div>
                    </div>
                </div>
            ` + reviewHTML;

            reviewContent.innerHTML = reviewHTML;

            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        }

        // Go to question from review modal
        function goToQuestionFromReview(questionIndex) {
            // Close the review modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
            modal.hide();

            // Navigate to the question
            goToQuestion(questionIndex);
        }

        // Initialize exam interface
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== INITIALIZING EXAM INTERFACE ===');

            updateNavigationButtons();
            startTimer();

            // Auto-save answers every 30 seconds
            setInterval(() => {
                saveCurrentAnswer();
            }, 30000);

            // Auto-save on page unload (browser close, refresh, etc.)
            window.addEventListener('beforeunload', function(e) {
                saveCurrentAnswer();
                // Don't show browser native prompt - just save silently
            });

            // Auto-save on visibility change (tab switch)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    saveCurrentAnswer();
                }
            });

            // Initialize security features based on security settings
            console.log('Initializing security features...');

            // Force fullscreen mode immediately if browser lockdown is enabled
            <?php if ($settings['browser_lockdown'] ?? false): ?>
            console.log('Browser lockdown enabled - forcing fullscreen');
            requestFullscreenImmediate();
            <?php endif; ?>

            // Security features are active (status indicator removed to avoid blocking UI)

            // Enhanced security features
            initializeSecurityFeatures();

            <?php if ($settings['prevent_copy_paste'] ?? false): ?>
            console.log('Copy/Paste prevention enabled');

            // Apply security CSS class
            document.body.classList.add('security-enabled');

            // Prevent copy/paste/cut with comprehensive coverage
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
                // Allow selection in input fields
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    return true;
                }
                e.preventDefault();
                return false;
            });

            // Disable drag
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });
            <?php endif; ?>

            <?php if ($settings['disable_right_click'] ?? false): ?>
            console.log('Right-click disabled');

            // Disable right-click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                showSecurityAlert('Right-click is disabled during the exam');
                return false;
            });
            <?php endif; ?>

            <?php if ($settings['browser_lockdown'] ?? false): ?>
            console.log('Browser lockdown enabled');

            // Browser lockdown features - comprehensive keyboard blocking
            document.addEventListener('keydown', function(e) {
                // Prevent developer tools and other shortcuts
                if (e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
                    (e.ctrlKey && e.key === 'u') ||
                    (e.ctrlKey && e.key === 's') ||
                    (e.ctrlKey && e.key === 'p') ||
                    (e.ctrlKey && e.key === 'r') ||
                    (e.ctrlKey && e.shiftKey && e.key === 'Delete') ||
                    (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a')) ||
                    (e.altKey && e.key === 'Tab') ||
                    (e.altKey && e.key === 'F4') ||
                    e.key === 'F5' ||
                    e.key === 'F11' ||
                    e.key === 'PrintScreen') {
                    e.preventDefault();
                    e.stopPropagation();
                    logSecurityEvent('blocked_key_combination', { key: e.key, ctrl: e.ctrlKey, shift: e.shiftKey, alt: e.altKey });
                    showSecurityAlert('This action is not allowed during the exam');
                    return false;
                }
            });

            // Enhanced tab switch/window focus loss detection
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    logSecurityEvent('tab_switch', { timestamp: new Date().toISOString() });
                    showSecurityAlert('Warning: Tab switching detected! This violation has been logged.');
                }
            });

            // Enhanced window blur (focus loss) detection
            window.addEventListener('blur', function() {
                logSecurityEvent('window_focus_loss', { timestamp: new Date().toISOString() });
                showSecurityAlert('Please keep focus on the exam window - focus loss logged');
            });
            <?php endif; ?>

            // Debug: Log security settings
            console.log('=== SECURITY SETTINGS DEBUG ===');
            console.log('Prevent Copy/Paste:', <?= json_encode($exam['prevent_copy_paste'] ?? false) ?>);
            console.log('Disable Right Click:', <?= json_encode($exam['disable_right_click'] ?? false) ?>);
            console.log('Browser Lockdown:', <?= json_encode($exam['browser_lockdown'] ?? false) ?>);
            console.log('Require Proctoring:', <?= json_encode($exam['require_proctoring'] ?? false) ?>);
            console.log('Calculator Enabled:', <?= json_encode($exam['calculator_enabled'] ?? false) ?>);
            console.log('Exam Pause Enabled:', <?= json_encode($exam['exam_pause_enabled'] ?? false) ?>);
            console.log('=== END SECURITY SETTINGS DEBUG ===');

            console.log('=== EXAM INTERFACE INITIALIZED SUCCESSFULLY ===');
        });

        // Show security status indicator
        function showSecurityStatus() {
            const securityFeatures = [];

            <?php if ($settings['browser_lockdown'] ?? false): ?>
            securityFeatures.push('Browser Lockdown');
            <?php endif; ?>

            <?php if ($settings['prevent_copy_paste'] ?? false): ?>
            securityFeatures.push('Copy/Paste Protection');
            <?php endif; ?>

            <?php if ($settings['disable_right_click'] ?? false): ?>
            securityFeatures.push('Right-Click Protection');
            <?php endif; ?>

            if (securityFeatures.length > 0) {
                const statusDiv = document.createElement('div');
                statusDiv.id = 'security-status';
                statusDiv.innerHTML = `
                    <div class="security-status-content">
                        <i class="fas fa-shield-alt"></i>
                        <span>Security Active: ${securityFeatures.join(', ')}</span>
                    </div>
                `;
                statusDiv.style.cssText = `
                    position: fixed;
                    top: 10px;
                    right: 10px;
                    background: #28a745;
                    color: white;
                    padding: 8px 12px;
                    border-radius: 5px;
                    font-size: 12px;
                    z-index: 10000;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                `;
                document.body.appendChild(statusDiv);

                console.log('Security features active:', securityFeatures);
            }
        }

        // Aggressive fullscreen enforcement for browser lockdown
        function requestFullscreenImmediate() {
            console.log('Enforcing fullscreen mode...');

            // Show blocking modal that forces fullscreen
            showFullscreenModal();
        }

        function showFullscreenModal() {
            // Create blocking modal
            const modal = document.createElement('div');
            modal.id = 'fullscreen-modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                z-index: 999999;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-family: Arial, sans-serif;
            `;

            modal.innerHTML = `
                <div style="text-align: center; padding: 40px; background: #dc3545; border-radius: 10px; max-width: 500px;">
                    <h2 style="margin-bottom: 20px; color: white;">⚠️ Fullscreen Required</h2>
                    <p style="margin-bottom: 30px; font-size: 16px; line-height: 1.5;">
                        This exam requires fullscreen mode for security purposes.<br>
                        Click the button below to enter fullscreen and continue.
                    </p>
                    <button id="enter-fullscreen-btn" style="
                        background: #28a745;
                        color: white;
                        border: none;
                        padding: 15px 30px;
                        font-size: 16px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: bold;
                    ">Enter Fullscreen Mode</button>
                    <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">
                        You cannot proceed without entering fullscreen mode.
                    </p>
                </div>
            `;

            document.body.appendChild(modal);

            // Add click handler to enter fullscreen
            document.getElementById('enter-fullscreen-btn').addEventListener('click', function() {
                enterFullscreen();
            });

            // Prevent any interaction with the page behind the modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }

        function enterFullscreen() {
            const elem = document.documentElement;

            if (elem.requestFullscreen) {
                elem.requestFullscreen().then(() => {
                    removeFullscreenModal();
                    setupFullscreenMonitoring();
                }).catch(err => {
                    console.log('Fullscreen request failed:', err);
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
                    logSecurityEvent('fullscreen_exit_attempt', { timestamp: new Date().toISOString() });
                    console.log('Fullscreen exited - showing modal again');
                    // Immediately show the modal again
                    setTimeout(() => {
                        showFullscreenModal();
                    }, 100);
                }
            }
        }

        // Enhanced Security Features
        function initializeSecurityFeatures() {
            console.log('Initializing enhanced security features...');

            // Fullscreen enforcement
            <?php if ($settings['browser_lockdown'] ?? false): ?>
            enforceFullscreen();
            <?php endif; ?>

            // Tab switching detection
            initializeTabSwitchingDetection();

            // Window focus monitoring
            initializeWindowFocusMonitoring();

            // Screen recording detection
            initializeScreenRecordingDetection();

            // Multiple monitor detection
            initializeMultipleMonitorDetection();

            // Keyboard shortcuts blocking
            initializeKeyboardBlocking();

            // Mouse behavior monitoring
            initializeMouseMonitoring();

            // Enhanced security features
            <?php if ($settings['prevent_screen_capture'] ?? false): ?>
            initializeEnhancedScreenCaptureProtection();
            <?php endif; ?>

            <?php if ($settings['enhanced_devtools_detection'] ?? false): ?>
            initializeEnhancedDevToolsDetection();
            <?php endif; ?>

            <?php if ($settings['browser_extension_detection'] ?? false): ?>
            initializeBrowserExtensionDetection();
            <?php endif; ?>

            <?php if ($settings['virtual_machine_detection'] ?? false): ?>
            initializeVirtualMachineDetection();
            <?php endif; ?>

            <?php if ($settings['mouse_tracking_enabled'] ?? false): ?>
            initializeAdvancedMouseTracking();
            <?php endif; ?>

            <?php if ($settings['keyboard_pattern_analysis'] ?? false): ?>
            initializeKeyboardPatternAnalysis();
            <?php endif; ?>

            <?php if ($settings['window_resize_detection'] ?? false): ?>
            initializeWindowResizeDetection();
            <?php endif; ?>

            <?php if ($settings['clipboard_monitoring'] ?? false): ?>
            initializeClipboardMonitoring();
            <?php endif; ?>
        }

        function enforceFullscreen() {
            console.log('Enforcing fullscreen mode...');

            // Request fullscreen
            function requestFullscreen() {
                const elem = document.documentElement;
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            }

            // Check if already in fullscreen
            if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                requestFullscreen();
            }

            // Monitor fullscreen changes
            document.addEventListener('fullscreenchange', handleFullscreenChange);
            document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
            document.addEventListener('msfullscreenchange', handleFullscreenChange);

            function handleFullscreenChange() {
                if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                    showSecurityWarning('Fullscreen mode is required for this exam. Please return to fullscreen.');
                    // Give user 5 seconds to return to fullscreen
                    setTimeout(() => {
                        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                            requestFullscreen();
                        }
                    }, 5000);
                }
            }
        }

        function initializeTabSwitchingDetection() {
            console.log('Initializing enhanced tab switching detection...');

            let tabSwitchCount = 0;
            let isTabActive = true;
            const maxTabSwitches = <?= $settings['max_tab_switches'] ?? 5 ?>;
            const strictMode = <?= ($settings['strict_security_mode'] ?? false) ? 'true' : 'false' ?>;
            const autoSubmit = <?= ($settings['auto_submit_on_violation'] ?? true) ? 'true' : 'false' ?>;

            console.log('Tab Switching Detection Settings:', {
                maxTabSwitches: maxTabSwitches,
                strictMode: strictMode,
                autoSubmit: autoSubmit
            });

            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    isTabActive = false;
                    tabSwitchCount++;
                    logSecurityEvent('tab_switch_away', { count: tabSwitchCount, max_allowed: maxTabSwitches });

                    console.log('Tab switch detected. Strict mode:', strictMode, 'Count:', tabSwitchCount);

                    // Only show warnings/violations if strict mode is enabled
                    if (strictMode === 'true') {
                        // Check if limit exceeded
                        if (tabSwitchCount >= maxTabSwitches) {
                            if (autoSubmit) {
                                showBlockingModal(
                                    'Exam Auto-Submitted',
                                    `You have exceeded the maximum allowed tab switches (${maxTabSwitches}). Your exam has been automatically submitted.`,
                                    'danger',
                                    () => {
                                        logSecurityEvent('exam_auto_submitted', { reason: 'tab_switching_limit_exceeded', count: tabSwitchCount });
                                        submitExam(true); // Force submit
                                    }
                                );
                            } else {
                                showBlockingModal(
                                    'Exam Terminated',
                                    `You have exceeded the maximum allowed tab switches (${maxTabSwitches}). Please contact your instructor.`,
                                    'danger'
                                );
                            }
                        } else if (tabSwitchCount >= Math.floor(maxTabSwitches * 0.8)) {
                            // Show blocking warning when approaching limit
                            const remaining = maxTabSwitches - tabSwitchCount;
                            showBlockingModal(
                                'Security Violation Warning',
                                `Tab switching detected (${tabSwitchCount}/${maxTabSwitches}). You have ${remaining} more violations before your exam is automatically submitted. Please close all other tabs and focus only on this exam.`,
                                'warning',
                                () => {
                                    // Check if other tabs are still open
                                    checkForMultipleTabs();
                                }
                            );
                        } else {
                            const remaining = maxTabSwitches - tabSwitchCount;
                            showSecurityWarning(`Tab switching detected (${tabSwitchCount}/${maxTabSwitches}). ${remaining} violations remaining before auto-submit.`);
                        }
                    } else {
                        // In non-strict mode, just log the event but don't show warnings to the user
                        console.log('Tab switch detected but strict mode is disabled. No user warning shown.');
                    }
                } else {
                    isTabActive = true;
                    logSecurityEvent('tab_switch_back', { count: tabSwitchCount });
                }
            });
        }

        function initializeWindowFocusMonitoring() {
            console.log('Initializing enhanced window focus monitoring...');

            let focusLossCount = 0;
            let lastFocusTime = Date.now();
            const maxFocusLoss = <?= $settings['max_window_focus_loss'] ?? 3 ?>;
            const strictMode = <?= ($settings['strict_security_mode'] ?? false) ? 'true' : 'false' ?>;
            const autoSubmit = <?= ($settings['auto_submit_on_violation'] ?? true) ? 'true' : 'false' ?>;

            console.log('Window Focus Monitoring Settings:', {
                maxFocusLoss: maxFocusLoss,
                strictMode: strictMode,
                autoSubmit: autoSubmit
            });

            window.addEventListener('blur', function() {
                focusLossCount++;
                const duration = Date.now() - lastFocusTime;
                logSecurityEvent('window_focus_lost', { count: focusLossCount, duration: duration, max_allowed: maxFocusLoss });

                console.log('Window focus lost. Strict mode:', strictMode, 'Count:', focusLossCount);

                // Only show warnings/violations if strict mode is enabled
                if (strictMode === 'true') {
                    // Check if limit exceeded
                    if (focusLossCount >= maxFocusLoss) {
                        if (autoSubmit) {
                            showBlockingModal(
                                'Exam Auto-Submitted',
                                `You have exceeded the maximum allowed window focus losses (${maxFocusLoss}). Your exam has been automatically submitted.`,
                                'danger',
                                () => {
                                    logSecurityEvent('exam_auto_submitted', { reason: 'focus_loss_limit_exceeded', count: focusLossCount });
                                    submitExam(true); // Force submit
                                }
                            );
                        } else {
                            showBlockingModal(
                                'Exam Terminated',
                                `You have exceeded the maximum allowed window focus losses (${maxFocusLoss}). Please contact your instructor.`,
                                'danger'
                            );
                        }
                    } else if (focusLossCount >= Math.floor(maxFocusLoss * 0.7)) {
                        // Show blocking warning when approaching limit
                        const remaining = maxFocusLoss - focusLossCount;
                        showBlockingModal(
                            'Focus Loss Warning',
                            `Window focus lost (${focusLossCount}/${maxFocusLoss}). You have ${remaining} more violations before your exam is automatically submitted. Please keep focus on this exam window only.`,
                            'warning'
                        );
                    } else {
                        const remaining = maxFocusLoss - focusLossCount;
                        showSecurityWarning(`Window focus lost (${focusLossCount}/${maxFocusLoss}). ${remaining} violations remaining before auto-submit.`);
                    }
                } else {
                    // In non-strict mode, just log the event but don't show warnings to the user
                    console.log('Window focus lost but strict mode is disabled. No user warning shown.');
                }
            });

            window.addEventListener('focus', function() {
                lastFocusTime = Date.now();
                logSecurityEvent('window_focus_gained', { count: focusLossCount });
            });
        }

        function initializeScreenRecordingDetection() {
            console.log('Initializing screen recording detection...');

            // Check for screen recording APIs
            if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
                // Monitor for screen capture attempts
                const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia;
                navigator.mediaDevices.getDisplayMedia = function() {
                    logSecurityEvent('screen_recording_attempt');
                    showSecurityAlert('Screen recording is not allowed during the exam.');
                    return Promise.reject(new Error('Screen recording blocked'));
                };
            }
        }

        function initializeMultipleMonitorDetection() {
            console.log('Initializing enhanced multiple monitor detection...');

            let monitorWarningCount = 0;
            const maxMonitorWarnings = <?= $settings['max_monitor_warnings'] ?? 2 ?>;
            const strictMode = <?= ($settings['strict_security_mode'] ?? false) ? 'true' : 'false' ?>;
            const autoSubmit = <?= ($settings['auto_submit_on_violation'] ?? true) ? 'true' : 'false' ?>;

            console.log('Multiple Monitor Detection Settings:', {
                maxMonitorWarnings: maxMonitorWarnings,
                strictMode: strictMode,
                autoSubmit: autoSubmit
            });

            // Store initial screen configuration
            let initialScreenConfig = {
                width: screen.width,
                height: screen.height,
                availWidth: screen.availWidth,
                availHeight: screen.availHeight,
                colorDepth: screen.colorDepth,
                pixelDepth: screen.pixelDepth
            };

            console.log('Initial screen configuration:', initialScreenConfig);

            function checkMultipleMonitors() {
                let multipleMonitorsDetected = false;
                let detectionMethod = '';

                // Method 1: Check if screen dimensions are unusually wide (common with extended displays)
                const aspectRatio = screen.width / screen.height;
                if (aspectRatio > 3.0) { // Typical single monitor max is ~2.4 (21:9)
                    multipleMonitorsDetected = true;
                    detectionMethod = 'Wide aspect ratio detected';
                }

                // Method 2: Check for very large screen widths (likely multiple monitors)
                if (screen.width > 3000) { // Most single monitors are under 3000px wide
                    multipleMonitorsDetected = true;
                    detectionMethod = 'Unusually wide screen detected';
                }

                // Method 3: Use Screen Detection API if available
                if ('getScreenDetails' in window) {
                    window.getScreenDetails().then(screenDetails => {
                        if (screenDetails.screens && screenDetails.screens.length > 1) {
                            multipleMonitorsDetected = true;
                            detectionMethod = 'Screen Detection API - Multiple screens found';
                            handleMultipleMonitorDetection(detectionMethod);
                        }
                    }).catch(err => {
                        console.log('Screen Detection API not available or permission denied:', err);
                    });
                }

                // Method 4: Check for significant screen configuration changes
                const currentConfig = {
                    width: screen.width,
                    height: screen.height,
                    availWidth: screen.availWidth,
                    availHeight: screen.availHeight
                };

                if (currentConfig.width !== initialScreenConfig.width ||
                    currentConfig.height !== initialScreenConfig.height) {
                    multipleMonitorsDetected = true;
                    detectionMethod = 'Screen configuration changed';
                }

                console.log('Monitor detection check:', {
                    detected: multipleMonitorsDetected,
                    method: detectionMethod,
                    aspectRatio: aspectRatio,
                    screenWidth: screen.width,
                    screenHeight: screen.height
                });

                if (multipleMonitorsDetected) {
                    handleMultipleMonitorDetection(detectionMethod);
                }
            }

            function handleMultipleMonitorDetection(detectionMethod) {
                monitorWarningCount++;
                logSecurityEvent('multiple_monitors_detected', {
                    count: monitorWarningCount,
                    max_allowed: maxMonitorWarnings,
                    detection_method: detectionMethod,
                    screen_width: screen.width,
                    screen_height: screen.height,
                    aspect_ratio: (screen.width / screen.height).toFixed(2)
                });

                console.log('Multiple monitors detected. Method:', detectionMethod, 'Strict mode:', strictMode, 'Count:', monitorWarningCount);

                // Only show warnings/violations if strict mode is enabled
                if (strictMode === 'true') {
                    if (monitorWarningCount >= maxMonitorWarnings) {
                        if (autoSubmit) {
                            showBlockingModal(
                                'Exam Auto-Submitted',
                                `You have exceeded the maximum allowed multiple monitor warnings (${maxMonitorWarnings}). Your exam has been automatically submitted.<br><br><small>Detection method: ${detectionMethod}</small>`,
                                'danger',
                                () => {
                                    logSecurityEvent('exam_auto_submitted', { reason: 'monitor_warnings_limit_exceeded', count: monitorWarningCount });
                                    submitExam(true); // Force submit
                                }
                            );
                        } else {
                            showBlockingModal(
                                'Exam Terminated',
                                `You have exceeded the maximum allowed multiple monitor warnings (${maxMonitorWarnings}). Please contact your instructor.<br><br><small>Detection method: ${detectionMethod}</small>`,
                                'danger'
                            );
                        }
                    } else {
                        const remaining = maxMonitorWarnings - monitorWarningCount;
                        showBlockingModal(
                            'Multiple Monitors Detected',
                            `Multiple monitors detected (${monitorWarningCount}/${maxMonitorWarnings}). You have ${remaining} more violations before your exam is automatically submitted. Please disconnect additional monitors and use only one monitor.<br><br><small>Detection method: ${detectionMethod}</small>`,
                            'warning'
                        );
                    }
                } else {
                    // In non-strict mode, just log the event but don't show warnings to the user
                    console.log('Multiple monitors detected but strict mode is disabled. No user warning shown.');
                }
            }

            // Initial check
            setTimeout(() => {
                checkMultipleMonitors();
            }, 1000); // Delay initial check to allow screen to stabilize

            // Monitor screen changes
            window.addEventListener('resize', function() {
                setTimeout(() => {
                    checkMultipleMonitors();
                }, 500); // Debounce resize events
                logSecurityEvent('screen_configuration_changed', { count: monitorWarningCount });
            });

            // Monitor orientation changes
            window.addEventListener('orientationchange', function() {
                setTimeout(() => {
                    checkMultipleMonitors();
                }, 1000);
            });

            // Test function for debugging (remove in production)
            window.testMultipleMonitorDetection = function() {
                console.log('=== TESTING MULTIPLE MONITOR DETECTION ===');
                console.log('Current screen info:', {
                    width: screen.width,
                    height: screen.height,
                    availWidth: screen.availWidth,
                    availHeight: screen.availHeight,
                    aspectRatio: (screen.width / screen.height).toFixed(2)
                });

                // Force trigger detection for testing
                handleMultipleMonitorDetection('Manual test trigger');
            };
        }

        function initializeKeyboardBlocking() {
            console.log('Initializing enhanced keyboard blocking...');

            document.addEventListener('keydown', function(e) {
                // Block dangerous key combinations
                const blockedKeys = [
                    { key: 'F12' }, // Developer tools
                    { ctrl: true, shift: true, key: 'I' }, // Developer tools
                    { ctrl: true, shift: true, key: 'J' }, // Console
                    { ctrl: true, shift: true, key: 'C' }, // Inspector
                    { ctrl: true, key: 'U' }, // View source
                    { ctrl: true, key: 'S' }, // Save page
                    { ctrl: true, key: 'P' }, // Print
                    { ctrl: true, key: 'R' }, // Refresh
                    { key: 'F5' }, // Refresh
                    { alt: true, key: 'Tab' }, // Alt+Tab
                    { ctrl: true, key: 'Tab' }, // Ctrl+Tab
                    { ctrl: true, shift: true, key: 'Tab' }, // Ctrl+Shift+Tab
                    { key: 'PrintScreen' }, // Screenshot
                ];

                for (let blocked of blockedKeys) {
                    if (isKeyMatch(e, blocked)) {
                        e.preventDefault();
                        e.stopPropagation();
                        logSecurityEvent('blocked_key_combination', { key: e.key, ctrl: e.ctrlKey, shift: e.shiftKey, alt: e.altKey });
                        showSecurityWarning('This keyboard shortcut is not allowed during the exam.');
                        return false;
                    }
                }
            });

            function isKeyMatch(event, pattern) {
                return (!pattern.key || event.key === pattern.key) &&
                       (!pattern.ctrl || event.ctrlKey) &&
                       (!pattern.shift || event.shiftKey) &&
                       (!pattern.alt || event.altKey);
            }
        }

        function initializeMouseMonitoring() {
            console.log('Initializing mouse monitoring...');

            // Disable right-click context menu
            <?php if ($settings['disable_right_click'] ?? false): ?>
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                logSecurityEvent('right_click_blocked');
                showSecurityWarning('Right-click is disabled during the exam.');
                return false;
            });
            <?php endif; ?>

            // Monitor for suspicious mouse behavior
            let rapidClickCount = 0;
            let lastClickTime = 0;

            document.addEventListener('click', function(e) {
                const currentTime = Date.now();
                if (currentTime - lastClickTime < 100) { // Less than 100ms between clicks
                    rapidClickCount++;
                    if (rapidClickCount > 10) {
                        logSecurityEvent('suspicious_clicking_pattern');
                    }
                } else {
                    rapidClickCount = 0;
                }
                lastClickTime = currentTime;
            });
        }

        // Enhanced Security Functions
        function initializeEnhancedScreenCaptureProtection() {
            console.log('Initializing enhanced screen capture protection...');

            // Block Print Screen key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'PrintScreen' || e.keyCode === 44) {
                    e.preventDefault();
                    e.stopPropagation();
                    logSecurityEvent('print_screen_blocked');
                    showSecurityAlert('Screenshot attempts are not allowed during the exam.');
                    return false;
                }
            });

            // Enhanced screen recording detection
            if (navigator.mediaDevices) {
                // Override getDisplayMedia more thoroughly
                const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia;
                const originalGetUserMedia = navigator.mediaDevices.getUserMedia;

                navigator.mediaDevices.getDisplayMedia = function() {
                    logSecurityEvent('screen_capture_attempt', { method: 'getDisplayMedia' });
                    showSecurityAlert('Screen recording/sharing is strictly prohibited during the exam.');
                    return Promise.reject(new Error('Screen capture blocked by exam security'));
                };

                navigator.mediaDevices.getUserMedia = function(constraints) {
                    if (constraints && constraints.video && constraints.video.mediaSource === 'screen') {
                        logSecurityEvent('screen_capture_attempt', { method: 'getUserMedia' });
                        showSecurityAlert('Screen recording is not allowed during the exam.');
                        return Promise.reject(new Error('Screen capture blocked'));
                    }
                    return originalGetUserMedia.call(this, constraints);
                };
            }

            // Detect screen recording software
            const suspiciousExtensions = [
                'chrome-extension://nlipoenfbbikpbjkfpfillcgkoblgpmj', // OBS
                'chrome-extension://jjndjgheafjngoipoacpjgeicjeomjli', // Screencastify
                'chrome-extension://mmeijimgabbpbgpdklnllpncmdofkcpn', // Loom
            ];

            suspiciousExtensions.forEach(ext => {
                try {
                    const img = new Image();
                    img.onload = function() {
                        logSecurityEvent('screen_recording_software_detected', { extension: ext });
                        showSecurityAlert('Screen recording software detected. Please disable all recording extensions.');
                    };
                    img.src = ext + '/icon.png';
                } catch (e) {
                    // Extension not present
                }
            });
        }

        function initializeEnhancedDevToolsDetection() {
            console.log('Initializing enhanced developer tools detection...');

            let devToolsOpen = false;
            let threshold = 160;

            // Method 1: Console detection
            let devtools = {
                open: false,
                orientation: null
            };

            const setDevToolsStatus = (status, orientation) => {
                if (status !== devtools.open) {
                    devtools.open = status;
                    devtools.orientation = orientation;
                    if (status) {
                        logSecurityEvent('developer_tools_opened', { orientation: orientation });
                        showSecurityAlert('Developer tools detected! Please close developer tools to continue the exam.');
                    }
                }
            };

            // Method 2: Window size detection
            setInterval(() => {
                if (window.outerHeight - window.innerHeight > threshold ||
                    window.outerWidth - window.innerWidth > threshold) {
                    if (!devToolsOpen) {
                        devToolsOpen = true;
                        setDevToolsStatus(true, 'unknown');
                    }
                } else {
                    devToolsOpen = false;
                }
            }, 500);

            // Method 3: Console.log detection
            let element = new Image();
            Object.defineProperty(element, 'id', {
                get: function() {
                    setDevToolsStatus(true, 'console');
                    throw new Error('Developer tools detected');
                }
            });

            setInterval(() => {
                console.log(element);
                console.clear();
            }, 1000);

            // Method 4: Debugger statement detection
            setInterval(() => {
                const start = performance.now();
                debugger;
                const end = performance.now();
                if (end - start > 100) {
                    setDevToolsStatus(true, 'debugger');
                }
            }, 1000);
        }

        function initializeBrowserExtensionDetection() {
            console.log('Initializing browser extension detection...');

            // Check for common extension indicators
            const checkExtensions = () => {
                // Check for extension-specific DOM modifications
                const suspiciousElements = document.querySelectorAll('[data-extension], [class*="extension"], [id*="extension"]');
                if (suspiciousElements.length > 0) {
                    logSecurityEvent('browser_extension_detected', { count: suspiciousElements.length });
                    showSecurityWarning('Browser extensions detected. Some extensions may interfere with exam security.');
                }

                // Check for extension scripts
                const scripts = document.querySelectorAll('script[src*="extension"], script[src*="chrome-extension"]');
                if (scripts.length > 0) {
                    logSecurityEvent('extension_scripts_detected', { count: scripts.length });
                }

                // Check for modified window properties
                const originalProperties = ['chrome', 'browser', 'moz'];
                originalProperties.forEach(prop => {
                    if (window[prop] && typeof window[prop] === 'object') {
                        logSecurityEvent('browser_api_detected', { property: prop });
                    }
                });
            };

            // Initial check
            checkExtensions();

            // Periodic checks
            setInterval(checkExtensions, 5000);
        }

        function initializeVirtualMachineDetection() {
            console.log('Initializing virtual machine detection...');

            const vmIndicators = [];

            // Check screen resolution (VMs often have specific resolutions)
            const resolution = `${screen.width}x${screen.height}`;
            const commonVMResolutions = ['1024x768', '800x600', '1280x1024', '1366x768'];
            if (commonVMResolutions.includes(resolution)) {
                vmIndicators.push('suspicious_resolution');
            }

            // Check for VM-specific user agents
            const userAgent = navigator.userAgent.toLowerCase();
            const vmKeywords = ['virtualbox', 'vmware', 'qemu', 'xen', 'kvm', 'parallels'];
            vmKeywords.forEach(keyword => {
                if (userAgent.includes(keyword)) {
                    vmIndicators.push('vm_user_agent');
                }
            });

            // Check hardware concurrency (VMs often have limited cores)
            if (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2) {
                vmIndicators.push('limited_cores');
            }

            // Check for VM-specific properties
            if (window.chrome && window.chrome.runtime && window.chrome.runtime.onConnect) {
                // This might indicate a VM environment
                vmIndicators.push('chrome_runtime_detected');
            }

            if (vmIndicators.length >= 2) {
                logSecurityEvent('virtual_machine_detected', { indicators: vmIndicators });
                showSecurityWarning('Virtual machine environment detected. Please take the exam on a physical device if possible.');
            }
        }

        function initializeAdvancedMouseTracking() {
            console.log('Initializing advanced mouse tracking...');

            let mouseData = {
                movements: [],
                clicks: [],
                lastPosition: { x: 0, y: 0 },
                suspiciousPatterns: 0
            };

            document.addEventListener('mousemove', function(e) {
                const currentTime = Date.now();
                const movement = {
                    x: e.clientX,
                    y: e.clientY,
                    timestamp: currentTime
                };

                // Check for unnatural mouse movements (too perfect/robotic)
                if (mouseData.movements.length > 0) {
                    const lastMovement = mouseData.movements[mouseData.movements.length - 1];
                    const distance = Math.sqrt(
                        Math.pow(movement.x - lastMovement.x, 2) +
                        Math.pow(movement.y - lastMovement.y, 2)
                    );
                    const timeDiff = currentTime - lastMovement.timestamp;

                    // Detect suspiciously perfect movements
                    if (distance > 0 && timeDiff > 0) {
                        const speed = distance / timeDiff;
                        if (speed > 5 || (distance > 100 && timeDiff < 10)) {
                            mouseData.suspiciousPatterns++;
                            if (mouseData.suspiciousPatterns > 10) {
                                logSecurityEvent('suspicious_mouse_pattern', {
                                    speed: speed,
                                    distance: distance,
                                    timeDiff: timeDiff
                                });
                            }
                        }
                    }
                }

                mouseData.movements.push(movement);
                if (mouseData.movements.length > 100) {
                    mouseData.movements.shift(); // Keep only last 100 movements
                }
            });

            // Track click patterns
            document.addEventListener('click', function(e) {
                mouseData.clicks.push({
                    x: e.clientX,
                    y: e.clientY,
                    timestamp: Date.now()
                });

                if (mouseData.clicks.length > 50) {
                    mouseData.clicks.shift();
                }
            });
        }

        function initializeKeyboardPatternAnalysis() {
            console.log('Initializing keyboard pattern analysis...');

            let keyboardData = {
                keystrokes: [],
                patterns: [],
                suspiciousActivity: 0
            };

            document.addEventListener('keydown', function(e) {
                const keystroke = {
                    key: e.key,
                    timestamp: Date.now(),
                    ctrlKey: e.ctrlKey,
                    shiftKey: e.shiftKey,
                    altKey: e.altKey
                };

                keyboardData.keystrokes.push(keystroke);

                // Analyze typing patterns
                if (keyboardData.keystrokes.length > 1) {
                    const lastKeystroke = keyboardData.keystrokes[keyboardData.keystrokes.length - 2];
                    const timeDiff = keystroke.timestamp - lastKeystroke.timestamp;

                    // Detect suspiciously fast typing (possible automation)
                    if (timeDiff < 50 && e.key.length === 1) {
                        keyboardData.suspiciousActivity++;
                        if (keyboardData.suspiciousActivity > 20) {
                            logSecurityEvent('suspicious_typing_pattern', {
                                avgTimeDiff: timeDiff,
                                suspiciousCount: keyboardData.suspiciousActivity
                            });
                        }
                    }

                    // Detect copy-paste patterns
                    if (e.ctrlKey && (e.key === 'v' || e.key === 'V')) {
                        logSecurityEvent('paste_attempt_detected');
                        showSecurityWarning('Paste operations are monitored during the exam.');
                    }
                }

                // Keep only last 200 keystrokes
                if (keyboardData.keystrokes.length > 200) {
                    keyboardData.keystrokes.shift();
                }
            });
        }

        function initializeWindowResizeDetection() {
            console.log('Initializing window resize detection...');

            let originalSize = {
                width: window.innerWidth,
                height: window.innerHeight
            };

            window.addEventListener('resize', function() {
                const newSize = {
                    width: window.innerWidth,
                    height: window.innerHeight
                };

                const sizeChange = {
                    widthDiff: Math.abs(newSize.width - originalSize.width),
                    heightDiff: Math.abs(newSize.height - originalSize.height)
                };

                // Log significant size changes
                if (sizeChange.widthDiff > 100 || sizeChange.heightDiff > 100) {
                    logSecurityEvent('window_resize_detected', {
                        originalSize: originalSize,
                        newSize: newSize,
                        sizeChange: sizeChange
                    });

                    // Check if window is no longer fullscreen
                    if (!document.fullscreenElement && (window.innerWidth < screen.width || window.innerHeight < screen.height)) {
                        showSecurityWarning('Window size changed. Please maintain fullscreen mode during the exam.');
                    }
                }

                originalSize = newSize;
            });
        }

        function initializeClipboardMonitoring() {
            console.log('Initializing clipboard monitoring...');

            // Monitor clipboard access attempts
            document.addEventListener('copy', function(e) {
                logSecurityEvent('copy_attempt', {
                    selectedText: window.getSelection().toString().substring(0, 100)
                });
                showSecurityWarning('Copy operations are monitored during the exam.');
            });

            document.addEventListener('cut', function(e) {
                logSecurityEvent('cut_attempt');
                showSecurityWarning('Cut operations are monitored during the exam.');
            });

            document.addEventListener('paste', function(e) {
                logSecurityEvent('paste_attempt');
                showSecurityWarning('Paste operations are monitored during the exam.');
            });

            // Monitor clipboard API usage
            if (navigator.clipboard) {
                const originalReadText = navigator.clipboard.readText;
                const originalWriteText = navigator.clipboard.writeText;

                navigator.clipboard.readText = function() {
                    logSecurityEvent('clipboard_read_attempt');
                    return originalReadText.call(this);
                };

                navigator.clipboard.writeText = function(text) {
                    logSecurityEvent('clipboard_write_attempt', { textLength: text.length });
                    return originalWriteText.call(this, text);
                };
            }
        }

        function logSecurityEvent(eventType, data = {}) {
            const securityEvent = {
                type: eventType,
                timestamp: new Date().toISOString(),
                attemptId: attemptId,
                data: data
            };

            console.log('Security Event:', securityEvent);

            // Send to server for logging
            fetch('<?= base_url('student/logSecurityEvent') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(securityEvent)
            }).catch(error => {
                console.error('Failed to log security event:', error);
            });
        }

        function showSecurityWarning(message) {
            // Create a less intrusive warning
            const warningDiv = document.createElement('div');
            warningDiv.className = 'security-warning';
            warningDiv.innerHTML = `
                <div class="security-warning-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="btn-close-warning">×</button>
                </div>
            `;
            document.body.appendChild(warningDiv);

            // Update security status to warning
            updateSecurityStatus('warning', 'Security Warning');

            // Auto-remove after 8 seconds
            setTimeout(() => {
                if (warningDiv.parentElement) {
                    warningDiv.remove();
                }
                // Reset security status after warning is dismissed
                updateSecurityStatus('active', 'Security Active');
            }, 8000);
        }

        function showSecurityAlert(message) {
            // Create a more prominent security alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'security-alert';
            alertDiv.innerHTML = `
                <div class="security-alert-content">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Security Alert</h4>
                    <p>${message}</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="btn btn-sm btn-outline-light">Dismiss</button>
                </div>
            `;
            document.body.appendChild(alertDiv);

            // Update security status to alert
            updateSecurityStatus('alert', 'Security Alert');

            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
                // Reset security status after alert is dismissed
                updateSecurityStatus('active', 'Security Active');
            }, 10000);
        }

        function updateSecurityStatus(level, message) {
            const statusElement = document.getElementById('securityStatus');
            if (statusElement) {
                // Remove all status classes
                statusElement.classList.remove('active', 'warning', 'alert');

                // Add new status class
                statusElement.classList.add(level);

                // Update message
                const messageElement = statusElement.querySelector('span');
                if (messageElement) {
                    messageElement.textContent = message;
                }

                // Update icon based on level
                const iconElement = statusElement.querySelector('i');
                if (iconElement) {
                    iconElement.className = level === 'alert' ? 'fas fa-exclamation-triangle' :
                                          level === 'warning' ? 'fas fa-shield-alt' :
                                          'fas fa-shield-alt';
                }
            }
        }
        // Enhanced security functions
        function showBlockingModal(title, message, type = 'danger', callback = null) {
            const modal = document.getElementById('securityBlockingModal');
            const modalTitle = document.getElementById('securityBlockingModalTitle');
            const modalBody = document.getElementById('securityBlockingModalBody');
            const modalHeader = document.getElementById('securityBlockingModalHeader');
            const modalFooter = document.getElementById('securityBlockingModalFooter');

            // Set title and message
            modalTitle.textContent = title;
            modalBody.innerHTML = `
                <div class="mb-3">
                    <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'warning'} fa-3x text-${type} mb-3"></i>
                </div>
                <p class="mb-0">${message}</p>
            `;

            // Set header color based on type
            modalHeader.className = `modal-header border-0 bg-${type} text-white`;

            // Set footer buttons based on callback
            if (callback) {
                modalFooter.innerHTML = `
                    <button type="button" class="btn btn-${type}" onclick="handleSecurityAction()">
                        <i class="fas fa-check me-2"></i>Understood
                    </button>
                `;
                window.currentSecurityCallback = callback;
            } else {
                modalFooter.innerHTML = `
                    <button type="button" class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-refresh me-2"></i>Reload Page
                    </button>
                `;
            }

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Disable exam interface
            disableExamInterface();
        }

        function handleSecurityAction() {
            if (window.currentSecurityCallback) {
                window.currentSecurityCallback();
                window.currentSecurityCallback = null;
            }
            bootstrap.Modal.getInstance(document.getElementById('securityBlockingModal')).hide();
        }

        function disableExamInterface() {
            // Disable all form elements
            const formElements = document.querySelectorAll('input, button, select, textarea');
            formElements.forEach(element => {
                if (!element.closest('#securityBlockingModal')) {
                    element.disabled = true;
                }
            });

            // Add overlay to prevent interaction
            if (!document.getElementById('securityOverlay')) {
                const overlay = document.createElement('div');
                overlay.id = 'securityOverlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 1040;
                    pointer-events: none;
                `;
                document.body.appendChild(overlay);
            }
        }

        function checkForMultipleTabs() {
            // Check if there are multiple tabs open
            if (typeof window.performance !== 'undefined' && window.performance.navigation) {
                const tabCount = window.performance.navigation.type;
                if (tabCount > 1) {
                    showBlockingModal(
                        'Multiple Tabs Detected',
                        'Please close all other browser tabs and keep only this exam tab open. Click "Continue" only after closing other tabs.',
                        'warning',
                        () => {
                            // Re-enable interface temporarily
                            enableExamInterface();
                        }
                    );
                }
            }
        }

        function enableExamInterface() {
            // Re-enable form elements
            const formElements = document.querySelectorAll('input, button, select, textarea');
            formElements.forEach(element => {
                element.disabled = false;
            });

            // Remove overlay
            const overlay = document.getElementById('securityOverlay');
            if (overlay) {
                overlay.remove();
            }
        }

        // Enhanced submit function with force parameter
        function submitExam(force = false) {
            if (force) {
                // Force submit without confirmation
                document.getElementById('examForm').submit();
                return;
            }

            // Normal submit process
            const modal = new bootstrap.Modal(document.getElementById('submitConfirmModal'));
            modal.show();
        }
    </script>

    <!-- Security Blocking Modal -->
    <div class="modal fade" id="securityBlockingModal" tabindex="-1" aria-labelledby="securityBlockingModalTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0" id="securityBlockingModalHeader">
                    <h5 class="modal-title fw-bold" id="securityBlockingModalTitle"></h5>
                </div>
                <div class="modal-body text-center py-4" id="securityBlockingModalBody">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer border-0 justify-content-center" id="securityBlockingModalFooter">
                    <!-- Buttons will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitConfirmModalLabel">Submit Exam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit your exam? Once submitted, you cannot make any changes.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="confirmSubmitExam()">Submit Exam</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Warning Modal -->
    <div class="modal fade" id="timeWarningModal" tabindex="-1" aria-labelledby="timeWarningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeWarningModalLabel">Time Warning</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="timeWarningMessage">Warning: Only 5 minutes remaining!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Up Modal -->
    <div class="modal fade" id="timeUpModal" tabindex="-1" aria-labelledby="timeUpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeUpModalLabel">Time's Up!</h5>
                </div>
                <div class="modal-body">
                    <p>Your exam time has expired. Your exam will be submitted automatically.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Alert Modal -->
    <div class="modal fade" id="securityAlertModal" tabindex="-1" aria-labelledby="securityAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="securityAlertModalLabel">
                        <i class="fas fa-shield-alt me-2"></i>Security Alert
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning fa-2x me-3"></i>
                        <p class="mb-0" id="securityAlertMessage">Security violation detected.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                        <i class="fas fa-check me-1"></i>Understood
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for exam submission -->
    <form id="examForm" method="post" action="<?= base_url('student/submitExam') ?>" style="display: none;">
        <input type="hidden" name="attempt_id" value="<?= $attempt['id'] ?>">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    </form>
</body>
</html>
