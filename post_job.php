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

// Process form submission
$success = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $required_skills = isset($_POST['required_skills']) ? $_POST['required_skills'] : '[]';
    $deadline = isset($_POST['deadline']) ? $_POST['deadline'] : '';
    
    // Validate form data
    if(empty($title)) {
        $error = "Job title is required";
    } elseif(empty($description)) {
        $error = "Job description is required";
    } elseif(empty($deadline)) {
        $error = "Application deadline is required";
    } elseif(strtotime($deadline) < strtotime(date('Y-m-d'))) {
        $error = "Deadline cannot be in the past";
    } else {
        // Insert job
        $insert_query = "INSERT INTO jobs (employer_id, title, description, required_skills, deadline) 
                        VALUES ($user_id, 
                                '" . sanitize_input($title) . "', 
                                '" . sanitize_input($description) . "', 
                                '" . sanitize_input($required_skills) . "', 
                                '" . sanitize_input($deadline) . "')";
        
        if(mysqli_query($conn, $insert_query)) {
            $success = true;
            $job_id = mysqli_insert_id($conn);
        } else {
            $error = "Error posting job: " . mysqli_error($conn);
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="profile-header">
            <h2 class="mb-1">Post a New Job</h2>
            <p class="text-muted mb-0">Create a job listing to find the perfect candidate</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <?php if($success): ?>
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>Job posted successfully!
                <div class="mt-3">
                    <a href="job_details.php?id=<?php echo $job_id; ?>" class="btn btn-sm btn-primary me-2">View Job</a>
                    <a href="post_job.php" class="btn btn-sm btn-outline-primary">Post Another Job</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(!$success): ?>
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Job Details</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="post_job.php">
                        <div class="mb-3">
                            <label for="title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                            <div class="form-text">Include responsibilities, qualifications, and any other relevant details</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Required Skills</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="skills-input" placeholder="Add a required skill">
                                <button class="btn btn-outline-secondary" type="button" onclick="addSkillFromInput()">Add</button>
                            </div>
                            <div class="form-text mb-2">Press Enter to add each skill</div>
                            
                            <div id="skills-container" class="d-flex flex-wrap">
                                <!-- Skills will be added here dynamically -->
                            </div>
                            
                            <input type="hidden" id="required_skills" name="required_skills" value='[]'>
                        </div>
                        
                        <div class="mb-4">
                            <label for="deadline" class="form-label">Application Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Post Job</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function addSkillFromInput() {
    const skillsInput = document.getElementById('skills-input');
    if (skillsInput.value.trim() !== '') {
        addSkill(skillsInput.value.trim());
        skillsInput.value = '';
        updateSkillsHiddenInput();
    }
}

function addSkill(skill) {
    const skillsContainer = document.getElementById('skills-container');
    const skillTag = document.createElement('span');
    skillTag.classList.add('skill-tag', 'me-2', 'mb-2');
    skillTag.innerHTML = skill + ' <i class="fas fa-times-circle" onclick="removeSkill(this)"></i>';
    skillsContainer.appendChild(skillTag);
}

function removeSkill(element) {
    element.parentElement.remove();
    updateSkillsHiddenInput();
}

function updateSkillsHiddenInput() {
    const skills = [];
    document.querySelectorAll('.skill-tag').forEach(tag => {
        skills.push(tag.textContent.trim().replace(' ×', ''));
    });
    document.getElementById('required_skills').value = JSON.stringify(skills);
}

// Add event listener for Enter key on skills input
document.getElementById('skills-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && this.value.trim() !== '') {
        e.preventDefault();
        addSkillFromInput();
    }
});
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>
