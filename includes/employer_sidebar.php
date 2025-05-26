<?php
// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar bg-white shadow-sm">
    <div class="sidebar-header p-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="logo-circle-sm me-2">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div>
                <div class="sidebar-title fw-bold text-primary">Talent Bridge</div>
                <div class="sidebar-subtitle small">Connecting Talent with Opportunity</div>
            </div>
        </div>
    </div>
    <div class="sidebar-body p-0">
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="../index.php" class="sidebar-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <i class="bi bi-house-door me-2"></i> Home
                </a>
            </li>
            <li class="sidebar-item">
                <a href="dashboard.php" class="sidebar-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="post_job.php" class="sidebar-link <?php echo $current_page == 'post_job.php' ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-plus me-2"></i> Post Job
                </a>
            </li>
            <li class="sidebar-item">
                <a href="../logout.php" class="sidebar-link text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    <div class="sidebar-footer p-3 border-top">
        <div class="d-grid">
            <a href="post_job.php" class="btn btn-primary rounded-pill">
                <i class="bi bi-plus-circle me-2"></i> Post New Job
            </a>
        </div>
    </div>
</div>
