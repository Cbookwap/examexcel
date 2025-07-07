<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TeacherFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to access this page.');
        }

        // Check if app is locked for this user's role
        helper('settings');
        $userRole = $session->get('role');
        if (is_app_locked($userRole)) {
            // Destroy session and redirect to login
            $session->destroy();
            return redirect()->to('/auth/login')->with('error', 'Access has been restricted for your role. Please contact administrator.');
        }

        // Check if user is teacher
        if ($session->get('role') !== 'teacher') {
            $redirectUrl = '/auth/login';

            // Redirect to appropriate dashboard if user is logged in but wrong role
            if ($userRole === 'admin') {
                $redirectUrl = '/admin/dashboard';
            } elseif ($userRole === 'student') {
                $redirectUrl = '/student/dashboard';
            }

            return redirect()->to($redirectUrl)->with('error', 'Access denied. Teacher privileges required.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
