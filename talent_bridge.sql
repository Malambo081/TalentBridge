-- SQL schema for Talent Bridge
CREATE DATABASE IF NOT EXISTS talent_bridge;
USE talent_bridge;

-- Users table (renamed to talent_users)
CREATE TABLE IF NOT EXISTS talent_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('jobseeker', 'employer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Jobs table (renamed to talent_opportunities)
CREATE TABLE IF NOT EXISTS talent_opportunities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(100),
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES talent_users(id) ON DELETE CASCADE
);

-- Applications table (renamed to talent_applications)
CREATE TABLE IF NOT EXISTS talent_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    cv_file VARCHAR(255),
    status ENUM('pending', 'shortlisted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES talent_opportunities(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES talent_users(id) ON DELETE CASCADE
);

-- Files table for CV uploads (renamed to talent_documents)
CREATE TABLE IF NOT EXISTS talent_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES talent_users(id) ON DELETE CASCADE
);
