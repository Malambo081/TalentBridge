<?php
// Include header
include_once 'includes/header.php';

// Require login and check if user is an employer
requireLogin();
if(!isEmployer()) {
    header("Location: index.php");
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];

// Get posted jobs
$jobs_query = "SELECT * FROM jobs WHERE employer_id = $user_id ORDER BY created_at DESC";
$jobs_result = mysqli_query($conn, $jobs_query);

// Get total applications for all jobs
$applications_query = "SELECT a.*, j.title as job_title, u.name as applicant_name 
                      FROM applications a 
                      JOIN jobs j ON a.job_id = j.id 
                      JOIN users u ON a.jobseeker_id = u.id 
                      WHERE j.employer_id = $user_id 
                      ORDER BY a.created_at DESC";
$applications_result = mysqli_query($conn, $applications_query);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="profile-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
                <p class="text-muted mb-0">Manage your job postings and applications</p>
            </div>
            <a href="post_job.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Post a New Job
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Dashboard Stats -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="dashboard-stats">
                    <i class="fas fa-briefcase"></i>
                    <h3><?php echo mysqli_num_rows($jobs_result); ?></h3>
                    <p class="text-muted mb-0">Jobs Posted</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="dashboard-stats">
                    <i class="fas fa-users"></i>
                    <h3><?php echo mysqli_num_rows($applications_result); ?></h3>
                    <p class="text-muted mb-0">Total Applications</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="dashboard-stats">
                    <i class="fas fa-clock"></i>
                    <?php
                    $active_query = "SELECT COUNT(*) as count FROM jobs WHERE employer_id = $user_id AND deadline >= CURDATE()";
                    $active_result = mysqli_query($conn, $active_query);
                    $active_count = mysqli_fetch_assoc($active_result)['count'];
                    ?>
                    <h3><?php echo $active_count; ?></h3>
                    <p class="text-muted mb-0">Active Job Listings</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Posted Jobs -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Your Job Postings</h5>
                <a href="post_job.php" class="btn btn-sm btn-outline-primary">Post New Job</a>
            </div>
            <div class="card-body p-0">
                <?php if(mysqli_num_rows($jobs_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Posted On</th>
                                    <th>Deadline</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($job = mysqli_fetch_assoc($jobs_result)): 
                                    // Get application count for this job
                                    $app_count_query = "SELECT COUNT(*) as count FROM applications WHERE job_id = " . $job['id'];
                                    $app_count_result = mysqli_query($conn, $app_count_query);
                                    $app_count = mysqli_fetch_assoc($app_count_result)['count'];
                                    
                                    // Check if job is active
                                    $is_active = strtotime($job['deadline']) >= strtotime(date('Y-m-d'));
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($job['created_at'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($job['deadline'])); ?></td>
                                        <td>
                                            <a href="view_applications.php?job_id=<?php echo $job['id']; ?>" class="text-decoration-none">
                                                <?php echo $app_count; ?> application<?php echo $app_count != 1 ? 's' : ''; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if($is_active): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Expired</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-primary me-1">View</a>
                                            <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <h5>No Jobs Posted Yet</h5>
                        <p class="text-muted">Start attracting talent by posting your first job.</p>
                        <a href="post_job.php" class="btn btn-primary">Post a Job</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Applications</h5>
            </div>
            <div class="card-body p-0">
                <?php if(mysqli_num_rows($applications_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Applicant</th>
                                    <th>Job Title</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 0;
                                while($application = mysqli_fetch_assoc($applications_result)): 
                                    if($count++ >= 5) break; // Show only 5 recent applications
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($application['applicant_name']); ?></td>
                                        <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($application['created_at'])); ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch($application['status']) {
                                                case 'pending':
                                                    $status_class = 'bg-warning text-dark';
                                                    break;
                                                case 'reviewed':
                                                    $status_class = 'bg-info text-white';
                                                    break;
                                                case 'accepted':
                                                    $status_class = 'bg-success text-white';
                                                    break;
                                                case 'rejected':
                                                    $status_class = 'bg-danger text-white';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($application['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view_application.php?id=<?php echo $application['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php if(mysqli_num_rows($applications_result) > 5): ?>
                            <div class="text-center py-3">
                                <a href="view_all_applications.php" class="btn btn-link">View All Applications</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5>No Applications Yet</h5>
                        <p class="text-muted">You haven't received any applications yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
