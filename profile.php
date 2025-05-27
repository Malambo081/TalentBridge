<?php
// Include header
include_once 'includes/header.php';

// Require login and check if user is a job seeker
requireLogin();
if(!isJobSeeker()) {
    header("Location: index.php");
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Get profile data
$profile_query = "SELECT * FROM profiles WHERE user_id = $user_id";
$profile_result = mysqli_query($conn, $profile_query);

// Check if profile exists, create if not
if(mysqli_num_rows($profile_result) == 0) {
    $create_profile_query = "INSERT INTO profiles (user_id) VALUES ($user_id)";
    mysqli_query($conn, $create_profile_query);
    $profile_result = mysqli_query($conn, $profile_query);
}

$profile = mysqli_fetch_assoc($profile_result);

// Process form submission
$success = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';
    $skills = isset($_POST['skills']) ? $_POST['skills'] : '[]';
    
    // Validate form data
    if(strlen($bio) > 1000) {
        $error = "Bio must be less than 1000 characters";
    } else {
        // Update profile
        $update_query = "UPDATE profiles SET 
                        bio = '" . sanitize_input($bio) . "', 
                        skills = '" . sanitize_input($skills) . "' 
                        WHERE user_id = $user_id";
        
        if(mysqli_query($conn, $update_query)) {
            $success = true;
            
            // Refresh profile data
            $profile_result = mysqli_query($conn, $profile_query);
            $profile = mysqli_fetch_assoc($profile_result);
        } else {
            $error = "Error updating profile: " . mysqli_error($conn);
        }
    }
}

// Parse skills
$skills_array = !empty($profile['skills']) ? json_decode($profile['skills'], true) : [];
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="profile-header">
            <h2 class="mb-1">Your Profile</h2>
            <p class="text-muted mb-0">Update your information to attract potential employers</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4a6bff&color=fff&size=100" alt="Profile" class="profile-img">
                </div>
                <h5 class="mb-1"><?php echo htmlspecialchars($_SESSION['name']); ?></h5>
                <p class="text-muted"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <hr>
                <div class="d-grid">
                    <a href="jobseeker_dashboard.php" class="btn btn-outline-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Edit Profile</h5>
            </div>
            <div class="card-body">
                <?php if($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Profile updated successfully!
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="profile.php">
                    <div class="mb-3">
                        <label for="bio" class="form-label">Professional Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Tell employers about yourself, your experience, and what you're looking for"><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                        <div class="form-text">Limit: 1000 characters</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Skills</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="skills-input" placeholder="Add a skill (e.g. JavaScript, Project Management)">
                            <button class="btn btn-outline-secondary" type="button" onclick="addSkillFromInput()">Add</button>
                        </div>
                        <div class="form-text mb-2">Press Enter to add each skill</div>
                        
                        <div id="skills-container" class="d-flex flex-wrap">
                            <?php foreach($skills_array as $skill): ?>
                                <span class="skill-tag me-2 mb-2"><?php echo htmlspecialchars($skill); ?> <i class="fas fa-times-circle" onclick="removeSkill(this)"></i></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" id="skills" name="skills" value='<?php echo htmlspecialchars(json_encode($skills_array)); ?>'>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
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
    document.getElementById('skills').value = JSON.stringify(skills);
}
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>
