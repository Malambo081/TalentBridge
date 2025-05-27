<?php
// Include header
include_once 'includes/header.php';

// Check if job ID is provided
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: jobs.php");
    exit;
}

$job_id = $_GET['id'];

// Get job details
$job_query = "SELECT j.*, u.name as employer_name, u.email as employer_email 
              FROM jobs j 
              JOIN users u ON j.employer_id = u.id 
              WHERE j.id = $job_id";
$job_result = mysqli_query($conn, $job_query);

// Check if job exists
if(mysqli_num_rows($job_result) == 0) {
    header("Location: jobs.php");
    exit;
}

$job = mysqli_fetch_assoc($job_result);

// Check if job has expired
$job_expired = strtotime($job['deadline']) < strtotime(date('Y-m-d'));

// Check if user has already applied
$already_applied = false;
if(isLoggedIn() && isJobSeeker()) {
    $user_id = $_SESSION['user_id'];
    $check_application_query = "SELECT * FROM applications WHERE job_id = $job_id AND jobseeker_id = $user_id";
    $check_application_result = mysqli_query($conn, $check_application_query);
    $already_applied = mysqli_num_rows($check_application_result) > 0;
    
    if($already_applied) {
        $application = mysqli_fetch_assoc($check_application_result);
    }
}

// Process application form
$application_success = false;
$application_error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn() && isJobSeeker() && !$already_applied && !$job_expired) {
    // Get form data
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    
    // Validate form data
    if(empty($message)) {
        $application_error = "Application message is required";
    } elseif(strlen($message) < 10) {
        $application_error = "Please provide a more detailed message";
    } else {
        // Insert application
        $user_id = $_SESSION['user_id'];
        $insert_query = "INSERT INTO applications (job_id, jobseeker_id, message) 
                        VALUES ($job_id, $user_id, '" . sanitize_input($message) . "')";
        
        if(mysqli_query($conn, $insert_query)) {
            $application_success = true;
            $already_applied = true;
            
            // Get the application details
            $application_id = mysqli_insert_id($conn);
            $application_query = "SELECT * FROM applications WHERE id = $application_id";
            $application_result = mysqli_query($conn, $application_query);
            $application = mysqli_fetch_assoc($application_result);
        } else {
            $application_error = "Error submitting application: " . mysqli_error($conn);
        }
    }
}

// Parse required skills
$skills = json_decode($job['required_skills'], true);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="jobs.php">Jobs</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($job['title']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Job Details -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="job-title mb-3"><?php echo htmlspecialchars($job['title']); ?></h2>
                
                <div class="d-flex align-items-center mb-3">
                    <span class="me-3">
                        <i class="fas fa-building me-2 text-muted"></i><?php echo htmlspecialchars($job['employer_name']); ?>
                    </span>
                    <span>
                        <i class="fas fa-calendar me-2 text-muted"></i>Deadline: <?php echo date('M d, Y', strtotime($job['deadline'])); ?>
                    </span>
                </div>
                
                <div class="mb-4">
                    <?php if(is_array($skills)): foreach($skills as $skill): ?>
                        <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                    <?php endforeach; endif; ?>
                </div>
                
                <div class="mb-4">
                    <h5>Job Description</h5>
                    <div class="job-description">
                        <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Posted on <?php echo date('M d, Y', strtotime($job['created_at'])); ?></small>
                    
                    <?php if($job_expired): ?>
                        <span class="badge bg-secondary">Job Expired</span>
                    <?php else: ?>
                        <span class="badge bg-success">Active</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Application Section -->
        <?php if(isLoggedIn() && isJobSeeker()): ?>
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Apply for this Job</h5>
                </div>
                <div class="card-body">
                    <?php if($application_success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>Your application has been submitted successfully!
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($application_error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $application_error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($already_applied): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>You have already applied for this job.
                            <div class="mt-3">
                                <strong>Status:</strong> 
                                <span class="badge <?php 
                                    switch($application['status']) {
                                        case 'pending': echo 'bg-warning text-dark'; break;
                                        case 'reviewed': echo 'bg-info text-white'; break;
                                        case 'accepted': echo 'bg-success text-white'; break;
                                        case 'rejected': echo 'bg-danger text-white'; break;
                                    }
                                ?>">
                                    <?php echo ucfirst($application['status']); ?>
                                </span>
                            </div>
                            <?php if($application['status'] == 'accepted'): ?>
                                <div class="mt-2">
                                    <p>Congratulations! Your application has been accepted. The employer may contact you via email soon.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif($job_expired): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>This job posting has expired and is no longer accepting applications.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="job_details.php?id=<?php echo $job_id; ?>" id="application-form">
                            <div class="mb-3">
                                <label for="application-message" class="form-label">Application Message</label>
                                <textarea class="form-control" id="application-message" name="message" rows="5" placeholder="Explain why you're a good fit for this position..." required></textarea>
                                <div class="form-text">Highlight your relevant skills and experience</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Application</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif(isLoggedIn() && isEmployer()): ?>
            <div class="card">
                <div class="card-body">
                    <?php if($job['employer_id'] == $_SESSION['user_id']): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>This is your job posting.
                            <div class="mt-3">
                                <a href="view_applications.php?job_id=<?php echo $job_id; ?>" class="btn btn-primary me-2">View Applications</a>
                                <a href="edit_job.php?id=<?php echo $job_id; ?>" class="btn btn-outline-secondary">Edit Job</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>You are logged in as an employer and cannot apply for jobs.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="fas fa-user-circle fa-3x text-muted mb-3"></i>
                    <h5>Interested in this job?</h5>
                    <p class="text-muted">Login or create an account to apply</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="login.php" class="btn btn-primary">Login</a>
                        <a href="register.php?type=jobseeker" class="btn btn-outline-primary">Register</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">About the Employer</h5>
            </div>
            <div class="card-body">
                <h6><?php echo htmlspecialchars($job['employer_name']); ?></h6>
                <?php if(!$job_expired && isLoggedIn() && isJobSeeker() && $already_applied && $application['status'] == 'accepted'): ?>
                    <p class="mb-0">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        <a href="mailto:<?php echo htmlspecialchars($job['employer_email']); ?>"><?php echo htmlspecialchars($job['employer_email']); ?></a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Job Summary</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-calendar-alt me-2 text-muted"></i>Posted On</span>
                        <span><?php echo date('M d, Y', strtotime($job['created_at'])); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-clock me-2 text-muted"></i>Application Deadline</span>
                        <span><?php echo date('M d, Y', strtotime($job['deadline'])); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-tag me-2 text-muted"></i>Status</span>
                        <span class="badge <?php echo $job_expired ? 'bg-secondary' : 'bg-success'; ?>">
                            <?php echo $job_expired ? 'Expired' : 'Active'; ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
