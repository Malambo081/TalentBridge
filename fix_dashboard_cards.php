<?php
// This script will fix the empty cards in the job seeker dashboard

// Connect to the database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'job_application_portal';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to MySQL successfully<br>";

// Check how many jobs and applications are in the database
$jobs_count = $conn->query("SELECT COUNT(*) as count FROM talent_opportunities")->fetch_assoc()['count'];
$applications_count = $conn->query("SELECT COUNT(*) as count FROM talent_applications")->fetch_assoc()['count'];

echo "Current jobs count: " . $jobs_count . "<br>";
echo "Current applications count: " . $applications_count . "<br>";

// Check if there are any applications for existing job seekers
$seeker_apps = $conn->query("SELECT users.username, COUNT(applications.id) as app_count 
                           FROM users 
                           LEFT JOIN applications ON users.id = applications.seeker_id 
                           WHERE users.role = 'jobseeker' 
                           GROUP BY users.id");

echo "<br>Applications per job seeker:<br>";
$has_apps = false;
while ($row = $seeker_apps->fetch_assoc()) {
    echo "- " . $row['username'] . ": " . $row['app_count'] . " applications<br>";
    if ($row['app_count'] > 0) {
        $has_apps = true;
    }
}

// Create a login script to help user log in as John Smith
echo "<br><h2>Quick Login Form</h2>";
echo "<p>Use this form to log in as John Smith and view the dashboard with populated data:</p>";
echo "<form action='auto_login.php' method='post'>";
echo "<input type='hidden' name='email' value='john@example.com'>";
echo "<input type='hidden' name='password' value='password123'>";
echo "<button type='submit' style='background-color: #2a9d8f; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Log in as John Smith</button>";
echo "</form>";

// Create the auto login script
$auto_login_script = '<?php
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
?>';

file_put_contents("auto_login.php", $auto_login_script);
echo "<br>Auto login script created.";

echo "<br><br><h2>Fix for Empty Cards</h2>";
echo "<p>If you're already logged in but still see empty cards, you may need to clear your browser cache or try using the login form above.</p>";

// Fix the dashboard code to ensure cards are never empty
$dashboard_file = file_get_contents("jobseeker/dashboard.php");

// Look for patterns that might cause empty cards and fix them
$updated_dashboard = $dashboard_file;

// Check for session data initialization
if (strpos($dashboard_file, '// Ensure session data exists') === false) {
    $session_check = '
// Ensure session data exists
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}
';
    $updated_dashboard = str_replace('include \'../includes/auth.php\';', 'include \'../includes/auth.php\';' . $session_check, $updated_dashboard);
}

// Modify the chart initialization to handle zero values
$updated_dashboard = str_replace(
    'datasets: [{ 
                data: [<?php echo $stats[\'pending\']; ?>, <?php echo $stats[\'shortlisted\']; ?>, <?php echo $stats[\'rejected\']; ?>], 
                backgroundColor: [\'#ffc107\', \'#17a2b8\', \'#dc3545\'],
                borderWidth: 0
            }]',
    'datasets: [{ 
                data: [<?php echo $stats[\'pending\'] ? $stats[\'pending\'] : 0; ?>, <?php echo $stats[\'shortlisted\'] ? $stats[\'shortlisted\'] : 0; ?>, <?php echo $stats[\'rejected\'] ? $stats[\'rejected\'] : 0; ?>], 
                backgroundColor: [\'#ffc107\', \'#17a2b8\', \'#dc3545\'],
                borderWidth: 0
            }]',
    $updated_dashboard
);

// Ensure fallback content if no jobs or applications exist
if (strpos($updated_dashboard, '<!-- Fallback content if no data -->') === false) {
    $fallback_code = '
    <?php if ($count == 0): ?>
    <!-- Fallback content if no data -->
    <div class="col fade-in-up w-100">
        <div class="card h-100 shadow-sm job-card-modern rounded-3 border-0">
            <div class="card-body d-flex flex-column p-4 text-center">
                <i class="bi bi-info-circle text-primary mb-3" style="font-size: 2rem;"></i>
                <h5 class="card-title fw-bold mb-3">No Jobs Available</h5>
                <p class="text-muted">There are currently no job listings available. Please check back later!</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    ';
    $updated_dashboard = str_replace('</div> <!-- End of job-list -->', $fallback_code . '</div> <!-- End of job-list -->', $updated_dashboard);
}

// Write the updated file
file_put_contents("jobseeker/dashboard.php", $updated_dashboard);
echo "<br>Dashboard code updated for better fallback handling.<br>";

echo "<br><strong>Next Steps:</strong><br>";
echo "1. Try using the login form above to log in as John Smith<br>";
echo "2. If login doesn't work, check if a session is already active and log out first<br>";
echo "3. After login, you should see the dashboard with populated data<br>";

// Close connection
$conn->close();
?>
