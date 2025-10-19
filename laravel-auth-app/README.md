# Laravel Custom Authentication System with Role Management

A comprehensive Laravel application featuring custom authentication with OTP email verification and role-based access control (RBAC) for User, Manager, and Admin roles.

## Features

### Authentication System
- **Registration**: Users register with name, email, and password
- **OTP Email Verification**: 6-digit OTP sent to email for account verification
- **OTP Expiration**: OTPs expire after 10 minutes
- **Resend OTP**: Users can request a new OTP if needed
- **Login**: Secure login with email and password
- **Role Selection**: After first login, users select their role (User, Manager, or Admin)

### Role-Based Access Control

#### User Role
- View personal profile with name, email, and details
- View tasks assigned to them
- Mark tasks as complete/incomplete
- **Permissions**: Read-only access

#### Manager Role
- View personal profile
- Create new tasks and assign them to users
- Update existing tasks (title, description, assigned user)
- View all tasks created by them
- **Permissions**: Create, Read, Update (No Delete)

#### Admin Role
- Full user management (Create, Read, Update, Delete)
- Full task management (Create, Read, Update, Delete)
- View system statistics
- Assign roles to users
- **Permissions**: Full CRUD access

## Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Bootstrap 3.3.7
- **Database**: SQLite (can be switched to MySQL)
- **Email**: SMTP (configurable, currently set to log driver for development)

## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- SQLite3 (or MySQL for production)

### Setup Instructions

1. **Navigate to the project directory**
   ```bash
   cd /workspace/laravel-auth-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   The `.env` file is already configured. For production, update these settings:
   
   - For MySQL database:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=laravel_auth
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```
   
   - For SMTP email:
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.gmail.com
     MAIL_PORT=587
     MAIL_USERNAME=your-email@gmail.com
     MAIL_PASSWORD=your-app-password
     MAIL_ENCRYPTION=tls
     ```

4. **Run migrations**
   ```bash
   php artisan migrate:fresh
   ```

5. **Seed the roles**
   ```bash
   php artisan db:seed --class=RoleSeeder
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   Open your browser and navigate to: `http://localhost:8000`

## Database Schema

### Tables

#### users
- `id`: Primary key
- `name`: User's full name
- `email`: Unique email address
- `password`: Hashed password
- `otp`: 6-digit OTP for verification
- `otp_expires_at`: OTP expiration timestamp
- `is_verified`: Boolean verification status
- `email_verified_at`: Email verification timestamp
- `remember_token`: Remember me token
- `timestamps`: Created and updated timestamps

#### roles
- `id`: Primary key
- `name`: Role name (user, manager, admin)
- `description`: Role description
- `timestamps`: Created and updated timestamps

#### role_user (Pivot Table)
- `id`: Primary key
- `user_id`: Foreign key to users table
- `role_id`: Foreign key to roles table
- `timestamps`: Created and updated timestamps

#### tasks
- `id`: Primary key
- `title`: Task title
- `description`: Task description (nullable)
- `assigned_to`: Foreign key to users table (who is assigned)
- `assigned_by`: Foreign key to users table (who assigned it)
- `is_completed`: Boolean completion status
- `completed_at`: Completion timestamp (nullable)
- `timestamps`: Created and updated timestamps

## Application Flow

1. **Registration**
   - User fills registration form with name, email, password
   - System generates 6-digit OTP
   - OTP is sent to user's email
   - User is redirected to OTP verification page

2. **OTP Verification**
   - User enters the 6-digit OTP received via email
   - System validates OTP and expiration time
   - Upon successful verification, user can login

3. **Login**
   - User logs in with email and password
   - If no role is assigned, user is redirected to role selection page
   - If role is assigned, user is redirected to their dashboard

4. **Role Selection**
   - User selects from three roles: User, Manager, or Admin
   - Role is assigned to the user
   - User is redirected to appropriate dashboard

5. **Dashboard Access**
   - **User Dashboard**: View profile and tasks, mark tasks complete
   - **Manager Dashboard**: Create/update tasks, assign tasks to users
   - **Admin Dashboard**: Full CRUD on users and tasks

## Routes

### Authentication Routes
- `GET /register` - Show registration form
- `POST /register` - Handle registration
- `GET /login` - Show login form
- `POST /login` - Handle login
- `GET /verify-otp` - Show OTP verification form
- `POST /verify-otp` - Handle OTP verification
- `POST /resend-otp` - Resend OTP
- `POST /logout` - Logout user

