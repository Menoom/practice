# Quick Start Guide

## Starting the Application

### Option 1: Using the startup script
```bash
./START_SERVER.sh
```

### Option 2: Manual start
```bash
cd /workspace/laravel-auth-app
php artisan serve --host=0.0.0.0 --port=8000
```

## Accessing the Application

Open your browser and navigate to: **http://localhost:8000**

## First Time Setup

If you haven't run migrations yet:
```bash
cd /workspace/laravel-auth-app
php artisan migrate:fresh
php artisan db:seed --class=RoleSeeder
```

## Testing the Application

### 1. Register a New Account
1. Go to http://localhost:8000/register
2. Fill in:
   - Name: John Doe
   - Email: john@example.com
   - Password: password123
   - Confirm Password: password123
3. Click "Register"

### 2. Verify Email with OTP
Since we're in development mode, emails are logged to `storage/logs/laravel.log`

**Quick way to get OTP:**
```bash
tail -20 /workspace/laravel-auth-app/storage/logs/laravel.log | grep "Your OTP"
```

Or check the database directly:
```bash
cd /workspace/laravel-auth-app
php artisan tinker
>>> \App\Models\User::where('email', 'john@example.com')->first()->otp
```

Enter the 6-digit OTP on the verification page.

### 3. Login
1. Go to http://localhost:8000/login
2. Enter your email and password
3. Click "Login"

### 4. Select a Role
Choose one of the three roles:
- **User**: Can view tasks and mark them complete
- **Manager**: Can create and assign tasks
- **Admin**: Full control over users and tasks

### 5. Explore the Dashboard
Based on your selected role, you'll see different features:

#### User Dashboard
- View your profile
- See tasks assigned to you
- Mark tasks as complete/incomplete

#### Manager Dashboard
- Create new tasks
- Assign tasks to users
- Edit existing tasks
- View all your created tasks

#### Admin Dashboard
- Create/Edit/Delete users
- Assign roles to users
- Delete tasks
- View system statistics

## Creating Test Users

### Via Tinker (Recommended)
```bash
cd /workspace/laravel-auth-app
php artisan tinker
```

Then run:
```php
// Create a User
$user = \App\Models\User::create([
    'name' => 'Test User',
    'email' => 'user@test.com',
    'password' => bcrypt('password'),
    'is_verified' => true
]);
$user->roles()->attach(\App\Models\Role::where('name', 'user')->first()->id);

// Create a Manager
$manager = \App\Models\User::create([
    'name' => 'Test Manager',
    'email' => 'manager@test.com',
    'password' => bcrypt('password'),
    'is_verified' => true
]);
$manager->roles()->attach(\App\Models\Role::where('name', 'manager')->first()->id);

// Create an Admin
$admin = \App\Models\User::create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'is_verified' => true
]);
$admin->roles()->attach(\App\Models\Role::where('name', 'admin')->first()->id);
```

### Test Credentials
After creating test users above:
- **User**: user@test.com / password
- **Manager**: manager@test.com / password
- **Admin**: admin@test.com / password

## Testing Manager Features

1. Login as manager@test.com
2. Go to Manager Dashboard
3. Create a task:
   - Title: "Complete project documentation"
   - Description: "Write comprehensive docs"
   - Assign To: Select a user
4. Click "Create Task"

## Testing User Features

1. Login as user@test.com
2. Go to User Dashboard
3. You should see tasks assigned to you
4. Click "Mark Complete" to complete a task

## Testing Admin Features

1. Login as admin@test.com
2. Go to Admin Dashboard
3. Create a new user with role
4. Edit user information
5. Delete a task or user

## Common Commands

### Clear all data and restart
```bash
cd /workspace/laravel-auth-app
php artisan migrate:fresh
php artisan db:seed --class=RoleSeeder
```

### View logs
```bash
tail -f /workspace/laravel-auth-app/storage/logs/laravel.log
```

### Check routes
```bash
cd /workspace/laravel-auth-app
php artisan route:list
```

### Clear cache
```bash
cd /workspace/laravel-auth-app
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Troubleshooting

### Can't login after registration?
Make sure you verified your email with OTP. Check the logs for the OTP code.

### No tasks showing?
- For Users: Manager/Admin needs to create and assign tasks first
- For Managers: You need to create tasks first

### Forgot to seed roles?
```bash
cd /workspace/laravel-auth-app
php artisan db:seed --class=RoleSeeder
```

### Database errors?
```bash
cd /workspace/laravel-auth-app
php artisan migrate:fresh
php artisan db:seed --class=RoleSeeder
```

## Features Summary

| Feature | User | Manager | Admin |
|---------|------|---------|-------|
| View own profile | ✅ | ✅ | ✅ |
| View assigned tasks | ✅ | ✅ | ✅ |
| Mark tasks complete | ✅ | ❌ | ❌ |
| Create tasks | ❌ | ✅ | ✅ |
| Update tasks | ❌ | ✅ | ✅ |
| Delete tasks | ❌ | ❌ | ✅ |
| Create users | ❌ | ❌ | ✅ |
| Update users | ❌ | ❌ | ✅ |
| Delete users | ❌ | ❌ | ✅ |
| Assign roles | ❌ | ✅ (user only) | ✅ (all roles) |

## Need Help?

Check the full README.md for detailed documentation.
