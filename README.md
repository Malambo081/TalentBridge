# Talent Bridge

![Talent Bridge Logo](assets/img/logo.png)

Talent Bridge is a modern web application that connects talented professionals with employers, streamlining the job application and hiring process. Built with PHP, MySQL, HTML5, CSS3, and JavaScript, featuring a responsive design and intuitive user interface.

## Features

### For Job Seekers
- Create a professional profile and manage applications
- Upload and manage CV documents
- Search and filter job opportunities by various criteria
- Track application status (pending, shortlisted, rejected)
- Dashboard with application statistics and insights

### For Employers
- Post and manage job opportunities
- Review applicant profiles and CV documents
- Manage the hiring pipeline with status updates
- Analytics dashboard with application statistics
- Company profile management

### General Features
- Responsive design that works on mobile, tablet and desktop
- Modern UI with animations and transitions
- Secure authentication system
- Accessible interface with ARIA attributes
- Protected against common security vulnerabilities

## Technology Stack
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 8.x
- **Database**: MySQL
- **Libraries**: Chart.js (for analytics), Font Awesome (for icons)

## Getting Started

### Prerequisites
- Web server with PHP 8.x support (Apache, Nginx)
- MySQL 5.7+ database server
- Composer (optional, for future dependencies)

### Installation
1. Clone this repository to your web server directory:
   ```
   git clone https://github.com/your-username/talent-bridge.git
   ```
2. Import the provided `talent_bridge.sql` file into your MySQL server:
   ```
   mysql -u username -p < talent_bridge.sql
   ```
3. Configure your database credentials in `config/db.php`
4. Ensure the `uploads` directory has write permissions
5. Access the application through your web browser

## Database Schema

The application uses the following database tables:
- `talent_users` - User accounts (both job seekers and employers)
- `talent_opportunities` - Job listings posted by employers
- `talent_applications` - Job applications submitted by job seekers
- `talent_documents` - Uploaded CV files and other documents

## Folder Structure

```
talent-bridge/
├── assets/             # Frontend assets
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   ├── img/           # Images and icons
│   └── uploads/       # User uploads (CVs, etc.)
├── config/            # Configuration files
├── includes/          # Shared PHP components
├── employer/          # Employer-specific functionality
├── jobseeker/         # Job seeker-specific functionality
└── README.md          # This documentation
```

## Future Improvements

- Email notifications for application updates
- Advanced applicant matching algorithms
- Social media integration for profiles
- Mobile application
- Job recommendation engine
- Interview scheduling system
- Reporting and advanced analytics

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
