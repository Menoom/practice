#!/bin/bash

echo "🚀 Laravel Custom Authentication System - Installation Script"
echo "============================================================"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.1 or higher."
    exit 1
fi

echo "✅ Prerequisites check passed"

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Creating environment file..."
    cp .env.example .env
else
    echo "⚠️  .env file already exists, skipping..."
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Run seeders
echo "🌱 Seeding database with default data..."
php artisan db:seed --force

# Clear and cache config
echo "🧹 Clearing and caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔒 Setting proper permissions..."
chmod -R 755 storage bootstrap/cache

echo ""
echo "🎉 Installation completed successfully!"
echo ""
echo "📋 Next Steps:"
echo "1. Configure your SMTP settings in the .env file"
echo "2. Update MAIL_* variables with your email provider details"
echo "3. Run 'php artisan serve' to start the development server"
echo "4. Visit http://localhost:8000 to access the application"
echo ""
echo "🔐 Default Test Accounts:"
echo "   Admin:   admin@example.com / password"
echo "   Manager: manager@example.com / password"
echo "   User:    user@example.com / password"
echo ""
echo "📚 For detailed setup instructions, see README.md"
echo ""