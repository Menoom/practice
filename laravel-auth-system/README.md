# Laravel Custom Authentication System

A comprehensive Laravel-based authentication system with role-based access control, OTP email verification, and task management features.

## Features

### Authentication & Security
- **Custom Authentication**: Email-based login/registration system
- **OTP Email Verification**: SMTP-based email verification with 6-digit OTP codes
- **Role-Based Access Control**: Three user roles (User, Manager, Admin) with different permissions
- **Secure Password Handling**: Bcrypt encryption with password confirmation

### User Roles & Permissions

#### 👤 User Role
- View assigned tasks
- Update task status (pending → in progress → completed)
- View and edit personal profile
- Read-only access to own dashboard

#### 👔 Manager Role
- All User permissions
- Create and assign tasks to users
- CRUD operations on tasks (except delete)
- CRUD operations on users (except delete)
- View team member profiles and task assignments

#### 👑 Admin Role
- All Manager permissions
- Full CRUD operations including delete
- System-wide user and task management
- Complete administrative control

### Dashboard Features
- **Role-specific Dashboards**: Customized interface based on user role
- **Task Management**: Create, assign, track, and complete tasks
- **User Management**: Add, edit, and manage system users
- **Statistics & Analytics**: Real-time stats on tasks, users, and system activity
- **Profile Management**: Update personal information and change passwords

### Technical Features
- **Bootstrap 3 Frontend**: Responsive, mobile-friendly interface
- **Database Relationships**: Proper foreign key relationships between users, roles, tasks, and employees
- **Email Templates**: Professional HTML email templates for OTP verification
- **Middleware Protection**: Route-level security with role-based middleware
- **Form Validation**: Comprehensive server-side validation
- **Error Handling**: User-friendly error messages and validation feedback

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- SQLite (default) or MySQL/PostgreSQL
- SMTP server access (for email functionality)

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd laravel-auth-system
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   - The system uses SQLite by default (no additional setup required)
   - For MySQL/PostgreSQL, update the `.env` file with your database credentials

5. **Configure Email (SMTP)**
   Update the following in your `.env` file:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@laravel-auth.com"
   MAIL_FROM_NAME="Laravel Auth System"
   ```

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Start the Development Server**
   ```bash
   php artisan serve
   ```

8. **Access the Application**
   Open your browser and navigate to `http://localhost:8000`

## Default Test Accounts

After running the seeders, you can use these test accounts:

- **Admin**: admin@example.com / password
- **Manager**: manager@example.com / password  
- **User**: user@example.com / password

## Database Schema

### Core Tables
- **users**: User accounts with authentication data
- **roles**: System roles (user, manager, admin)
- **user_roles**: Many-to-many relationship between users and roles
- **employees**: Employee information linked to users
- **tasks**: Task management with assignments and status tracking

### Key Relationships
- Users can have multiple roles
- Tasks are assigned to users by managers/admins
- Employees are linked to user accounts
- Role assignments track who assigned roles and when

## Usage Guide

### For New Users
1. **Registration**: Create account with email verification
2. **OTP Verification**: Check email for 6-digit verification code
3. **Role Selection**: Choose your role (User, Manager, or Admin)
4. **Dashboard Access**: Access role-specific dashboard and features

### For Managers
1. **Task Creation**: Create and assign tasks to team members
2. **User Management**: Add new users and manage existing ones
3. **Task Tracking**: Monitor task progress and completion
4. **Team Overview**: View team statistics and performance

### For Admins
1. **System Management**: Full control over all users and tasks
2. **Data Management**: Create, edit, and delete any system data
3. **User Administration**: Manage user roles and permissions
4. **System Monitoring**: View comprehensive system statistics

## Email Configuration

### Gmail Setup (Recommended)
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password for the application
3. Use the App Password in the `MAIL_PASSWORD` field
4. Set `MAIL_HOST=smtp.gmail.com` and `MAIL_PORT=587`

### Other SMTP Providers
- **Outlook**: smtp-mail.outlook.com:587
- **Yahoo**: smtp.mail.yahoo.com:587
- **Custom SMTP**: Contact your hosting provider for settings

## Security Features

- **Password Encryption**: All passwords are hashed using Laravel's Bcrypt
- **OTP Expiration**: Verification codes expire after 10 minutes
- **Role-based Access**: Routes protected by middleware based on user roles
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation on all user inputs
- **SQL Injection Prevention**: Eloquent ORM prevents SQL injection attacks

## File Structure

```
laravel-auth-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Authentication logic
│   │   ├── DashboardController.php # Role-specific dashboards
│   │   ├── TaskController.php      # Task management
│   │   └── UserController.php      # User management
│   ├── Mail/
│   │   └── OtpMail.php            # OTP email template
│   ├── Models/
│   │   ├── User.php               # User model with relationships
│   │   ├── Role.php               # Role model
│   │   ├── Task.php               # Task model
│   │   └── Employee.php           # Employee model
│   └── Http/Middleware/
│       └── RoleMiddleware.php     # Role-based access control
├── database/
│   ├── migrations/                # Database schema
│   └── seeders/                   # Default data
├── resources/views/
│   ├── auth/                      # Authentication pages
│   ├── dashboard/                 # Role-specific dashboards
│   ├── tasks/                     # Task management pages
│   ├── users/                     # User management pages
│   └── emails/                    # Email templates
└── routes/
    └── web.php                    # Application routes
```

## Troubleshooting

### Common Issues

1. **Email Not Sending**
   - Check SMTP credentials in `.env`
   - Verify firewall settings allow SMTP connections
   - Check Laravel logs: `storage/logs/laravel.log`

2. **Database Connection Error**
   - Ensure SQLite file exists: `database/database.sqlite`
   - For MySQL/PostgreSQL, verify database credentials

3. **Permission Denied Errors**
   - Set proper permissions: `chmod -R 755 storage bootstrap/cache`
   - Ensure web server can write to storage directories

4. **OTP Not Working**
   - Check email spam/junk folder
   - Verify OTP hasn't expired (10-minute limit)
   - Check application logs for email sending errors

### Development Tips

- Use `php artisan tinker` to test database relationships
- Monitor logs with `tail -f storage/logs/laravel.log`
- Use `php artisan route:list` to view all available routes
- Clear cache with `php artisan cache:clear` if needed

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:
- Check the troubleshooting section above
- Review Laravel documentation: https://laravel.com/docs
- Create an issue in the repository for bugs or feature requests

---

**Built with Laravel 11 and Bootstrap 3**