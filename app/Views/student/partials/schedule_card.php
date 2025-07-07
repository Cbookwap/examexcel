<?php if (!isset($schedule) || !is_array($schedule) || empty($schedule)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Schedule data is not available.
    </div>
<?php else: ?>
<div class="schedule-card">
    <div class="schedule-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="mb-1"><?= esc($schedule['title'] ?? 'Untitled Exam') ?></h5>
                <p class="mb-0 opacity-75">
                    <?= esc($schedule['subject_name'] ?? 'Multi-Subject Exam') ?>
                </p>
            </div>
            <span class="status-badge bg-<?= $schedule['status_class'] ?? 'secondary' ?> text-white">
                <?= ucfirst($schedule['status'] ?? 'unknown') ?>
            </span>
        </div>
    </div>
    
    <div class="schedule-body">
        <!-- Exam Meta Information -->
        <div class="exam-meta">
            <?php if (!empty($schedule['start_time'])): ?>
            <div class="exam-meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span><?= date('M j, Y', strtotime($schedule['start_time'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($schedule['start_time']) && !empty($schedule['end_time'])): ?>
            <div class="exam-meta-item">
                <i class="fas fa-clock"></i>
                <span><?= date('g:i A', strtotime($schedule['start_time'])) ?> - <?= date('g:i A', strtotime($schedule['end_time'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (isset($schedule['duration_minutes'])): ?>
            <div class="exam-meta-item">
                <i class="fas fa-hourglass-half"></i>
                <span><?= $schedule['duration_minutes'] ?> minutes</span>
            </div>
            <?php endif; ?>
            <?php if (isset($schedule['total_marks'])): ?>
            <div class="exam-meta-item">
                <i class="fas fa-star"></i>
                <span><?= $schedule['total_marks'] ?> marks</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Exam Description -->
        <?php if (!empty($schedule['description'])): ?>
            <div class="mb-3">
                <p class="text-muted mb-0"><?= esc($schedule['description']) ?></p>
            </div>
        <?php endif; ?>

        <!-- Attempt Information -->
        <?php if ($schedule['attempt_count'] > 0): ?>
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted">Attempts Made</small>
                        <div class="fw-bold"><?= $schedule['attempt_count'] ?> / <?= $schedule['max_attempts'] ?? '∞' ?></div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Best Score</small>
                        <div class="fw-bold text-success"><?= number_format($schedule['best_score'], 1) ?> marks</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Last Attempt</small>
                        <div class="fw-bold">
                            <?php if ($schedule['latest_attempt']): ?>
                                <?= date('M j, g:i A', strtotime($schedule['latest_attempt']['created_at'])) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="exam-actions">
            <?php if ($schedule['status'] === 'active' && $schedule['can_take']): ?>
                <?php 
                $hasInProgress = false;
                if (!empty($schedule['attempts'])) {
                    foreach ($schedule['attempts'] as $attempt) {
                        if ($attempt['status'] === 'in_progress') {
                            $hasInProgress = true;
                            break;
                        }
                    }
                }
                ?>
                <?php if ($hasInProgress): ?>
                    <a href="<?= base_url('student/takeExam/' . $attempt['id']) ?>" class="btn btn-warning btn-exam">
                        <i class="fas fa-play me-1"></i>Resume Exam
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('student/startExam/' . $schedule['id']) ?>" class="btn btn-success btn-exam">
                        <i class="fas fa-play me-1"></i>Start Exam
                    </a>
                <?php endif; ?>
            <?php elseif ($schedule['status'] === 'upcoming'): ?>
                <button class="btn btn-outline-primary btn-exam" disabled>
                    <i class="fas fa-clock me-1"></i>Starts <?= date('M j, g:i A', strtotime($schedule['start_time'])) ?>
                </button>
            <?php elseif ($schedule['status'] === 'completed' && $schedule['attempt_count'] > 0): ?>
                <a href="<?= base_url('student/examResult/' . $schedule['latest_attempt']['id']) ?>" class="btn btn-outline-info btn-exam">
                    <i class="fas fa-chart-line me-1"></i>View Results
                </a>
            <?php endif; ?>

            <?php if ($schedule['attempt_count'] > 0): ?>
                <button class="btn btn-outline-secondary btn-exam" onclick="showAttemptHistory(<?= $schedule['id'] ?>)">
                    <i class="fas fa-history me-1"></i>Attempt History
                </button>
            <?php endif; ?>

            <button class="btn btn-outline-dark btn-exam" onclick="showExamDetails(<?= $schedule['id'] ?>)">
                <i class="fas fa-info-circle me-1"></i>Details
            </button>
        </div>
    </div>
</div>

<!-- Exam Details Modal -->
<div class="modal fade" id="examDetailsModal<?= $schedule['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exam Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Title:</strong></td>
                                <td><?= esc($schedule['title']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Subject:</strong></td>
                                <td><?= esc($schedule['subject_name'] ?? 'Multi-Subject') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total Marks:</strong></td>
                                <td><?= $schedule['total_marks'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Passing Marks:</strong></td>
                                <td><?= $schedule['passing_marks'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td><?= $schedule['duration_minutes'] ?> minutes</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Schedule</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Start Time:</strong></td>
                                <td><?= date('M j, Y g:i A', strtotime($schedule['start_time'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>End Time:</strong></td>
                                <td><?= date('M j, Y g:i A', strtotime($schedule['end_time'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Max Attempts:</strong></td>
                                <td><?= $schedule['max_attempts'] ?? 'Unlimited' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-<?= $schedule['status_class'] ?>">
                                        <?= ucfirst($schedule['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if (!empty($schedule['description'])): ?>
                    <div class="mt-3">
                        <h6>Description</h6>
                        <p><?= esc($schedule['description']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($schedule['instructions'])): ?>
                    <div class="mt-3">
                        <h6>Instructions</h6>
                        <div><?= $schedule['instructions'] ?></div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Attempt History Modal -->
<div class="modal fade" id="attemptHistoryModal<?= $schedule['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attempt History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($schedule['attempts'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Attempt #</th>
                                    <th>Date & Time</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($schedule['attempts'] as $index => $attempt): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= date('M j, Y g:i A', strtotime($attempt['created_at'])) ?></td>
                                        <td><?= number_format($attempt['marks_obtained'] ?? 0, 1) ?> / <?= $schedule['total_marks'] ?></td>
                                        <td><?= number_format($attempt['percentage'] ?? 0, 1) ?>%</td>
                                        <td>
                                            <span class="badge bg-<?= $attempt['status'] === 'submitted' ? 'success' : ($attempt['status'] === 'in_progress' ? 'warning' : 'secondary') ?>">
                                                <?= ucfirst($attempt['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($attempt['status'] === 'submitted'): ?>
                                                <a href="<?= base_url('student/examResult/' . $attempt['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    View Result
                                                </a>
                                            <?php elseif ($attempt['status'] === 'in_progress'): ?>
                                                <a href="<?= base_url('student/takeExam/' . $attempt['id']) ?>" class="btn btn-sm btn-warning">
                                                    Resume
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No attempts made yet.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showExamDetails(examId) {
    const modal = new bootstrap.Modal(document.getElementById('examDetailsModal' + examId));
    modal.show();
}

function showAttemptHistory(examId) {
    const modal = new bootstrap.Modal(document.getElementById('attemptHistoryModal' + examId));
    modal.show();
}
</script>
<?php endif; ?>
