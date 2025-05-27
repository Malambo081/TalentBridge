<?php
// Include header
include_once 'includes/header.php';

// Require login and check if user is a job seeker
requireLogin();
if(!isJobSeeker()) {
    header("Location: index.php");
    exit;
}

// Get user profile
$user_id = $_SESSION['user_id'];
$profile_query = "SELECT * FROM profiles WHERE user_id = $user_id";
$profile_result = mysqli_query($conn, $profile_query);
$profile = mysqli_fetch_assoc($profile_result);

// Get user applications
$applications_query = "SELECT a.*, j.title as job_title, j.deadline, u.name as employer_name 
                      FROM applications a 
                      JOIN jobs j ON a.job_id = j.id 
                      JOIN users u ON j.employer_id = u.id 
                      WHERE a.jobseeker_id = $user_id 
                      ORDER BY a.created_at DESC";
$applications_result = mysqli_query($conn, $applications_query);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="profile-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
                <p class="text-muted mb-0">Manage your job applications and profile</p>
            </div>
            <a href="profile.php" class="btn btn-outline-primary">
                <i class="fas fa-user-edit me-2"></i>Edit Profile
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
                    <i class="fas fa-file-alt"></i>
                    <h3><?php echo mysqli_num_rows($applications_result); ?></h3>
                    <p class="text-muted mb-0">Applications Submitted</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="dashboard-stats">
                    <i class="fas fa-check-circle"></i>
                    <?php
                    $accepted_query = "SELECT COUNT(*) as count FROM applications WHERE jobseeker_id = $user_id AND status = 'accepted'";
                    $accepted_result = mysqli_query($conn, $accepted_query);
                    $accepted_count = mysqli_fetch_assoc($accepted_result)['count'];
                    ?>
                    <h3><?php echo $accepted_count; ?></h3>
                    <p class="text-muted mb-0">Accepted Applications</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="dashboard-stats">
                    <i class="fas fa-hourglass-half"></i>
                    <?php
                    $pending_query = "SELECT COUNT(*) as count FROM applications WHERE jobseeker_id = $user_id AND status = 'pending'";
                    $pending_result = mysqli_query($conn, $pending_query);
                    $pending_count = mysqli_fetch_assoc($pending_result)['count'];
                    ?>
                    <h3><?php echo $pending_count; ?></h3>
                    <p class="text-muted mb-0">Pending Applications</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Completeness -->
<?php
$profile_fields = ['bio', 'skills'];
$filled_fields = 0;

foreach($profile_fields as $field) {
    if(!empty($profile[$field])) {
        $filled_fields++;
    }
}

$completeness = ($filled_fields / count($profile_fields)) * 100;
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Profile Completeness</h5>
                <div class="progress mb-3">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $completeness; ?>%" aria-valuenow="<?php echo $completeness; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <?php if($completeness < 100): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>Complete your profile to increase your chances of getting hired!
                        <a href="profile.php" class="alert-link">Update now</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>Your profile is complete! Keep it updated to maintain visibility to employers.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Your Applications</h5>
                <a href="jobs.php" class="btn btn-sm btn-primary">Find More Jobs</a>
            </div>
            <div class="card-body p-0">
                <?php if(mysqli_num_rows($applications_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Company</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($application = mysqli_fetch_assoc($applications_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                                        <td><?php echo htmlspecialchars($application['employer_name']); ?></td>
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
                                            <a href="job_details.php?id=<?php echo $application['job_id']; ?>" class="btn btn-sm btn-outline-primary">View Job</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5>No Applications Yet</h5>
                        <p class="text-muted">You haven't applied to any jobs yet.</p>
                        <a href="jobs.php" class="btn btn-primary">Browse Jobs</a>
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
