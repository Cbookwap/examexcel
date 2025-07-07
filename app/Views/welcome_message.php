<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to ExamExcel CBT System</title>
    <meta name="description" content="Computer Based Testing System for Educational Institutions">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon.ico') ?>">

    <!-- STYLES -->

    <style {csp-style-nonce}>
        * {
            transition: background-color 300ms ease, color 300ms ease;
        }
        *:focus {
            background-color: rgba(221, 72, 20, .2);
            outline: none;
        }
        html, body {
            color: rgba(33, 37, 41, 1);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 16px;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        header {
            background-color: rgba(255, 255, 255, 0.95);
            padding: .4rem 0 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .menu {
            padding: .4rem 2rem;
        }
        header ul {
            border-bottom: 1px solid rgba(242, 242, 242, 1);
            list-style-type: none;
            margin: 0;
            overflow: hidden;
            padding: 0;
            text-align: right;
        }
        header li {
            display: inline-block;
        }
        header li a {
            border-radius: 5px;
            color: rgba(102, 126, 234, 1);
            display: block;
            height: 44px;
            text-decoration: none;
            font-weight: 500;
        }
        header li.menu-item a {
            border-radius: 5px;
            margin: 5px 0;
            height: 38px;
            line-height: 36px;
            padding: .4rem .65rem;
            text-align: center;
            background-color: rgba(102, 126, 234, 0.1);
        }
        header li.menu-item a:hover,
        header li.menu-item a:focus {
            background-color: rgba(102, 126, 234, 0.2);
            color: rgba(102, 126, 234, 1);
        }
        header .logo {
            float: left;
            height: 44px;
            padding: .4rem .5rem;
        }
        header .menu-toggle {
            display: none;
            float: right;
            font-size: 2rem;
            font-weight: bold;
        }
        header .menu-toggle button {
            background-color: rgba(221, 72, 20, .6);
            border: none;
            border-radius: 3px;
            color: rgba(255, 255, 255, 1);
            cursor: pointer;
            font: inherit;
            font-size: 1.3rem;
            height: 36px;
            padding: 0;
            margin: 11px 0;
            overflow: visible;
            width: 40px;
        }
        header .menu-toggle button:hover,
        header .menu-toggle button:focus {
            background-color: rgba(221, 72, 20, .8);
            color: rgba(255, 255, 255, .8);
        }
        header .heroe {
            margin: 0 auto;
            max-width: 1100px;
            padding: 2rem 1.75rem 2.5rem 1.75rem;
            text-align: center;
        }
        header .heroe h1 {
            font-size: 3rem;
            font-weight: 600;
            color: rgba(102, 126, 234, 1);
            margin-bottom: 0.5rem;
        }
        header .heroe h2 {
            font-size: 1.3rem;
            font-weight: 400;
            color: rgba(118, 75, 162, 1);
            margin-bottom: 2rem;
        }
        section {
            margin: 0 auto;
            max-width: 1100px;
            padding: 2.5rem 1.75rem 3.5rem 1.75rem;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 10px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: transparent;
            color: #667eea;
            border-color: #667eea;
        }
        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        section h1 {
            margin-bottom: 2.5rem;
        }
        section h2 {
            font-size: 120%;
            line-height: 2.5rem;
            padding-top: 1.5rem;
        }
        section pre {
            background-color: rgba(247, 248, 249, 1);
            border: 1px solid rgba(242, 242, 242, 1);
            display: block;
            font-size: .9rem;
            margin: 2rem 0;
            padding: 1rem 1.5rem;
            white-space: pre-wrap;
            word-break: break-all;
        }
        section code {
            display: block;
        }
        section a {
            color: rgba(221, 72, 20, 1);
        }
        section svg {
            margin-bottom: -5px;
            margin-right: 5px;
            width: 25px;
        }
        .further {
            background-color: rgba(247, 248, 249, 1);
            border-bottom: 1px solid rgba(242, 242, 242, 1);
            border-top: 1px solid rgba(242, 242, 242, 1);
        }
        .further h2:first-of-type {
            padding-top: 0;
        }
        .svg-stroke {
            fill: none;
            stroke: #000;
            stroke-width: 32px;
        }
        footer {
            background-color: rgba(221, 72, 20, .8);
            text-align: center;
        }
        footer .environment {
            color: rgba(255, 255, 255, 1);
            padding: 2rem 1.75rem;
        }
        footer .copyrights {
            background-color: rgba(62, 62, 62, 1);
            color: rgba(200, 200, 200, 1);
            padding: .25rem 1.75rem;
        }
        @media (max-width: 629px) {
            header ul {
                padding: 0;
            }
            header .menu-toggle {
                padding: 0 1rem;
            }
            header .menu-item {
                background-color: rgba(244, 245, 246, 1);
                border-top: 1px solid rgba(242, 242, 242, 1);
                margin: 0 15px;
                width: calc(100% - 30px);
            }
            header .menu-toggle {
                display: block;
            }
            header .hidden {
                display: none;
            }
            header li.menu-item a {
                background-color: rgba(221, 72, 20, .1);
            }
            header li.menu-item a:hover,
            header li.menu-item a:focus {
                background-color: rgba(221, 72, 20, .7);
                color: rgba(255, 255, 255, .8);
            }
        }
    </style>
</head>
<body>

<!-- HEADER: MENU + HEROE SECTION -->
<header>

    <div class="menu">
        <ul>
            <li class="logo">
                <a href="<?= base_url() ?>">
                    <span style="font-size: 1.5rem; font-weight: bold; color: #667eea;">📚 ExamExcel</span>
                </a>
            </li>
            <li class="menu-toggle">
                <button id="menuToggle">&#9776;</button>
            </li>
            <li class="menu-item hidden"><a href="<?= base_url() ?>">Home</a></li>
            <?php if (session()->get('is_logged_in')): ?>
                <li class="menu-item hidden"><a href="<?= base_url(session()->get('role') . '/dashboard') ?>">Dashboard</a></li>
                <li class="menu-item hidden"><a href="<?= base_url('auth/logout') ?>">Logout</a></li>
            <?php else: ?>
                <li class="menu-item hidden"><a href="<?= base_url('auth/login') ?>">Login</a></li>
                <li class="menu-item hidden"><a href="<?= base_url('admin') ?>">Admin</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="heroe">

        <?php if (session()->get('is_logged_in')): ?>
            <h1>Welcome back, <?= esc(session()->get('first_name')) ?>!</h1>
            <h2>You are already logged in as <?= ucfirst(esc(session()->get('role'))) ?></h2>
            <div style="margin-top: 2rem;">
                <a href="<?= base_url(session()->get('role') . '/dashboard') ?>" class="btn btn-primary">🏠 Go to Dashboard</a>
                <a href="<?= base_url('auth/logout') ?>" class="btn btn-secondary">🚪 Logout</a>
            </div>
        <?php else: ?>
            <h1>Welcome to ExamExcel CBT System</h1>
            <h2>Advanced Computer Based Testing Platform for Educational Excellence</h2>
            <div style="margin-top: 2rem;">
                <a href="<?= base_url('auth/login') ?>" class="btn btn-primary">🚀 Login to System</a>
                <a href="<?= base_url('admin') ?>" class="btn btn-secondary">⚙️ Admin Panel</a>
            </div>
        <?php endif; ?>

    </div>

</header>

<!-- CONTENT -->

<section>

    <h1>🎯 About ExamExcel CBT System</h1>

    <p>ExamExcel is a comprehensive Computer Based Testing (CBT) platform designed for educational institutions. Our system provides a secure, reliable, and user-friendly environment for conducting online examinations.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin: 2rem 0;">

        <div style="padding: 1.5rem; border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h3>🔒 Secure Testing</h3>
            <p>Advanced security features including browser lockdown, anti-cheating measures, and secure question delivery.</p>
        </div>

        <div style="padding: 1.5rem; border-radius: 10px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <h3>📊 Real-time Analytics</h3>
            <p>Comprehensive reporting and analytics to track student performance and exam statistics.</p>
        </div>

        <div style="padding: 1.5rem; border-radius: 10px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <h3>🎨 User-Friendly Interface</h3>
            <p>Intuitive design for students, teachers, and administrators with responsive mobile support.</p>
        </div>

    </div>

    <div style="text-align: center; margin: 3rem 0;">
        <?php if (session()->get('is_logged_in')): ?>
            <h2>🎯 Quick Actions</h2>
            <p>You are currently logged in. Access your dashboard or explore the system:</p>
            <div style="margin: 2rem 0;">
                <a href="<?= base_url(session()->get('role') . '/dashboard') ?>" class="btn btn-primary">🏠 My Dashboard</a>
                <?php if (in_array(session()->get('role'), ['admin', 'principal'])): ?>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">👥 Manage Users</a>
                    <a href="<?= base_url('admin/exams') ?>" class="btn btn-secondary">📝 Manage Exams</a>
                <?php elseif (session()->get('role') === 'teacher'): ?>
                    <a href="<?= base_url('questions') ?>" class="btn btn-secondary">❓ Questions</a>
                    <a href="<?= base_url('teacher/exams') ?>" class="btn btn-secondary">📝 My Exams</a>
                <?php elseif (session()->get('role') === 'student'): ?>
                    <a href="<?= base_url('student/exams') ?>" class="btn btn-secondary">📝 Available Exams</a>
                    <a href="<?= base_url('student/results') ?>" class="btn btn-secondary">📊 My Results</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <h2>🚀 Get Started</h2>
            <p>Ready to begin your examination journey? Choose your role below:</p>
            <div style="margin: 2rem 0;">
                <a href="<?= base_url('auth/login') ?>" class="btn btn-primary">👨‍🎓 Student Login</a>
                <a href="<?= base_url('auth/login') ?>" class="btn btn-secondary">👨‍🏫 Teacher Login</a>
                <a href="<?= base_url('admin') ?>" class="btn btn-secondary">👨‍💼 Admin Panel</a>
            </div>
        <?php endif; ?>
    </div>

</section>

<div class="further">

    <section>

        <h1>🌟 System Features</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin: 2rem 0;">

            <div>
                <h3>📝 Question Management</h3>
                <p>Create, edit, and organize questions with multiple question types including multiple choice, true/false, and essay questions.</p>
            </div>

            <div>
                <h3>👥 User Management</h3>
                <p>Comprehensive user management for students, teachers, and administrators with role-based access control.</p>
            </div>

            <div>
                <h3>📊 Exam Analytics</h3>
                <p>Detailed performance analytics, grade reports, and statistical analysis of exam results.</p>
            </div>

            <div>
                <h3>🔐 Security Features</h3>
                <p>Advanced security measures including session monitoring, browser lockdown, and anti-cheating detection.</p>
            </div>

            <div>
                <h3>📱 Mobile Responsive</h3>
                <p>Fully responsive design that works seamlessly on desktop, tablet, and mobile devices.</p>
            </div>

            <div>
                <h3>🤖 AI Integration</h3>
                <p>AI-powered question generation and automated grading capabilities for enhanced efficiency.</p>
            </div>

        </div>

    </section>

</div>

<!-- FOOTER: SYSTEM INFO + COPYRIGHTS -->

<footer>
    <div class="environment">

        <p>🚀 ExamExcel CBT System - Powered by Advanced Technology</p>

        <p>Environment: <?= ENVIRONMENT ?> | Network Ready ✅</p>

    </div>

    <div class="copyrights">

        <p>&copy; <?= date('Y') ?> ExamExcel CBT System. Developed by <a href="https://peculiardigitals.netlify.app" target="_blank" style="color: #ccc;">Peculiar Digital Solution</a></p>

    </div>

</footer>

<!-- SCRIPTS -->

<script {csp-script-nonce}>
    document.getElementById("menuToggle").addEventListener('click', toggleMenu);
    function toggleMenu() {
        var menuItems = document.getElementsByClassName('menu-item');
        for (var i = 0; i < menuItems.length; i++) {
            var menuItem = menuItems[i];
            menuItem.classList.toggle("hidden");
        }
    }
</script>

<!-- -->

</body>
</html>
