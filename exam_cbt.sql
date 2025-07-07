-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examexcel`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_sessions`
--

CREATE TABLE `academic_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `session_name` varchar(20) NOT NULL COMMENT 'e.g., 2024/2025',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academic_terms`
--

CREATE TABLE `academic_terms` (
  `id` int(11) UNSIGNED NOT NULL,
  `session_id` int(11) UNSIGNED NOT NULL,
  `term_number` tinyint(1) NOT NULL COMMENT '1=First Term, 2=Second Term, 3=Third Term',
  `term_name` varchar(50) NOT NULL COMMENT 'e.g., First Term, Second Term, Third Term',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `active_sessions`
--

CREATE TABLE `active_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_role` enum('admin','teacher','student') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_api_keys`
--

CREATE TABLE `ai_api_keys` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL,
  `api_key` text NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `academic_year` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `max_students` int(11) NOT NULL DEFAULT 50,
  `class_teacher_id` int(11) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_promotion_rules`
--

CREATE TABLE `class_promotion_rules` (
  `id` int(11) UNSIGNED NOT NULL,
  `from_class_id` int(11) UNSIGNED NOT NULL,
  `to_class_id` int(11) UNSIGNED NOT NULL,
  `minimum_percentage` decimal(5,2) NOT NULL DEFAULT 40.00,
  `minimum_subjects_passed` int(11) NOT NULL DEFAULT 5,
  `is_automatic` tinyint(1) NOT NULL DEFAULT 1,
  `requires_approval` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `essay_grading`
--

CREATE TABLE `essay_grading` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_attempt_id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) UNSIGNED NOT NULL,
  `student_answer` text NOT NULL,
  `ai_suggested_score` decimal(5,2) DEFAULT NULL,
  `ai_feedback` text DEFAULT NULL,
  `teacher_score` decimal(5,2) DEFAULT NULL,
  `teacher_feedback` text DEFAULT NULL,
  `status` enum('pending','ai_graded','teacher_reviewed','finalized') NOT NULL DEFAULT 'pending',
  `graded_by` int(11) UNSIGNED DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Subject ID for single-subject exams, NULL for multi-subject exams',
  `class_id` int(11) UNSIGNED DEFAULT NULL,
  `exam_mode` enum('single_subject','multi_subject') DEFAULT 'single_subject' COMMENT 'Exam mode: single subject or multiple subjects',
  `session_id` int(11) UNSIGNED DEFAULT NULL,
  `term_id` int(11) UNSIGNED DEFAULT NULL,
  `exam_type` int(11) UNSIGNED NOT NULL,
  `duration_minutes` int(11) DEFAULT 60,
  `total_marks` int(11) DEFAULT 0,
  `passing_marks` decimal(5,2) DEFAULT 60.00,
  `question_count` int(11) DEFAULT 0,
  `total_questions` int(11) DEFAULT 0 COMMENT 'Total number of questions across all subjects',
  `questions_configured` tinyint(1) DEFAULT 0 COMMENT 'Whether questions have been configured for this exam',
  `negative_marking` tinyint(1) DEFAULT 0,
  `negative_marks_per_question` decimal(5,2) DEFAULT 0.00,
  `show_result_immediately` tinyint(1) DEFAULT 1,
  `status` enum('draft','scheduled','active','completed','cancelled') NOT NULL DEFAULT 'draft',
  `duration` int(11) NOT NULL DEFAULT 60,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `passing_score` decimal(5,2) NOT NULL DEFAULT 60.00,
  `max_attempts` int(11) NOT NULL DEFAULT 1,
  `randomize_questions` tinyint(1) NOT NULL DEFAULT 0,
  `randomize_options` tinyint(1) NOT NULL DEFAULT 0,
  `show_results` tinyint(1) NOT NULL DEFAULT 1,
  `show_correct_answers` tinyint(1) NOT NULL DEFAULT 0,
  `allow_review` tinyint(1) NOT NULL DEFAULT 1,
  `require_proctoring` tinyint(1) NOT NULL DEFAULT 0,
  `browser_lockdown` tinyint(1) NOT NULL DEFAULT 0,
  `prevent_copy_paste` tinyint(1) NOT NULL DEFAULT 1,
  `disable_right_click` tinyint(1) NOT NULL DEFAULT 1,
  `calculator_enabled` tinyint(1) DEFAULT 1 COMMENT 'Whether calculator is enabled for this exam',
  `exam_pause_enabled` tinyint(1) DEFAULT 0 COMMENT 'Whether students can pause this exam',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`instructions`)),
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `allowed_ips` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allowed_ips`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `attempt_delay_minutes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_attempts`
--

CREATE TABLE `exam_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_id` int(11) UNSIGNED NOT NULL,
  `session_id` int(11) UNSIGNED DEFAULT NULL,
  `term_id` int(11) UNSIGNED DEFAULT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `status` enum('in_progress','submitted','auto_submitted','completed','terminated') DEFAULT 'in_progress',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `time_taken_minutes` int(11) DEFAULT 0,
  `total_questions` int(11) DEFAULT 0,
  `answered_questions` int(11) DEFAULT 0,
  `correct_answers` int(11) DEFAULT 0,
  `wrong_answers` int(11) DEFAULT 0,
  `marks_obtained` decimal(8,2) DEFAULT 0.00,
  `started_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `score` decimal(8,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `time_spent` int(11) NOT NULL DEFAULT 0,
  `is_passed` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `violations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`violations`)),
  `browser_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`browser_info`)),
  `security_flags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`security_flags`)),
  `proctoring_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`proctoring_data`)),
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Subject ID for multi-subject exams',
  `order_index` int(11) NOT NULL DEFAULT 0,
  `subject_order` int(11) DEFAULT 1 COMMENT 'Order within subject for multi-subject exams',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_subjects`
--

CREATE TABLE `exam_subjects` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `question_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of questions for this subject in the exam',
  `total_marks` int(11) NOT NULL DEFAULT 0 COMMENT 'Total marks for this subject in the exam',
  `time_allocation` int(11) NOT NULL DEFAULT 0 COMMENT 'Time allocated for this subject in minutes',
  `subject_order` int(11) NOT NULL DEFAULT 1 COMMENT 'Order of subjects in the exam',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_types`
--

CREATE TABLE `exam_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `default_total_marks` int(11) DEFAULT 100 COMMENT 'Default total marks for this exam type',
  `is_test` tinyint(1) DEFAULT 1 COMMENT '1 for tests/CAs, 0 for main exams',
  `assessment_category` enum('continuous_assessment','main_examination','practice') DEFAULT 'continuous_assessment' COMMENT 'Category of assessment',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practice_questions`
--

CREATE TABLE `practice_questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `category` varchar(100) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `explanation` text DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `points` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practice_question_options`
--

CREATE TABLE `practice_question_options` (
  `id` int(11) UNSIGNED NOT NULL,
  `practice_question_id` int(11) UNSIGNED NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practice_sessions`
