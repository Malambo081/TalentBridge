<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Talent Bridge connects talented professionals with world-class employers. Find your dream job or hire the perfect candidate today.">
    <meta name="keywords" content="jobs, career, employment, hiring, recruitment, talent, job search, job portal">
    <meta name="author" content="Talent Bridge">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://talentbridge.com/">
    <meta property="og:title" content="Talent Bridge - Connecting Talent with Opportunity">
    <meta property="og:description" content="Find your dream job or perfect candidate. Talent Bridge connects professionals with employers globally.">
    <meta property="og:image" content="/assets/img/logo.svg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://talentbridge.com/">
    <meta property="twitter:title" content="Talent Bridge - Connecting Talent with Opportunity">
    <meta property="twitter:description" content="Find your dream job or perfect candidate. Talent Bridge connects professionals with employers globally.">
    <meta property="twitter:image" content="/assets/img/logo.svg">
    
    <title>Talent Bridge - Connecting Talent with Opportunity</title>
    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="assets/img/logo-icon.svg" sizes="any">
    <meta name="theme-color" content="#4361ee">
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {},
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.7/countUp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/skeleton.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    <link rel="stylesheet" href="assets/css/enhanced-header.css">
</head>
<body class="custom-body-bg">
<header class="main-header">
    <div class="container">
        <div class="header-content">
            <!-- Logo and Tagline -->
            <div class="logo-tagline">
                <span class="logo-circle"><i class="bi bi-briefcase-fill"></i></span>
                <div>
                    <span class="site-title">Talent Bridge</span>
                    <span class="site-tagline">Connecting Talent with Opportunity</span>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <nav id="main-nav" class="main-navigation">
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
                    <!-- Authenticated Users -->
                    <a href="./dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                    
                    <?php if ($_SESSION['role'] === 'jobseeker'): ?>
                        <!-- Jobseeker Navigation -->
                        <a href="jobseeker/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        
                        <div class="nav-dropdown">
                            <a href="#" class="nav-link">
                                <i class="bi bi-briefcase"></i> Jobs <i class="bi bi-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="jobseeker/find_jobs.php" class="dropdown-item">
                                    <i class="bi bi-search"></i> Find Jobs
                                </a>
                                <a href="jobseeker/applied_jobs.php" class="dropdown-item">
                                    <i class="bi bi-clipboard-check"></i> Applied Jobs
                                </a>
                                <a href="jobseeker/saved_jobs.php" class="dropdown-item">
                                    <i class="bi bi-bookmark"></i> Saved Jobs
                                </a>
                            </div>
                        </div>
                        
                        <a href="jobseeker/profile.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                            <i class="bi bi-person"></i> Profile
                        </a>
                        
                    <?php elseif ($_SESSION['role'] === 'employer'): ?>
                        <!-- Employer Navigation -->
                        <a href="employer/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        
                        <div class="nav-dropdown">
                            <a href="#" class="nav-link">
                                <i class="bi bi-briefcase"></i> Jobs <i class="bi bi-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="employer/post_job.php" class="dropdown-item">
                                    <i class="bi bi-plus-circle"></i> Post Job
                                </a>
                                <a href="employer/manage_jobs.php" class="dropdown-item">
                                    <i class="bi bi-list-check"></i> Manage Jobs
                                </a>
                            </div>
                        </div>
                        
                        <div class="nav-dropdown">
                            <a href="#" class="nav-link notification-badge">
                                <i class="bi bi-people"></i> Candidates <i class="bi bi-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="employer/applicants.php" class="dropdown-item">
                                    <i class="bi bi-person-lines-fill"></i> All Applicants
                                </a>
                                <a href="employer/shortlisted.php" class="dropdown-item">
                                    <i class="bi bi-star"></i> Shortlisted
                                </a>
                            </div>
                        </div>
                        
                        <a href="employer/profile.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                            <i class="bi bi-building"></i> Company Profile
                        </a>
                    <?php endif; ?>
                    
                    <a href="logout.php" class="nav-link btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                    
                <?php else: ?>
                    <!-- Guest Navigation -->
                    <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                    <a href="login.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="register.php" class="nav-link btn btn-primary rounded-pill px-3 <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                <?php endif; ?>
            </nav>
            
            <!-- Header Controls -->
            <div class="header-controls d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
                <!-- User Avatar/Menu if logged in -->
                <div class="nav-dropdown">
                    <div class="user-avatar">
                        <?php echo substr($_SESSION['username'] ?? '', 0, 1); ?>
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-item" style="cursor: default; font-weight: 500;">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['username'] ?? 'User'; ?>
                        </div>
                        <hr style="margin: 0.25rem 0;">
                        <?php if ($_SESSION['role'] === 'jobseeker'): ?>
                            <a href="jobseeker/profile.php" class="dropdown-item">
                                <i class="bi bi-person-gear"></i> Edit Profile
                            </a>
                        <?php elseif ($_SESSION['role'] === 'employer'): ?>
                            <a href="employer/profile.php" class="dropdown-item">
                                <i class="bi bi-building-gear"></i> Company Profile
                            </a>
                        <?php endif; ?>
                        <a href="settings.php" class="dropdown-item">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                        <hr style="margin: 0.25rem 0;">
                        <a href="logout.php" class="dropdown-item" style="color: #ef4444;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Dark Mode Toggle -->
                <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
                    <i class="bi bi-moon"></i>
                </button>
                
                <!-- Mobile Navigation Toggle -->
                <button id="mobile-nav-toggle" class="d-lg-none" aria-label="Toggle navigation" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>
</header>
<main>
<!-- Header script loaded in separate file to prevent 'headers already sent' errors -->
<script src="assets/js/enhanced-header.js"></script>
<script src="assets/js/header.js"></script>
