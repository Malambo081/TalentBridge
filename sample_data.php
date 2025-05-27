<?php
// Include database configuration
require_once 'includes/config.php';

echo "<h2>TalentBridge Sample Data Generator</h2>";

// Temporarily disable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// Clear existing data in reverse order of dependencies
$tables = ['applications', 'jobs', 'profiles', 'users'];
foreach ($tables as $table) {
    mysqli_query($conn, "TRUNCATE TABLE $table");
    echo "Cleared existing data from $table table.<br>";
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// Sample employer companies
$employers = [
    [
        'name' => 'Tech Innovations Inc.',
        'email' => 'hr@techinnovations.com',
        'password' => password_hash('employer123', PASSWORD_DEFAULT)
    ],
    [
        'name' => 'Global Solutions Ltd.',
        'email' => 'careers@globalsolutions.com',
        'password' => password_hash('employer123', PASSWORD_DEFAULT)
    ],
    [
        'name' => 'Creative Designs Studio',
        'email' => 'jobs@creativedesigns.com',
        'password' => password_hash('employer123', PASSWORD_DEFAULT)
    ],
    [
        'name' => 'Data Analytics Pro',
        'email' => 'hiring@dataanalyticspro.com',
        'password' => password_hash('employer123', PASSWORD_DEFAULT)
    ],
    [
        'name' => 'Cloud Systems Inc.',
        'email' => 'recruitment@cloudsystems.com',
        'password' => password_hash('employer123', PASSWORD_DEFAULT)
    ]
];

// Sample job seekers
$jobseekers = [
    [
        'name' => 'John Smith',
        'email' => 'john.smith@example.com',
        'password' => password_hash('jobseeker123', PASSWORD_DEFAULT),
        'bio' => 'Experienced software developer with 5+ years in web development. Passionate about creating efficient and user-friendly applications.',
        'skills' => json_encode(['JavaScript', 'React', 'Node.js', 'PHP', 'MySQL'])
    ],
    [
        'name' => 'Emily Johnson',
        'email' => 'emily.johnson@example.com',
        'password' => password_hash('jobseeker123', PASSWORD_DEFAULT),
        'bio' => 'UX/UI designer with a strong portfolio of mobile and web applications. Focused on creating intuitive and accessible user experiences.',
        'skills' => json_encode(['UI Design', 'Figma', 'Adobe XD', 'Sketch', 'Prototyping'])
    ],
    [
        'name' => 'Michael Wong',
        'email' => 'michael.wong@example.com',
        'password' => password_hash('jobseeker123', PASSWORD_DEFAULT),
        'bio' => 'Data scientist with expertise in machine learning and predictive analytics. Experience in finance and healthcare industries.',
        'skills' => json_encode(['Python', 'R', 'Machine Learning', 'SQL', 'Data Visualization'])
    ],
    [
        'name' => 'Sarah Garcia',
        'email' => 'sarah.garcia@example.com',
        'password' => password_hash('jobseeker123', PASSWORD_DEFAULT),
        'bio' => 'Marketing specialist with focus on digital campaigns and social media strategy. Proven track record of increasing engagement and conversion.',
        'skills' => json_encode(['Digital Marketing', 'Social Media', 'Content Creation', 'SEO', 'Analytics'])
    ],
    [
        'name' => 'David Kim',
        'email' => 'david.kim@example.com',
        'password' => password_hash('jobseeker123', PASSWORD_DEFAULT),
        'bio' => 'Full-stack developer specialized in building scalable web applications. Experience with modern JavaScript frameworks and cloud infrastructure.',
        'skills' => json_encode(['JavaScript', 'Vue.js', 'AWS', 'Docker', 'MongoDB'])
    ]
];

// Sample jobs
$jobs = [
    [
        'employer_index' => 0,
        'title' => 'Senior Frontend Developer',
        'description' => "We're looking for a Senior Frontend Developer to join our growing team. You'll be responsible for building responsive web applications, collaborating with designers and backend developers, and mentoring junior developers.\n\nRequirements:\n- 5+ years of experience with JavaScript and modern frameworks\n- Strong understanding of web performance optimization\n- Experience with responsive design and cross-browser compatibility\n- Excellent problem-solving skills and attention to detail",
        'required_skills' => json_encode(['JavaScript', 'React', 'HTML5', 'CSS3', 'Webpack']),
        'deadline' => date('Y-m-d', strtotime('+30 days'))
    ],
    [
        'employer_index' => 1,
        'title' => 'Data Analyst',
        'description' => "Global Solutions Ltd. is seeking a Data Analyst to help interpret data and turn it into actionable insights. You will work with stakeholders to identify requirements and deliver reports and visualizations.\n\nResponsibilities:\n- Collect, process, and analyze large datasets\n- Create reports and dashboards to track KPIs\n- Identify trends and opportunities for optimization\n- Collaborate with different departments to understand data needs",
        'required_skills' => json_encode(['SQL', 'Excel', 'Data Visualization', 'Statistical Analysis', 'Power BI']),
        'deadline' => date('Y-m-d', strtotime('+45 days'))
    ],
    [
        'employer_index' => 2,
        'title' => 'UX/UI Designer',
        'description' => "Creative Designs Studio is hiring a talented UX/UI Designer to create beautiful, intuitive interfaces for our clients. You'll work on a variety of projects from concept to implementation.\n\nWhat we're looking for:\n- Strong portfolio demonstrating UI design skills\n- Experience with user research and usability testing\n- Proficiency in design tools like Figma or Adobe XD\n- Understanding of accessibility standards and best practices",
        'required_skills' => json_encode(['UI Design', 'Figma', 'User Research', 'Wireframing', 'Prototyping']),
        'deadline' => date('Y-m-d', strtotime('+60 days'))
    ],
    [
        'employer_index' => 3,
        'title' => 'Machine Learning Engineer',
        'description' => "Data Analytics Pro is seeking a Machine Learning Engineer to develop and implement ML models. You will work on challenging problems and help our clients leverage their data for better decision-making.\n\nRequirements:\n- Strong background in machine learning algorithms\n- Experience with Python and relevant ML libraries\n- Knowledge of data structures and algorithms\n- Ability to work with large datasets and optimize model performance",
        'required_skills' => json_encode(['Python', 'Machine Learning', 'TensorFlow', 'Data Processing', 'Algorithm Design']),
        'deadline' => date('Y-m-d', strtotime('+15 days'))
    ],
    [
        'employer_index' => 4,
        'title' => 'DevOps Engineer',
        'description' => "Cloud Systems Inc. is looking for a DevOps Engineer to help us build and maintain our cloud infrastructure. You'll work on automating deployment processes and ensuring system reliability.\n\nResponsibilities:\n- Implement and manage CI/CD pipelines\n- Configure and maintain cloud infrastructure\n- Monitor system performance and troubleshoot issues\n- Collaborate with development teams to improve deployment processes",
        'required_skills' => json_encode(['AWS', 'Docker', 'Kubernetes', 'CI/CD', 'Linux']),
        'deadline' => date('Y-m-d', strtotime('+21 days'))
    ],
    [
        'employer_index' => 0,
        'title' => 'Mobile App Developer',
        'description' => "Tech Innovations Inc. is seeking a Mobile App Developer to join our product team. You'll be responsible for developing and maintaining mobile applications for iOS and Android platforms.\n\nRequirements:\n- Experience with React Native or Flutter\n- Understanding of mobile app architecture\n- Knowledge of RESTful APIs and data persistence\n- Familiarity with app store submission processes",
        'required_skills' => json_encode(['React Native', 'JavaScript', 'Mobile Development', 'REST APIs', 'Git']),
        'deadline' => date('Y-m-d', strtotime('+40 days'))
    ],
    [
        'employer_index' => 1,
        'title' => 'Project Manager',
        'description' => "Global Solutions Ltd. is looking for an experienced Project Manager to lead our client projects. You'll be responsible for planning, executing, and closing projects while ensuring they're delivered on time and within budget.\n\nResponsibilities:\n- Develop project plans and timelines\n- Coordinate team members and resources\n- Communicate with stakeholders and manage expectations\n- Identify and mitigate risks throughout the project lifecycle",
        'required_skills' => json_encode(['Project Management', 'Agile', 'Budgeting', 'Risk Management', 'Stakeholder Communication']),
        'deadline' => date('Y-m-d', strtotime('+25 days'))
    ],
    [
        'employer_index' => 2,
        'title' => 'Content Writer',
        'description' => "Creative Designs Studio needs a talented Content Writer to create engaging copy for our clients. You'll work on various projects including websites, social media, and marketing materials.\n\nWhat we're looking for:\n- Excellent writing and editing skills\n- Experience creating content for different platforms\n- Understanding of SEO principles\n- Ability to adapt tone and style for different audiences",
        'required_skills' => json_encode(['Content Writing', 'SEO', 'Copywriting', 'Editing', 'Research']),
        'deadline' => date('Y-m-d', strtotime('+35 days'))
    ]
];

// Insert employers
$employer_ids = [];
foreach ($employers as $employer) {
    $query = "INSERT INTO users (name, email, password, user_type) VALUES (
        '" . mysqli_real_escape_string($conn, $employer['name']) . "',
        '" . mysqli_real_escape_string($conn, $employer['email']) . "',
        '" . mysqli_real_escape_string($conn, $employer['password']) . "',
        'employer'
    )";
    
    if (mysqli_query($conn, $query)) {
        $employer_ids[] = mysqli_insert_id($conn);
        echo "Added employer: {$employer['name']}<br>";
    } else {
        echo "Error adding employer: " . mysqli_error($conn) . "<br>";
    }
}