--

CREATE TABLE `practice_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED DEFAULT NULL,
  `class_id` int(11) UNSIGNED NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `questions` text NOT NULL COMMENT 'JSON array of question IDs',
  `answers` text DEFAULT NULL COMMENT 'JSON object of question_id => answer pairs',
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('in_progress','completed','abandoned') NOT NULL DEFAULT 'in_progress',
  `score` int(11) DEFAULT NULL COMMENT 'Number of correct answers',
  `total_questions` int(11) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL COMMENT 'Score percentage (0.00 to 100.00)',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proctoring_ai_models`
--

CREATE TABLE `proctoring_ai_models` (
  `id` int(11) UNSIGNED NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_type` enum('face_detection','gaze_tracking','object_detection','behavior_analysis','audio_analysis') NOT NULL,
  `model_version` varchar(50) NOT NULL,
  `model_path` varchar(500) NOT NULL,
  `configuration` text DEFAULT NULL,
  `accuracy_metrics` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proctoring_events`
--

CREATE TABLE `proctoring_events` (
  `id` int(11) UNSIGNED NOT NULL,
  `proctoring_session_id` int(11) UNSIGNED NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_subtype` varchar(100) DEFAULT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `confidence_score` decimal(5,2) DEFAULT NULL,
  `event_data` text DEFAULT NULL,
  `evidence_file_id` int(11) UNSIGNED DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `admin_action` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proctoring_evidence`
--

CREATE TABLE `proctoring_evidence` (
  `id` int(11) UNSIGNED NOT NULL,
  `proctoring_session_id` int(11) UNSIGNED NOT NULL,
  `proctoring_event_id` int(11) UNSIGNED DEFAULT NULL,
  `evidence_type` enum('screenshot','video_clip','audio_clip','webcam_photo','screen_recording') NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) UNSIGNED NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `analysis_results` text DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `admin_reviewed` tinyint(1) NOT NULL DEFAULT 0,
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proctoring_sessions`
--

CREATE TABLE `proctoring_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_attempt_id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('active','completed','terminated','error') NOT NULL DEFAULT 'active',
  `browser_info` text DEFAULT NULL,
  `proctoring_settings` text DEFAULT NULL,
  `session_stats` text DEFAULT NULL,
  `violation_count` int(11) NOT NULL DEFAULT 0,
  `integrity_score` decimal(5,2) DEFAULT NULL,
  `evidence_files` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `class_id` int(11) UNSIGNED DEFAULT NULL,
  `session_id` int(11) UNSIGNED DEFAULT NULL,
  `term_id` int(11) UNSIGNED DEFAULT NULL,
  `exam_type_id` int(11) UNSIGNED DEFAULT NULL,
  `instruction_id` int(11) UNSIGNED DEFAULT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('mcq','true_false','yes_no','fill_blank','short_answer','essay','drag_drop','image_based','math_equation') NOT NULL DEFAULT 'mcq',
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `points` int(11) NOT NULL DEFAULT 1,
  `time_limit` int(11) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `hints` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `enable_rubric` tinyint(1) DEFAULT 0,
  `rubric_data` text DEFAULT NULL,
  `model_answer` text DEFAULT NULL,
  `decimal_places` int(2) DEFAULT 2,
  `tolerance` decimal(10,4) DEFAULT 0.0100,
  `randomize_options` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_instructions`
--

CREATE TABLE `question_instructions` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `instruction_text` text NOT NULL,
  `subject_id` int(11) UNSIGNED DEFAULT NULL,
  `class_id` int(11) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) UNSIGNED NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `blank_number` int(11) DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_attempt_id` int(11) UNSIGNED DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`event_data`)),
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_settings`
--

CREATE TABLE `security_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','integer','boolean','json') NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','integer','boolean','json') NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_academic_history`
--

CREATE TABLE `student_academic_history` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `session_id` int(11) UNSIGNED NOT NULL,
  `term_id` int(11) UNSIGNED DEFAULT NULL,
  `class_id` int(11) UNSIGNED NOT NULL,
  `enrollment_date` date NOT NULL,
  `promotion_date` date DEFAULT NULL,
  `status` enum('active','promoted','repeated','graduated','withdrawn') NOT NULL DEFAULT 'active',
  `promotion_type` enum('automatic','manual','conditional') DEFAULT NULL,
  `overall_percentage` decimal(5,2) DEFAULT NULL,
  `position_in_class` int(11) DEFAULT NULL,
  `total_students` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_attempt_id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) UNSIGNED NOT NULL,
  `answer_text` text DEFAULT NULL,
  `selected_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`selected_options`)),
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `points_earned` decimal(8,2) NOT NULL DEFAULT 0.00,
  `answered_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_term_results`
