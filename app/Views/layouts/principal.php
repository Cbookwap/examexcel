<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Principal Dashboard</title>

    <!-- Cache busting for dynamic styles -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            <?php
            helper('settings');
            $themeSettings = get_theme_settings();
            
            // Set CSS variables for theme colors
            echo "--primary-color: {$themeSettings['primary_color']};";
            echo "--primary-light: {$themeSettings['primary_light']};";
            echo "--primary-dark: {$themeSettings['primary_dark']};";
            echo "--primary-color-rgb: {$themeSettings['primary_color_rgb']};";
            echo "--font-family: {$themeSettings['font_family']};";
            echo "--font-size: {$themeSettings['font_size']};";
            ?>
        }

        body {
            font-family: var(--font-family);
            font-size: var(--font-size);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin: 20px;
            height: calc(100vh - 40px);
            position: fixed;
            width: 280px;
            z-index: 1000;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 320px;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand h4 {
            color: var(--primary-color);
            font-weight: bold;
            margin: 0;
        }

        .sidebar-brand small {
            color: #6c757d;
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            background: rgba(var(--primary-color-rgb), 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .content-wrapper {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            flex: 1;
            margin-bottom: 0;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* Force dynamic theme colors to override Bootstrap defaults */
        .btn-primary,
        .bg-primary,
        .badge.bg-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content .app-footer {
                margin-left: 0 !important;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px;
        }

        /* Principal Layout Footer Positioning */
        .main-content .app-footer {
            margin-left: 0 !important;
            margin-top: 2rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 20px;
        }

        /* Ensure footer content wrapper has proper spacing */
        .content-wrapper {
            margin-bottom: 0;
        }
    </style>
    
    <?= $this->renderSection('css') ?>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="btn mobile-toggle" onclick="toggleSidebar()">
        <i class="material-symbols-rounded">menu</i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <h4><?= get_app_name() ?></h4>
            <small>Principal Panel</small>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == base_url('principal/dashboard')) ? 'active' : '' ?>" href="<?= base_url('principal/dashboard') ?>">
                        <i class="material-symbols-rounded">dashboard</i>
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/students') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/students') ?>">
                        <i class="material-symbols-rounded">school</i>
                        Students
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/teachers') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/teachers') ?>">
                        <i class="material-symbols-rounded">person</i>
                        Teachers
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/classes') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/classes') ?>">
                        <i class="material-symbols-rounded">class</i>
                        Classes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'questions') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/questions') ?>">
                        <i class="material-symbols-rounded">quiz</i>
                        Question Bank
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/exams') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/exams') ?>">
                        <i class="material-symbols-rounded">assignment</i>
                        Exams
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/violations') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/violations') ?>">
                        <i class="material-symbols-rounded">security</i>
                        Violations
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/reports') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/reports') ?>">
                        <i class="material-symbols-rounded">analytics</i>
                        Reports
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/settings') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/settings') ?>">
                        <i class="material-symbols-rounded">settings</i>
                        Settings
                    </a>
                </li>

                <hr class="my-3">
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'principal/profile') !== false) ? 'active' : '' ?>" href="<?= base_url('principal/profile') ?>">
                        <i class="material-symbols-rounded">account_circle</i>
                        Profile
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                        <i class="material-symbols-rounded">logout</i>
                        Sign Out
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            <?= $this->renderSection('page_content') ?>
        </div>

        <!-- Footer -->
        <?= $this->include('partials/footer') ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    
    <?= $this->renderSection('js') ?>
</body>
</html>
