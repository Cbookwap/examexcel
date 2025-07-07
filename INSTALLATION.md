# ExamExcel CBT Web App Installation Guide

## Installation Steps

### 1. Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Required PHP extensions: mysqli, json, mbstring, openssl

### 2. Installation Process

1. **Access the installer**: Navigate to `http://your-domain/public/setup.php`

2. **System Requirements Check**: The installer will verify:
   - PHP version compatibility
   - Required PHP extensions
   - Directory permissions
   - Database connectivity

3. **Site Configuration**: Configure:
   - Application name
   - Institution name
   - Base URL
   - Administrator account details

4. **Database Setup**: Provide:
   - Database host (usually localhost)
   - Database name
   - Database username and password
   - Database port (usually 3306)

5. **Automatic Installation**: The system will:
   - Create the database if it doesn't exist
   - Extract schema
   - Create all required tables
   - Set up the admin user
   - Configure initial settings

6. **Completion**:
   - Delete the setup.php file (recommended)
   - Access your CBT application

## Database Schema

The installation creates these essential tables:

### Core Tables
- `users` - User accounts and authentication
- `classes` - Academic classes/grades
- `subjects` - Academic subjects
- `academic_sessions` - Academic years
- `academic_terms` - Academic terms/semesters

### Exam Management
- `exam_types` - Types of examinations
- `exams` - Exam configurations
- `questions` - Question bank
- `question_options` - Multiple choice options
- `exam_questions` - Exam-question relationships

### Assessment & Results
- `exam_attempts` - Student exam sessions
- `student_answers` - Student responses
- `security_logs` - Security monitoring

### System Tables
- `settings` - Application configuration
- `migrations` - Database version tracking
- `active_sessions` - User session management

## Security Features

### During Installation
- Input validation and sanitization
- Database connection security
- Password hashing
- SQL injection prevention

### Post Installation
- Automatic setup file deletion option
- Session management
- User role-based access control
- Security logging

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Verify database credentials
   - Ensure MySQL service is running
   - Check database user permissions

2. **Permission Errors**
   - Ensure web server has write access to required directories
   - Check file ownership and permissions

3. **PHP Extension Missing**
   - Install required PHP extensions
   - Restart web server after installation

4. **Schema Extraction Failed**
   - Verify `exam_cbt.sql` file exists in root directory
   - Check file permissions
   - System will fall back to built-in schema

### Manual Database Setup

If automatic installation fails, you can manually:

1. Create the database
2. Import `exam_cbt.sql` directly into MySQL
3. Run the installer again (it will detect existing tables)

## Post-Installation Setup

After successful installation:

1. **Login as Admin**
   - Use the email and password set during installation
   - Change the default password

2. **Configure System Settings**
   - Set up institution details
   - Configure exam settings
   - Set up academic sessions and terms

3. **Create Academic Structure**
   - Add classes/grades
   - Add subjects
   - Create teacher accounts
   - Import or create student accounts

4. **Security Hardening**
   - Delete setup.php file
   - Configure proper file permissions
   - Set up regular backups
   - Configure SSL/HTTPS

## File Structure

```
exam/
├── public/
│   ├── setup.php          # Installation script
│   └── index.php          # Main application
├── exam_cbt.sql           # Complete database schema
├── INSTALLATION.md        # This file
└── [other application files]
```

## Support

For installation issues:
1. Check the troubleshooting section above
2. Verify system requirements
3. Check server error logs
4. Ensure all prerequisites are met

The installation system is designed to be robust and handle most common scenarios automatically.