// Insert job seekers
$jobseeker_ids = [];
foreach ($jobseekers as $jobseeker) {
    $query = "INSERT INTO users (name, email, password, user_type) VALUES (
        '" . mysqli_real_escape_string($conn, $jobseeker['name']) . "',
        '" . mysqli_real_escape_string($conn, $jobseeker['email']) . "',
        '" . mysqli_real_escape_string($conn, $jobseeker['password']) . "',
        'jobseeker'
    )";
    
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);
        $jobseeker_ids[] = $user_id;
        
        // Create profile
        $profile_query = "INSERT INTO profiles (user_id, bio, skills) VALUES (
            $user_id,
            '" . mysqli_real_escape_string($conn, $jobseeker['bio']) . "',
            '" . mysqli_real_escape_string($conn, $jobseeker['skills']) . "'
        )";
        
        if (mysqli_query($conn, $profile_query)) {
            echo "Added job seeker: {$jobseeker['name']} with profile<br>";
        } else {
            echo "Error adding profile: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error adding job seeker: " . mysqli_error($conn) . "<br>";
    }
}

// Insert jobs
$job_ids = [];
foreach ($jobs as $job) {
    $employer_id = $employer_ids[$job['employer_index']];
    
    $query = "INSERT INTO jobs (employer_id, title, description, required_skills, deadline) VALUES (
        $employer_id,
        '" . mysqli_real_escape_string($conn, $job['title']) . "',
        '" . mysqli_real_escape_string($conn, $job['description']) . "',
        '" . mysqli_real_escape_string($conn, $job['required_skills']) . "',
        '" . mysqli_real_escape_string($conn, $job['deadline']) . "'
    )";
    
    if (mysqli_query($conn, $query)) {
        $job_ids[] = mysqli_insert_id($conn);
        echo "Added job: {$job['title']}<br>";
    } else {
        echo "Error adding job: " . mysqli_error($conn) . "<br>";
    }
}