--

CREATE TABLE `student_term_results` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `session_id` int(11) UNSIGNED NOT NULL,
  `term_id` int(11) UNSIGNED NOT NULL,
  `class_id` int(11) UNSIGNED NOT NULL,
  `total_subjects` int(11) NOT NULL DEFAULT 0,
  `subjects_passed` int(11) NOT NULL DEFAULT 0,
  `subjects_failed` int(11) NOT NULL DEFAULT 0,
  `total_marks_obtained` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_marks_possible` decimal(8,2) NOT NULL DEFAULT 0.00,
  `overall_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `grade` varchar(5) DEFAULT NULL,
  `position_in_class` int(11) DEFAULT NULL,
  `total_students` int(11) DEFAULT NULL,
  `attendance_percentage` decimal(5,2) DEFAULT NULL,
  `conduct_grade` varchar(20) DEFAULT NULL,
  `teacher_remarks` text DEFAULT NULL,
  `principal_remarks` text DEFAULT NULL,
  `next_term_begins` date DEFAULT NULL,
  `is_promoted` tinyint(1) NOT NULL DEFAULT 0,
  `promotion_status` enum('promoted','repeated','conditional','pending') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_violations`
--

CREATE TABLE `student_violations` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `violation_count` int(11) NOT NULL DEFAULT 0,
  `punishment_type` enum('warning','temporary_suspension','permanent_ban') NOT NULL DEFAULT 'warning',
  `punishment_duration` int(11) DEFAULT NULL COMMENT 'Duration in days for temporary suspensions',
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `notes` text DEFAULT NULL,
  `admin_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Admin who applied manual punishment',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `teacher_id` int(11) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_categories`
