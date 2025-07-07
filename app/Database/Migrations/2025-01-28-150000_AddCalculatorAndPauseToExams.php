<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCalculatorAndPauseToExams extends Migration
{
    public function up()
    {
        // Add calculator and pause settings to exams table
        $this->forge->addColumn('exams', [
            'calculator_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether calculator is enabled for this exam',
                'after' => 'disable_right_click'
            ],
            'exam_pause_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether students can pause this exam',
                'after' => 'calculator_enabled'
            ]
        ]);
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('exams', ['calculator_enabled', 'exam_pause_enabled']);
    }
}
