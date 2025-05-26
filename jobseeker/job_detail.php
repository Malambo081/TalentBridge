<?php
include '../includes/header.php';
include '../includes/auth.php';
require_once '../config/db.php';
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Check if job exists and get details
$stmt = $conn->prepare('SELECT t_o.*, t_u.username AS employer, t_u.email AS employer_email FROM talent_opportunities t_o JOIN talent_users t_u ON t_o.employer_id = t_u.id WHERE t_o.id = ?');
$stmt->bind_param('i', $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Check if the user has already applied
$has_applied = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check_stmt = $conn->prepare('SELECT id FROM talent_applications WHERE job_id = ? AND seeker_id = ?');
    $check_stmt->bind_param('ii', $job_id, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    $has_applied = $check_stmt->num_rows > 0;
    $check_stmt->close();
}

// Format date for better readability
$posted_date = date('F j, Y', strtotime($job['posted_at']));

// If job not found, show error
if (!$job) { 
    echo '<div class="container mt-5"><div class="alert alert-danger">Job opportunity not found.</div><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></div>'; 
    include '../includes/footer.php'; 
    exit(); 
}
?>

<main class="py-5 bg-light">
    <div class="container">
        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($job['title']); ?></li>
            </ol>
        </nav>
        
        <!-- Back button for mobile -->
        <div class="d-md-none mb-3">
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="row">
            <!-- Main job details column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <!-- Job header with title and company -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-briefcase text-primary fs-4"></i>
                            </div>
                            <div>
                                <h1 class="fs-2 fw-bold mb-1"><?php echo htmlspecialchars($job['title']); ?></h1>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($job['employer']); ?></p>
                            </div>
                        </div>
                        
                        <!-- Job highlights -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    <span><?php echo htmlspecialchars($job['location']); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-check text-primary me-2"></i>
                                    <span><?php echo $posted_date; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    <span>Applicants: <span class="fw-bold" id="applicant-count">--</span></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job description -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Job Description</h5>
                            <div class="job-description">
                                <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                            </div>
                        </div>
                        
                        <!-- Apply button (mobile) -->
                        <div class="d-md-none">
                            <form method="post" action="apply.php" class="mb-3">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <?php if ($has_applied): ?>
                                    <button type="button" class="btn btn-success w-100" disabled>
                                        <i class="bi bi-check-circle me-2"></i>Already Applied
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-primary w-100 hover-lift">
                                        <i class="bi bi-send me-2"></i>Apply for this Position
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Additional sections -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">About the Company</h5>
                        <p>Learn more about working at <?php echo htmlspecialchars($job['employer']); ?> and discover if this opportunity aligns with your career goals.</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                <i class="bi bi-building me-1"></i> Company Profile
                            </a>
                            <a href="mailto:<?php echo $job['employer_email']; ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-envelope me-1"></i> Contact Employer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar with action buttons -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-3 sticky-lg-top" style="top: 2rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Take the Next Step</h5>
                        
                        <!-- Apply button (desktop) -->
                        <form method="post" action="apply.php" class="mb-3">
                            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                            <?php if ($has_applied): ?>
                                <button type="button" class="btn btn-success w-100 py-3" disabled>
                                    <i class="bi bi-check-circle me-2"></i>Already Applied
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary w-100 py-3 hover-lift">
                                    <i class="bi bi-send me-2"></i>Apply for this Position
                                </button>
                            <?php endif; ?>
                        </form>
                        
                        <a href="dashboard.php" class="btn btn-outline-secondary w-100 mb-3">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        
                        <!-- Share options -->
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-light flex-grow-1 me-2" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Print
                            </button>
                            <button class="btn btn-light flex-grow-1 me-2" onclick="shareJob()">
                                <i class="bi bi-share me-1"></i> Share
                            </button>
                            <button class="btn btn-light flex-grow-1" onclick="saveJob(<?php echo $job['id']; ?>)">
                                <i class="bi bi-bookmark me-1"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Simple placeholder functions for save/share functionality
function saveJob(jobId) {
    alert('Job saved to your favorites!');
    // In a real implementation, this would call an API endpoint to save the job
}

function shareJob() {
    // Check if the Web Share API is available
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes(htmlspecialchars($job['title'])); ?>',
            text: 'Check out this job opportunity: <?php echo addslashes(htmlspecialchars($job['title'])); ?> at <?php echo addslashes(htmlspecialchars($job['employer'])); ?>',
            url: window.location.href
        })
        .catch(err => {
            console.error('Share failed:', err);
        });
    } else {
        alert('Copy this URL to share: ' + window.location.href);
    }
}
</script>

<?php include '../includes/footer.php'; ?>
