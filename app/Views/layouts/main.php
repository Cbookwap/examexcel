<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= get_app_name() ?> - ExamExcel">
    <meta name="author" content="<?= get_app_name() ?>">
    <title><?= $title ?? get_app_name() ?></title>

    <!-- Favicon -->
    <?php $favicon = get_app_favicon(); ?>
    <?php if ($favicon): ?>
        <link rel="icon" type="image/png" href="<?= $favicon ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= $favicon ?>">
    <?php else: ?>
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <?php endif; ?>

    <!-- Fonts and icons - LOCAL VERSION -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/fonts/inter/inter.css') ?>" />
    <!-- Nucleo Icons -->
    <link href="<?= base_url('assets/css/nucleo-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/nucleo-svg.css') ?>" rel="stylesheet" />
    <!-- Font Awesome Icons - LOCAL VERSION -->
    <link href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>" rel="stylesheet" />
    <!-- Material Icons - LOCAL VERSION -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/material-icons/material-icons.css') ?>" />

    <!-- Material Dashboard CSS -->
    <link id="pagestyle" href="<?= base_url('assets/css/material-dashboard.css?v=3.2.0') ?>" rel="stylesheet" />

    <!-- CSS Variables from Theme Config -->
    <style>
        <?php
        $theme = new \App\Config\UITheme();
        echo $theme->getCSSVariables();
        ?>
    </style>

    <!-- Additional CSS -->
    <?= $this->renderSection('css') ?>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
