<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Preview - ExamExcel</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fonts/material-icons/material-icons.css') ?>" rel="stylesheet">

    <!-- CSS Variables from Theme Config -->
    <style>
        <?php
        $theme = new \App\Config\UITheme();
        echo $theme->getCSSVariables();
        ?>
    </style>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .preview-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .question-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .question-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
        }
        .question-body {
            padding: 2rem;
        }
        .question-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .option-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }
        .option-item.correct {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .option-item:hover {
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }
        .badge-custom {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-type {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
        }
        .badge-difficulty-easy { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
        .badge-difficulty-medium { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
        .badge-difficulty-hard { background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); color: white; }
        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="question-card">
            <div class="question-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-2 text-white">Question Preview</h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge-custom badge-type">
                                <?= esc($question_types[$question['question_type']] ?? $question['question_type']) ?>
                            </span>
                            <span class="badge-custom badge-difficulty-<?= $question['difficulty'] ?>">
                                <?= esc(ucfirst($question['difficulty'])) ?>
                            </span>
                            <span class="badge-custom" style="background: rgba(255,255,255,0.2);">
                                <?= $question['points'] ?> Marks
                            </span>
                        </div>
                    </div>
                    <button onclick="window.close()" class="btn btn-light btn-sm">
                        <i class="material-symbols-rounded">close</i>
                    </button>
                </div>
            </div>

            <div class="question-body">
                <!-- Question Text -->
                <div class="question-text">
                    <?= nl2br(esc($question['question_text'])) ?>
                </div>

                <!-- Options (for MCQ, True/False, etc.) -->
                <?php if (!empty($question['options'])): ?>
                    <div class="options-section">
                        <h6 class="mb-3">
                            <i class="material-symbols-rounded me-2">radio_button_checked</i>
                            Answer Options
                        </h6>
                        <?php foreach ($question['options'] as $index => $option): ?>
                            <div class="option-item <?= $option['is_correct'] ? 'correct' : '' ?>">
                                <div class="d-flex align-items-center">
                                    <span class="me-3 fw-bold"><?= chr(65 + $index) ?>.</span>
                                    <span><?= esc($option['option_text']) ?></span>
                                    <?php if ($option['is_correct']): ?>
                                        <i class="material-symbols-rounded ms-auto text-success">check_circle</i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Additional Information -->
                <?php if (!empty($question['explanation']) || !empty($question['hints'])): ?>
                    <div class="info-section">
                        <?php if (!empty($question['explanation'])): ?>
                            <div class="mb-3">
                                <h6 class="mb-2">
                                    <i class="material-symbols-rounded me-2">info</i>
                                    Explanation
                                </h6>
                                <p class="mb-0"><?= nl2br(esc($question['explanation'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($question['hints'])): ?>
                            <div>
                                <h6 class="mb-2">
                                    <i class="material-symbols-rounded me-2">lightbulb</i>
                                    Hints
                                </h6>
                                <p class="mb-0"><?= nl2br(esc($question['hints'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Question Metadata -->
                <div class="info-section mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Subject:</strong> <?= esc($question['subject_name'] ?? 'N/A') ?>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Class:</strong>
                                <?php if (!empty($question['class_name'])): ?>
                                    <?= esc($question['class_name']) ?>
                                    <?php if (!empty($question['class_section'])): ?>
                                        - <?= esc($question['class_section']) ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </small>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted">
                                <strong>Created By:</strong>
                                <?php if (!empty($question['first_name']) || !empty($question['last_name'])): ?>
                                    <?= esc(trim($question['first_name'] . ' ' . $question['last_name'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </small>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted">
                                <strong>Created:</strong> <?= date('M j, Y', strtotime($question['created_at'])) ?>
                            </small>
                        </div>
                        <?php if (!empty($question['session_name']) || !empty($question['term_name'])): ?>
                            <div class="col-md-6 mt-2">
                                <small class="text-muted">
                                    <strong>Session/Term:</strong>
                                    <?= esc($question['session_name'] ?? 'N/A') ?> / <?= esc($question['term_name'] ?? 'N/A') ?>
                                </small>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($question['time_limit'])): ?>
                            <div class="col-md-6 mt-2">
                                <small class="text-muted">
                                    <strong>Time Limit:</strong> <?= $question['time_limit'] ?> seconds
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <button onclick="window.print()" class="btn btn-outline-primary me-2">
                <i class="material-symbols-rounded me-2">print</i>Print
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2">close</i>Close
            </button>
        </div>
    </div>

    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