### Role Selection
- `GET /select-role` - Show role selection page
- `POST /select-role` - Handle role selection

### Dashboard Routes
- `GET /dashboard/user` - User dashboard (role:user)
- `GET /dashboard/manager` - Manager dashboard (role:manager)
- `GET /dashboard/admin` - Admin dashboard (role:admin)

### Task Routes
- `POST /tasks` - Create task (manager, admin)
- `PUT /tasks/{task}` - Update task (manager, admin)
- `DELETE /tasks/{task}` - Delete task (admin only)
- `POST /tasks/{task}/toggle-complete` - Toggle task completion (user)

### User Routes
- `POST /users` - Create user (admin only)
- `PUT /users/{user}` - Update user (admin only)
- `DELETE /users/{user}` - Delete user (admin only)
- `POST /users/{user}/assign-role` - Assign role (manager, admin)

## Security Features

- Password hashing using bcrypt
- CSRF protection on all forms
- Middleware for authentication and role-based authorization
- OTP expiration for email verification
- Protected routes based on user roles
- SQL injection protection via Eloquent ORM
- XSS protection in views

## Email Configuration for Production

### Gmail SMTP Setup

1. Enable 2-factor authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate a new app password
3. Update `.env` file:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-16-digit-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   ```

### Other SMTP Providers
- **Mailgun**: Update MAIL_HOST to smtp.mailgun.org
- **SendGrid**: Update MAIL_HOST to smtp.sendgrid.net
- **Mailtrap** (for testing): Use Mailtrap credentials

## Development vs Production

### Development Mode (Current Setup)
- Uses SQLite database
- Email driver set to 'log' (emails saved to storage/logs/laravel.log)
- Debug mode enabled

### Production Setup
1. Switch to MySQL database
2. Configure SMTP for email sending
3. Set `APP_DEBUG=false` in `.env`
4. Set `APP_ENV=production` in `.env`
5. Run `php artisan config:cache`
6. Run `php artisan route:cache`
7. Set proper file permissions
8. Use a web server (Apache/Nginx) instead of `php artisan serve`

## Testing the Application

### Test User Registration
1. Go to `/register`
2. Fill in the form
3. Check `storage/logs/laravel.log` for the OTP (in development mode)
4. Verify OTP on `/verify-otp`
5. Login at `/login`
6. Select a role at `/select-role`

### Test Role Functionality

#### As User:
1. Login with a user account
2. View tasks assigned to you
3. Mark tasks as complete/incomplete

#### As Manager:
1. Login with a manager account
2. Create new tasks
3. Assign tasks to users
4. Edit existing tasks

#### As Admin:
1. Login with an admin account
2. Create new users with roles
3. Edit user information
4. Delete users
5. Delete tasks

## File Structure

```
laravel-auth-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── TaskController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       ├── User.php
│       ├── Role.php
│       └── Task.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_10_19_181918_create_roles_table.php
│   │   ├── 2025_10_19_181919_create_role_user_table.php
│   │   └── 2025_10_19_181919_create_tasks_table.php
│   └── seeders/
│       └── RoleSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── verify-otp.blade.php
│       │   └── select-role.blade.php
│       └── dashboards/
│           ├── user.blade.php
│           ├── manager.blade.php
│           └── admin.blade.php
└── routes/
    └── web.php
```

## Troubleshooting

### Database Connection Issues
- Ensure SQLite extension is installed: `sudo apt-get install php-sqlite3`
- Check database file exists: `database/database.sqlite`

### Email Not Sending
- In development, check `storage/logs/laravel.log` for logged emails
- For production, verify SMTP credentials in `.env`
- Test SMTP connection: `php artisan tinker` then `Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); })`

### Permission Denied Errors
- Run: `chmod -R 775 storage bootstrap/cache`
- Run: `chown -R www-data:www-data storage bootstrap/cache` (on production)

### OTP Not Working
- Check OTP hasn't expired (10-minute validity)
- Use "Resend OTP" button to generate a new code
- Verify email is being sent/logged correctly

## License

This project is open-source and available under the MIT License.

## Support

For issues, questions, or contributions, please contact the development team.
