<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\SessionManager;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;
    protected $session;
    protected $sessionManager;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        $this->sessionManager = new SessionManager();
        helper(['form', 'url']);
    }

    public function login()
    {
        // Debug: Log all requests to login
        log_message('debug', 'Login method called with method: ' . $this->request->getMethod());
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

        // Check if user is already logged in (for GET requests only)
        if ($this->request->getMethod() === 'GET' && $this->session->get('is_logged_in') && $this->session->get('user_id')) {
            log_message('debug', 'User already logged in, redirecting to dashboard');
            return $this->redirectToDashboard();
        }

        $data = [
            'title' => 'Login - ' . get_app_name(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            log_message('debug', 'Processing POST request for login');
            return $this->processLogin();
        }

        log_message('debug', 'Showing login form (GET request)');
        return view('auth/login', $data);
    }

    private function processLogin()
    {
        log_message('debug', 'processLogin method called');

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            log_message('debug', 'Validation failed');
            log_message('debug', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            return view('auth/login', [
                'title' => 'Login - ' . get_app_name(),
                'validation' => $this->validator
            ]);
        }

        log_message('debug', 'Validation passed, proceeding with authentication');

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Find user by username, email, or student ID
        $user = $this->userModel->findByUsername($username);
        if (!$user) {
            $user = $this->userModel->findByEmail($username);
        }
        if (!$user) {
            $user = $this->userModel->findByStudentId($username);
        }

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            session()->setFlashdata('error', 'Invalid username/email or password');
            return redirect()->back()->withInput();
        }

        if (!$user['is_active']) {
            session()->setFlashdata('error', 'Your account has been deactivated. Please contact administrator.');
            return redirect()->back()->withInput();
        }

        // Check if app is locked for this user's role
        helper('settings');
        if (is_app_locked($user['role'])) {
            session()->setFlashdata('error', 'Access is currently restricted for your role. Please contact administrator.');
            return redirect()->back()->withInput();
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'role' => $user['role'],
            'class_id' => $user['class_id'],
            'is_logged_in' => true
        ];

        // Add title to session for principal users
        if ($user['role'] === 'principal' && !empty($user['title'])) {
            $sessionData['title'] = $user['title'];
        }

        $this->session->set($sessionData);

        // Track the session
        $this->sessionManager->trackLogin($user['id'], $user['role'], $this->request);

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Debug: Check if headers are already sent
        if (headers_sent($file, $line)) {
            log_message('error', "Headers already sent in $file on line $line");
        }

        // Debug: Log before redirect
        log_message('debug', 'About to redirect to dashboard for role: ' . $user['role']);

        // Try direct redirect without flash message first
        $role = $this->session->get('role');
        log_message('debug', 'Direct redirect for role: ' . $role);

        switch ($role) {
            case 'admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'teacher':
                return redirect()->to(base_url('teacher/dashboard'));
            case 'student':
                return redirect()->to(base_url('student/dashboard'));
            case 'class_teacher':
                return redirect()->to(base_url('class-teacher/dashboard'));
            case 'principal':
                return redirect()->to(base_url('principal/dashboard'));
            default:
                return redirect()->to(base_url());
        }
    }

    public function logout()
    {
        // Track the logout
        $this->sessionManager->trackLogout();

        $this->session->destroy();
        session()->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to(base_url('auth/login'));
    }

    public function register()
    {
        // Only allow registration if user is admin or if registration is open
        if ($this->session->get('role') !== 'admin' && !$this->isRegistrationOpen()) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Registration is not available.');
        }

        $data = [
            'title' => 'Register - ' . get_app_name(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processRegistration();
        }

        return view('auth/register', $data);
    }

    private function processRegistration()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'role' => 'required|in_list[admin,teacher,student,principal]'
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', [
                'title' => 'Register - SRMS CBT System',
                'validation' => $this->validator
            ]);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => $this->request->getPost('role'),
            'phone' => $this->request->getPost('phone'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'student_id' => $this->request->getPost('student_id'),
            'employee_id' => $this->request->getPost('employee_id'),
            'class_id' => $this->request->getPost('class_id'),
            'department' => $this->request->getPost('department'),
            'qualification' => $this->request->getPost('qualification'),
            'is_active' => 1,
            'is_verified' => 0
        ];

        if ($this->userModel->insert($userData)) {
            session()->setFlashdata('success', 'Registration successful! Please wait for account verification.');

            // If admin is creating the account, redirect to user management
            if ($this->session->get('role') === 'admin') {
                return redirect()->to(base_url('admin/users'));
            }

            return redirect()->to(base_url('auth/login'));
        } else {
            session()->setFlashdata('error', 'Registration failed. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    private function redirectToDashboard()
    {
        $role = $this->session->get('role');

        log_message('debug', 'redirectToDashboard called with role: ' . $role);

        switch ($role) {
            case 'admin':
                log_message('debug', 'Redirecting to admin dashboard');
                return redirect()->to(base_url('admin/dashboard'));
            case 'teacher':
                log_message('debug', 'Redirecting to teacher dashboard');
                return redirect()->to(base_url('teacher/dashboard'));
            case 'student':
                log_message('debug', 'Redirecting to student dashboard');
                return redirect()->to(base_url('student/dashboard'));
            case 'class_teacher':
                log_message('debug', 'Redirecting to class-teacher dashboard');
                return redirect()->to(base_url('class-teacher/dashboard'));
            case 'principal':
                log_message('debug', 'Redirecting to principal dashboard');
                return redirect()->to(base_url('principal/dashboard'));
            default:
                log_message('debug', 'Redirecting to home (unknown role)');
                return redirect()->to(base_url());
        }
    }

    private function isRegistrationOpen()
    {
        // This can be configured in settings
        return false; // For now, only admin can register users
    }

    public function profile()
    {
        if (!$this->session->get('is_logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('auth/logout'));
        }

        // Get class information for students
        $classInfo = null;
        if ($user['role'] === 'student' && !empty($user['class_id'])) {
            $classModel = new \App\Models\ClassModel();
            $classInfo = $classModel->find($user['class_id']);
        }

        $data = [
            'title' => 'My Profile - ' . get_app_name(),
            'user' => $user,
            'classInfo' => $classInfo,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->updateProfile($userId);
        }

        return view('auth/profile', $data);
    }

    private function updateProfile($userId)
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
            'address' => 'permit_empty',
            'date_of_birth' => 'permit_empty|valid_date',
            'gender' => 'permit_empty|in_list[male,female,other]',
            'department' => 'permit_empty|max_length[100]',
            'qualification' => 'permit_empty|max_length[255]'
        ];

        // If password is being changed
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            $user = $this->userModel->find($userId);

            // Get class information for students
            $classInfo = null;
            if ($user['role'] === 'student' && !empty($user['class_id'])) {
                $classModel = new \App\Models\ClassModel();
                $classInfo = $classModel->find($user['class_id']);
            }

            return view('auth/profile', [
                'title' => 'My Profile - ' . get_app_name(),
                'user' => $user,
                'classInfo' => $classInfo,
                'validation' => $this->validator
            ]);
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'department' => $this->request->getPost('department'),
            'qualification' => $this->request->getPost('qualification')
        ];

        // Add password if provided
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->update($userId, $updateData)) {
            // Update session data
            $this->session->set('full_name', $updateData['first_name'] . ' ' . $updateData['last_name']);

            session()->setFlashdata('success', 'Profile updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update profile. Please try again.');
        }

        return redirect()->back();
    }

    public function testRedirect()
    {
        log_message('debug', 'testRedirect called');
        return redirect()->to(base_url('class-teacher/dashboard'));
    }
}
