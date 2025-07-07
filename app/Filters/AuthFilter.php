<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to access this page.');
        }

        // Check if app is locked for this user's role (principals are exempt from app lock)
        helper('settings');
        $userRole = $session->get('role');
        if ($userRole !== 'principal' && is_app_locked($userRole)) {
            // Destroy session and redirect to login
            $session->destroy();
            return redirect()->to('/auth/login')->with('error', 'Access has been restricted for your role. Please contact administrator.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
