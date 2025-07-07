<?php

if (!function_exists('generate_unique_student_id')) {
    /**
     * Generate a unique student ID with prefix and 4 random digits
     *
     * @return string
     */
    function generate_unique_student_id()
    {
        log_message('info', 'generate_unique_student_id: Starting student ID generation');

        // Load settings helper to get the prefix
        helper('settings');

        // Get the student ID prefix from settings with fallback
        $prefix = 'STD'; // Default fallback

        try {
            // Try to get from settings helper first
            helper('settings');
            $settingValue = get_app_setting('student_id_prefix', null);

            if (!empty($settingValue)) {
                $prefix = $settingValue;
                log_message('debug', 'Student ID prefix from settings helper: ' . $prefix);
            } else {
                // If settings helper fails, try direct database query
                $db = \Config\Database::connect();
                $query = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'student_id_prefix' LIMIT 1");
                $result = $query->getRow();

                if ($result && !empty($result->setting_value)) {
                    $prefix = $result->setting_value;
                    log_message('debug', 'Student ID prefix from direct DB query: ' . $prefix);
                } else {
                    log_message('debug', 'No student ID prefix found in settings, using default: STD');
                }
            }
        } catch (\Exception $e) {
            // Fallback to default if everything fails
            log_message('error', 'Failed to get student ID prefix: ' . $e->getMessage());
            $prefix = 'STD';
        }

        // Load UserModel to check for uniqueness
        try {
            $userModel = new \App\Models\UserModel();
        } catch (\Exception $e) {
            log_message('error', 'Failed to load UserModel in generate_unique_student_id: ' . $e->getMessage());
            // Fallback: generate simple ID without uniqueness check
            $randomDigits = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            return strtoupper($prefix) . '-' . $randomDigits;
        }

        $maxAttempts = 100; // Prevent infinite loop
        $attempts = 0;

        do {
            // Generate 4 random digits
            $randomDigits = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

            // Create the student ID
            $studentId = strtoupper($prefix) . '-' . $randomDigits;

            // Check if this student ID already exists
            try {
                $exists = $userModel->where('student_id', $studentId)->first();
            } catch (\Exception $e) {
                log_message('error', 'Database error in generate_unique_student_id: ' . $e->getMessage());
                // If database check fails, assume ID is unique and break
                break;
            }

            $attempts++;

            if ($attempts >= $maxAttempts) {
                // If we can't generate a unique ID after many attempts,
                // add timestamp to ensure uniqueness
                $timestamp = substr(time(), -3);
                $studentId = strtoupper($prefix) . '-' . $timestamp . rand(10, 99);
                break;
            }

        } while ($exists);

        log_message('info', 'generate_unique_student_id: Generated student ID: ' . $studentId);
        return $studentId;
    }
}

if (!function_exists('validate_student_id_format')) {
    /**
     * Validate student ID format
     *
     * @param string $studentId
     * @return bool
     */
    function validate_student_id_format($studentId)
    {
        // Pattern: 2-5 letters, hyphen, 4 digits
        return preg_match('/^[A-Z]{2,5}-\d{4}$/', strtoupper($studentId));
    }
}

if (!function_exists('is_student_id_unique')) {
    /**
     * Check if student ID is unique
     *
     * @param string $studentId
     * @param int|null $excludeUserId
     * @return bool
     */
    function is_student_id_unique($studentId, $excludeUserId = null)
    {
        $userModel = new \App\Models\UserModel();

        $builder = $userModel->where('student_id', $studentId);

        if ($excludeUserId) {
            $builder->where('id !=', $excludeUserId);
        }

        return $builder->first() === null;
    }
}
