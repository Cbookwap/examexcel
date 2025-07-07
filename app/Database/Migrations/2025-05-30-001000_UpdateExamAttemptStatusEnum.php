<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateExamAttemptStatusEnum extends Migration
{
    public function up()
    {
        // Update the status ENUM to match the application constants
        $this->db->query("ALTER TABLE exam_attempts MODIFY COLUMN status ENUM('in_progress', 'submitted', 'auto_submitted', 'completed', 'terminated') DEFAULT 'in_progress'");
    }

    public function down()
    {
        // Revert to original ENUM values
        $this->db->query("ALTER TABLE exam_attempts MODIFY COLUMN status ENUM('in_progress', 'completed', 'submitted', 'timed_out', 'cancelled') DEFAULT 'in_progress'");
    }
}
