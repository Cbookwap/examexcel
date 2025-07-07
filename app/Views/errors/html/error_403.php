<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Forbidden - SRMS CBT System</title>
    <link href="<?= base_url('assets/vendor/fonts/inter/inter.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --secondary-color: #764ba2;
            --danger-color: #e53e3e;
            --warning-color: #dd6b20;
            --success-color: #38a169;
            --info-color: #3182ce;
            --light-color: #f7fafc;
            --dark-color: #2d3748;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --border-color: #e2e8f0;
            --white-color: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --border-radius-sm: 0.375rem;
            --border-radius-md: 0.5rem;
            --border-radius-lg: 0.75rem;
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            --font-size-sm: 0.875rem;
            --font-size-base: 1rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.25rem;
            --font-size-2xl: 1.5rem;
            --font-size-3xl: 1.875rem;
            --font-size-4xl: 2.25rem;
            --font-size-5xl: 3rem;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;
            --transition-duration: 0.3s;
            --transition-easing: cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--warning-color) 0%, #f6ad55 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .error-container {
            max-width: 600px;
            width: 90%;
            background: var(--white-color);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            text-align: center;
            position: relative;
        }

        .error-header {
            background: linear-gradient(135deg, var(--warning-color) 0%, #f6ad55 100%);
            color: var(--white-color);
            padding: var(--spacing-2xl);
            position: relative;
        }

        .error-icon {
            font-size: var(--font-size-5xl);
            margin-bottom: var(--spacing-md);
            opacity: 0.9;
        }

        .error-code {
            font-size: var(--font-size-5xl);
            font-weight: var(--font-weight-bold);
            margin-bottom: var(--spacing-sm);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-medium);
            opacity: 0.9;
        }

        .error-body {
            padding: var(--spacing-2xl);
        }

        .error-message {
            font-size: var(--font-size-lg);
            color: var(--text-secondary);
            margin-bottom: var(--spacing-xl);
            line-height: 1.7;
        }

        .error-suggestions {
            background: var(--light-color);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            text-align: left;
        }

        .error-suggestions h4 {
            color: var(--text-primary);
            font-weight: var(--font-weight-semibold);
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .error-suggestions ul {
            list-style: none;
            padding: 0;
        }

        .error-suggestions li {
            padding: var(--spacing-sm) 0;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .error-suggestions li:before {
            content: "→";
            color: var(--warning-color);
            font-weight: var(--font-weight-bold);
        }

        .error-actions {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--border-radius-md);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            transition: all var(--transition-duration) var(--transition-easing);
            border: none;
            cursor: pointer;
            font-size: var(--font-size-base);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white-color);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--white-color);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .error-footer {
            background: var(--light-color);
            padding: var(--spacing-lg);
            border-top: 1px solid var(--border-color);
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
        }

        .brand-logo {
            font-weight: var(--font-weight-bold);
            color: var(--primary-color);
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @media (max-width: 768px) {
            .error-container {
                width: 95%;
                margin: var(--spacing-md);
            }
            
            .error-header {
                padding: var(--spacing-xl);
            }
            
            .error-code {
                font-size: var(--font-size-4xl);
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape">
            <i class="fas fa-shield-alt" style="font-size: 3rem;"></i>
        </div>
        <div class="shape">
            <i class="fas fa-lock" style="font-size: 2.5rem;"></i>
        </div>
        <div class="shape">
            <i class="fas fa-key" style="font-size: 2rem;"></i>
        </div>
    </div>

    <div class="error-container">
        <div class="error-header">
            <div class="error-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="error-code">403</div>
            <div class="error-title">Access Forbidden</div>
        </div>

        <div class="error-body">
            <div class="error-message">
                <?php if (ENVIRONMENT !== 'production') : ?>
                    <?= nl2br(esc($message)) ?>
                <?php else : ?>
                    Sorry, you don't have permission to access this resource. This area is restricted and requires proper authorization.
                <?php endif; ?>
            </div>

            <div class="error-suggestions">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    What you can do:
                </h4>
                <ul>
                    <li>Check if you're logged in with the correct account</li>
                    <li>Verify you have the necessary permissions</li>
                    <li>Contact your administrator for access</li>
                    <li>Return to a page you have access to</li>
                    <li>Log out and try with different credentials</li>
                </ul>
            </div>

            <div class="error-actions">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </a>
                <a href="/auth/login" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
            </div>
        </div>

        <div class="error-footer">
            <div class="brand-logo">SRMS CBT System</div>
            <div>Peculiar Digital Solution - Advanced CBT Platform</div>
        </div>
    </div>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate error container on load
            const container = document.querySelector('.error-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
