<?php
// Include database configuration
require_once 'config.php';

// Function to register a new user
function registerUser($name, $email, $password, $user_type) {
    global $conn;
    
    // Sanitize inputs
    $name = sanitize_input($name);
    $email = sanitize_input($email);
    $user_type = sanitize_input($user_type);
    
    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($result) > 0) {
        return ["success" => false, "message" => "Email already exists"];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $query = "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_password', '$user_type')";
    
    if(mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);
        
        // Create empty profile for job seekers
        if($user_type == 'jobseeker') {
            $profile_query = "INSERT INTO profiles (user_id) VALUES ($user_id)";
            mysqli_query($conn, $profile_query);
        }
        
        return ["success" => true, "user_id" => $user_id];
    } else {
        return ["success" => false, "message" => "Registration failed: " . mysqli_error($conn)];
    }
}

// Function to authenticate a user
function loginUser($email, $password) {
    global $conn;
    
    // Sanitize input
    $email = sanitize_input($email);
    
    // Get user from database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if(password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['logged_in'] = true;
            
            return ["success" => true, "user" => $user];
        } else {
            return ["success" => false, "message" => "Invalid password"];
        }
    } else {
        return ["success" => false, "message" => "User not found"];
    }
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to check user type
function isJobSeeker() {
    return isLoggedIn() && $_SESSION['user_type'] === 'jobseeker';
}

function isEmployer() {
    return isLoggedIn() && $_SESSION['user_type'] === 'employer';
}

// Function to logout user
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    return true;
}

// Function to redirect if not logged in
function requireLogin() {
    if(!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Function to redirect based on user type
function redirectBasedOnUserType() {
    if(isLoggedIn()) {
        if(isJobSeeker()) {
            header("Location: jobseeker_dashboard.php");
        } else if(isEmployer()) {
            header("Location: employer_dashboard.php");
        }
        exit;
    }
}
?>
