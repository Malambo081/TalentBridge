# TalentBridge - Job Matching Platform

TalentBridge is a minimal job matching platform built with HTML, CSS, JavaScript, PHP, and MySQL. The platform connects job seekers with employers through a clean, modern interface.

## Features

### Job Seeker Features
- Sign Up / Login with secure password encryption
- Profile Creation (name, email, skills, bio)
- Browse Job Listings
- Apply to Jobs with personalized messages
- Track application status

### Employer Features
- Sign Up / Login
- Post Jobs (title, description, required skills, deadline)
- View and manage applications
- Accept or reject applicants

### General Features
- Responsive UI using Bootstrap
- Clean Dashboard Layout
- Modern design with smooth colors and good spacing
- Mobile-friendly interface

## Installation

1. Make sure you have XAMPP (or similar PHP/MySQL environment) installed
2. Clone or download this repository to your `htdocs` folder
3. Start Apache and MySQL services in XAMPP
4. Open your browser and navigate to `http://localhost/Talent-Bridge/setup.php` to initialize the database
5. After setup is complete, you'll be redirected to the homepage

## Database Configuration

The default database configuration uses:
- Host: localhost
- Username: root
- Password: (empty)
- Database name: talentbridge

If you need to change these settings, edit the `includes/config.php` file.

## Usage

1. Register as either a Job Seeker or an Employer
2. Job Seekers: Complete your profile, browse jobs, and submit applications
3. Employers: Post jobs and review applications from candidates

## Technologies Used

- Frontend: HTML, CSS (Bootstrap 5), JavaScript
- Backend: PHP
- Database: MySQL
- Icons: Font Awesome

## File Structure

```
Talent-Bridge/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
│       └── hero-image.svg
├── includes/
│   ├── auth.php
│   ├── config.php
│   ├── db_schema.php
│   ├── footer.php
│   └── header.php
├── index.php
├── register.php
├── login.php
├── logout.php
├── jobs.php
├── job_details.php
├── profile.php
├── jobseeker_dashboard.php
├── employer_dashboard.php
├── post_job.php
├── view_applications.php
├── setup.php
└── README.md
```

## Security Features

- Password encryption using PHP's `password_hash()` function
- Input sanitization to prevent SQL injection
- Session handling for secure authentication
- Form validation on both client and server sides
