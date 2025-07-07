<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();

        // Check if user is already logged in
        if ($session->get('is_logged_in') && $session->get('user_id')) {
            // Redirect to appropriate dashboard based on user role
            return $this->redirectToDashboard($session->get('role'));
        }

        // Pass session data to view for authentication detection
        $data = [
            'is_logged_in' => $session->get('is_logged_in') ?? false,
            'user_name' => $session->get('full_name') ?? null,
            'user_role' => $session->get('role') ?? null
        ];

        return view('welcome_message', $data);
    }

    /**
     * Redirect user to their appropriate dashboard based on role
     */
    private function redirectToDashboard($role)
    {
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
                // If role is unknown, show welcome page
                return view('welcome_message');
        }
    }
}
