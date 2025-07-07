<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateExamTypeToReference extends Migration
{
    public function up()
    {
        // First, let's create a mapping of old enum values to new exam type IDs
        $examTypeMapping = [
            'practice' => 1,  // Will be mapped to appropriate exam type
            'quiz' => 2,
            'midterm' => 3,
            'final' => 4,
            'assignment' => 5
        ];

        // Get existing exam types from the exam_types table
        $examTypes = $this->db->table('exam_types')->get()->getResultArray();
        $examTypesByCode = [];
        foreach ($examTypes as $type) {
            $examTypesByCode[strtolower($type['code'])] = $type['id'];
        }

        // Create mapping based on available exam types
        $finalMapping = [];
        if (isset($examTypesByCode['first_ca'])) {
            $finalMapping['quiz'] = $examTypesByCode['first_ca'];
            $finalMapping['midterm'] = $examTypesByCode['second_ca'] ?? $examTypesByCode['first_ca'];
            $finalMapping['final'] = $examTypesByCode['exam'] ?? $examTypesByCode['first_ca'];
            $finalMapping['assignment'] = $examTypesByCode['third_ca'] ?? $examTypesByCode['first_ca'];
            $finalMapping['practice'] = $examTypesByCode['first_ca'];
        } else {
            // Fallback - use first available exam type
            $firstExamType = reset($examTypes);
            $defaultId = $firstExamType ? $firstExamType['id'] : 1;
            $finalMapping = [
                'practice' => $defaultId,
                'quiz' => $defaultId,
                'midterm' => $defaultId,
                'final' => $defaultId,
                'assignment' => $defaultId
            ];
        }

        // Add a temporary column to store the new exam type ID
        $this->forge->addColumn('exams', [
            'exam_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'exam_type'
            ]
        ]);

        // Update existing records to use the new exam type IDs
        foreach ($finalMapping as $oldType => $newId) {
            $this->db->table('exams')
                ->where('exam_type', $oldType)
                ->update(['exam_type_id' => $newId]);
        }

        // Drop the old exam_type column
        $this->forge->dropColumn('exams', 'exam_type');

        // Rename the new column to exam_type
        $this->forge->modifyColumn('exams', [
            'exam_type_id' => [
                'name' => 'exam_type',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ]
        ]);

        // Add foreign key constraint
        $this->forge->addForeignKey('exam_type', 'exam_types', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        // Remove foreign key constraint
        $this->forge->dropForeignKey('exams', 'exams_exam_type_foreign');

        // Add temporary column with old enum structure
        $this->forge->addColumn('exams', [
            'exam_type_enum' => [
                'type' => 'ENUM',
                'constraint' => ['practice', 'quiz', 'midterm', 'final', 'assignment'],
                'default' => 'quiz',
                'after' => 'exam_type'
            ]
        ]);

        // Get exam types for reverse mapping
        $examTypes = $this->db->table('exam_types')->get()->getResultArray();
        $examTypesById = [];
        foreach ($examTypes as $type) {
            $examTypesById[$type['id']] = strtolower($type['code']);
        }

        // Create reverse mapping
        $reverseMapping = [
            'first_ca' => 'quiz',
            'second_ca' => 'midterm', 
            'third_ca' => 'assignment',
            'exam' => 'final'
        ];

        // Update records back to enum values
        foreach ($examTypesById as $id => $code) {
            $enumValue = $reverseMapping[$code] ?? 'quiz';
            $this->db->table('exams')
                ->where('exam_type', $id)
                ->update(['exam_type_enum' => $enumValue]);
        }

        // Drop the INT column
        $this->forge->dropColumn('exams', 'exam_type');

        // Rename enum column back to exam_type
        $this->forge->modifyColumn('exams', [
            'exam_type_enum' => [
                'name' => 'exam_type',
                'type' => 'ENUM',
                'constraint' => ['practice', 'quiz', 'midterm', 'final', 'assignment'],
                'default' => 'quiz'
            ]
        ]);
    }
}
