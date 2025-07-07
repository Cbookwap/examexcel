<?php
// Get current year for copyright
$currentYear = date('Y');
$institutionName = get_institution_name();
$appName = get_app_name();
?>

<footer class="app-footer">
    <!-- Animated Background Elements -->
    <div class="footer-bg-animation">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
    </div>

    <div class="footer-content">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Left Section: Institution Info -->
                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                    <div class="footer-brand">
                        <?php $logo = get_app_logo(); ?>
                        <?php if ($logo): ?>
                            <img src="<?= $logo ?>" alt="<?= $institutionName ?>" class="footer-logo">
                        <?php else: ?>
                            <i class="fas fa-graduation-cap footer-icon"></i>
                        <?php endif; ?>
                        <div class="footer-brand-text">
                            <h6 class="institution-name"><?= esc($institutionName) ?></h6>
                            <p class="app-tagline">Empowering assessment integrity with smart, reliable, and user-friendly digital solutions</p>
                        </div>
                    </div>
                </div>

                <!-- Center Section: Quick Links -->
                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                    <div class="footer-links">
                        <h6 class="footer-title">Quick Links</h6>
                        <div class="links-grid">
                            <?php if (session()->get('role') === 'student'): ?>
                                <a href="<?= base_url('student/dashboard') ?>" class="footer-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="<?= base_url('student/exams') ?>" class="footer-link">
                                    <i class="fas fa-clipboard-list"></i>
                                    Exams
                                </a>
                                <a href="<?= base_url('student/results') ?>" class="footer-link">
                                    <i class="fas fa-chart-line"></i>
                                    Results
                                </a>
                                <a href="<?= base_url('student/practice') ?>" class="footer-link">
                                    <i class="fas fa-dumbbell"></i>
                                    Practice
                                </a>
                            <?php elseif (session()->get('role') === 'teacher'): ?>
                                <a href="<?= base_url('teacher/dashboard') ?>" class="footer-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="<?= base_url('teacher/questions') ?>" class="footer-link">
                                    <i class="fas fa-question-circle"></i>
                                    Questions
                                </a>
                                <a href="<?= base_url('teacher/exams') ?>" class="footer-link">
                                    <i class="fas fa-clipboard-list"></i>
                                    Exams
                                </a>
                                <a href="<?= base_url('teacher/students') ?>" class="footer-link">
                                    <i class="fas fa-users"></i>
                                    Students
                                </a>
                            <?php elseif (session()->get('role') === 'admin'): ?>
                                <a href="<?= base_url('admin/dashboard') ?>" class="footer-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="<?= base_url('admin/users') ?>" class="footer-link">
                                    <i class="fas fa-users-cog"></i>
                                    Users
                                </a>
                                <a href="<?= base_url('admin/exams') ?>" class="footer-link">
                                    <i class="fas fa-clipboard-list"></i>
                                    Exams
                                </a>
                                <a href="<?= base_url('admin/settings') ?>" class="footer-link">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                            <?php elseif (session()->get('role') === 'principal'): ?>
                                <a href="<?= base_url('principal/dashboard') ?>" class="footer-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="<?= base_url('principal/students') ?>" class="footer-link">
                                    <i class="fas fa-user-graduate"></i>
                                    Students
                                </a>
                                <a href="<?= base_url('principal/teachers') ?>" class="footer-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    Teachers
                                </a>
                                <a href="<?= base_url('principal/classes') ?>" class="footer-link">
                                    <i class="fas fa-school"></i>
                                    Classes
                                </a>
                            <?php elseif (session()->get('role') === 'class_teacher'): ?>
                                <a href="<?= base_url('class-teacher/dashboard') ?>" class="footer-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="<?= base_url('class-teacher/marksheet') ?>" class="footer-link">
                                    <i class="fas fa-chart-bar"></i>
                                    Marksheet
                                </a>
                                <a href="<?= base_url('auth/logout') ?>" class="footer-link">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url() ?>" class="footer-link">
                                    <i class="fas fa-home"></i>
                                    Home
                                </a>
                                <a href="<?= base_url('auth/login') ?>" class="footer-link">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Login
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Copyright & Developer -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-info">
                        <div class="copyright">
                            <i class="fas fa-copyright"></i>
                            <?= $currentYear ?> <?= esc($institutionName) ?>
                        </div>
                        <div class="developer-credit">
                            <span class="powered-by">Powered by</span>
                            <a href="https://peculiardigitals.netlify.app" target="_blank" class="developer-link">
                                <i class="fas fa-code"></i>
                                Peculiar Digital Solution
                            </a>
                        </div>
                        <div class="version-info">
                            <i class="fas fa-info-circle"></i>
                            ExamExcel Web CBT System v<?= get_app_setting('system_version', '1.0.0') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Wave -->
    <div class="footer-wave">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
        </svg>
    </div>
