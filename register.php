<?php
// Include header
include_once 'includes/header.php';

// Redirect if already logged in
if(isLoggedIn()) {
    redirectBasedOnUserType();
}

// Set default user type
$user_type = isset($_GET['type']) && in_array($_GET['type'], ['jobseeker', 'employer']) ? $_GET['type'] : 'jobseeker';

// Process registration form
$errors = [];
$success = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
    
    // Validate form data
    if(empty($name)) {
        $errors[] = "Name is required";
    }
    
    if(empty($email)) {
        $errors[] = "Email is required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if(empty($password)) {
        $errors[] = "Password is required";
    } elseif(strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if(empty($user_type) || !in_array($user_type, ['jobseeker', 'employer'])) {
        $errors[] = "Invalid user type";
    }
    
    // Register user if no errors
    if(empty($errors)) {
        $result = registerUser($name, $email, $password, $user_type);
        
        if($result['success']) {
            // Auto login after registration
            $login_result = loginUser($email, $password);
            
            if($login_result['success']) {
                redirectBasedOnUserType();
            } else {
                $success = true;
                $message = "Registration successful! Please <a href='login.php'>login</a> to continue.";
            }
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<div class="row justify-content-center py-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0">Create Your Account</h3>
            </div>
            <div class="card-body">
                <?php if($success): ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="user_type" class="form-label">I am a:</label>
                        <div class="d-flex">
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" name="user_type" id="jobseeker" value="jobseeker" <?php echo $user_type == 'jobseeker' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="jobseeker">
                                    <i class="fas fa-user me-2"></i>Job Seeker
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="employer" value="employer" <?php echo $user_type == 'employer' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="employer">
                                    <i class="fas fa-building me-2"></i>Employer
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="progress mt-2" style="height: 5px;">
                            <div id="password-strength" class="progress-bar" role="progressbar"></div>
                        </div>
                        <small class="text-muted">Use at least 8 characters with a mix of letters, numbers & symbols</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-white text-center">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
