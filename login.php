<?php
// Include header
include_once 'includes/header.php';

// Redirect if already logged in
if(isLoggedIn()) {
    redirectBasedOnUserType();
}

// Process login form
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate form data
    if(empty($email)) {
        $errors[] = "Email is required";
    }
    
    if(empty($password)) {
        $errors[] = "Password is required";
    }
    
    // Login user if no errors
    if(empty($errors)) {
        $result = loginUser($email, $password);
        
        if($result['success']) {
            redirectBasedOnUserType();
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0">Login to Your Account</h3>
            </div>
            <div class="card-body">
                <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-white text-center">
                Don't have an account? <a href="register.php">Register</a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
