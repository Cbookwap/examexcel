<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuestionBankSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Clear existing questions first
        echo "Clearing existing questions...\n";
        $this->db->table('question_options')->truncate();
        $this->db->table('questions')->truncate();

        // Get current session and term
        $currentSession = $this->db->table('academic_sessions')->where('is_current', 1)->get()->getRowArray();
        $currentTerm = $this->db->table('academic_terms')->where('is_current', 1)->get()->getRowArray();

        if (!$currentSession || !$currentTerm) {
            echo "Error: No current session or term found. Please run AcademicStructureSeeder first.\n";
            return;
        }

        $sessionId = $currentSession['id'];
        $termId = $currentTerm['id'];

        // Get subject IDs for the 6 subjects we'll use
        $mathId = $this->db->table('subjects')->where('code', 'MATH')->get()->getRowArray()['id'] ?? null;
        $englishId = $this->db->table('subjects')->where('code', 'ENG')->get()->getRowArray()['id'] ?? null;
        $physicsId = $this->db->table('subjects')->where('code', 'PHY')->get()->getRowArray()['id'] ?? null;
        $chemistryId = $this->db->table('subjects')->where('code', 'CHE')->get()->getRowArray()['id'] ?? null;
        $biologyId = $this->db->table('subjects')->where('code', 'BIO')->get()->getRowArray()['id'] ?? null;
        $civicId = $this->db->table('subjects')->where('code', 'CIV')->get()->getRowArray()['id'] ?? null;

        // Get class IDs for JSS 1 A and SS 1 Science (using available classes)
        $jss1Row = $this->db->table('classes')->where('name', 'JSS 1')->where('section', 'A')->get()->getRowArray();
        $ss1Row = $this->db->table('classes')->where('name', 'SS 1')->where('section', 'Science')->get()->getRowArray();

        $jss1Id = $jss1Row['id'] ?? null;
        $ss1Id = $ss1Row['id'] ?? null;

        // Debug output
        echo "Looking for classes...\n";
        echo "JSS 1 A found: " . ($jss1Id ? "Yes (ID: $jss1Id)" : "No") . "\n";
        echo "SS 1 Science found: " . ($ss1Id ? "Yes (ID: $ss1Id)" : "No") . "\n";

        if (!$mathId || !$englishId || !$physicsId || !$chemistryId || !$biologyId || !$civicId) {
            echo "Error: Required subjects not found. Please run AcademicStructureSeeder first.\n";
            echo "Math ID: $mathId, English ID: $englishId, Physics ID: $physicsId\n";
            echo "Chemistry ID: $chemistryId, Biology ID: $biologyId, Civic ID: $civicId\n";
            return;
        }

        if (!$jss1Id || !$ss1Id) {
            echo "Error: Required classes (JSS 1 A or SS 1 Science) not found. Please run AcademicStructureSeeder first.\n";

            // Show available classes for debugging
            $allClasses = $this->db->table('classes')->select('id, name, section')->get()->getResultArray();
            echo "Available classes:\n";
            foreach ($allClasses as $class) {
                echo "- ID: {$class['id']}, Name: {$class['name']}, Section: {$class['section']}\n";
            }
            return;
        }

        // Questions data - 30 questions total (5 per subject, distributed across JSS 1 and SSS 1)
        $questionsData = [
            // Mathematics Questions (5) - 3 for JSS 1, 2 for SSS 1
            [
                'subject_id' => $mathId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the value of 3² + 4²?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 2,
                'explanation' => '3² + 4² = 9 + 16 = 25',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $mathId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Solve for x: 2x + 8 = 20',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => '2x + 8 = 20, so 2x = 12, therefore x = 6',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $mathId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the perimeter of a square with side length 7cm?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Perimeter of square = 4 × side length = 4 × 7 = 28 cm',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $mathId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Find the derivative of f(x) = 3x² + 2x - 5',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'f\'(x) = 6x + 2 (using power rule)',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $mathId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Solve the quadratic equation: x² - 5x + 6 = 0',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Factoring: (x-2)(x-3) = 0, so x = 2 or x = 3',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // English Language Questions (5) - 3 for JSS 1, 2 for SSS 1
            [
                'subject_id' => $englishId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Choose the correct synonym for "brave".',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Courageous means showing bravery and fearlessness.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $englishId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the plural form of "child"?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Children is the irregular plural form of child.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $englishId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Identify the noun in this sentence: "The dog barked loudly."',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Dog is the noun - it names a person, place, or thing.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $englishId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Which literary device is used in "The wind whispered through the trees"?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'Personification gives human qualities (whispering) to non-human things (wind).',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $englishId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What type of sentence is this: "Although it was raining, we went to the park."?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'This is a complex sentence with a dependent clause and an independent clause.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Physics Questions (5) - 3 for JSS 1, 2 for SSS 1
            [
                'subject_id' => $physicsId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the unit of force?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Newton (N) is the SI unit of force.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $physicsId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What happens to the volume of a gas when temperature increases at constant pressure?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'According to Charles\' Law, volume increases when temperature increases at constant pressure.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $physicsId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the speed of light in vacuum?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'The speed of light in vacuum is approximately 3.0 × 10⁸ m/s.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $physicsId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the relationship between current, voltage, and resistance according to Ohm\'s Law?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Ohm\'s Law states that V = IR, where V is voltage, I is current, and R is resistance.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $physicsId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the formula for kinetic energy?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Kinetic energy KE = ½mv², where m is mass and v is velocity.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Chemistry Questions (5) - 3 for JSS 1, 2 for SSS 1
            [
                'subject_id' => $chemistryId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the chemical symbol for water?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'H₂O represents water - 2 hydrogen atoms and 1 oxygen atom.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $chemistryId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the atomic number of carbon?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Carbon has an atomic number of 6, meaning it has 6 protons.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $chemistryId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What gas is produced when metals react with acids?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'Hydrogen gas is produced when metals react with acids.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $chemistryId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the molecular formula for methane?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Methane has the molecular formula CH₄ - one carbon atom bonded to four hydrogen atoms.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $chemistryId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What type of bond is formed when electrons are shared between atoms?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Covalent bonds are formed when electrons are shared between atoms.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Biology Questions (5) - 3 for JSS 1, 2 for SSS 1
            [
                'subject_id' => $biologyId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the basic unit of life?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'The cell is the basic unit of life in all living organisms.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $biologyId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'Which organ is responsible for pumping blood in the human body?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'The heart is the organ that pumps blood throughout the body.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $biologyId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What process do plants use to make their own food?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'Photosynthesis is the process plants use to convert sunlight into food.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $biologyId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the function of mitochondria in a cell?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Mitochondria are the powerhouses of the cell, producing ATP energy.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $biologyId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the process by which cells divide to form two identical cells?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Mitosis is the process of cell division that produces two identical diploid cells.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Civic Education Questions (5) - 3 for JSS 1, 2 for SSS 1
            [
                'subject_id' => $civicId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is democracy?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Democracy is a system of government where power is vested in the people.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $civicId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is citizenship?',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'points' => 2,
                'explanation' => 'Citizenship is the status of being a member of a particular country.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $civicId,
                'class_id' => $jss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What are human rights?',
                'question_type' => 'mcq',
                'difficulty' => 'medium',
                'points' => 3,
                'explanation' => 'Human rights are basic rights and freedoms that belong to every person.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $civicId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the rule of law?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Rule of law means that everyone, including government officials, is subject to the law.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'subject_id' => $civicId,
                'class_id' => $ss1Id,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'question_text' => 'What is the separation of powers in government?',
                'question_type' => 'mcq',
                'difficulty' => 'hard',
                'points' => 4,
                'explanation' => 'Separation of powers divides government into three branches: executive, legislative, and judicial.',
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        // Insert questions first
        $this->db->table('questions')->insertBatch($questionsData);

        // Now add options for each question
        $this->addQuestionOptions();

        echo "30 questions with options inserted successfully across 6 subjects!\n";
        echo "Questions distributed across JSS 1 A and SSS 1 Science classes.\n";
        echo "Subjects: Mathematics, English Language, Physics, Chemistry, Biology, Civic Education\n";
    }

    private function addQuestionOptions()
    {
        $now = date('Y-m-d H:i:s');

        // Get all questions we just inserted
        $questions = $this->db->table('questions')
                             ->select('id, question_text, subject_id')
                             ->orderBy('id', 'DESC')
                             ->limit(30)
                             ->get()
                             ->getResultArray();

        // Define all question options in a more organized way
        $questionOptions = [
            // Mathematics
            '3² + 4²' => [
                ['23', false], ['25', true], ['27', false], ['29', false]
            ],
            '2x + 8 = 20' => [
                ['4', false], ['6', true], ['8', false], ['10', false]
            ],
            'perimeter of a square' => [
                ['21 cm', false], ['28 cm', true], ['35 cm', false], ['49 cm', false]
            ],
            'derivative of f(x) = 3x²' => [
                ['3x + 2', false], ['6x + 2', true], ['6x - 5', false], ['3x² + 2x', false]
            ],
            'x² - 5x + 6 = 0' => [
                ['x = 1, x = 6', false], ['x = 2, x = 3', true], ['x = -2, x = -3', false], ['x = 5, x = 1', false]
            ],
            // English Language
            'synonym for "brave"' => [
                ['Cowardly', false], ['Courageous', true], ['Fearful', false], ['Timid', false]
            ],
            'plural form of "child"' => [
                ['Childs', false], ['Children', true], ['Childes', false], ['Child', false]
            ],
            'noun in this sentence' => [
                ['barked', false], ['dog', true], ['loudly', false], ['the', false]
            ],
            'wind whispered' => [
                ['Metaphor', false], ['Personification', true], ['Simile', false], ['Alliteration', false]
            ],
            'Although it was raining' => [
                ['Simple sentence', false], ['Complex sentence', true], ['Compound sentence', false], ['Fragment', false]
            ],
            // Physics
            'unit of force' => [
                ['Joule', false], ['Newton', true], ['Watt', false], ['Pascal', false]
            ],
            'volume of a gas when temperature increases' => [
                ['Decreases', false], ['Increases', true], ['Stays same', false], ['Becomes zero', false]
            ],
            'speed of light' => [
                ['3.0 × 10⁶ m/s', false], ['3.0 × 10⁸ m/s', true], ['3.0 × 10¹⁰ m/s', false], ['3.0 × 10⁴ m/s', false]
            ],
            'Ohm\'s Law' => [
                ['V = I/R', false], ['V = IR', true], ['V = I + R', false], ['V = I - R', false]
            ],
            'kinetic energy' => [
                ['KE = mv²', false], ['KE = ½mv²', true], ['KE = 2mv²', false], ['KE = mv', false]
            ],
            // Chemistry
            'chemical symbol for water' => [
                ['CO₂', false], ['H₂O', true], ['O₂', false], ['H₂SO₄', false]
            ],
            'atomic number of carbon' => [
                ['4', false], ['6', true], ['8', false], ['12', false]
            ],
            'metals react with acids' => [
                ['Oxygen', false], ['Hydrogen', true], ['Carbon dioxide', false], ['Nitrogen', false]
            ],
            'molecular formula for methane' => [
                ['CH₂', false], ['CH₄', true], ['C₂H₄', false], ['C₂H₆', false]
            ],
            'electrons are shared' => [
                ['Ionic bond', false], ['Covalent bond', true], ['Metallic bond', false], ['Hydrogen bond', false]
            ],
            // Biology
            'basic unit of life' => [
                ['Tissue', false], ['Cell', true], ['Organ', false], ['Organism', false]
            ],
            'pumping blood' => [
                ['Brain', false], ['Heart', true], ['Lungs', false], ['Liver', false]
            ],
            'plants use to make their own food' => [
                ['Respiration', false], ['Photosynthesis', true], ['Digestion', false], ['Excretion', false]
            ],
            'function of mitochondria' => [
                ['Protein synthesis', false], ['Energy production', true], ['Waste removal', false], ['DNA storage', false]
            ],
            'cells divide to form two identical cells' => [
                ['Meiosis', false], ['Mitosis', true], ['Binary fission', false], ['Budding', false]
            ],
            // Civic Education
            'What is democracy' => [
                ['Rule by one person', false], ['Government by the people', true], ['Rule by military', false], ['Rule by the rich', false]
            ],
            'What is citizenship' => [
                ['Being a tourist', false], ['Membership of a country', true], ['Being wealthy', false], ['Being educated', false]
            ],
            'What are human rights' => [
                ['Rights for animals', false], ['Basic rights for all people', true], ['Rights for government', false], ['Rights for children only', false]
            ],
            'rule of law' => [
                ['Laws apply to everyone equally', true], ['Only poor people follow laws', false], ['Laws are optional', false], ['Government is above law', false]
            ],
            'separation of powers' => [
                ['Two branches of government', false], ['Three branches of government', true], ['Four branches of government', false], ['One branch of government', false]
            ]
        ];

        $optionsData = [];

        foreach ($questions as $question) {
            $questionId = $question['id'];
            $questionText = $question['question_text'];

            // Find matching options for this question
            foreach ($questionOptions as $keyword => $options) {
                if (strpos($questionText, $keyword) !== false) {
                    for ($i = 0; $i < count($options); $i++) {
                        $optionsData[] = [
                            'question_id' => $questionId,
                            'option_text' => $options[$i][0],
                            'is_correct' => $options[$i][1] ? 1 : 0,
                            'order_index' => $i + 1,
                            'created_at' => $now
                        ];
                    }
                    break; // Found match, no need to check other keywords
                }
            }
        }

        // Insert all options
        if (!empty($optionsData)) {
            $this->db->table('question_options')->insertBatch($optionsData);
        }
    }
}
