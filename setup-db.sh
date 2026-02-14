#!/bin/bash
set -e

echo "🗑️  Removing old database..."
rm -f database/database.sqlite

echo "🔄 Running migrations..."
php artisan migrate

echo "🌱 Seeding database..."
php artisan db:seed

echo "✅ Database setup complete!"
echo ""
echo "Verifying tables..."
sqlite3 database/database.sqlite ".tables"
