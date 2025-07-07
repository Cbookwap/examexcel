<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Favicon -->
    <?php $favicon = get_app_favicon(); ?>
    <?php if ($favicon): ?>
        <link rel="icon" type="image/png" href="<?= $favicon ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= $favicon ?>">
    <?php else: ?>
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <?php endif; ?>

    <link href="<?= base_url('assets/vendor/fonts/inter/inter.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --white-color: #ffffff;
            --light-color: #f8fafc;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --border-radius-lg: 0.5rem;
            --border-radius-xl: 0.75rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-xxl: 3rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        .floating-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatUp 6s infinite linear;
        }

        .particle:nth-child(1) {
            left: 10%;
            width: 4px;
            height: 4px;
            animation-delay: 0s;
            animation-duration: 8s;
        }

        .particle:nth-child(2) {
            left: 20%;
            width: 6px;
            height: 6px;
            animation-delay: 1s;
            animation-duration: 10s;
        }

        .particle:nth-child(3) {
            left: 30%;
            width: 3px;
            height: 3px;
            animation-delay: 2s;
            animation-duration: 7s;
        }

        .particle:nth-child(4) {
            left: 40%;
            width: 5px;
            height: 5px;
            animation-delay: 3s;
            animation-duration: 9s;
        }

        .particle:nth-child(5) {
            left: 50%;
            width: 4px;
            height: 4px;
            animation-delay: 4s;
            animation-duration: 8s;
        }

        .particle:nth-child(6) {
            left: 60%;
            width: 6px;
            height: 6px;
            animation-delay: 5s;
            animation-duration: 11s;
        }

        .particle:nth-child(7) {
            left: 70%;
            width: 3px;
            height: 3px;
            animation-delay: 6s;
            animation-duration: 7s;
        }

        .particle:nth-child(8) {
            left: 80%;
            width: 5px;
            height: 5px;
            animation-delay: 7s;
            animation-duration: 9s;
        }

        .particle:nth-child(9) {
            left: 90%;
            width: 4px;
            height: 4px;
            animation-delay: 8s;
            animation-duration: 8s;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: var(--white-color);
            border-radius: var(--border-radius-xl);
            box-shadow:
                var(--shadow-lg),
                0 0 40px rgba(102, 126, 234, 0.15),
                0 0 80px rgba(118, 75, 162, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
            animation: cardGlow 4s ease-in-out infinite alternate;
            position: relative;
            border: 2px solid transparent;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: conic-gradient(
                from 0deg,
                transparent 0deg,
                transparent 340deg,
                rgba(102, 126, 234, 0.8) 350deg,
                rgba(118, 75, 162, 1) 355deg,
                rgba(240, 147, 251, 0.8) 360deg,
                transparent 10deg,
                transparent 360deg
            );
            border-radius: calc(var(--border-radius-xl) + 1px);
            z-index: -1;
            animation: flowBorder 2.5s linear infinite;
            filter: blur(1px);
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--white-color);
            border-radius: var(--border-radius-xl);
            z-index: -1;
        }

        @keyframes flowBorder {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes cardGlow {
            0% {
                box-shadow:
                    var(--shadow-lg),
                    0 0 40px rgba(102, 126, 234, 0.15),
                    0 0 80px rgba(118, 75, 162, 0.1);
            }
            100% {
                box-shadow:
                    var(--shadow-lg),
                    0 0 60px rgba(102, 126, 234, 0.25),
                    0 0 120px rgba(118, 75, 162, 0.15);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--white-color);
            padding: var(--spacing-xxl);
            text-align: center;
        }

        .login-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
        }

        .login-header p {
            opacity: 0.9;
            font-size: 0.875rem;
        }

        .login-body {
            padding: var(--spacing-xxl);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-text {
            background: var(--light-color);
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: var(--border-radius-lg) 0 0 var(--border-radius-lg);
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
        }

        .form-control {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0 var(--border-radius-lg) var(--border-radius-lg) 0;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: var(--white-color);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            border-radius: var(--border-radius-lg);
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--white-color);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-lg);
        }

        .form-check-input {
            width: 1rem;
            height: 1rem;
        }

        .form-check-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }



        .alert {
            padding: var(--spacing-md);
            border-radius: var(--border-radius-lg);
            margin-bottom: var(--spacing-lg);
            font-size: 0.875rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            z-index: 10;
        }

        @media (max-width: 768px) {
            body {
                padding: var(--spacing-md);
            }

            .login-header {
                padding: var(--spacing-xl);
            }

            .login-body {
                padding: var(--spacing-xl);
            }
        }
    </style>
</head>
<body>
    <!-- Floating Particles -->
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <?php $logo = get_app_logo(); ?>
                <?php if ($logo): ?>
                    <div style="margin-bottom: 1rem;">
                        <img src="<?= $logo ?>" alt="<?= get_app_name() ?>" style="max-height: 3rem; max-width: 200px;">
                    </div>
                    <h1><?= get_app_name() ?></h1>
                <?php else: ?>
                    <h1><i class="fas fa-graduation-cap"></i> <?= get_app_name() ?></h1>
                <?php endif; ?>
                <p>Computer-Based Test Web Application</p>
            </div>

            <div class="login-body">
                <?php $newsFlash = get_news_flash(); ?>
                <?php if ($newsFlash['enabled'] && !empty($newsFlash['content'])): ?>
                    <div class="alert" style="background: rgba(255, 193, 7, 0.1); border: 1px solid #ffc107; color: #856404; margin-bottom: 1.5rem;">
                        <i class="fas fa-bullhorn me-2"></i>
                        <strong>News:</strong> <?= esc($newsFlash['content']) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('auth/login') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="username" class="form-label">Username, Email or Student ID</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="<?= old('username') ?>" required
                                   placeholder="Enter username, email or student ID">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password"
                                   required placeholder="Enter password">
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        LOGIN
                    </button>
                </form>
            </div>
        </div>


    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
