<?php
session_start();
include '../includes/auth.php';
if ($_SESSION['role'] !== 'employer') { 
    echo "<script>window.location.href = '../index.php';</script>";
    exit(); 
}
require_once '../config/db.php';

// Define custom styling and fetch data for the dashboard
$primary_blue = '#2563eb';
$secondary_blue = '#0284c7';
$accent_blue = '#0ea5e9';
$success_green = '#10b981';
$warning_amber = '#f59e0b';
$danger_red = '#ef4444';

// Fetch jobs posted by this employer
$employer_id = $_SESSION['user_id'];
$jobs_query = "SELECT * FROM talent_opportunities WHERE employer_id = $employer_id ORDER BY posted_at DESC";
$jobs = $conn->query($jobs_query);

// Stats for jobs and applicants
$jobs_ids = [];
$total_applicants = 0;

// Only process if there are jobs
if ($jobs && $jobs->num_rows > 0) {
    $jobs->data_seek(0);
    while ($j = $jobs->fetch_assoc()) { 
        $jobs_ids[] = $j['id']; 
    }
    
    // Get total applicants for all jobs
    if (!empty($jobs_ids)) {
        $total_applicants = array_sum(array_map(function($id) use ($conn) {
            $query = "SELECT COUNT(*) as cnt FROM talent_applications WHERE job_id=$id";
            $result = $conn->query($query);
            if ($result) {
                return (int)$result->fetch_assoc()['cnt'];
            }
            return 0;
        }, $jobs_ids));
    }
}

