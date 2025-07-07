<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateExamTypesToMarksSystem extends Migration
{
    public function up()
    {
        // Add new columns for marks-based system
        $this->forge->addColumn('exam_types', [
            'default_total_marks' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
                'comment'    => 'Default total marks for this exam type',
                'after'      => 'description'
            ],
            'is_test' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1 for tests/CAs, 0 for main exams',
                'after'      => 'default_total_marks'
            ],
            'assessment_category' => [
                'type'       => 'ENUM',
                'constraint' => ['continuous_assessment', 'main_examination', 'practice'],
                'default'    => 'continuous_assessment',
                'comment'    => 'Category of assessment',
                'after'      => 'is_test'
            ]
        ]);

        // Update existing exam types with appropriate values
        $examTypes = $this->db->table('exam_types')->get()->getResultArray();
        
        foreach ($examTypes as $examType) {
            $updateData = [];
            
            // Set default marks based on exam type code
            switch (strtoupper($examType['code'])) {
                case 'FIRST_CA':
                case 'SECOND_CA':
                case 'THIRD_CA':
                    $updateData['default_total_marks'] = 30;
                    $updateData['is_test'] = 1;
                    $updateData['assessment_category'] = 'continuous_assessment';
                    break;
                case 'EXAM':
                    $updateData['default_total_marks'] = 70;
                    $updateData['is_test'] = 0;
                    $updateData['assessment_category'] = 'main_examination';
                    break;
                default:
                    // For custom exam types, use weight to determine marks
                    $weight = $examType['weight_percentage'] ?? 0;
                    if ($weight >= 50) {
                        $updateData['default_total_marks'] = 70;
                        $updateData['is_test'] = 0;
                        $updateData['assessment_category'] = 'main_examination';
                    } else {
                        $updateData['default_total_marks'] = 30;
                        $updateData['is_test'] = 1;
                        $updateData['assessment_category'] = 'continuous_assessment';
                    }
                    break;
            }
            
            $this->db->table('exam_types')
                ->where('id', $examType['id'])
                ->update($updateData);
        }

        // Drop the old weight_percentage column
        $this->forge->dropColumn('exam_types', 'weight_percentage');
    }

    public function down()
    {
        // Add back weight_percentage column
        $this->forge->addColumn('exam_types', [
            'weight_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'comment'    => 'Weight in final grade calculation',
                'after'      => 'description'
            ]
        ]);

        // Convert marks back to weights (approximate conversion)
        $examTypes = $this->db->table('exam_types')->get()->getResultArray();
        
        foreach ($examTypes as $examType) {
            $weight = 0;
            
            if ($examType['assessment_category'] === 'main_examination') {
                $weight = 70.00;
            } else {
                $weight = 10.00;
            }
            
            $this->db->table('exam_types')
                ->where('id', $examType['id'])
                ->update(['weight_percentage' => $weight]);
        }

        // Drop new columns
        $this->forge->dropColumn('exam_types', ['default_total_marks', 'is_test', 'assessment_category']);
    }
}