</head>
<body class="g-sidenav-show bg-gray-100 <?= $bodyClass ?? '' ?>" style="min-height: 100vh; display: flex; flex-direction: column;">

    <!-- Main Content -->
    <div id="app" style="flex: 1; display: flex; flex-direction: column;">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay d-none">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Loading...</span>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Global JavaScript -->
    <script>
        // Global Configuration
        window.CBT = {
            baseUrl: '<?= base_url() ?>',
            csrfToken: '<?= csrf_hash() ?>',
            csrfName: '<?= csrf_token() ?>',
            user: {
                id: <?= session()->get('user_id') ?? 'null' ?>,
                role: '<?= session()->get('role') ?? '' ?>',
                name: '<?= session()->get('full_name') ?? '' ?>'
            }
        };
    </script>

    <!-- Material Dashboard Core JS -->
    <script src="<?= base_url('assets/js/core/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/smooth-scrollbar.min.js') ?>"></script>

    <!-- Material Dashboard Main JS -->
    <script src="<?= base_url('assets/js/material-dashboard.min.js?v=3.2.0') ?>"></script>

    <!-- Material Icons to FontAwesome Converter -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Comprehensive icon mapping from Material Icons to FontAwesome
        const iconMap = {
            // Navigation & Dashboard
            'dashboard': 'fas fa-tachometer-alt',
            'home': 'fas fa-home',
            'menu': 'fas fa-bars',
            'keyboard_arrow_down': 'fas fa-chevron-down',
            'keyboard_arrow_up': 'fas fa-chevron-up',
            'keyboard_arrow_left': 'fas fa-chevron-left',
            'keyboard_arrow_right': 'fas fa-chevron-right',
            'expand_more': 'fas fa-chevron-down',
            'expand_less': 'fas fa-chevron-up',

            // Users & People
            'group': 'fas fa-users',
            'person': 'fas fa-user',
            'person_add': 'fas fa-user-plus',
            'people': 'fas fa-users',
            'account_circle': 'fas fa-user-circle',
            'manage_accounts': 'fas fa-user-cog',

            // Education & Academic
            'school': 'fas fa-graduation-cap',
            'book': 'fas fa-book',
            'library_books': 'fas fa-books',
            'class': 'fas fa-chalkboard',
            'subject': 'fas fa-book-open',

            // Assignments & Tasks
            'assignment': 'fas fa-clipboard-list',
            'assignment_add': 'fas fa-plus-square',
            'assignment_ind': 'fas fa-tasks',
            'task': 'fas fa-tasks',
            'quiz': 'fas fa-question-circle',
            'question_answer': 'fas fa-comments',

            // Analytics & Charts
            'analytics': 'fas fa-chart-bar',
            'trending_up': 'fas fa-chart-line',
            'trending_down': 'fas fa-chart-line-down',
            'insights': 'fas fa-lightbulb',
            'assessment': 'fas fa-clipboard-check',
            'bar_chart': 'fas fa-chart-bar',
            'pie_chart': 'fas fa-chart-pie',

            // Settings & Admin
            'settings': 'fas fa-cogs',
            'admin_panel_settings': 'fas fa-user-shield',
            'tune': 'fas fa-sliders-h',
            'build': 'fas fa-tools',

            // Actions & Controls
            'add': 'fas fa-plus',
            'edit': 'fas fa-edit',
            'delete': 'fas fa-trash',
            'remove': 'fas fa-minus',
            'save': 'fas fa-save',
            'cancel': 'fas fa-times',
            'close': 'fas fa-times',
            'done': 'fas fa-check',
            'check': 'fas fa-check',
            'clear': 'fas fa-times',

            // Media Controls
            'play_arrow': 'fas fa-play',
            'pause': 'fas fa-pause',
            'stop': 'fas fa-stop',
            'replay': 'fas fa-redo',

            // Files & Documents
            'description': 'fas fa-file-alt',
            'folder': 'fas fa-folder',
            'file_copy': 'fas fa-copy',
            'download': 'fas fa-download',
            'upload': 'fas fa-upload',
            'attach_file': 'fas fa-paperclip',

            // Communication
            'email': 'fas fa-envelope',
            'phone': 'fas fa-phone',
            'message': 'fas fa-comment',
            'notifications': 'fas fa-bell',

            // Status & Indicators
            'check_circle': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle',
            'info': 'fas fa-info-circle',
            'help': 'fas fa-question-circle',
            'circle': 'fas fa-circle',

            // Time & Calendar
            'calendar_today': 'fas fa-calendar-alt',
            'schedule': 'fas fa-clock',
            'timer': 'fas fa-stopwatch',
            'access_time': 'fas fa-clock',

            // Search & Filter
            'search': 'fas fa-search',
            'filter_list': 'fas fa-filter',
            'sort': 'fas fa-sort',

            // Visibility & Security
            'visibility': 'fas fa-eye',
            'visibility_off': 'fas fa-eye-slash',
            'lock': 'fas fa-lock',
            'lock_open': 'fas fa-lock-open',
            'security': 'fas fa-shield-alt',

            // Missing icons from screenshots
            'category': 'fas fa-folder-open',
            'pause_circle': 'fas fa-pause-circle',
            'upcoming': 'fas fa-clock',
            'event_available': 'fas fa-calendar-check',
            'assignment_turned_in': 'fas fa-clipboard-check',
            'list': 'fas fa-list',
            'table_view': 'fas fa-table',
            'emoji_events': 'fas fa-trophy',
            'event': 'fas fa-calendar-alt',
            'refresh': 'fas fa-sync-alt',
            'arrow_back': 'fas fa-arrow-left',
            'delete_forever': 'fas fa-trash-alt',
            'hourglass_empty': 'fas fa-hourglass-half',
            'palette': 'fas fa-palette',
            'backup': 'fas fa-database',
            'cleaning_services': 'fas fa-broom',
            'image': 'fas fa-image',
            'tab': 'fas fa-window-maximize',
            'logout': 'fas fa-sign-out-alt',
            'calendar_month': 'fas fa-calendar-alt',
            'preview': 'fas fa-eye',

            // Additional missing icons from screenshots
            'fitness_center': 'fas fa-dumbbell',
            'library_add': 'fas fa-plus-square',
            'content_copy': 'fas fa-copy',
            'checklist': 'fas fa-tasks',
            'visibility_off': 'fas fa-eye-slash',
            'bolt': 'fas fa-bolt',
            'select_all': 'fas fa-check-square',
            'deselect': 'fas fa-square',

            // Reports page icons
            'timeline': 'fas fa-chart-line',
            'groups': 'fas fa-users',
            'login': 'fas fa-sign-in-alt',
            'computer': 'fas fa-desktop',
            'bar_chart': 'fas fa-chart-bar',
            'leaderboard': 'fas fa-trophy',
            'storage': 'fas fa-database',

            // Question creation icons
            'radio_button_checked': 'fas fa-dot-circle',
            'edit_note': 'fas fa-edit',
            'playlist_add': 'fas fa-plus-square',
            'bookmark': 'fas fa-bookmark',
            'print': 'fas fa-print',
            'close': 'fas fa-times',

            // Bulk creation icons
            'unfold_more': 'fas fa-expand-alt',
            'unfold_less': 'fas fa-compress-alt',
            'expand_more': 'fas fa-chevron-down',
            'expand_less': 'fas fa-chevron-up'
        };

        // Convert all material-symbols-rounded elements
        window.convertMaterialIcons = function convertMaterialIcons() {
            const materialIcons = document.querySelectorAll('.material-symbols-rounded');

            materialIcons.forEach(icon => {
                const iconText = icon.textContent.trim();

                if (iconMap[iconText]) {
                    // Replace classes
                    icon.className = icon.className.replace('material-symbols-rounded', iconMap[iconText]);
                    // Clear text content
                    icon.textContent = '';
                    // Add converted attribute to avoid re-processing
                    icon.setAttribute('data-converted', 'true');
                } else if (iconText && !icon.hasAttribute('data-converted')) {
                    // Log unmapped icons for debugging
                    console.warn('Unmapped Material Icon:', iconText);
                }
            });
        }

        // Run conversion
        convertMaterialIcons();

        // Also run on dynamic content
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    convertMaterialIcons();
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Apply theme-specific body classes
        applyThemeBodyClass();
    });

    // Function to apply theme-specific body classes
    function applyThemeBodyClass() {
        // Get current theme from CSS variables or settings
        const rootStyles = getComputedStyle(document.documentElement);
        const bodyBg = rootStyles.getPropertyValue('--body-bg').trim();

        // Remove existing theme classes
        document.body.classList.remove('gradient-theme', 'white-theme');

        // Apply theme-specific classes based on background
        if (bodyBg.includes('gradient')) {
            document.body.classList.add('gradient-theme');
        } else if (bodyBg === '#ffffff') {
            document.body.classList.add('white-theme');
        }
    }
    </script>

    <!-- Additional JavaScript -->
    <?= $this->renderSection('js') ?>

    <style>
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-spinner {
            background-color: var(--white-color);
            padding: var(--spacing-xl);
            border-radius: var(--border-radius-lg);
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        .loading-spinner i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: var(--spacing-md);
        }

        .loading-spinner span {
            display: block;
            color: var(--text-primary);
            font-weight: var(--font-weight-medium);
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: var(--spacing-lg);
            right: var(--spacing-lg);
            z-index: 1050;
            max-width: 350px;
        }

        .toast {
            background-color: var(--white-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-lg);
            margin-bottom: var(--spacing-sm);
            overflow: hidden;
            opacity: 0;
            transform: translateX(100%);
            transition: all var(--transition-duration) var(--transition-easing);
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-header {
            display: flex;
            align-items: center;
            padding: var(--spacing-sm) var(--spacing-md);
            background-color: var(--light-color);
            border-bottom: 1px solid var(--border-color);
        }

        .toast-body {
            padding: var(--spacing-md);
        }

        .toast-success .toast-header {
            background-color: var(--success-color);
            color: var(--white-color);
        }

        .toast-warning .toast-header {
            background-color: var(--warning-color);
            color: var(--white-color);
        }

        .toast-danger .toast-header {
            background-color: var(--danger-color);
            color: var(--white-color);
        }

        .toast-info .toast-header {
            background-color: var(--info-color);
            color: var(--white-color);
        }

        .toast-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            margin-left: auto;
            padding: 0;
            font-size: 1.2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .toast-container {
                top: var(--spacing-sm);
                right: var(--spacing-sm);
                left: var(--spacing-sm);
                max-width: none;
            }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus Styles */
        *:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                color: black !important;
            }

            .card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
            }
        }
    </style>
</body>
</html>
