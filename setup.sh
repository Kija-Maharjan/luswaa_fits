#!/bin/bash

# LuswaaFits Setup Script
# This script automates the local development setup

set -e

echo "🚀 Setting up LuswaaFits..."

# Check if required tools are installed
command -v php >/dev/null 2>&1 || { echo "❌ PHP is required but not installed. Aborting." >&2; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "❌ Composer is required but not installed. Aborting." >&2; exit 1; }
command -v npm >/dev/null 2>&1 || { echo "❌ NPM is required but not installed. Aborting." >&2; exit 1; }
command -v psql >/dev/null 2>&1 || { echo "❌ PostgreSQL is required but not installed. Aborting." >&2; exit 1; }

echo "✅ All required tools are installed"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "⚠️  .env file already exists, skipping..."
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Get database configuration
echo ""
echo "📊 Database Configuration"
read -p "Enter database name (default: luswaafits): " DB_NAME
DB_NAME=${DB_NAME:-luswaafits}

read -p "Enter database username (default: postgres): " DB_USER
DB_USER=${DB_USER:-postgres}

read -sp "Enter database password: " DB_PASS
echo ""

# Update .env file with database credentials
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

# Create database
echo "🗄️  Creating database..."
PGPASSWORD=$DB_PASS createdb -U $DB_USER $DB_NAME 2>/dev/null || echo "⚠️  Database already exists"

# Run migrations
echo "🔄 Running database migrations..."
php artisan migrate

# Seed database (optional)
read -p "Do you want to seed the database with sample data? (y/n): " SEED_DB
if [ "$SEED_DB" = "y" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed
fi

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Build assets
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "✨ Setup complete!"
echo ""
echo "To start the development server, run:"
echo "  php artisan serve"
echo ""
echo "The application will be available at: http://localhost:8000"
echo ""
echo "For production deployment to Vercel, see VERCEL_DEPLOYMENT.md"
