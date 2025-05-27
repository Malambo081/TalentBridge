<?php
// Include header
include_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-delay="100">
                <h1 class="hero-title">Find Your <span style="color: var(--primary-color);">Perfect Match</span></h1>
                <p class="hero-subtitle">TalentBridge connects talented professionals with the right opportunities. Whether you're looking for your next career move or searching for the perfect candidate, we've got you covered.</p>
                <div class="d-flex flex-wrap gap-3">
                    <?php if(!isLoggedIn()): ?>
                        <a href="register.php?type=jobseeker" class="btn btn-primary btn-lg"><i class="fas fa-user-plus me-2"></i>I'm Looking for a Job</a>
                        <a href="register.php?type=employer" class="btn btn-outline-primary btn-lg"><i class="fas fa-building me-2"></i>I'm Hiring</a>
                    <?php else: ?>
                        <?php if(isJobSeeker()): ?>
                            <a href="jobs.php" class="btn btn-primary btn-lg"><i class="fas fa-search me-2"></i>Browse Jobs</a>
                            <a href="jobseeker_dashboard.php" class="btn btn-outline-primary btn-lg"><i class="fas fa-tachometer-alt me-2"></i>My Dashboard</a>
                        <?php else: ?>
                            <a href="post_job.php" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle me-2"></i>Post a Job</a>
                            <a href="employer_dashboard.php" class="btn btn-outline-primary btn-lg"><i class="fas fa-tachometer-alt me-2"></i>My Dashboard</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="mt-5 d-flex align-items-center">
                    <div class="d-flex">
                        <div class="avatar-stack">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=4361ee&color=fff&size=40" class="rounded-circle border border-2 border-white" alt="User" style="margin-right: -10px;">
                            <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=4361ee&color=fff&size=40" class="rounded-circle border border-2 border-white" alt="User" style="margin-right: -10px;">
                            <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=4361ee&color=fff&size=40" class="rounded-circle border border-2 border-white" alt="User">
                        </div>
                        <div class="ms-3">
                            <p class="mb-0 text-muted"><strong>1000+</strong> professionals found jobs through TalentBridge</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                <div class="position-relative">
                    <div class="position-absolute top-0 start-50 translate-middle-x" style="z-index: 1;">
                        <div class="badge bg-primary p-2 rounded-pill shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> Trusted by 500+ companies
                        </div>
                    </div>
                    <img src="assets/images/hero-image.svg" alt="TalentBridge" class="img-fluid rounded shadow-lg" style="border-radius: 20px;">
                    <div class="position-absolute bottom-0 end-0 mb-4 me-4 bg-white p-3 rounded-3 shadow-lg" style="z-index: 1;" data-aos="fade-up" data-aos-delay="400">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-center" style="width: 40px;">
                                <i class="fas fa-bolt fs-3" style="color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Quick Matching</h6>
                                <p class="mb-0 small text-muted">Find the right fit in minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <h6 class="text-uppercase fw-bold" style="color: var(--primary-color);" data-aos="fade-up">Our Benefits</h6>
                <h2 class="fw-bold mb-3" data-aos="fade-up" data-aos-delay="100">Why Choose TalentBridge?</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="200">Simple, effective, and designed with you in mind</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="feature-title">Find Opportunities</h4>
                    <p class="feature-description">Discover jobs that match your skills and career goals with our intuitive job search and matching algorithm.</p>
                    <a href="jobs.php" class="btn btn-sm btn-outline-primary mt-3">Browse Jobs</a>
                </div>
            </div>
            
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4 class="feature-title">Showcase Your Talent</h4>
                    <p class="feature-description">Create a profile that highlights your skills and experience to stand out to employers looking for your expertise.</p>
                    <a href="register.php?type=jobseeker" class="btn btn-sm btn-outline-primary mt-3">Create Profile</a>
                </div>
            </div>
            
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="feature-title">Connect Directly</h4>
                    <p class="feature-description">Apply to jobs and communicate with employers through our streamlined platform with real-time notifications.</p>
                    <a href="register.php" class="btn btn-sm btn-outline-primary mt-3">Get Started</a>
                </div>
            </div>
        </div>
        
        <div class="row mt-5 pt-4">
            <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="d-inline-block p-4 rounded-3 shadow-sm bg-white">
                    <div class="row align-items-center g-0">
                        <div class="col-md-3 border-end px-3">
                            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">500+</h3>
                            <p class="text-muted mb-0">Companies</p>
                        </div>
                        <div class="col-md-3 border-end px-3">
                            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">1,000+</h3>
                            <p class="text-muted mb-0">Job Seekers</p>
                        </div>
                        <div class="col-md-3 border-end px-3">
                            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">5,000+</h3>
                            <p class="text-muted mb-0">Job Postings</p>
                        </div>
                        <div class="col-md-3 px-3">
                            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">98%</h3>
                            <p class="text-muted mb-0">Satisfaction</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Jobs Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col-lg-6" data-aos="fade-right">
                <h6 class="text-uppercase fw-bold" style="color: var(--primary-color);">Latest Listings</h6>
                <h2 class="fw-bold">Recent Opportunities</h2>
                <p class="text-muted">Take a look at the latest job postings from top companies</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0" data-aos="fade-left">
                <a href="jobs.php" class="btn btn-outline-primary"><i class="fas fa-th-list me-2"></i>View All Jobs</a>
            </div>
        </div>
    
    <?php
    // Get recent jobs from database
    $query = "SELECT j.*, u.name as employer_name 
              FROM jobs j 
              JOIN users u ON j.employer_id = u.id 
              WHERE j.deadline >= CURDATE() 
              ORDER BY j.created_at DESC 
              LIMIT 3";
    $result = mysqli_query($conn, $query);
    
    // Start the row for job cards
    echo '<div class="row">';
    
    if(mysqli_num_rows($result) > 0):
        $delay = 100;
        while($job = mysqli_fetch_assoc($result)):
            // Parse required skills
            $skills = json_decode($job['required_skills'], true);
            $delay += 100; // Increment delay for staggered animation
    ?>
    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
        <div class="card h-100 job-listing">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <?php 
                            $days_left = floor((strtotime($job['deadline']) - time()) / (60 * 60 * 24));
                            echo $days_left > 0 ? $days_left . ' days left' : 'Last day';
                        ?>
                    </span>
                    <span class="text-muted small"><i class="far fa-clock me-1"></i> <?php echo date('M d', strtotime($job['created_at'])); ?></span>
                </div>
                
                <h5 class="job-title">
                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($job['title']); ?>
                    </a>
                </h5>
                
                <p class="job-company mb-3">
                    <i class="fas fa-building me-2"></i><?php echo htmlspecialchars($job['employer_name']); ?>
                </p>
                
                <div class="job-skills mb-3">
                    <?php if(is_array($skills)): foreach($skills as $skill): ?>
                        <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                    <?php endforeach; endif; ?>
                </div>
                
                <p class="job-description"><?php echo substr(htmlspecialchars($job['description']), 0, 100) . '...'; ?></p>
                
                <div class="job-meta">
                    <span class="text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i> Deadline: <?php echo date('M d, Y', strtotime($job['deadline'])); ?>
                    </span>
                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
    <?php
        endwhile;
    else:
    ?>
    <div class="col-12" data-aos="fade-up">
        <div class="alert alert-info shadow-sm">
            <i class="fas fa-info-circle me-2"></i>No jobs available at the moment. Check back soon!
        </div>
    </div>
    <?php 
        endif; 
    ?>
        </div><!-- End of row -->
    </div><!-- End of container -->
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="fw-bold mb-3">Ready to Take the Next Step in Your Career?</h2>
                <p class="lead mb-0">Join thousands of professionals who have found their dream jobs through TalentBridge.</p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <?php if(!isLoggedIn()): ?>
                    <a href="register.php?type=jobseeker" class="btn btn-light btn-lg me-2 mb-2 mb-md-0">
                        <i class="fas fa-user-plus me-2"></i>Sign Up
                    </a>
                    <a href="jobs.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Browse Jobs
                    </a>
                <?php else: ?>
                    <a href="jobs.php" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i>Browse Jobs
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'includes/footer.php';
?>
