#!/bin/bash

echo "=========================================="
echo "Laravel Custom Authentication System"
echo "=========================================="
echo ""
echo "Starting Laravel development server..."
echo ""
echo "The application will be available at:"
echo "http://localhost:8000"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""
echo "=========================================="
echo ""

cd /workspace/laravel-auth-app
php artisan serve --host=0.0.0.0 --port=8000
