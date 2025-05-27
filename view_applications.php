<?php
// Include header
include_once 'includes/header.php';

// Require login and check if user is an employer
requireLogin();
if(!isEmployer()) {
    header("Location: index.php");
    exit;
}

// Check if job ID is provided
if(!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {
    header("Location: employer_dashboard.php");
    exit;
}

$job_id = $_GET['job_id'];
$user_id = $_SESSION['user_id'];

// Check if the job belongs to the logged-in employer
$job_query = "SELECT * FROM jobs WHERE id = $job_id AND employer_id = $user_id";
$job_result = mysqli_query($conn, $job_query);

if(mysqli_num_rows($job_result) == 0) {
    header("Location: employer_dashboard.php");
    exit;
}

$job = mysqli_fetch_assoc($job_result);

// Process application status updates
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id']) && isset($_POST['status'])) {
    $application_id = sanitize_input($_POST['application_id']);
    $status = sanitize_input($_POST['status']);
    
    // Validate status
    if(in_array($status, ['pending', 'reviewed', 'accepted', 'rejected'])) {
        $update_query = "UPDATE applications SET status = '$status' WHERE id = $application_id AND job_id = $job_id";
        mysqli_query($conn, $update_query);
    }
}

// Get applications for this job
$applications_query = "SELECT a.*, u.name as applicant_name, u.email as applicant_email, p.bio, p.skills 
                      FROM applications a 
                      JOIN users u ON a.jobseeker_id = u.id 
                      LEFT JOIN profiles p ON u.id = p.user_id 
                      WHERE a.job_id = $job_id 
                      ORDER BY a.created_at DESC";
$applications_result = mysqli_query($conn, $applications_query);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="employer_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="job_details.php?id=<?php echo $job_id; ?>"><?php echo htmlspecialchars($job['title']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Applications</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Applications for: <?php echo htmlspecialchars($job['title']); ?></h5>
                <span class="badge bg-primary"><?php echo mysqli_num_rows($applications_result); ?> Applications</span>
            </div>
            <div class="card-body p-0">
                <?php if(mysqli_num_rows($applications_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Applicant</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($application = mysqli_fetch_assoc($applications_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($application['applicant_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($application['created_at'])); ?></td>
                                        <td>
                                            <form method="POST" action="view_applications.php?job_id=<?php echo $job_id; ?>" class="status-form">
                                                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                                <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                                                    <option value="pending" <?php echo $application['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="reviewed" <?php echo $application['status'] == 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                                    <option value="accepted" <?php echo $application['status'] == 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                    <option value="rejected" <?php echo $application['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#applicationModal<?php echo $application['id']; ?>">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Application Details Modal -->
                                    <div class="modal fade" id="applicationModal<?php echo $application['id']; ?>" tabindex="-1" aria-labelledby="applicationModalLabel<?php echo $application['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="applicationModalLabel<?php echo $application['id']; ?>">
                                                        Application from <?php echo htmlspecialchars($application['applicant_name']); ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <h6>Applicant Information</h6>
                                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($application['applicant_name']); ?></p>
                                                            <?php if($application['status'] == 'accepted'): ?>
                                                                <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($application['applicant_email']); ?>"><?php echo htmlspecialchars($application['applicant_email']); ?></a></p>
                                                            <?php endif; ?>
                                                            <p><strong>Applied:</strong> <?php echo date('M d, Y', strtotime($application['created_at'])); ?></p>
                                                            <p>
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
                                                            </p>
                                                            
                                                            <?php if(!empty($application['skills'])): 
                                                                $skills = json_decode($application['skills'], true);
                                                                if(is_array($skills) && !empty($skills)):
                                                            ?>
                                                                <h6 class="mt-3">Skills</h6>
                                                                <div>
                                                                    <?php foreach($skills as $skill): ?>
                                                                        <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; endif; ?>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h6>Application Message</h6>
                                                            <div class="card mb-3">
                                                                <div class="card-body">
                                                                    <?php echo nl2br(htmlspecialchars($application['message'])); ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php if(!empty($application['bio'])): ?>
                                                                <h6>Professional Bio</h6>
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <?php echo nl2br(htmlspecialchars($application['bio'])); ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="view_applications.php?job_id=<?php echo $job_id; ?>">
                                                        <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                                        
                                                        <?php if($application['status'] != 'accepted'): ?>
                                                            <button type="submit" name="status" value="accepted" class="btn btn-success me-2">Accept</button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($application['status'] != 'rejected'): ?>
                                                            <button type="submit" name="status" value="rejected" class="btn btn-danger me-2">Reject</button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($application['status'] != 'reviewed' && $application['status'] != 'accepted' && $application['status'] != 'rejected'): ?>
                                                            <button type="submit" name="status" value="reviewed" class="btn btn-info me-2">Mark as Reviewed</button>
                                                        <?php endif; ?>
                                                        
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5>No Applications Yet</h5>
                        <p class="text-muted">You haven't received any applications for this job yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit status form when selection changes
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>
