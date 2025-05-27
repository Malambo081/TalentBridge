<?php
// Include database configuration
require_once 'config.php';

// Create users table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('jobseeker', 'employer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create profiles table for job seekers
$sql_profiles = "CREATE TABLE IF NOT EXISTS profiles (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    bio TEXT,
    skills TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Create jobs table
$sql_jobs = "CREATE TABLE IF NOT EXISTS jobs (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    employer_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    required_skills TEXT NOT NULL,
    deadline DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Create applications table
$sql_applications = "CREATE TABLE IF NOT EXISTS applications (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    job_id INT NOT NULL,
    jobseeker_id INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (jobseeker_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Execute queries
if (mysqli_query($conn, $sql_users)) {
    echo "Users table created successfully.<br>";
} else {
    echo "ERROR: Could not create users table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $sql_profiles)) {
    echo "Profiles table created successfully.<br>";
} else {
    echo "ERROR: Could not create profiles table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $sql_jobs)) {
    echo "Jobs table created successfully.<br>";
} else {
    echo "ERROR: Could not create jobs table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $sql_applications)) {
    echo "Applications table created successfully.<br>";
} else {
    echo "ERROR: Could not create applications table: " . mysqli_error($conn) . "<br>";
}

// Close connection
mysqli_close($conn);
echo "Database setup completed.";
?>
