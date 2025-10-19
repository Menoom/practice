#!/bin/bash

echo "=========================================="
echo "Laravel Auth System - Setup Verification"
echo "=========================================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Not in Laravel directory"
    echo "   Please run this script from /workspace/laravel-auth-app"
    exit 1
fi

echo "✅ In correct directory"
echo ""

# Check PHP version
echo "Checking PHP version..."
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "   PHP Version: $PHP_VERSION"
if [ $(echo "$PHP_VERSION >= 8.2" | bc -l) -eq 1 ]; then
    echo "   ✅ PHP version is sufficient (>= 8.2)"
else
    echo "   ⚠️  PHP version might be too old"
fi
echo ""

# Check if database exists
echo "Checking database..."
if [ -f "database/database.sqlite" ]; then
    echo "   ✅ Database file exists"
    
    # Check if migrations ran
    TABLE_COUNT=$(echo "SELECT COUNT(*) FROM sqlite_master WHERE type='table';" | sqlite3 database/database.sqlite)
    echo "   📊 Tables in database: $TABLE_COUNT"
    
    if [ $TABLE_COUNT -gt 5 ]; then
        echo "   ✅ Migrations have been run"
    else
        echo "   ⚠️  Migrations might not have been run"
        echo "   Run: php artisan migrate:fresh"
    fi
    
    # Check if roles are seeded
    ROLE_COUNT=$(echo "SELECT COUNT(*) FROM roles;" | sqlite3 database/database.sqlite)
    echo "   👥 Roles in database: $ROLE_COUNT"
    
    if [ $ROLE_COUNT -eq 3 ]; then
        echo "   ✅ Roles have been seeded correctly"
        echo ""
        echo "   Available roles:"
        echo "SELECT '   - ' || name || ': ' || description FROM roles;" | sqlite3 database/database.sqlite
    else
        echo "   ⚠️  Roles might not have been seeded"
        echo "   Run: php artisan db:seed --class=RoleSeeder"
    fi
else
    echo "   ❌ Database file not found"
    echo "   Run: php artisan migrate:fresh"
fi
echo ""

# Check if .env exists
echo "Checking environment configuration..."
if [ -f ".env" ]; then
    echo "   ✅ .env file exists"
    
    # Check database configuration
    DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d "=" -f 2)
    echo "   📂 Database: $DB_CONNECTION"
    
    # Check mail configuration
    MAIL_MAILER=$(grep "^MAIL_MAILER=" .env | cut -d "=" -f 2)
    echo "   📧 Mail driver: $MAIL_MAILER"
    
    if [ "$MAIL_MAILER" == "log" ]; then
        echo "   ℹ️  Emails will be logged to storage/logs/laravel.log"
    fi
else
    echo "   ❌ .env file not found"
    echo "   Run: cp .env.example .env && php artisan key:generate"
fi
echo ""

# Check required directories
echo "Checking required directories..."
REQUIRED_DIRS=("storage/logs" "storage/framework" "bootstrap/cache")
ALL_DIRS_OK=true

for dir in "${REQUIRED_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        if [ -w "$dir" ]; then
            echo "   ✅ $dir (writable)"
        else
            echo "   ⚠️  $dir (not writable)"
            ALL_DIRS_OK=false
        fi
    else
        echo "   ❌ $dir (missing)"
        ALL_DIRS_OK=false
    fi
done

if [ "$ALL_DIRS_OK" = false ]; then
    echo ""
    echo "   To fix permissions, run:"
    echo "   chmod -R 775 storage bootstrap/cache"
fi
echo ""

# Check routes
echo "Checking routes..."
ROUTE_COUNT=$(php artisan route:list 2>/dev/null | grep -c "│")
if [ $ROUTE_COUNT -gt 20 ]; then
    echo "   ✅ Routes are registered ($ROUTE_COUNT routes)"
else
    echo "   ⚠️  Routes might not be loaded properly"
fi
echo ""

# Check controllers
echo "Checking controllers..."
CONTROLLERS=("AuthController" "DashboardController" "TaskController" "UserController")
ALL_CONTROLLERS_OK=true

for controller in "${CONTROLLERS[@]}"; do
    if [ -f "app/Http/Controllers/$controller.php" ]; then
        echo "   ✅ $controller.php"
    else
        echo "   ❌ $controller.php (missing)"
        ALL_CONTROLLERS_OK=false
    fi
done
echo ""

# Check models
echo "Checking models..."
MODELS=("User" "Role" "Task")
ALL_MODELS_OK=true

for model in "${MODELS[@]}"; do
    if [ -f "app/Models/$model.php" ]; then
        echo "   ✅ $model.php"
    else
        echo "   ❌ $model.php (missing)"
        ALL_MODELS_OK=false
    fi
done
echo ""

# Check views
echo "Checking views..."
VIEWS=(
    "layouts/app.blade.php"
    "auth/register.blade.php"
    "auth/login.blade.php"
    "auth/verify-otp.blade.php"
    "auth/select-role.blade.php"
    "dashboards/user.blade.php"
    "dashboards/manager.blade.php"
    "dashboards/admin.blade.php"
)
ALL_VIEWS_OK=true

for view in "${VIEWS[@]}"; do
    if [ -f "resources/views/$view" ]; then
        echo "   ✅ $view"
    else
        echo "   ❌ $view (missing)"
        ALL_VIEWS_OK=false
    fi
done
echo ""

# Final summary
echo "=========================================="
echo "Summary"
echo "=========================================="
echo ""

if [ $ROLE_COUNT -eq 3 ] && [ "$ALL_DIRS_OK" = true ] && [ "$ALL_CONTROLLERS_OK" = true ] && [ "$ALL_MODELS_OK" = true ] && [ "$ALL_VIEWS_OK" = true ]; then
    echo "✅ All checks passed! System is ready to use."
    echo ""
    echo "To start the server:"
    echo "  ./START_SERVER.sh"
    echo ""
    echo "Or manually:"
    echo "  php artisan serve --host=0.0.0.0 --port=8000"
    echo ""
    echo "Then open: http://localhost:8000"
else
    echo "⚠️  Some checks failed. Please review the issues above."
    echo ""
    echo "Common fixes:"
    echo "  php artisan migrate:fresh"
    echo "  php artisan db:seed --class=RoleSeeder"
    echo "  chmod -R 775 storage bootstrap/cache"
fi
echo ""
echo "=========================================="