</footer>

<style>
/* Dynamic Theme Variables for Footer */
:root {
    <?php
    helper('settings');
    $themeSettings = get_theme_settings();

    // Set CSS variables for theme colors
    echo "--primary-color: {$themeSettings['primary_color']};";
    echo "--primary-light: {$themeSettings['primary_light']};";
    echo "--primary-dark: {$themeSettings['primary_dark']};";
    echo "--primary-color-rgb: {$themeSettings['primary_color_rgb']};";
    ?>
}

/* Footer Styles */
.app-footer {
    position: relative;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    margin-top: auto;
    overflow: hidden;
    z-index: 1;
    border-top-left-radius: 20px;
}

/* Animated Background */
.footer-bg-animation {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
}

.floating-shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    top: 10%;
    right: 30%;
    animation-delay: 4s;
}

.shape-4 {
    width: 40px;
    height: 40px;
    bottom: 30%;
    left: 60%;
    animation-delay: 1s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.7;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 1;
    }
}

/* Footer Content */
.footer-content {
    position: relative;
    padding: 3rem 0 2rem;
    z-index: 2;
}

/* Footer Brand */
.footer-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideInLeft 1s ease-out;
}

.footer-logo {
    max-height: 3rem;
    max-width: 150px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.footer-icon {
    font-size: 2.5rem;
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.footer-brand-text .institution-name {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0;
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.footer-brand-text .app-tagline {
    font-size: 0.85rem;
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 400;
    line-height: 1.4;
    font-style: italic;
    max-width: 280px;
}

/* Footer Links */
.footer-links {
    text-align: center;
    animation: slideInUp 1s ease-out 0.2s both;
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.links-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.8rem;
}

.footer-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
}

.footer-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.footer-link i {
    font-size: 0.8rem;
    width: 16px;
    text-align: center;
}

/* Footer Info */
.footer-info {
    text-align: right;
    animation: slideInRight 1s ease-out 0.4s both;
}

.copyright {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0.8rem;
    font-weight: 500;
}

.copyright i {
    margin-right: 0.3rem;
}

.developer-credit {
    margin-bottom: 0.8rem;
}

.powered-by {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
    display: block;
    margin-bottom: 0.3rem;
}

.developer-link {
    color: #ffd700;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.developer-link:hover {
    color: #ffed4e;
    transform: scale(1.05);
    text-shadow: 0 2px 10px rgba(255, 215, 0, 0.5);
}

.version-info {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 400;
}

.version-info i {
    margin-right: 0.3rem;
}

/* Footer Wave */
.footer-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    transform: rotate(180deg);
}

.footer-wave svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 60px;
}

.footer-wave .shape-fill {
    fill: rgba(255, 255, 255, 0.1);
}

/* Animations */
@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-content {
        padding: 2rem 0 1.5rem;
    }
    
    .footer-brand {
        justify-content: center;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .footer-links {
        margin-bottom: 2rem;
    }
    
    .links-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.6rem;
    }
    
    .footer-info {
        text-align: center;
    }
    
    .footer-wave svg {
        height: 40px;
    }
}

@media (max-width: 576px) {
    .links-grid {
        grid-template-columns: 1fr;
    }
    
    .footer-link {
        justify-content: center;
    }
}

/* Footer positioning - align with sidebar */
.app-footer {
    margin-left: 15rem; /* Match sidebar width exactly (240px) */
    transition: margin-left 0.2s ease-in-out;
    margin-top: 3rem; /* Add some space above footer */
}

/* Responsive footer alignment - match dashboard layout breakpoints */
@media (max-width: 1199.98px) {
    .app-footer {
        margin-left: 0; /* Full width on mobile/tablet */
    }
}

/* Ensure footer aligns with Material Dashboard layout */
.g-sidenav-show .app-footer {
    margin-left: 15rem;
    transition: margin-left 0.2s ease-in-out;
}

@media (max-width: 1199.98px) {
    .g-sidenav-show .app-footer {
        margin-left: 0;
    }
}

/* Handle collapsed sidebar state */
.sidebar.collapsed ~ .main-content .app-footer,
.g-sidenav-show.g-sidenav-hidden .app-footer {
    margin-left: 4rem; /* Match collapsed sidebar width */
}

@media (max-width: 1199.98px) {
    .sidebar.collapsed ~ .main-content .app-footer,
    .g-sidenav-show.g-sidenav-hidden .app-footer {
        margin-left: 0;
    }
}

/* Principal layout specific footer positioning */
body:has(.sidebar[id="sidebar"]) .app-footer {
    margin-left: 280px; /* Match principal sidebar width (280px) */
}

@media (max-width: 768px) {
    body:has(.sidebar[id="sidebar"]) .app-footer {
        margin-left: 0;
    }
}
</style>
