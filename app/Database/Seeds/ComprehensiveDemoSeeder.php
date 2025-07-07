<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ComprehensiveDemoSeeder extends Seeder
{
    public function run()
    {
        // Get current academic session and term
        $session = $this->db->table('academic_sessions')->where('is_current', 1)->get()->getRowArray();
        $term = $this->db->table('academic_terms')->where('is_current', 1)->get()->getRowArray();
        
        if (!$session || !$term) {
            echo "Please run AcademicStructureSeeder first to create academic sessions and terms.\n";
            return;
        }

        $sessionId = $session['id'];
        $termId = $term['id'];

        // 1. Create Users (Students, Teachers, Principal, Vice Principal)
        $this->createUsers();
        
        // 2. Create Subject Categories
        $this->createSubjectCategories();
        
        // 3. Create Subjects for Senior Classes (SS)
        $this->createSeniorSubjects();
        
        // 4. Create Classes (JSS 1 to SS 3)
        $this->createClasses();
        
        // 5. Assign subjects to classes
        $this->assignSubjectsToClasses();
        
        // 6. Assign teachers to subjects
        $this->assignTeachersToSubjects($sessionId);
        
        // 7. Create Question Bank (5 questions per subject)
        $this->createQuestionBank($sessionId, $termId);
        
        // 8. Create Exams (single and multi-subject)
        $this->createExams($sessionId, $termId);

        echo "Comprehensive demo data seeded successfully!\n";
    }

    private function createUsers()
    {
        // Clear existing demo users (keep admin)
        $this->db->table('users')->where('id >', 1)->delete();

        $users = [];
        
        // Principal (1)
        $users[] = [
            'username' => 'principal',
            'email' => 'principal@school.edu',
            'password' => password_hash('principal123', PASSWORD_DEFAULT),
            'first_name' => 'Dr. Margaret',
            'last_name' => 'Adebayo',
            'role' => 'principal',
            'title' => 'Principal',
            'phone' => '+234-801-234-5678',
            'date_of_birth' => '1970-03-15',
            'gender' => 'female',
            'employee_id' => 'PRIN001',
            'qualification' => 'Ph.D in Educational Administration',
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Vice Principal (1)
        $users[] = [
            'username' => 'viceprincipal',
            'email' => 'vp@school.edu',
            'password' => password_hash('vp123', PASSWORD_DEFAULT),
            'first_name' => 'Mr. Ibrahim',
            'last_name' => 'Musa',
            'role' => 'principal',
            'title' => 'Vice Principal',
            'phone' => '+234-802-345-6789',
            'date_of_birth' => '1975-07-22',
            'gender' => 'male',
            'employee_id' => 'VP001',
            'qualification' => 'M.Ed in Educational Management',
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Teachers (5)
        $teacherData = [
            ['Amina', 'Hassan', 'Mathematics & Physics', 'M.Sc Mathematics', 'female', '1985-04-10'],
            ['John', 'Okafor', 'English & Literature', 'B.A English Language', 'male', '1982-11-28'],
            ['Fatima', 'Bello', 'Chemistry & Biology', 'M.Sc Chemistry', 'female', '1987-09-14'],
            ['David', 'Adeyemi', 'Geography & Economics', 'B.Sc Geography', 'male', '1984-06-03'],
            ['Grace', 'Okoro', 'Government & History', 'B.A Political Science', 'female', '1986-12-18']
        ];

        foreach ($teacherData as $i => $teacher) {
            $users[] = [
                'username' => strtolower($teacher[0] . $teacher[1]),
                'email' => strtolower($teacher[0] . '.' . $teacher[1]) . '@school.edu',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'first_name' => $teacher[0],
                'last_name' => $teacher[1],
                'role' => 'teacher',
                'title' => 'Teacher',
                'phone' => '+234-80' . (3 + $i) . '-456-789' . $i,
                'date_of_birth' => $teacher[5],
                'gender' => $teacher[4],
                'employee_id' => 'TCH' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'department' => $teacher[2],
                'qualification' => $teacher[3],
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Students (10) - distributed across different classes
        $studentNames = [
            ['Kemi', 'Adebayo', 'female', '2008-03-12'],
            ['Chidi', 'Okonkwo', 'male', '2007-08-25'],
            ['Aisha', 'Yusuf', 'female', '2008-01-18'],
            ['Emeka', 'Nwankwo', 'male', '2007-11-07'],
            ['Zainab', 'Ibrahim', 'female', '2008-05-30'],
            ['Tunde', 'Ogundipe', 'male', '2007-09-14'],
            ['Blessing', 'Eze', 'female', '2008-02-28'],
            ['Ahmed', 'Lawal', 'male', '2007-12-03'],
            ['Chioma', 'Okeke', 'female', '2008-07-16'],
            ['Segun', 'Afolabi', 'male', '2007-10-22']
        ];

        foreach ($studentNames as $i => $student) {
            $users[] = [
                'username' => strtolower($student[0] . $student[1]),
                'email' => strtolower($student[0] . '.' . $student[1]) . '@student.school.edu',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => $student[0],
                'last_name' => $student[1],
                'role' => 'student',
                'phone' => '+234-81' . $i . '-123-456' . $i,
                'date_of_birth' => $student[3],
                'gender' => $student[2],
                'student_id' => 'STU' . date('Y') . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'class_id' => ($i % 6) + 8, // Distribute across JSS 1 to SS 3 (class IDs 8-13)
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->table('users')->insertBatch($users);
        echo "Created " . count($users) . " users (1 Principal, 1 Vice Principal, 5 Teachers, 10 Students)\n";
    }

    private function createSubjectCategories()
    {
        $categories = [
            ['name' => 'Core Subjects', 'description' => 'Compulsory subjects for all students', 'color' => '#007bff'],
            ['name' => 'Science Subjects', 'description' => 'Science stream subjects', 'color' => '#28a745'],
            ['name' => 'Arts Subjects', 'description' => 'Arts stream subjects', 'color' => '#dc3545'],
            ['name' => 'Commercial Subjects', 'description' => 'Commercial stream subjects', 'color' => '#ffc107'],
            ['name' => 'Language Subjects', 'description' => 'Language subjects', 'color' => '#6f42c1'],
            ['name' => 'Vocational Subjects', 'description' => 'Vocational and technical subjects', 'color' => '#fd7e14']
        ];

        foreach ($categories as &$category) {
            $category['is_active'] = 1;
            $category['created_at'] = date('Y-m-d H:i:s');
            $category['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('subject_categories')->insertBatch($categories);
        echo "Created " . count($categories) . " subject categories\n";
    }

    private function createSeniorSubjects()
    {
        // Clear existing subjects and add comprehensive senior subjects
        $this->db->table('subjects')->truncate();
        
        $subjects = [
            // Core Subjects (for all SS classes)
            ['name' => 'Mathematics', 'code' => 'MATH', 'description' => 'General Mathematics', 'category' => 'Core Subjects'],
            ['name' => 'English Language', 'code' => 'ENG', 'description' => 'English Language', 'category' => 'Core Subjects'],
            ['name' => 'Civic Education', 'code' => 'CIV', 'description' => 'Civic Education', 'category' => 'Core Subjects'],
            
            // Science Subjects
            ['name' => 'Physics', 'code' => 'PHY', 'description' => 'Physics', 'category' => 'Science Subjects'],
            ['name' => 'Chemistry', 'code' => 'CHE', 'description' => 'Chemistry', 'category' => 'Science Subjects'],
            ['name' => 'Biology', 'code' => 'BIO', 'description' => 'Biology', 'category' => 'Science Subjects'],
            ['name' => 'Further Mathematics', 'code' => 'FMATH', 'description' => 'Further Mathematics', 'category' => 'Science Subjects'],
            
            // Arts Subjects
            ['name' => 'Literature in English', 'code' => 'LIT', 'description' => 'Literature in English', 'category' => 'Arts Subjects'],
            ['name' => 'Government', 'code' => 'GOV', 'description' => 'Government', 'category' => 'Arts Subjects'],
            ['name' => 'History', 'code' => 'HIS', 'description' => 'History', 'category' => 'Arts Subjects'],
            ['name' => 'Geography', 'code' => 'GEO', 'description' => 'Geography', 'category' => 'Arts Subjects'],
            ['name' => 'Christian Religious Studies', 'code' => 'CRS', 'description' => 'Christian Religious Studies', 'category' => 'Arts Subjects'],
            
            // Commercial Subjects
            ['name' => 'Economics', 'code' => 'ECO', 'description' => 'Economics', 'category' => 'Commercial Subjects'],
            ['name' => 'Commerce', 'code' => 'COM', 'description' => 'Commerce', 'category' => 'Commercial Subjects'],
            ['name' => 'Accounting', 'code' => 'ACC', 'description' => 'Financial Accounting', 'category' => 'Commercial Subjects'],
            ['name' => 'Business Studies', 'code' => 'BUS', 'description' => 'Business Studies', 'category' => 'Commercial Subjects']
        ];

        foreach ($subjects as &$subject) {
            $subject['is_active'] = 1;
            $subject['created_at'] = date('Y-m-d H:i:s');
            $subject['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('subjects')->insertBatch($subjects);
        echo "Created " . count($subjects) . " subjects for senior classes\n";
    }

    private function createClasses()
    {
        // Clear existing classes and create JSS 1 to SS 3
        $this->db->table('classes')->truncate();
        
        $classes = [
            // Junior Secondary School
            ['name' => 'JSS 1', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 1', 'max_students' => 35],
            ['name' => 'JSS 2', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 2', 'max_students' => 35],
            ['name' => 'JSS 3', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 3', 'max_students' => 35],
            
            // Senior Secondary School
            ['name' => 'SS 1', 'section' => 'Science', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 1 Science Class', 'max_students' => 40],
            ['name' => 'SS 1', 'section' => 'Arts', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 1 Arts Class', 'max_students' => 40],
            ['name' => 'SS 1', 'section' => 'Commercial', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 1 Commercial Class', 'max_students' => 40],
            ['name' => 'SS 2', 'section' => 'Science', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 2 Science Class', 'max_students' => 40],
            ['name' => 'SS 2', 'section' => 'Arts', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 2 Arts Class', 'max_students' => 40],
            ['name' => 'SS 2', 'section' => 'Commercial', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 2 Commercial Class', 'max_students' => 40],
            ['name' => 'SS 3', 'section' => 'Science', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 3 Science Class', 'max_students' => 40],
            ['name' => 'SS 3', 'section' => 'Arts', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 3 Arts Class', 'max_students' => 40],
            ['name' => 'SS 3', 'section' => 'Commercial', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary 3 Commercial Class', 'max_students' => 40]
        ];

        foreach ($classes as &$class) {
            $class['is_active'] = 1;
            $class['created_at'] = date('Y-m-d H:i:s');
            $class['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('classes')->insertBatch($classes);
        echo "Created " . count($classes) . " classes (JSS 1 to SS 3)\n";
    }

    private function assignSubjectsToClasses()
    {
        // Get all subjects and classes
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        $classes = $this->db->table('classes')->get()->getResultArray();

        $assignments = [];

        foreach ($classes as $class) {
            $className = $class['name'];
            $section = $class['section'];

            foreach ($subjects as $subject) {
                $subjectCategory = $subject['category'];

                // Assign subjects based on class and section
                $shouldAssign = false;

                if ($subjectCategory === 'Core Subjects') {
                    // Core subjects for all classes
                    $shouldAssign = true;
                } elseif (strpos($className, 'SS') !== false) {
                    // Senior secondary subjects
                    if ($section === 'Science' && $subjectCategory === 'Science Subjects') {
                        $shouldAssign = true;
                    } elseif ($section === 'Arts' && $subjectCategory === 'Arts Subjects') {
                        $shouldAssign = true;
                    } elseif ($section === 'Commercial' && $subjectCategory === 'Commercial Subjects') {
                        $shouldAssign = true;
                    }
                } elseif (strpos($className, 'JSS') !== false) {
                    // JSS classes get core subjects and some general subjects
                    if (in_array($subjectCategory, ['Core Subjects', 'Science Subjects', 'Arts Subjects'])) {
                        $shouldAssign = true;
                    }
                }

                if ($shouldAssign) {
                    $assignments[] = [
                        'subject_id' => $subject['id'],
                        'class_id' => $class['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        $this->db->table('subject_classes')->insertBatch($assignments);
        echo "Created " . count($assignments) . " subject-class assignments\n";
    }

    private function assignTeachersToSubjects($sessionId)
    {
        // Get teachers and subjects
        $teachers = $this->db->table('users')->where('role', 'teacher')->get()->getResultArray();
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        $classes = $this->db->table('classes')->get()->getResultArray();

        $assignments = [];

        // Teacher specializations based on their departments
        $teacherSubjects = [
            'aminahassan' => ['Mathematics', 'Physics', 'Further Mathematics'],
            'johnokafor' => ['English Language', 'Literature in English'],
            'fatimabello' => ['Chemistry', 'Biology'],
            'davidadeyemi' => ['Geography', 'Economics'],
            'graceokoro' => ['Government', 'History', 'Civic Education']
        ];

        foreach ($teachers as $teacher) {
            $username = $teacher['username'];
            if (isset($teacherSubjects[$username])) {
                $teacherSubjectNames = $teacherSubjects[$username];

                foreach ($subjects as $subject) {
                    if (in_array($subject['name'], $teacherSubjectNames)) {
                        // Assign this teacher to this subject for all relevant classes
                        foreach ($classes as $class) {
                            // Check if this subject is assigned to this class
                            $subjectClassExists = $this->db->table('subject_classes')
                                ->where('subject_id', $subject['id'])
                                ->where('class_id', $class['id'])
                                ->get()->getRowArray();

                            if ($subjectClassExists) {
                                $assignments[] = [
                                    'teacher_id' => $teacher['id'],
                                    'subject_id' => $subject['id'],
                                    'class_id' => $class['id'],
                                    'session_id' => $sessionId,
                                    'is_active' => 1,
                                    'assigned_by' => 1, // Admin user
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ];
                            }
                        }
                    }
                }
            }
        }

        $this->db->table('teacher_subject_assignments')->insertBatch($assignments);
        echo "Created " . count($assignments) . " teacher-subject assignments\n";
    }

    private function createQuestionBank($sessionId, $termId)
    {
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        $classes = $this->db->table('classes')->get()->getResultArray();
        $examTypes = $this->db->table('exam_types')->get()->getResultArray();

        $questions = [];
        $questionOptions = [];
        $questionId = 1;

        // Create questions for all major subjects
        $keySubjects = ['Mathematics', 'English Language', 'Physics', 'Chemistry', 'Biology',
                       'Government', 'Literature in English', 'Geography', 'Economics', 'Commerce', 'Accounting'];

        foreach ($subjects as $subject) {
            // Create questions for key subjects only
            if (in_array($subject['name'], $keySubjects)) {
                // Create 5 questions per subject
                for ($i = 1; $i <= 5; $i++) {
                    $difficulty = ['easy', 'medium', 'hard'][($i - 1) % 3];
                    $points = $difficulty === 'easy' ? 2 : ($difficulty === 'medium' ? 3 : 5);

                    $questionText = $this->generateQuestionText($subject['name'], $i);

                    $questions[] = [
                        'id' => $questionId,
                        'subject_id' => $subject['id'],
                        'class_id' => $classes[array_rand($classes)]['id'], // Random class
                        'session_id' => $sessionId,
                        'term_id' => $termId,
                        'exam_type_id' => $examTypes[array_rand($examTypes)]['id'],
                        'question_text' => $questionText,
                        'question_type' => 'mcq',
                        'difficulty' => $difficulty,
                        'points' => $points,
                        'explanation' => 'This question tests understanding of ' . $subject['name'] . ' concepts.',
                        'is_active' => 1,
                        'created_by' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    // Create 4 options for each MCQ
                    $options = $this->generateQuestionOptions($subject['name'], $i);
                    foreach ($options as $index => $option) {
                        $questionOptions[] = [
                            'question_id' => $questionId,
                            'option_text' => $option,
                            'is_correct' => $index === 0 ? 1 : 0, // First option is correct
                            'order_index' => $index + 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }

                    $questionId++;
                }
            }
        }

        $this->db->table('questions')->insertBatch($questions);
        $this->db->table('question_options')->insertBatch($questionOptions);

        echo "Created " . count($questions) . " questions with " . count($questionOptions) . " options\n";
    }

    private function createExams($sessionId, $termId)
    {
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        $classes = $this->db->table('classes')->get()->getResultArray();
        $examTypes = $this->db->table('exam_types')->where('code', 'TERMINAL_EXAM')->get()->getRowArray();

        $exams = [];
        $examSubjects = [];
        $examQuestions = [];
        $examId = 1;

        // Create single-subject exams for each class
        foreach ($classes as $class) {
            // Get subjects for this class
            $classSubjects = $this->db->table('subject_classes sc')
                ->join('subjects s', 's.id = sc.subject_id')
                ->where('sc.class_id', $class['id'])
                ->get()->getResultArray();

            foreach ($classSubjects as $subject) {
                $exams[] = [
                    'id' => $examId,
                    'title' => $subject['name'] . ' - ' . $class['name'] . ' ' . $class['section'] . ' Terminal Exam',
                    'description' => 'Terminal examination for ' . $subject['name'] . ' - ' . $class['name'] . ' ' . $class['section'],
                    'subject_id' => $subject['id'],
                    'class_id' => $class['id'],
                    'exam_mode' => 'single_subject',
                    'session_id' => $sessionId,
                    'term_id' => $termId,
                    'exam_type' => $examTypes['id'],
                    'status' => 'published',
                    'duration_minutes' => 90,
                    'total_marks' => 50,
                    'passing_marks' => 25,
                    'question_count' => 5,
                    'total_questions' => 5,
                    'questions_configured' => 1,
                    'randomize_questions' => 1,
                    'randomize_options' => 1,
                    'show_result_immediately' => 0,
                    'allow_review' => 1,
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+7 days')),
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Add questions to this exam
                $subjectQuestions = $this->db->table('questions')
                    ->where('subject_id', $subject['id'])
                    ->limit(5)
                    ->get()->getResultArray();

                foreach ($subjectQuestions as $index => $question) {
                    $examQuestions[] = [
                        'exam_id' => $examId,
                        'question_id' => $question['id'],
                        'subject_id' => $subject['id'],
                        'order_index' => $index + 1,
                        'subject_order' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                $examId++;
            }
        }

        // Create multi-subject exams for SS classes (Science, Arts, Commercial)
        $ssClasses = array_filter($classes, function($class) {
            return strpos($class['name'], 'SS') !== false;
        });

        foreach ($ssClasses as $class) {
            $section = $class['section'];
            $subjectsForExam = [];

            // Define subjects for each stream (Core + Stream-specific)
            if ($section === 'Science') {
                // Core subjects + Science subjects
                $subjectsForExam = ['Mathematics', 'English Language', 'Physics', 'Chemistry', 'Biology'];
            } elseif ($section === 'Arts') {
                // Core subjects + Arts subjects
                $subjectsForExam = ['Mathematics', 'English Language', 'Government', 'Literature in English', 'Geography'];
            } elseif ($section === 'Commercial') {
                // Core subjects + Commercial subjects
                $subjectsForExam = ['Mathematics', 'English Language', 'Economics', 'Commerce', 'Accounting'];
            }

            if (!empty($subjectsForExam)) {
                // Get the actual subject records
                $classSubjects = [];
                foreach ($subjectsForExam as $subjectName) {
                    $subject = $this->db->table('subjects')
                        ->where('name', $subjectName)
                        ->get()->getRowArray();
                    if ($subject) {
                        $classSubjects[] = $subject;
                    }
                }

                if (count($classSubjects) >= 5) {
                    $exams[] = [
                        'id' => $examId,
                        'title' => $class['name'] . ' ' . $class['section'] . ' Multi-Subject Terminal Exam',
                        'description' => 'Multi-subject terminal examination for ' . $class['name'] . ' ' . $class['section'] . ' combining core and ' . strtolower($section) . ' subjects',
                        'class_id' => $class['id'],
                        'exam_mode' => 'multi_subject',
                        'session_id' => $sessionId,
                        'term_id' => $termId,
                        'exam_type' => $examTypes['id'],
                        'status' => 'published',
                        'duration_minutes' => 250, // 50 minutes per subject
                        'total_marks' => 250, // 50 marks per subject
                        'passing_marks' => 125,
                        'question_count' => 25, // 5 questions per subject
                        'total_questions' => 25,
                        'questions_configured' => 1,
                        'randomize_questions' => 1,
                        'randomize_options' => 1,
                        'show_result_immediately' => 0,
                        'allow_review' => 1,
                        'start_date' => date('Y-m-d'),
                        'end_date' => date('Y-m-d', strtotime('+7 days')),
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'created_by' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    // Add exam subjects
                    foreach ($classSubjects as $index => $subject) {
                        $examSubjects[] = [
                            'exam_id' => $examId,
                            'subject_id' => $subject['id'],
                            'question_count' => 5,
                            'total_marks' => 50,
                            'time_allocation' => 50,
                            'subject_order' => $index + 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        // Add questions for this subject
                        $subjectQuestions = $this->db->table('questions')
                            ->where('subject_id', $subject['id'])
                            ->limit(5)
                            ->get()->getResultArray();

                        foreach ($subjectQuestions as $qIndex => $question) {
                            $examQuestions[] = [
                                'exam_id' => $examId,
                                'question_id' => $question['id'],
                                'subject_id' => $subject['id'],
                                'order_index' => ($index * 5) + $qIndex + 1,
                                'subject_order' => $qIndex + 1,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                        }
                    }

                    $examId++;
                }
            }
        }

        $this->db->table('exams')->insertBatch($exams);
        if (!empty($examSubjects)) {
            $this->db->table('exam_subjects')->insertBatch($examSubjects);
        }
        $this->db->table('exam_questions')->insertBatch($examQuestions);

        echo "Created " . count($exams) . " exams (" . (count($exams) - count($ssClasses)) . " single-subject, " . count($ssClasses) . " multi-subject)\n";
    }

    private function generateQuestionText($subjectName, $questionNumber)
    {
        $questions = [
            'Mathematics' => [
                'What is the value of x in the equation 2x + 5 = 15?',
                'If a triangle has angles of 60° and 80°, what is the third angle?',
                'What is the area of a rectangle with length 8cm and width 5cm?',
                'Solve for y: 3y - 7 = 14',
                'What is 25% of 80?'
            ],
            'English Language' => [
                'Which of the following is a noun?',
                'Identify the correct past tense of "go".',
                'What is the plural form of "child"?',
                'Choose the correct preposition: "The book is ___ the table."',
                'Which sentence is grammatically correct?'
            ],
            'Physics' => [
                'What is the SI unit of force?',
                'Which law states that "for every action, there is an equal and opposite reaction"?',
                'What is the speed of light in vacuum?',
                'Which of the following is a vector quantity?',
                'What happens to the resistance of a conductor when its temperature increases?'
            ],
            'Chemistry' => [
                'What is the chemical symbol for Gold?',
                'How many electrons does a neutral carbon atom have?',
                'Which gas is most abundant in Earth\'s atmosphere?',
                'What is the pH of pure water at 25°C?',
                'Which of the following is an alkali metal?'
            ],
            'Biology' => [
                'What is the powerhouse of the cell?',
                'Which blood type is known as the universal donor?',
                'What is the process by which plants make their own food?',
                'How many chambers does a human heart have?',
                'Which organ produces insulin in the human body?'
            ],
            'Government' => [
                'What is the highest court in Nigeria?',
                'How many states are there in Nigeria?',
                'What does the acronym "INEC" stand for?',
                'Which arm of government is responsible for making laws?',
                'What is the term of office for the President of Nigeria?'
            ],
            'Economics' => [
                'What is the basic economic problem?',
                'Which of the following is a factor of production?',
                'What does GDP stand for?',
                'What is inflation?',
                'Which market structure has only one seller?'
            ],
            'Geography' => [
                'What is the largest continent by area?',
                'Which river is the longest in the world?',
                'What causes earthquakes?',
                'Which layer of the atmosphere contains the ozone layer?',
                'What is the capital of Australia?'
            ],
            'Literature in English' => [
                'Who wrote the novel "Things Fall Apart"?',
                'What is a sonnet?',
                'Which literary device involves giving human characteristics to non-human things?',
                'What is the main theme of "The Lion and the Jewel"?',
                'Who is the author of "Purple Hibiscus"?'
            ],
            'Commerce' => [
                'What is the primary function of a commercial bank?',
                'Which document is used to request goods from a supplier?',
                'What does FOB stand for in international trade?',
                'What is the difference between wholesale and retail trade?',
                'Which type of insurance covers goods in transit?'
            ],
            'Accounting' => [
                'What is the accounting equation?',
                'Which side of the trial balance shows assets?',
                'What is depreciation?',
                'Which book of account records all cash transactions?',
                'What is the difference between capital and revenue expenditure?'
            ]
        ];

        $defaultQuestions = [
            'What is the main concept discussed in this topic?',
            'Which of the following best describes the subject matter?',
            'What is the most important principle to remember?',
            'Which statement is most accurate about this subject?',
            'What is the key takeaway from this lesson?'
        ];

        if (isset($questions[$subjectName])) {
            return $questions[$subjectName][$questionNumber - 1] ?? $defaultQuestions[$questionNumber - 1];
        }

        return $defaultQuestions[$questionNumber - 1];
    }

    private function generateQuestionOptions($subjectName, $questionNumber)
    {
        $options = [
            'Mathematics' => [
                ['5', '10', '7', '3'],
                ['40°', '50°', '30°', '45°'],
                ['40 cm²', '30 cm²', '35 cm²', '25 cm²'],
                ['7', '5', '6', '8'],
                ['20', '25', '15', '30']
            ],
            'English Language' => [
                ['Book', 'Run', 'Beautiful', 'Quickly'],
                ['Went', 'Gone', 'Going', 'Goes'],
                ['Children', 'Childs', 'Childrens', 'Child'],
                ['On', 'In', 'At', 'Under'],
                ['She is going to school', 'She are going to school', 'She going to school', 'She go to school']
            ],
            'Physics' => [
                ['Newton', 'Joule', 'Watt', 'Pascal'],
                ['Newton\'s Third Law', 'Newton\'s First Law', 'Newton\'s Second Law', 'Law of Gravitation'],
                ['3 × 10⁸ m/s', '3 × 10⁶ m/s', '3 × 10⁷ m/s', '3 × 10⁹ m/s'],
                ['Velocity', 'Speed', 'Distance', 'Time'],
                ['Increases', 'Decreases', 'Remains constant', 'Becomes zero']
            ],
            'Chemistry' => [
                ['Au', 'Go', 'Gd', 'Ag'],
                ['6', '4', '8', '12'],
                ['Nitrogen', 'Oxygen', 'Carbon dioxide', 'Argon'],
                ['7', '6', '8', '9'],
                ['Sodium', 'Iron', 'Copper', 'Zinc']
            ],
            'Biology' => [
                ['Mitochondria', 'Nucleus', 'Ribosome', 'Chloroplast'],
                ['O', 'AB', 'A', 'B'],
                ['Photosynthesis', 'Respiration', 'Transpiration', 'Digestion'],
                ['4', '2', '3', '5'],
                ['Pancreas', 'Liver', 'Kidney', 'Heart']
            ],
            'Government' => [
                ['Supreme Court', 'High Court', 'Court of Appeal', 'Federal High Court'],
                ['36', '37', '35', '38'],
                ['Independent National Electoral Commission', 'International Nigerian Electoral Commission', 'Independent Nigerian Electoral Committee', 'Internal National Electoral Commission'],
                ['Legislature', 'Executive', 'Judiciary', 'Civil Service'],
                ['4 years', '5 years', '6 years', '3 years']
            ],
            'Economics' => [
                ['Scarcity', 'Abundance', 'Surplus', 'Deficit'],
                ['Land', 'Money', 'Goods', 'Services'],
                ['Gross Domestic Product', 'General Development Program', 'Government Development Plan', 'Gross Development Product'],
                ['General rise in prices', 'Fall in prices', 'Stable prices', 'Price control'],
                ['Monopoly', 'Oligopoly', 'Perfect competition', 'Monopolistic competition']
            ],
            'Geography' => [
                ['Asia', 'Africa', 'North America', 'Europe'],
                ['Nile', 'Amazon', 'Mississippi', 'Yangtze'],
                ['Tectonic plate movement', 'Wind erosion', 'Ocean currents', 'Solar radiation'],
                ['Stratosphere', 'Troposphere', 'Mesosphere', 'Thermosphere'],
                ['Canberra', 'Sydney', 'Melbourne', 'Perth']
            ],
            'Literature in English' => [
                ['Chinua Achebe', 'Wole Soyinka', 'Chimamanda Adichie', 'Buchi Emecheta'],
                ['A 14-line poem', 'A type of novel', 'A dramatic play', 'A short story'],
                ['Personification', 'Metaphor', 'Simile', 'Alliteration'],
                ['Tradition vs Modernity', 'Love and Romance', 'War and Peace', 'Education'],
                ['Chimamanda Ngozi Adichie', 'Chinua Achebe', 'Wole Soyinka', 'Buchi Emecheta']
            ],
            'Commerce' => [
                ['Accept deposits and lend money', 'Sell goods', 'Manufacture products', 'Provide insurance'],
                ['Purchase Order', 'Invoice', 'Receipt', 'Delivery Note'],
                ['Free on Board', 'Freight on Board', 'Full of Business', 'Forward on Board'],
                ['Wholesale is bulk, retail is small quantities', 'No difference', 'Wholesale is expensive', 'Retail is for businesses only'],
                ['Marine Insurance', 'Life Insurance', 'Fire Insurance', 'Motor Insurance']
            ],
            'Accounting' => [
                ['Assets = Liabilities + Capital', 'Assets = Capital - Liabilities', 'Liabilities = Assets + Capital', 'Capital = Assets + Liabilities'],
                ['Debit side', 'Credit side', 'Both sides', 'Neither side'],
                ['Decrease in asset value over time', 'Increase in asset value', 'Purchase of assets', 'Sale of assets'],
                ['Cash Book', 'Sales Book', 'Purchase Book', 'General Journal'],
                ['Capital is for long-term, revenue is for short-term', 'No difference', 'Capital is smaller', 'Revenue is for assets only']
            ]
        ];

        $defaultOptions = [
            ['Option A', 'Option B', 'Option C', 'Option D'],
            ['Choice 1', 'Choice 2', 'Choice 3', 'Choice 4'],
            ['Answer A', 'Answer B', 'Answer C', 'Answer D'],
            ['Selection 1', 'Selection 2', 'Selection 3', 'Selection 4'],
            ['Alternative A', 'Alternative B', 'Alternative C', 'Alternative D']
        ];

        if (isset($options[$subjectName])) {
            return $options[$subjectName][$questionNumber - 1] ?? $defaultOptions[$questionNumber - 1];
        }

        return $defaultOptions[$questionNumber - 1];
    }
}
