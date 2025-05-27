<?php
// Include header
include_once 'includes/header.php';

// Get search parameters
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$skill_filter = isset($_GET['skill']) ? sanitize_input($_GET['skill']) : '';

// Build query
$query = "SELECT j.*, u.name as employer_name 
          FROM jobs j 
          JOIN users u ON j.employer_id = u.id 
          WHERE j.deadline >= CURDATE()";

// Add search condition if provided
if(!empty($search)) {
    $query .= " AND (j.title LIKE '%$search%' OR j.description LIKE '%$search%')";
}

// Add skill filter if provided
if(!empty($skill_filter)) {
    $query .= " AND j.required_skills LIKE '%$skill_filter%'";
}

// Order by most recent
$query .= " ORDER BY j.created_at DESC";

// Execute query
$result = mysqli_query($conn, $query);

// Get all unique skills for filter dropdown
$skills_query = "SELECT required_skills FROM jobs WHERE deadline >= CURDATE()";
$skills_result = mysqli_query($conn, $skills_query);

$all_skills = [];
while($row = mysqli_fetch_assoc($skills_result)) {
    $job_skills = json_decode($row['required_skills'], true);
    if(is_array($job_skills)) {
        $all_skills = array_merge($all_skills, $job_skills);
    }
}
$unique_skills = array_unique($all_skills);
sort($unique_skills);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="profile-header">
            <h2 class="mb-1">Browse Jobs</h2>
            <p class="text-muted mb-0">Find the perfect opportunity for your career</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Search and Filters -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Search & Filters</h5>
            </div>
            <div class="card-body">
                <form action="jobs.php" method="GET">
                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Job title or keywords" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="skill" class="form-label">Filter by Skill</label>
                        <select class="form-select" id="skill" name="skill">
                            <option value="">All Skills</option>
                            <?php foreach($unique_skills as $skill): ?>
                                <option value="<?php echo htmlspecialchars($skill); ?>" <?php echo $skill_filter == $skill ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($skill); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                    
                    <?php if(!empty($search) || !empty($skill_filter)): ?>
                        <div class="d-grid mt-2">
                            <a href="jobs.php" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <?php if(isJobSeeker()): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Job Seeker Tools</h5>
                    <div class="d-grid">
                        <a href="profile.php" class="btn btn-outline-primary">Update Your Profile</a>
                    </div>
                </div>
            </div>
        <?php elseif(isEmployer()): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Employer Tools</h5>
                    <div class="d-grid">
                        <a href="post_job.php" class="btn btn-outline-primary">Post a New Job</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Job Listings -->
    <div class="col-md-8">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="mb-3">
                <p class="text-muted">Showing <?php echo mysqli_num_rows($result); ?> job listings</p>
            </div>
            
            <?php while($job = mysqli_fetch_assoc($result)): 
                // Parse required skills
                $skills = json_decode($job['required_skills'], true);
            ?>
                <div class="card mb-3 job-listing">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="job-title mb-1">
                                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($job['title']); ?>
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-building me-2"></i><?php echo htmlspecialchars($job['employer_name']); ?>
                                </p>
                            </div>
                            <span class="badge bg-primary">
                                <?php 
                                    $days_left = floor((strtotime($job['deadline']) - time()) / (60 * 60 * 24));
                                    echo $days_left > 0 ? $days_left . ' days left' : 'Last day';
                                ?>
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <?php if(is_array($skills)): foreach($skills as $skill): ?>
                                <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; endif; ?>
                        </div>
                        
                        <p class="card-text">
                            <?php 
                                $desc = htmlspecialchars($job['description']);
                                echo strlen($desc) > 200 ? substr($desc, 0, 200) . '...' : $desc; 
                            ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Posted <?php echo date('M d, Y', strtotime($job['created_at'])); ?></small>
                            <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            
        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No Jobs Found</h4>
                    <p class="text-muted">
                        <?php if(!empty($search) || !empty($skill_filter)): ?>
                            Try adjusting your search filters to find more opportunities.
                        <?php else: ?>
                            There are no active job listings at the moment. Please check back later.
                        <?php endif; ?>
                    </p>
                    <?php if(!empty($search) || !empty($skill_filter)): ?>
                        <a href="jobs.php" class="btn btn-primary mt-2">Clear Filters</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
