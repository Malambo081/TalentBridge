<?php
// This script will insert sample data into the job_application_portal database

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

// Clear existing data (optional)
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("TRUNCATE TABLE applications");
$conn->query("TRUNCATE TABLE jobs");
$conn->query("TRUNCATE TABLE files");
$conn->query("TRUNCATE TABLE users");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

echo "Tables cleared successfully<br>";

// Password will be 'password123' for all users
$password = password_hash('password123', PASSWORD_DEFAULT);

// Insert sample employers
$employers = [
    ['TechInnovate Solutions', 'tech@example.com', 'employer'],
    ['Global Enterprises', 'global@example.com', 'employer'],
    ['Creative Design Co', 'creative@example.com', 'employer'],
    ['Financial Solutions Inc', 'finance@example.com', 'employer']
];

echo "Inserting employers...<br>";
foreach ($employers as $employer) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $employer[0], $employer[1], $password, $employer[2]);
    $stmt->execute();
    $stmt->close();
}

// Insert sample job seekers
$jobseekers = [
    ['John Smith', 'john@example.com', 'jobseeker'],
    ['Emily Johnson', 'emily@example.com', 'jobseeker'],
    ['Michael Brown', 'michael@example.com', 'jobseeker'],
    ['Sarah Wilson', 'sarah@example.com', 'jobseeker']
];

echo "Inserting job seekers...<br>";
foreach ($jobseekers as $seeker) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $seeker[0], $seeker[1], $password, $seeker[2]);
    $stmt->execute();
    $stmt->close();
}

// Get employer IDs
$employersResult = $conn->query("SELECT id, username FROM talent_users WHERE role = 'employer'");
$employerIds = [];
while ($row = $employersResult->fetch_assoc()) {
    $employerIds[$row['username']] = $row['id'];
}

// Insert sample jobs
$jobs = [
    [
        'employer' => 'TechInnovate Solutions',
        'title' => 'Senior Web Developer',
        'description' => 'We are looking for an experienced web developer proficient in HTML, CSS, JavaScript, and React. The ideal candidate will have 5+ years of experience building responsive web applications.',
        'location' => 'New York, NY'
    ],
    [
        'employer' => 'TechInnovate Solutions',
        'title' => 'UX/UI Designer',
        'description' => 'Join our design team to create intuitive and beautiful user experiences. You should have a strong portfolio demonstrating your design skills and experience with Figma or Adobe XD.',
        'location' => 'Remote'
    ],
    [
        'employer' => 'Global Enterprises',
        'title' => 'Project Manager',
        'description' => 'Lead cross-functional teams to deliver complex projects on time and within budget. PMP certification and 3+ years of experience required.',
        'location' => 'Chicago, IL'
    ],
    [
        'employer' => 'Creative Design Co',
        'title' => 'Graphic Designer',
        'description' => 'Create visual concepts for marketing materials, social media, and website content. Experience with Adobe Creative Suite is required.',
        'location' => 'Los Angeles, CA'
    ],
    [
        'employer' => 'Financial Solutions Inc',
        'title' => 'Financial Analyst',
        'description' => 'Analyze financial data and prepare reports to guide business decisions. CFA certification preferred.',
        'location' => 'Boston, MA'
    ],
    [
        'employer' => 'Global Enterprises',
        'title' => 'Marketing Specialist',
        'description' => 'Develop and implement marketing strategies to increase brand awareness and drive customer engagement.',
        'location' => 'San Francisco, CA'
    ]
];

echo "Inserting jobs...<br>";
foreach ($jobs as $job) {
    $employerId = $employerIds[$job['employer']];
    $stmt = $conn->prepare("INSERT INTO jobs (employer_id, title, description, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $employerId, $job['title'], $job['description'], $job['location']);
    $stmt->execute();
    $stmt->close();
}

// Get job seeker IDs
$seekersResult = $conn->query("SELECT id FROM talent_users WHERE role = 'jobseeker'");
$seekerIds = [];
while ($row = $seekersResult->fetch_assoc()) {
    $seekerIds[] = $row['id'];
}

// Get job IDs
$jobsResult = $conn->query("SELECT id FROM talent_opportunities");
$jobIds = [];
while ($row = $jobsResult->fetch_assoc()) {
    $jobIds[] = $row['id'];
}

// Insert sample applications
echo "Inserting applications...<br>";
$statuses = ['pending', 'shortlisted', 'rejected'];

// Application for John (User ID 5)
$stmt = $conn->prepare("INSERT INTO applications (job_id, seeker_id, status) VALUES (?, ?, ?)");
$seekerId = $seekerIds[0];
$jobId = $jobIds[0];
$status = 'pending';
$stmt->bind_param("iis", $jobId, $seekerId, $status);
$stmt->execute();

$jobId = $jobIds[1];
$status = 'shortlisted';
$stmt->bind_param("iis", $jobId, $seekerId, $status);
$stmt->execute();

// Application for Emily (User ID 6)
$seekerId = $seekerIds[1];
$jobId = $jobIds[2];
$status = 'pending';
$stmt->bind_param("iis", $jobId, $seekerId, $status);
$stmt->execute();

$jobId = $jobIds[3];
$status = 'rejected';
$stmt->bind_param("iis", $jobId, $seekerId, $status);
$stmt->execute();

// Application for Michael (User ID 7)
$seekerId = $seekerIds[2];
$jobId = $jobIds[4];
$status = 'shortlisted';
$stmt->bind_param("iis", $jobId, $seekerId, $status);
$stmt->execute();

$stmt->close();

// Confirmation
echo "<br>Sample data has been successfully inserted!<br>";
echo "<br>Sample employer accounts:<br>";
foreach ($employers as $employer) {
    echo "- Username: {$employer[0]}, Email: {$employer[1]}, Password: password123<br>";
}

echo "<br>Sample job seeker accounts:<br>";
foreach ($jobseekers as $seeker) {
    echo "- Username: {$seeker[0]}, Email: {$seeker[1]}, Password: password123<br>";
}

echo "<br><a href='index.php' class='btn btn-primary'>Go to Homepage</a>";

// Close connection
$conn->close();
?>