// Create some applications
$applications = [
    ['jobseeker_index' => 0, 'job_index' => 0, 'status' => 'pending'],
    ['jobseeker_index' => 0, 'job_index' => 4, 'status' => 'reviewed'],
    ['jobseeker_index' => 1, 'job_index' => 2, 'status' => 'accepted'],
    ['jobseeker_index' => 2, 'job_index' => 1, 'status' => 'pending'],
    ['jobseeker_index' => 2, 'job_index' => 3, 'status' => 'rejected'],
    ['jobseeker_index' => 3, 'job_index' => 7, 'status' => 'pending'],
    ['jobseeker_index' => 4, 'job_index' => 0, 'status' => 'reviewed'],
    ['jobseeker_index' => 4, 'job_index' => 4, 'status' => 'accepted'],
    ['jobseeker_index' => 4, 'job_index' => 5, 'status' => 'pending']
];

foreach ($applications as $application) {
    $jobseeker_id = $jobseeker_ids[$application['jobseeker_index']];
    $job_id = $job_ids[$application['job_index']];
    $status = $application['status'];
    $message = "I am very interested in this position and believe my skills and experience make me a great fit. I have attached my resume and would appreciate the opportunity to discuss how I can contribute to your team.";
    
    $query = "INSERT INTO applications (job_id, jobseeker_id, message, status) VALUES (
        $job_id,
        $jobseeker_id,
        '" . mysqli_real_escape_string($conn, $message) . "',
        '$status'
    )";
    
    if (mysqli_query($conn, $query)) {
        echo "Added application from jobseeker ID $jobseeker_id for job ID $job_id<br>";
    } else {
        echo "Error adding application: " . mysqli_error($conn) . "<br>";
    }
}

echo "<h3>Sample data generation completed!</h3>";
echo "<p>You can now log in with the following credentials:</p>";
echo "<h4>Employers:</h4>";
echo "<ul>";
foreach ($employers as $employer) {
    echo "<li>{$employer['email']} / employer123</li>";
}
echo "</ul>";
echo "<h4>Job Seekers:</h4>";
echo "<ul>";
foreach ($jobseekers as $jobseeker) {
    echo "<li>{$jobseeker['email']} / jobseeker123</li>";
}
echo "</ul>";
echo "<p><a href='index.php'>Go to homepage</a></p>";
?>