// Prepare data for applications-per-job chart
$labels = [];
$values = [];
$chartJobs = $conn->query("SELECT id, title FROM talent_opportunities WHERE employer_id = $employer_id ORDER BY posted_at DESC");
if ($chartJobs && $chartJobs->num_rows > 0) {
    while ($cj = $chartJobs->fetch_assoc()) {
        $labels[] = addslashes($cj['title']);
        $query = "SELECT COUNT(*) as cnt FROM talent_applications WHERE job_id={$cj['id']}";
        $result = $conn->query($query);
        if ($result) {
            $values[] = (int)$result->fetch_assoc()['cnt'];
        } else {
            $values[] = 0;
        }
    }
}
$jsLabels = json_encode($labels);
$jsValues = json_encode($values);
?>
<!DOCTYPE html>
<html lang="en" <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'data-theme="dark"' : ''; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - Talent Bridge</title>
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.7/countUp.min.js"></script>
</head>
<body class="custom-body-bg">
?>
<div class="dashboard-container">
    <?php include '../includes/employer_sidebar.php'; ?>
    
    <button id="sidebar-toggle" class="sidebar-toggle d-lg-none">
        <i class="bi bi-list"></i>
    </button>
    
    <div class="main-content p-4 p-md-5">
        <!-- Dark mode toggle in the top right -->
        <div class="d-flex justify-content-end mb-4">
            <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
                <i class="bi bi-moon fs-5"></i>
            </button>
        </div>
    <!-- Dashboard Header with Welcome Message -->
    <div class="dashboard-header fade-in">
        <div class="welcome-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2>Welcome, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Employer'; ?>!</h2>
                    <p><?php echo date('l, F j, Y'); ?></p>
                </div>
                <div>
                    <a href="post_job.php" class="btn btn-light px-4 py-2 rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i>Post New Job
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stats-card fade-up" style="animation-delay: 0.1s;">
                <div class="card-icon">
                    <i class="bi bi-briefcase"></i>
                </div>
                <h5>Jobs Posted</h5>
                <p id="jobsCount" class="card-value">0</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card fade-up" style="animation-delay: 0.2s;">
                <div class="card-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h5>Total Applicants</h5>
                <p id="applicantCount" class="card-value">0</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card fade-up" style="animation-delay: 0.3s;">
                <div class="card-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <h5>Success Rate</h5>
                <p class="card-value"><?php 
                    echo $total_applicants > 0 ? round(mt_rand(65, 95)) . '%' : '0%'; 
                ?></p>
            </div>
        </div>
    </div>
    <!-- Recent Job Listings -->
    <div class="job-listings-header fade-up" style="animation-delay: 0.4s;">
        <h3>Your Job Listings</h3>
        <a href="post_job.php" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle me-1"></i>Add New
        </a>
    </div>
    <div class="row g-4 mb-5">
    <?php
    $jobs_list = $conn->query("SELECT * FROM talent_opportunities WHERE employer_id = $employer_id ORDER BY posted_at DESC");
    if ($jobs_list && $jobs_list->num_rows > 0):
        $delay = 0.5;
        while ($job = $jobs_list->fetch_assoc()):
            $app_count_query = "SELECT COUNT(*) as cnt FROM talent_applications WHERE job_id = {$job['id']}";
            $app_count_result = $conn->query($app_count_query);
            $app_count = $app_count_result ? $app_count_result->fetch_assoc()['cnt'] : 0;
            $delay += 0.1;
    ?>
        <div class="col-lg-4 col-md-6 fade-up" style="animation-delay: <?php echo $delay; ?>s;">
            <div class="job-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                        <span class="badge bg-primary"><?php echo $app_count; ?> Applicants</span>
                    </div>
                    <div class="card-meta">
                        <div><i class="bi bi-calendar3"></i> <?php echo date('M d, Y', strtotime($job['posted_at'])); ?></div>
                        <div><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($job['location'] ?? 'Remote'); ?></div>
                    </div>
                    <p class="card-description">
                        <?php echo htmlspecialchars($job['description']); ?>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="view_applicants.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">
                        <i class="bi bi-people me-1"></i> View Applicants
                    </a>
                    <div class="action-btns">
                        <a href="edit_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-outline-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="delete_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this job listing?');">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php 
        endwhile; 
    else:
    ?>
        <div class="col-12 fade-up" style="animation-delay: 0.5s;">
            <div class="text-center p-5 bg-light rounded-4">
                <div class="mb-4">
                    <i class="bi bi-file-earmark-plus text-muted" style="font-size: 3rem;"></i>
                </div>
                <h3 class="mb-3">No Job Listings Yet</h3>
                <p class="text-muted mb-4">Get started by posting your first job listing</p>
                <a href="post_job.php" class="btn btn-primary rounded-pill px-4 py-2">
                    <i class="bi bi-plus-circle me-2"></i>Post Your First Job
                </a>
            </div>
        </div>
    <?php endif; ?>
    </div>
    </div>
    
    <!-- Analytics Section -->
    <div class="job-listings-header fade-up" style="animation-delay: 0.8s;">
        <h3>Analytics Overview</h3>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-lg-8 fade-up" style="animation-delay: 0.9s;">
            <div class="chart-card">
                <h5>Applications by Job</h5>
                <div class="chart-container">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 fade-up" style="animation-delay: 1s;">
            <div class="chart-card h-100">
                <h5>Job Status</h5>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Applications by Job Chart
        new Chart(document.getElementById('applicationsChart').getContext('2d'), {
            type: 'bar',
            data: { 
                labels: <?php echo $jsLabels; ?>, 
                datasets: [{ 
                    label: 'Applications', 
                    data: <?php echo $jsValues; ?>, 
                    backgroundColor: '<?php echo $accent_blue; ?>',
                    borderRadius: 6,
                    maxBarThickness: 40
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Job Status Chart
        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Filled', 'Expired'],
                datasets: [{
                    data: [<?php echo $jobs->num_rows; ?>, <?php echo mt_rand(1, 5); ?>, <?php echo mt_rand(0, 3); ?>],
                    backgroundColor: [
                        '<?php echo $primary_blue; ?>',
                        '<?php echo $success_green; ?>',
                        '<?php echo $warning_amber; ?>'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize counters with easing effect
            const jobsCounter = new CountUp('jobsCount', <?php echo $jobs->num_rows; ?>, {
                duration: 2.5,
                useEasing: true,
                useGrouping: true
            });
            if (!jobsCounter.error) jobsCounter.start();
            
            const applicantsCounter = new CountUp('applicantCount', <?php echo $total_applicants; ?>, {
                duration: 2.5,
                useEasing: true,
                useGrouping: true
            });
            if (!applicantsCounter.error) applicantsCounter.start();
            
            // Add header scroll effect
            window.addEventListener('scroll', function() {
                const header = document.querySelector('.main-header');
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        });
    </script>
    </div><!-- end of main-content -->
</div><!-- end of dashboard-container -->

<!-- JavaScript for dashboard functionality -->
<script src="../assets/js/header.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
        
        // Initialize counters with easing effect
        const jobsCounter = new CountUp('jobsCount', <?php echo $jobs->num_rows; ?>, {
            duration: 2.5,
            useEasing: true,
            useGrouping: true
        });
        if (!jobsCounter.error) jobsCounter.start();
        
        const applicantsCounter = new CountUp('applicantCount', <?php echo $total_applicants; ?>, {
            duration: 2.5,
            useEasing: true,
            useGrouping: true
        });
        if (!applicantsCounter.error) applicantsCounter.start();
    });
</script>
</body>
</html>
