<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable - ExamExcel</title>
    <link href="<?= base_url('assets/vendor/fonts/inter/inter.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --warning-color: #dd6b20;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --white-color: #ffffff;
            --light-color: #f7fafc;
            --border-color: #e2e8f0;
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --border-radius-lg: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.25rem;
            --font-size-5xl: 3rem;
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
        }

        .error-icon {
            font-size: var(--font-size-5xl);
            margin-bottom: var(--spacing-md);
            opacity: 0.9;
            animation: pulse 2s infinite;
        }

        .error-code {
            font-size: var(--font-size-5xl);
            font-weight: var(--font-weight-bold);
            margin-bottom: var(--spacing-md);
        }

        .error-title {
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-medium);
        }

        .error-body {
            padding: var(--spacing-2xl);
        }

        .error-message {
            font-size: var(--font-size-lg);
            color: var(--text-secondary);
            margin-bottom: var(--spacing-xl);
        }

        .countdown {
            background: var(--light-color);
            padding: var(--spacing-lg);
            border-radius: 0.5rem;
            margin-bottom: var(--spacing-xl);
            font-weight: var(--font-weight-medium);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md) var(--spacing-lg);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white-color);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: var(--font-weight-medium);
            transition: transform 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
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
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            <div class="error-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="error-code">503</div>
            <div class="error-title">Service Unavailable</div>
        </div>

        <div class="error-body">
            <div class="error-message">
                <?php if (ENVIRONMENT !== 'production') : ?>
                    <?= nl2br(esc($message)) ?>
                <?php else : ?>
                    The service is temporarily unavailable due to maintenance or high load. Please try again shortly.
                <?php endif; ?>
            </div>

            <div class="countdown">
                <i class="fas fa-clock"></i>
                Auto-retry in <span id="countdown">60</span> seconds...
            </div>

            <a href="javascript:location.reload()" class="btn">
                <i class="fas fa-redo"></i>
                Try Now
            </a>
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

            // Countdown timer
            let countdown = 60;
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    location.reload();
                }
            }, 1000);
        });
    </script>
</body>
</html>
