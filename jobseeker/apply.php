<?php
include '../includes/header.php';
include '../includes/auth.php';
if ($_SESSION['role'] !== 'jobseeker') { header('Location: ../index.php'); exit(); }
require_once '../config/db.php';

$seeker_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = intval($_POST['job_id']);
    // Get latest CV file
    $stmt = $conn->prepare('SELECT filename FROM talent_documents WHERE user_id = ? ORDER BY uploaded_at DESC LIMIT 1');
    $stmt->bind_param('i', $seeker_id);
    $stmt->execute();
    $stmt->bind_result($cv_file);
    $stmt->fetch();
    $stmt->close();
    if (!$cv_file) {
        // Modern design for CV missing error
        ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-4">
                        <div class="mb-4">
                            <div class="rounded-circle bg-warning bg-opacity-10 mx-auto p-4 mb-3" style="width: 100px; height: 100px;">
                                <i class="bi bi-file-earmark-text text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold">CV Required</h2>
                            <p class="text-muted mb-4">Please upload your CV/resume before applying for this position.</p>
                        </div>
                        
                        <div class="card bg-light border-0 mb-4 p-4">
                            <h5 class="mb-3">Why is a CV needed?</h5>
                            <p>Employers need to review your qualifications, experience, and skills to determine if you're a good fit for the position.</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="profile.php" class="btn btn-primary py-3 hover-lift">
                                <i class="bi bi-upload me-2"></i>Upload CV Now
                            </a>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Go Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include '../includes/footer.php';
        exit();
    }
    // Prevent duplicate applications
    $stmt = $conn->prepare('SELECT id FROM talent_applications WHERE job_id = ? AND seeker_id = ?');
    $stmt->bind_param('ii', $job_id, $seeker_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        
        // Get job details for display
        $job_stmt = $conn->prepare('SELECT title FROM talent_opportunities WHERE id = ?');
        $job_stmt->bind_param('i', $job_id);
        $job_stmt->execute();
        $job_stmt->bind_result($job_title);
        $job_stmt->fetch();
        $job_stmt->close();
        
        // Modern design for already applied notice
        ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-4">
                        <div class="mb-4">
                            <div class="rounded-circle bg-info bg-opacity-10 mx-auto p-4 mb-3" style="width: 100px; height: 100px;">
                                <i class="bi bi-info-circle text-info" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold">Already Applied</h2>
                            <p class="text-muted mb-4">You have already submitted an application for <strong><?php echo htmlspecialchars($job_title ?? 'this position'); ?></strong>.</p>
                        </div>
                        
                        <div class="card bg-light border-0 mb-4 p-4">
                            <h5 class="mb-3">Application Status</h5>
                            <p>You can track the status of your application in your dashboard under the 'My Applications' section.</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="dashboard.php?tab=applications" class="btn btn-primary py-3 hover-lift">
                                <i class="bi bi-list-check me-2"></i>View My Applications
                            </a>
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="bi bi-speedometer2 me-2"></i>Return to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include '../includes/footer.php';
        exit();
    }
    $stmt->close();
    // Insert application - Fixed table name to match our schema update
    $stmt = $conn->prepare('INSERT INTO talent_applications (job_id, seeker_id, cv_file) VALUES (?, ?, ?)');
    $stmt->bind_param('iis', $job_id, $seeker_id, $cv_file);
    if ($stmt->execute()) {
        // Get job details for success page
        $job_stmt = $conn->prepare('SELECT title, employer FROM talent_opportunities WHERE id = ?');
        $job_stmt->bind_param('i', $job_id);
        $job_stmt->execute();
        $job_result = $job_stmt->get_result();
        $job = $job_result->fetch_assoc();
        $job_stmt->close();
        
        // Success state stored for display
        $application_success = true;
        $job_title = $job['title'] ?? 'the position';
    } else {
        // Error state stored for display
        $application_error = true;
        $error_message = $conn->error;
    }
    $stmt->close();
    
    // Display success message with modern design
    if (isset($application_success)) {
        ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-4">
                        <div class="mb-4">
                            <div class="rounded-circle bg-success bg-opacity-10 mx-auto p-4 mb-3" style="width: 100px; height: 100px;">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold">Application Submitted!</h2>
                            <p class="text-muted mb-4">Your application for <strong><?php echo htmlspecialchars($job_title); ?></strong> has been successfully submitted.</p>
                        </div>
                        
                        <div class="card bg-light border-0 mb-4 p-3">
                            <h5 class="mb-3">What's Next?</h5>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 24px; height: 24px;">
                                    <small>1</small>
                                </div>
                                <p class="mb-0 text-start">Your application is now under review by the employer</p>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 24px; height: 24px;">
                                    <small>2</small>
                                </div>
                                <p class="mb-0 text-start">You'll receive updates on your application status</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 24px; height: 24px;">
                                    <small>3</small>
                                </div>
                                <p class="mb-0 text-start">If selected, the employer will contact you</p>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="dashboard.php" class="btn btn-primary py-3 hover-lift">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                            <a href="dashboard.php?tab=applications" class="btn btn-outline-secondary">
                                <i class="bi bi-list-check me-2"></i>View My Applications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } elseif (isset($application_error)) {
        // Display error message with modern design
        ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-4">
                        <div class="mb-4">
                            <div class="rounded-circle bg-danger bg-opacity-10 mx-auto p-4 mb-3" style="width: 100px; height: 100px;">
                                <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold">Application Failed</h2>
                            <p class="text-muted mb-4">There was an error submitting your application. Please try again.</p>
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="javascript:history.back()" class="btn btn-primary py-3">
                                <i class="bi bi-arrow-left me-2"></i>Go Back and Try Again
                            </a>
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="bi bi-speedometer2 me-2"></i>Return to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    include '../includes/footer.php';
    exit();
} else {
    header('Location: dashboard.php');
    exit();
}
?>


