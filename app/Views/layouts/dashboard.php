<?= $this->extend('layouts/main') ?>

<?= $this->section('css') ?>
<style>
    /* Material Dashboard Customizations - Dynamic Sidebar */
    .sidenav {
        background: var(--sidebar-bg) !important;
        backdrop-filter: blur(15px);
        border-right: 1px solid var(--primary-color);
        box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
    }

    /* Glassmorphism effect for modern look */
    .sidenav::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        pointer-events: none;
        z-index: 1;
    }

    .sidenav > * {
        position: relative;
        z-index: 2;
    }

    .sidenav .navbar-brand-img {
        max-height: 2rem;
    }

    .sidenav .nav-link.active {
        background: rgba(255, 255, 255, 0.25) !important;
        color: white !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        font-weight: 600;
    }

    .sidenav .nav-link:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .sidenav .nav-link {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        font-weight: 500;
    }

    .sidenav .navbar-brand {
        color: white !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        font-weight: 700;
    }

    .sidenav .navbar-brand .text-primary {
        color: white !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar-header {
        padding: var(--spacing-lg);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .sidebar-logo {
        font-size: var(--font-size-xl);
        font-weight: var(--font-weight-bold);
        color: var(--text-white);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-sm);
    }

    .sidebar-logo i {
        color: var(--primary-light);
    }

    .sidebar.collapsed .sidebar-logo-text {
        display: none;
    }

    .sidebar-nav {
        padding: var(--spacing-md) 0;
    }

    .nav-item {
        margin-bottom: var(--spacing-xs);
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: var(--spacing-md) var(--spacing-lg);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all var(--transition-duration) var(--transition-easing);
        border-left: 3px solid transparent;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--text-white);
        border-left-color: var(--primary-light);
    }

    .nav-link.active {
        background-color: rgba(37, 99, 235, 0.2);
        color: var(--text-white);
        border-left-color: var(--primary-light);
    }

    .nav-link i {
        width: 20px;
        margin-right: var(--spacing-md);
        text-align: center;
    }

    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding-left: var(--spacing-md);
        padding-right: var(--spacing-md);
    }

    .sidebar.collapsed .nav-link span {
        display: none;
    }

    /* Main Content - Fixed for Material Dashboard */
    .main-content {
        margin-left: 15rem !important;
        transition: margin-left 0.2s ease-in-out;
    }

    /* Mobile responsive */
    @media (max-width: 1199.98px) {
        .main-content {
            margin-left: 0 !important;
        }
    }

    /* Ensure proper Material Dashboard layout */
    .g-sidenav-show .sidenav.fixed-start + .main-content {
        margin-left: 15rem !important;
    }

    @media (max-width: 1199.98px) {
        .g-sidenav-show .sidenav.fixed-start + .main-content {
            margin-left: 0 !important;
        }
    }



    /* Top Navigation */
    .top-navbar {
        background-color: var(--navbar-bg);
        border-bottom: 1px solid var(--border-color);
        padding: 0 var(--spacing-lg);
        height: var(--navbar-height);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 999;
        box-shadow: var(--shadow-sm);
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .sidebar-toggle {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-size: var(--font-size-lg);
        cursor: pointer;
        padding: var(--spacing-sm);
        border-radius: var(--border-radius-md);
        transition: all var(--transition-duration) var(--transition-easing);
    }

    .sidebar-toggle:hover {
        background-color: var(--light-color);
        color: var(--text-primary);
    }

    .navbar-title {
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-semibold);
        color: var(--text-primary);
        margin: 0;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .user-dropdown {
        position: relative;
    }

    .user-dropdown-toggle {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        background: none;
        border: none;
        color: var(--text-primary);
        cursor: pointer;
        padding: var(--spacing-sm);
        border-radius: var(--border-radius-md);
        transition: background-color var(--transition-duration) var(--transition-easing);
    }

    .user-dropdown-toggle:hover {
        background-color: var(--light-color);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: var(--white-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: var(--font-weight-semibold);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background-color: var(--white-color);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-lg);
        min-width: 200px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all var(--transition-duration) var(--transition-easing);
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-sm) var(--spacing-md);
        color: var(--text-primary);
        text-decoration: none;
        transition: background-color var(--transition-duration) var(--transition-easing);
    }

    .dropdown-item:hover {
        background-color: var(--light-color);
        color: var(--text-primary);
    }

    .dropdown-divider {
        height: 1px;
        background-color: var(--border-color);
        margin: var(--spacing-xs) 0;
    }

    /* Page Content */
    .page-content {
        padding: var(--spacing-lg);
        max-width: var(--container-max-width);
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: var(--spacing-lg);
    }

    .page-title {
        font-size: var(--font-size-xxl);
        font-weight: var(--font-weight-semibold);
        color: var(--text-primary);
        margin: 0;
    }

    .page-subtitle {
        color: var(--text-secondary);
        margin: var(--spacing-xs) 0 0 0;
    }

    /* Responsive Design - Updated for Material Dashboard */
    @media (max-width: 768px) {
        .page-content {
            padding: var(--spacing-md);
        }
    }

    /* Sidebar Overlay for Mobile */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition-duration) var(--transition-easing);
    }

    .sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    @media (min-width: 769px) {
        .sidebar-overlay {
            display: none;
        }
    }

    /* Purple Theme Single Color System */
    .bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
    }

    .icon-shape.bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
    }

    /* Professional Font Sizes - Smaller and Cleaner */
    .card-header h6 {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        margin-bottom: 0.25rem !important;
    }

    .card-header p {
        font-size: 0.75rem !important;
        margin-bottom: 0 !important;
    }

    .quick-action-card h6 {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        margin-bottom: 0.5rem !important;
    }

    .quick-action-card p {
        font-size: 0.75rem !important;
        line-height: 1.3 !important;
        margin-bottom: 0.75rem !important;
    }

    .quick-action-card .btn {
        font-size: 0.75rem !important;
        padding: 0.375rem 0.75rem !important;
        font-weight: 500 !important;
    }

    /* Stats Cards Font Sizes */
    .card-header .text-sm {
        font-size: 0.75rem !important;
    }

    .card-header h4 {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
    }

    .card-footer .text-sm {
        font-size: 0.75rem !important;
    }

    /* Page Header */
    .ms-3 h3 {
        font-size: 1.25rem !important;
        font-weight: 600 !important;
    }

    .ms-3 p {
        font-size: 0.875rem !important;
    }

    /* Table Styling - Professional Small Fonts */
    .table th {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: white !important;
        padding: 0.75rem !important;
    }

    .table td {
        font-size: 0.875rem !important;
        padding: 0.75rem !important;
        vertical-align: middle;
    }

    .table .fw-medium {
        font-weight: 500 !important;
        font-size: 0.875rem !important;
    }

    .badge {
        font-size: 0.7rem !important;
        padding: 0.25rem 0.5rem !important;
    }

    .btn-group-sm .btn {
        font-size: 0.7rem !important;
        padding: 0.25rem 0.5rem !important;
    }

    /* Activity and Status Cards */
    .activity-card h5, .status-card h5 {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
    }

    .activity-card p, .status-card p {
        font-size: 0.75rem !important;
    }

    .user-avatar-small {
        font-size: 0.75rem !important;
    }

    /* Mobile First Responsive Design for Quick Actions */
    @media (max-width: 575.98px) {
        .col-12 {
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .quick-action-card {
            margin-bottom: 1rem;
        }
    }

    @media (min-width: 576px) and (max-width: 767.98px) {
        .col-sm-6:nth-child(odd) {
            padding-right: 0.5rem;
        }

        .col-sm-6:nth-child(even) {
            padding-left: 0.5rem;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .col-lg-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (min-width: 992px) {
        .col-lg-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }

    /* Ensure equal height cards */
    .h-100 {
        height: 100% !important;
    }

    .quick-action-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Collapsible Navigation Styles */
    .nav-section-toggle {
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-section-toggle:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    .nav-section-toggle .nav-arrow {
        transition: transform 0.3s ease;
        font-size: 18px;
    }

    .nav-section-toggle[aria-expanded="true"] .nav-arrow {
        transform: rotate(180deg);
    }

    .nav-section-toggle.collapsed .nav-arrow {
        transform: rotate(0deg);
    }

    /* Sub-navigation styles */
    .nav-sub-link {
        padding-left: 3rem !important;
        font-size: 0.875rem !important;
        color: rgba(255, 255, 255, 0.8) !important;
        border-left: 2px solid transparent;
        margin-left: 1rem;
        transition: all 0.2s ease;
    }

    .nav-sub-link:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: white !important;
        border-left-color: rgba(255, 255, 255, 0.3);
    }

    .nav-sub-link.active {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border-left-color: white;
        font-weight: 600;
    }

    /* Collapse animation */
    .collapse {
        transition: height 0.3s ease;
    }

    .collapsing {
        transition: height 0.3s ease;
    }

    /* Auto-expand active sections */
    .nav-section-toggle.has-active {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .nav-sub-link {
            padding-left: 2.5rem !important;
            margin-left: 0.5rem;
        }
    }

    /* Gradient Theme Specific Styles */
    body.gradient-theme {
        background: var(--body-bg) !important;
        min-height: 100vh;
    }

    .gradient-theme .card {
        background: var(--card-bg) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .gradient-theme .navbar-main {
        background: var(--navbar-bg) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .gradient-theme .sidenav {
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* White Theme Specific Styles */
    body.white-theme {
        background: var(--body-bg) !important;
    }

    .white-theme .card {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .white-theme .navbar-main {
        background: var(--navbar-bg) !important;
        border-bottom: 1px solid var(--border-color);
    }

    .white-theme .sidenav {
        background: var(--sidebar-bg) !important;
        border-right: 1px solid var(--border-color);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    }

    /* White theme sidebar text styling */
    .white-theme .sidenav .nav-link {
        color: var(--text-primary) !important;
        text-shadow: none !important;
    }

    .white-theme .sidenav .nav-link:hover {
        background: rgba(99, 102, 241, 0.1) !important;
        color: var(--primary-color) !important;
    }

    .white-theme .sidenav .nav-link.active {
        background: rgba(99, 102, 241, 0.15) !important;
        color: var(--primary-color) !important;
        font-weight: 600;
    }

    .white-theme .sidenav .navbar-brand {
        color: var(--text-primary) !important;
        text-shadow: none !important;
    }

    .white-theme .sidenav .navbar-brand .text-primary {
        color: var(--primary-color) !important;
        text-shadow: none !important;
    }

    /* Remove glassmorphism effects for white theme */
    .white-theme .sidenav::before {
        display: none;
    }

    /* White theme sub-navigation */
    .white-theme .nav-sub-link {
        color: var(--text-secondary) !important;
        border-left-color: var(--border-color) !important;
    }

    .white-theme .nav-sub-link:hover {
        background-color: rgba(99, 102, 241, 0.08) !important;
        color: var(--primary-color) !important;
        border-left-color: var(--primary-color) !important;
    }

    .white-theme .nav-sub-link.active {
        background-color: rgba(99, 102, 241, 0.12) !important;
        color: var(--primary-color) !important;
        border-left-color: var(--primary-color) !important;
    }

    /* Beautiful Sleek Header Shadow */
    .navbar-sleek-shadow {
        box-shadow:
            0 1px 3px rgba(0, 0, 0, 0.02),
            0 2px 8px rgba(0, 0, 0, 0.04),
            0 4px 16px rgba(0, 0, 0, 0.06),
            0 8px 32px rgba(0, 0, 0, 0.08) !important;
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-bottom: 1px solid rgba(var(--primary-color-rgb), 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 1000;
    }

    /* Enhanced shadow on scroll */
    .navbar-sleek-shadow.scrolled {
        box-shadow:
            0 4px 12px rgba(0, 0, 0, 0.08),
            0 8px 24px rgba(0, 0, 0, 0.12),
            0 16px 48px rgba(0, 0, 0, 0.16) !important;
        background: rgba(255, 255, 255, 0.98) !important;
    }

    /* Subtle gradient overlay for depth */
    .navbar-sleek-shadow::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg,
            rgba(var(--primary-color-rgb), 0.02) 0%,
            rgba(var(--primary-color-rgb), 0.01) 50%,
            rgba(255, 255, 255, 0.02) 100%
        );
        border-radius: inherit;
        pointer-events: none;
        z-index: -1;
    }

    /* Enhance breadcrumb styling in the new header */
    .navbar-sleek-shadow .breadcrumb-item a {
        color: var(--text-secondary);
        transition: color 0.2s ease;
    }

    .navbar-sleek-shadow .breadcrumb-item a:hover {
        color: var(--primary-color);
    }

    /* Profile dropdown enhancement */
    .navbar-sleek-shadow .dropdown-toggle {
        border: 1px solid rgba(var(--primary-color-rgb), 0.1);
        background: rgba(var(--primary-color-rgb), 0.05);
        transition: all 0.2s ease;
    }

    .navbar-sleek-shadow .dropdown-toggle:hover {
        border-color: rgba(var(--primary-color-rgb), 0.2);
        background: rgba(var(--primary-color-rgb), 0.1);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15);
    }

    /* Search input enhancement */
    .navbar-sleek-shadow .form-control {
        border: 1px solid rgba(var(--primary-color-rgb), 0.1);
        background: rgba(255, 255, 255, 0.8);
        transition: all 0.2s ease;
    }

    .navbar-sleek-shadow .form-control:focus {
        border-color: var(--primary-color);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
    }

    /* Additional depth and animation enhancements */
    .navbar-sleek-shadow {
        animation: headerFadeIn 0.6s ease-out;
    }

    @keyframes headerFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Enhanced hover effects for navbar items */
    .navbar-sleek-shadow .nav-link {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }

    .navbar-sleek-shadow .nav-link:hover {
        background: rgba(var(--primary-color-rgb), 0.08);
        transform: translateY(-1px);
    }

    .navbar-sleek-shadow .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .navbar-sleek-shadow .nav-link:hover::before {
        left: 100%;
    }

    /* Breadcrumb enhancement with subtle glow */
    .navbar-sleek-shadow .breadcrumb {
        background: rgba(var(--primary-color-rgb), 0.03);
        border-radius: 12px;
        padding: 8px 16px;
        border: 1px solid rgba(var(--primary-color-rgb), 0.08);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Material Dashboard Sidebar -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="<?= base_url() ?>">
            <?php $logo = get_app_logo(); ?>
            <?php if ($logo): ?>
                <img src="<?= $logo ?>" alt="<?= get_app_name() ?>" style="max-height: 2rem; max-width: 150px;" class="me-2">
            <?php else: ?>
                <i class="fas fa-graduation-cap text-primary me-2" style="font-size: 1.5rem;"></i>
            <?php endif; ?>
            <span class="ms-1 text-sm text-dark font-weight-bold"><?= get_app_name() ?></span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <?= $this->include('partials/sidebar_nav') ?>
    </div>
</aside>

<!-- Main Content -->
<main class="main-content position-relative border-radius-lg" style="flex: 1; display: flex; flex-direction: column; min-height: 0;">
    <!-- Enhanced Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 border-radius-xl navbar-sleek-shadow" id="navbarBlur" data-scroll="true" style="margin-bottom:30px;">
        <div class="container-fluid py-1 px-3">
            <!-- Enhanced Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-dark" href="<?= base_url() ?>">
                            <i class="material-symbols-rounded opacity-5 me-1" style="font-size: 14px;">home</i>
                            Dashboard
                        </a>
                    </li>
                    <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                            <?php if ($index === count($breadcrumbs) - 1): ?>
                                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                                    <?= $breadcrumb['title'] ?>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item text-sm">
                                    <a class="opacity-5 text-dark" href="<?= $breadcrumb['url'] ?>">
                                        <?= $breadcrumb['title'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                            <?= $pageTitle ?? 'Home' ?>
                        </li>
                    <?php endif; ?>
                </ol>
                <!-- Page Title -->
                <!--<h6 class="font-weight-bolder mb-0 mt-2"><?= $pageTitle ?? 'Dashboard' ?></h6> -->
            </nav>

            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <!-- Quick Search -->
                    <div class="input-group input-group-outline me-3" style="max-width: 250px;">
                      <?php
date_default_timezone_set('Africa/Lagos');   // ensure correct zone
$serverTime = time();                        // Unix timestamp (seconds)
?>

<span id="clock"></span>

<script>
let serverNow = <?= $serverTime ?> * 1000;   // ms

function updateClock() {
  const now   = new Date(serverNow);
  const date  = now.toLocaleDateString('en-US', {
                weekday: 'long', month: 'long', day: 'numeric', year: 'numeric'
              });
  const time  = now.toLocaleTimeString('en-GB');
  document.getElementById('clock').textContent = `${date} ${time}`;
  serverNow += 1000;                         // advance 1 s
}

updateClock();
setInterval(updateClock, 1000);
</script>
                    </div>
                </div>

                <ul class="navbar-nav d-flex align-items-center justify-content-end">
                    <!-- Mobile Menu Toggle -->
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>

                    <!-- Settings (Admin Only) -->
                    <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item px-3 d-flex align-items-center">
                        <a href="<?= base_url('admin/settings') ?>" class="nav-link text-body p-0" title="Settings">
                            <i class="material-symbols-rounded">settings</i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Notifications - Temporarily Hidden -->
                   
                    <!--
                    <li class="nav-item dropdown pe-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                            <i class="material-symbols-rounded">notifications</i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                            <li class="dropdown-header">
                                <h6 class="text-sm font-weight-bold mb-0">Notifications</h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <div class="avatar avatar-sm bg-gradient-primary me-3 d-flex align-items-center justify-content-center">
                                                <i class="material-symbols-rounded text-white" style="font-size: 14px;">assignment</i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                <span class="font-weight-bold">New exam submitted</span>
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="material-symbols-rounded me-1" style="font-size: 12px;">schedule</i>
                                                2 minutes ago
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center">
                                <a class="dropdown-item text-sm" href="javascript:;">View all notifications</a>
                            </li>
                        </ul>
                    </li>
                    -->

                    <!-- User Profile -->
                    <li class="nav-item dropdown d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body font-weight-bold px-0 d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar avatar-sm bg-gradient-primary me-2 d-flex align-items-center justify-content-center">
                                <span class="text-white text-sm font-weight-bold">
                                    <?php
                                        $firstName = session()->get('first_name');
                                        $username = session()->get('username');
                                        if ($firstName) {
                                            echo strtoupper(substr($firstName, 0, 1));
                                        } elseif ($username) {
                                            echo strtoupper(substr($username, 0, 1));
                                        } else {
                                            echo 'U';
                                        }
                                    ?>
                                </span>
                            </div>
                            <span class="d-none d-sm-inline"><?= session()->get('first_name') ?? session()->get('username') ?? 'User' ?></span>
                            <i class="material-symbols-rounded ms-1">keyboard_arrow_down</i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li class="dropdown-header">
                                <h6 class="text-sm font-weight-bold mb-0">
                                    <?= trim((session()->get('first_name') ?? '') . ' ' . (session()->get('last_name') ?? '')) ?: (session()->get('username') ?? 'User') ?>
                                </h6>
                                <p class="text-xs text-secondary mb-0"><?= ucfirst(session()->get('role')) ?></p>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <?php
                                $profileUrl = 'auth/profile'; // Default
                                $userRole = session()->get('role');
                                if ($userRole === 'student') {
                                    $profileUrl = 'student/profile';
                                } elseif ($userRole === 'principal') {
                                    $profileUrl = 'principal/profile';
                                }
                                ?>
                                <a class="dropdown-item" href="<?= base_url($profileUrl) ?>">
                                    <i class="material-symbols-rounded me-2">person</i>
                                    My Profile
                                </a>
                            </li>
                            <?php if (session()->get('role') === 'admin'): ?>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/settings') ?>">
                                    <i class="material-symbols-rounded me-2">settings</i>
                                    Settings
                                </a>
                            </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                                    <i class="material-symbols-rounded me-2">logout</i>
                                    Sign Out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid py-2" style="flex: 1; display: flex; flex-direction: column;">
        <!-- Flash Messages -->
      

        <!-- Page Content -->
        <div style="flex: 1;">
            <?= $this->renderSection('page_content') ?>
        </div>
    </div>
</main>

<!-- Footer -->
<?= $this->include('partials/footer') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Dashboard JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainContent = document.querySelector('.main-content');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    // Mobile: Show/hide sidebar
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                } else {
                    // Desktop: Collapse/expand sidebar
                    sidebar.classList.toggle('collapsed');
                }
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // User Dropdown
        const userDropdownToggle = document.getElementById('user-dropdown-toggle');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');

        if (userDropdownToggle && userDropdownMenu) {
            userDropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                userDropdownMenu.classList.remove('show');
            });

            // Prevent dropdown from closing when clicking inside
            userDropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);

        // Enhanced Navigation Functionality
        initializeCollapsibleNavigation();
    });

    function initializeCollapsibleNavigation() {
        // Auto-expand sections that contain active links
        const activeLinks = document.querySelectorAll('.nav-sub-link.active');
        activeLinks.forEach(function(activeLink) {
            const parentCollapse = activeLink.closest('.collapse');
            if (parentCollapse) {
                // Show the parent collapse
                parentCollapse.classList.add('show');

                // Update the toggle button
                const toggleButton = document.querySelector(`[href="#${parentCollapse.id}"]`);
                if (toggleButton) {
                    toggleButton.classList.remove('collapsed');
                    toggleButton.setAttribute('aria-expanded', 'true');
                    toggleButton.classList.add('has-active');
                }
            }
        });

        // Handle section toggle clicks
        const sectionToggles = document.querySelectorAll('.nav-section-toggle');
        sectionToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetCollapse = document.getElementById(targetId);

                if (targetCollapse) {
                    // Toggle the collapse
                    if (targetCollapse.classList.contains('show')) {
                        targetCollapse.classList.remove('show');
                        this.classList.add('collapsed');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        targetCollapse.classList.add('show');
                        this.classList.remove('collapsed');
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });

        // Close other sections when opening a new one (accordion behavior)
        const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(function(collapse) {
            collapse.addEventListener('show.bs.collapse', function() {
                // Optional: Close other sections for accordion behavior
                // Uncomment the lines below if you want accordion behavior
                /*
                collapseElements.forEach(function(otherCollapse) {
                    if (otherCollapse !== collapse && otherCollapse.classList.contains('show')) {
                        otherCollapse.classList.remove('show');
                        const otherToggle = document.querySelector(`[href="#${otherCollapse.id}"]`);
                        if (otherToggle) {
                            otherToggle.classList.add('collapsed');
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                */
            });
        });

        // Smooth scrolling for navigation links
        const navLinks = document.querySelectorAll('.nav-sub-link');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Add loading state or other effects here if needed
                console.log('Navigating to:', this.href);
            });
        });

        // Material Icons to FontAwesome Converter
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
                    // Small delay to ensure content is fully loaded
                    setTimeout(convertMaterialIcons, 100);
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Also run conversion when modals are shown
        document.addEventListener('shown.bs.modal', function() {
            setTimeout(convertMaterialIcons, 100);
        });

        // Run conversion when AJAX content is loaded
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(convertMaterialIcons, 500);
        });
    }

    // Enhanced Header Shadow on Scroll
    function initHeaderShadowEffect() {
        const navbar = document.querySelector('.navbar-sleek-shadow');
        if (!navbar) return;

        let ticking = false;

        function updateNavbarShadow() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateNavbarShadow);
                ticking = true;
            }
        }

        // Listen for scroll events
        window.addEventListener('scroll', requestTick, { passive: true });

        // Initial check
        updateNavbarShadow();
    }

    // Initialize header shadow effect when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeaderShadowEffect);
    } else {
        initHeaderShadowEffect();
    }
</script>
<?= $this->endSection() ?>
