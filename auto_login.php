<?php
session_start();
// This script will automatically log in as John Smith
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    require_once "config/db.php";
    
    $email = "john@example.com";
    $password = "password123";
    
    // Get user from database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM talent_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user["password"])) {
            // Password is correct, create session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            
            // Redirect to appropriate dashboard
            if ($user["role"] == "jobseeker") {
                header("Location: jobseeker/dashboard.php");
            } else {
                header("Location: employer/dashboard.php");
            }
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>