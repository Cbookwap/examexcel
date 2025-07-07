<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>System Error - ExamExcel</title>
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
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --border-radius-lg: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.25rem;
            --font-size-4xl: 2.25rem;
            --font-weight-medium: 500;
            --font-weight-bold: 700;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--danger-color) 0%, #fc8181 100%);
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
            background: linear-gradient(135deg, var(--danger-color) 0%, #fc8181 100%);
            color: var(--white-color);
            padding: var(--spacing-2xl);
        }

        .error-icon {
            font-size: var(--font-size-4xl);
            margin-bottom: var(--spacing-md);
            opacity: 0.9;
            animation: pulse 2s infinite;
        }

        .error-title {
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
            margin-bottom: var(--spacing-md);
        }

        .error-subtitle {
            font-size: var(--font-size-lg);
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

        .error-actions {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white-color);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
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
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .brand-logo {
            font-weight: var(--font-weight-bold);
            color: var(--primary-color);
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.9; }
            50% { opacity: 0.6; }
        }

        @media (max-width: 768px) {
            .error-container {
                width: 95%;
                margin: var(--spacing-md);
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
    <div class="error-container">
        <div class="error-header">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="error-title">Oops! Something went wrong</div>
            <div class="error-subtitle">We're experiencing technical difficulties</div>
        </div>

        <div class="error-body">
            <div class="error-message">
                We apologize for the inconvenience. Our technical team has been automatically notified and is working to resolve this issue as quickly as possible.
            </div>

            <div class="error-actions">
                <a href="javascript:location.reload()" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                    Try Again
                </a>
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Go Home
                </a>
            </div>
        </div>

        <div class="error-footer">
            <div class="brand-logo">SRMS CBT System</div>
            <div>Peculiar Digital Solution - Advanced CBT Platform</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.error-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';

            setTimeout(() => {
                container.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);

            // Auto-refresh after 30 seconds
            setTimeout(() => {
                const refreshBtn = document.querySelector('.btn-secondary');
                if (refreshBtn) {
                    refreshBtn.innerHTML = '<i class="fas fa-redo"></i> Auto-refreshing...';
                    setTimeout(() => location.reload(), 2000);
                }
            }, 30000);
        });
    </script>
</body>
</html>