--

CREATE TABLE `subject_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#6c757d' COMMENT 'Hex color code for category display',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_classes`
--

CREATE TABLE `subject_classes` (
  `id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `class_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_time_tracking`
--

CREATE TABLE `subject_time_tracking` (
  `id` int(11) UNSIGNED NOT NULL,
  `exam_attempt_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL COMMENT 'When student started working on this subject',
  `end_time` datetime DEFAULT NULL COMMENT 'When student finished/left this subject',
  `time_spent_seconds` int(11) NOT NULL DEFAULT 0 COMMENT 'Total time spent on this subject in seconds',
  `is_completed` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether student completed this subject',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subject_assignments`
--

CREATE TABLE `teacher_subject_assignments` (
  `id` int(11) UNSIGNED NOT NULL,
  `teacher_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `class_id` int(11) UNSIGNED NOT NULL,
  `session_id` int(11) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `assigned_by` int(11) UNSIGNED NOT NULL COMMENT 'Admin who made the assignment',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `role` enum('admin','teacher','student','class_teacher','principal') DEFAULT 'student',
  `title` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `class_id` int(11) UNSIGNED DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `exam_banned` tinyint(1) DEFAULT 0 COMMENT 'Whether user is permanently banned from exams',
  `ban_reason` text DEFAULT NULL COMMENT 'Reason for permanent ban',
  `exam_suspended_until` datetime DEFAULT NULL COMMENT 'Date/time until which user is suspended from exams',
  `suspension_reason` text DEFAULT NULL COMMENT 'Reason for temporary suspension'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_sessions`
--
ALTER TABLE `academic_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_name` (`session_name`);

--
-- Indexes for table `academic_terms`
--
ALTER TABLE `academic_terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id_term_number` (`session_id`,`term_number`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_role` (`user_role`),
  ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `ai_api_keys`
--
ALTER TABLE `ai_api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_provider_model` (`provider`,`model`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_promotion_rules`
--
ALTER TABLE `class_promotion_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_promotion_rules_from_class_id_foreign` (`from_class_id`),
  ADD KEY `class_promotion_rules_to_class_id_foreign` (`to_class_id`);

--
-- Indexes for table `essay_grading`
--
ALTER TABLE `essay_grading`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_attempt_id` (`exam_attempt_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id_student_id` (`exam_id`,`student_id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id_question_id` (`exam_id`,`question_id`);

--
-- Indexes for table `exam_subjects`
--
ALTER TABLE `exam_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_subjects_subject_id_foreign` (`subject_id`),
  ADD KEY `exam_id_subject_id` (`exam_id`,`subject_id`);

--
-- Indexes for table `exam_types`
--
ALTER TABLE `exam_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `exam_types_created_by_foreign` (`created_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `practice_questions`
--
ALTER TABLE `practice_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `difficulty` (`difficulty`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `practice_question_options`
--
ALTER TABLE `practice_question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `practice_question_id` (`practice_question_id`);

--
-- Indexes for table `practice_sessions`
--
ALTER TABLE `practice_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `proctoring_ai_models`
--
ALTER TABLE `proctoring_ai_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_type` (`model_type`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `proctoring_events`
--
ALTER TABLE `proctoring_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proctoring_session_id` (`proctoring_session_id`),
  ADD KEY `event_type` (`event_type`),
  ADD KEY `severity` (`severity`),
  ADD KEY `timestamp` (`timestamp`),
  ADD KEY `processed` (`processed`);

--
-- Indexes for table `proctoring_evidence`
--
ALTER TABLE `proctoring_evidence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proctoring_session_id` (`proctoring_session_id`),
  ADD KEY `proctoring_event_id` (`proctoring_event_id`),
  ADD KEY `evidence_type` (`evidence_type`),
  ADD KEY `is_flagged` (`is_flagged`),
  ADD KEY `admin_reviewed` (`admin_reviewed`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `proctoring_sessions`
--
ALTER TABLE `proctoring_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `exam_attempt_id` (`exam_attempt_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `status` (`status`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `integrity_score` (`integrity_score`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `idx_session_term` (`session_id`,`term_id`),
  ADD KEY `idx_class_exam_type` (`class_id`,`exam_type_id`);

--
-- Indexes for table `question_instructions`
--
ALTER TABLE `question_instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_instructions_subject_id_foreign` (`subject_id`),
  ADD KEY `question_instructions_class_id_foreign` (`class_id`),
  ADD KEY `question_instructions_created_by_foreign` (`created_by`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_attempt_id` (`exam_attempt_id`),
  ADD KEY `event_type` (`event_type`);

--
-- Indexes for table `security_settings`
--
ALTER TABLE `security_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `student_academic_history`
--
ALTER TABLE `student_academic_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_academic_history_session_id_foreign` (`session_id`),
  ADD KEY `student_academic_history_term_id_foreign` (`term_id`),
  ADD KEY `student_academic_history_class_id_foreign` (`class_id`),
  ADD KEY `student_id_session_id_term_id` (`student_id`,`session_id`,`term_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_attempt_id_question_id` (`exam_attempt_id`,`question_id`);

--
-- Indexes for table `student_term_results`
--
ALTER TABLE `student_term_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id_session_id_term_id` (`student_id`,`session_id`,`term_id`),
  ADD KEY `student_term_results_session_id_foreign` (`session_id`),
  ADD KEY `student_term_results_term_id_foreign` (`term_id`),
  ADD KEY `student_term_results_class_id_foreign` (`class_id`);

--
-- Indexes for table `student_violations`
--
ALTER TABLE `student_violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_violations_admin_id_foreign` (`admin_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `punishment_type` (`punishment_type`),
  ADD KEY `severity` (`severity`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `subject_categories`
--
ALTER TABLE `subject_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `subject_classes`
--
ALTER TABLE `subject_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id_class_id` (`subject_id`,`class_id`);

--
-- Indexes for table `subject_time_tracking`
--
ALTER TABLE `subject_time_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_time_tracking_subject_id_foreign` (`subject_id`),
  ADD KEY `exam_attempt_id_subject_id` (`exam_attempt_id`,`subject_id`);

--
-- Indexes for table `teacher_subject_assignments`
--
ALTER TABLE `teacher_subject_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id_subject_id_class_id_session_id` (`teacher_id`,`subject_id`,`class_id`,`session_id`),
  ADD KEY `teacher_subject_assignments_subject_id_foreign` (`subject_id`),
  ADD KEY `teacher_subject_assignments_class_id_foreign` (`class_id`),
  ADD KEY `teacher_subject_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `teacher_id_subject_id_class_id` (`teacher_id`,`subject_id`,`class_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `idx_exam_banned` (`exam_banned`),
  ADD KEY `idx_exam_suspended_until` (`exam_suspended_until`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_sessions`
--
ALTER TABLE `academic_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `academic_terms`
--
ALTER TABLE `academic_terms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `active_sessions`
--
ALTER TABLE `active_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_api_keys`
--
ALTER TABLE `ai_api_keys`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_promotion_rules`
--
ALTER TABLE `class_promotion_rules`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `essay_grading`
--
ALTER TABLE `essay_grading`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_subjects`
--
ALTER TABLE `exam_subjects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_types`
--
ALTER TABLE `exam_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practice_questions`
--
ALTER TABLE `practice_questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practice_question_options`
--
ALTER TABLE `practice_question_options`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practice_sessions`
--
ALTER TABLE `practice_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proctoring_ai_models`
--
ALTER TABLE `proctoring_ai_models`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proctoring_events`
--
ALTER TABLE `proctoring_events`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proctoring_evidence`
--
ALTER TABLE `proctoring_evidence`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proctoring_sessions`
--
ALTER TABLE `proctoring_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_instructions`
--
ALTER TABLE `question_instructions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_settings`
--
ALTER TABLE `security_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_academic_history`
--
ALTER TABLE `student_academic_history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_term_results`
--
ALTER TABLE `student_term_results`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_violations`
--
ALTER TABLE `student_violations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_categories`
--
ALTER TABLE `subject_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_classes`
--
ALTER TABLE `subject_classes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_time_tracking`
--
ALTER TABLE `subject_time_tracking`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_subject_assignments`
--
ALTER TABLE `teacher_subject_assignments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_terms`
--
ALTER TABLE `academic_terms`
  ADD CONSTRAINT `academic_terms_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `academic_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD CONSTRAINT `active_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_promotion_rules`
--
ALTER TABLE `class_promotion_rules`
  ADD CONSTRAINT `class_promotion_rules_from_class_id_foreign` FOREIGN KEY (`from_class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `class_promotion_rules_to_class_id_foreign` FOREIGN KEY (`to_class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_subjects`
--
ALTER TABLE `exam_subjects`
  ADD CONSTRAINT `exam_subjects_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_subjects_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_types`
--
ALTER TABLE `exam_types`
  ADD CONSTRAINT `exam_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `practice_question_options`
--
ALTER TABLE `practice_question_options`
  ADD CONSTRAINT `practice_question_options_practice_question_id_foreign` FOREIGN KEY (`practice_question_id`) REFERENCES `practice_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `practice_sessions`
--
ALTER TABLE `practice_sessions`
  ADD CONSTRAINT `practice_sessions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `practice_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `proctoring_events`
--
ALTER TABLE `proctoring_events`
  ADD CONSTRAINT `fk_proctoring_events_session` FOREIGN KEY (`proctoring_session_id`) REFERENCES `proctoring_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proctoring_evidence`
--
ALTER TABLE `proctoring_evidence`
  ADD CONSTRAINT `fk_proctoring_evidence_event` FOREIGN KEY (`proctoring_event_id`) REFERENCES `proctoring_events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_proctoring_evidence_session` FOREIGN KEY (`proctoring_session_id`) REFERENCES `proctoring_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proctoring_sessions`
--
ALTER TABLE `proctoring_sessions`
  ADD CONSTRAINT `fk_proctoring_sessions_exam_attempt` FOREIGN KEY (`exam_attempt_id`) REFERENCES `exam_attempts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_proctoring_sessions_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_instructions`
--
ALTER TABLE `question_instructions`
  ADD CONSTRAINT `question_instructions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `question_instructions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `question_instructions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `student_academic_history`
--
ALTER TABLE `student_academic_history`
  ADD CONSTRAINT `student_academic_history_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_academic_history_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `academic_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_academic_history_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_academic_history_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_term_results`
--
ALTER TABLE `student_term_results`
  ADD CONSTRAINT `student_term_results_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_term_results_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `academic_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_term_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_term_results_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_violations`
--
ALTER TABLE `student_violations`
  ADD CONSTRAINT `student_violations_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `student_violations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject_time_tracking`
--
ALTER TABLE `subject_time_tracking`
  ADD CONSTRAINT `subject_time_tracking_exam_attempt_id_foreign` FOREIGN KEY (`exam_attempt_id`) REFERENCES `exam_attempts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_time_tracking_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teacher_subject_assignments`
--
ALTER TABLE `teacher_subject_assignments`
  ADD CONSTRAINT `teacher_subject_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_subject_assignments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_subject_assignments_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `academic_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_subject_assignments_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_subject_assignments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
