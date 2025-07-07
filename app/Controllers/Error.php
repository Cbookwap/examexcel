<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Error Controller
 * 
 * Handles custom error pages for the SRMS CBT System
 * Provides branded, user-friendly error pages for common HTTP status codes
 * 
 * @package SRMS CBT System
 * @version 1.0.0
 */
class Error extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'settings'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    /**
     * Display 400 Bad Request error page
     * 
     * @return string
     */
    public function error400()
    {
        $this->response->setStatusCode(400);
        
        $data = [
            'title' => 'Bad Request - ExamExcel',
            'message' => 'The request could not be understood by the server due to malformed syntax.',
            'code' => 400
        ];

        return view('errors/html/error_400', $data);
    }

    /**
     * Display 403 Forbidden error page
     * 
     * @return string
     */
    public function error403()
    {
        $this->response->setStatusCode(403);
        
        $data = [
            'title' => 'Access Forbidden - SRMS CBT System',
            'message' => 'You do not have permission to access this resource.',
            'code' => 403
        ];

        return view('errors/html/error_403', $data);
    }

    /**
     * Display 404 Not Found error page
     * 
     * @return string
     */
    public function error404()
    {
        $this->response->setStatusCode(404);
        
        $data = [
            'title' => 'Page Not Found - SRMS CBT System',
            'message' => 'The requested page could not be found on this server.',
            'code' => 404
        ];

        return view('errors/html/error_404', $data);
    }

    /**
     * Display 500 Internal Server Error page
     * 
     * @return string
     */
    public function error500()
    {
        $this->response->setStatusCode(500);
        
        $data = [
            'title' => 'Internal Server Error - SRMS CBT System',
            'message' => 'The server encountered an internal error and was unable to complete your request.',
            'code' => 500
        ];

        return view('errors/html/error_500', $data);
    }

    /**
     * Display 501 Not Implemented error page
     * 
     * @return string
     */
    public function error501()
    {
        $this->response->setStatusCode(501);
        
        $data = [
            'title' => 'Not Implemented - SRMS CBT System',
            'message' => 'The server does not support the functionality required to fulfill the request.',
            'code' => 501
        ];

        return view('errors/html/error_501', $data);
    }

    /**
     * Display 502 Bad Gateway error page
     * 
     * @return string
     */
    public function error502()
    {
        $this->response->setStatusCode(502);
        
        $data = [
            'title' => 'Bad Gateway - SRMS CBT System',
            'message' => 'The server received an invalid response from an upstream server.',
            'code' => 502
        ];

        return view('errors/html/error_500', $data); // Reuse 500 template
    }

    /**
     * Display 503 Service Unavailable error page
     * 
     * @return string
     */
    public function error503()
    {
        $this->response->setStatusCode(503);
        
        $data = [
            'title' => 'Service Unavailable - SRMS CBT System',
            'message' => 'The server is temporarily unable to service your request due to maintenance or capacity problems.',
            'code' => 503
        ];

        return view('errors/html/error_500', $data); // Reuse 500 template
    }

    /**
     * Generic error handler
     * 
     * @param int $code HTTP status code
     * @return string
     */
    public function show($code = 404)
    {
        $code = (int) $code;
        
        // Map error codes to methods
        $errorMethods = [
            400 => 'error400',
            403 => 'error403',
            404 => 'error404',
            500 => 'error500',
            501 => 'error501',
            502 => 'error502',
            503 => 'error503'
        ];

        // Check if we have a specific method for this error code
        if (isset($errorMethods[$code]) && method_exists($this, $errorMethods[$code])) {
            return $this->{$errorMethods[$code]}();
        }

        // Default to 404 for unknown error codes
        return $this->error404();
    }

    /**
     * Maintenance mode page
     * 
     * @return string
     */
    public function maintenance()
    {
        $this->response->setStatusCode(503);
        
        $data = [
            'title' => 'Maintenance Mode - SRMS CBT System',
            'message' => 'The system is currently undergoing scheduled maintenance. Please check back shortly.',
            'code' => 503
        ];

        return view('errors/html/error_500', $data);
    }

    /**
     * Access denied page (for role-based restrictions)
     * 
     * @return string
     */
    public function accessDenied()
    {
        $this->response->setStatusCode(403);
        
        $data = [
            'title' => 'Access Denied - SRMS CBT System',
            'message' => 'You do not have sufficient privileges to access this area. Please contact your administrator if you believe this is an error.',
            'code' => 403
        ];

        return view('errors/html/error_403', $data);
    }

    /**
     * Session expired page
     * 
     * @return string
     */
    public function sessionExpired()
    {
        $this->response->setStatusCode(401);
        
        $data = [
            'title' => 'Session Expired - SRMS CBT System',
            'message' => 'Your session has expired. Please log in again to continue.',
            'code' => 401
        ];

        return view('errors/html/error_403', $data); // Reuse 403 template
    }

    /**
     * Feature not available page
     * 
     * @return string
     */
    public function featureUnavailable()
    {
        $this->response->setStatusCode(501);
        
        $data = [
            'title' => 'Feature Unavailable - SRMS CBT System',
            'message' => 'This feature is currently unavailable or under development. Please try again later.',
            'code' => 501
        ];

        return view('errors/html/error_501', $data);
    }
}
?>
