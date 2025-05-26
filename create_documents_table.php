<?php
// Connect to the database
require_once 'config/db.php';

// Create the talent_documents table
$sql = "CREATE TABLE IF NOT EXISTS talent_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES talent_users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'talent_documents' created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
echo "<br><br><a href='jobseeker/profile.php'>Return to your profile</a>";
?>
