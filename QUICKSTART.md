# LuswaaFits - Quick Start Guide

## 🎯 Project Overview

LuswaaFits is a fashion marketplace platform where users can:
- Buy and sell fashion items (clothing, shoes, accessories)
- Post and share fashion stories
- Build their fashion profile
- Manage orders and sales

## 🚀 Quick Setup (5 Minutes)

### Local Development

```bash
# 1. Run the setup script
./setup.sh

# 2. Start the development server
php artisan serve

# 3. Visit http://localhost:8000
```

### Manual Setup

```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
createdb luswaafits
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start server
php artisan serve
```

## 🌐 Deploy to Vercel (10 Minutes)

```bash
# 1. Install Vercel CLI
npm install -g vercel

# 2. Build assets
npm run build

# 3. Deploy
vercel --prod

# 4. Set environment variables in Vercel Dashboard

# 5. Run migrations
vercel exec -- php artisan migrate --force
```

**Detailed guide**: See [VERCEL_DEPLOYMENT.md](VERCEL_DEPLOYMENT.md)

## 📁 Project Structure

```
luswaafits-project/
├── app/
│   ├── Http/Controllers/      # Application controllers
│   └── Models/                # Database models
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Sample data
├── routes/
│   └── web.php               # Application routes
├── resources/
│   └── views/                # Blade templates (to be created)
├── public/                   # Public assets
├── vercel.json              # Vercel configuration
└── api/index.php            # Vercel entry point
```

## 🔑 Key Features

### For Buyers
- Browse products by category
- Search and filter listings
- Add items to cart
- Secure checkout process
- Order tracking
- Read fashion stories

### For Sellers
- List products for sale
- Manage inventory
- Track sales
- Update product status
- Create fashion stories
- Build seller profile

## 📊 Database Models

- **User**: User accounts with roles (buyer/seller/both)
- **Product**: Fashion items for sale
- **Cart**: Shopping cart items
- **Order**: Purchase orders
- **OrderItem**: Individual items in orders
- **Story**: Fashion stories and blog posts

## 🎨 Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Database**: PostgreSQL
- **Frontend**: Blade Templates + Tailwind CSS
- **Deployment**: Vercel
- **Storage**: Local/S3/Cloudinary (configurable)

## 🔐 Authentication

Built-in Laravel authentication with:
- Registration
- Login/Logout
- Password reset
- Profile management

## 📝 Sample Credentials

After seeding the database:

```
Email: john@example.com
Password: password123

Email: jane@example.com
Password: password123

Email: mike@example.com
Password: password123
```

## 🌟 Routes Overview

### Public Routes
- `GET /` - Homepage
- `GET /products` - Browse products
- `GET /products/{id}` - Product details
- `GET /stories` - Browse stories
- `GET /stories/{id}` - Story details

### Authenticated Routes
- `GET /profile` - User profile
- `GET /cart` - Shopping cart
- `GET /checkout` - Checkout page
- `GET /orders` - Order history
- `POST /products` - Create product
- `POST /stories` - Create story

## 🛠️ Development Commands

```bash
# Start development server
php artisan serve

# Watch for file changes
npm run dev

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate IDE helper (optional)
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate
```

## 📦 Adding Features

### Add a New Model

```bash
php artisan make:model ModelName -m
# -m flag creates migration
```

### Add a New Controller

```bash
php artisan make:controller ControllerName
```

### Add a New Migration

```bash
php artisan make:migration create_table_name_table
```

## 🐛 Troubleshooting

### Database Connection Issues
```bash
# Check PostgreSQL is running
sudo service postgresql status

# Verify credentials in .env file
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
```

### Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Asset Build Issues
```bash
# Clear node modules and reinstall
rm -rf node_modules
npm install
npm run build
```

## 🎯 Next Steps

1. **Customize Design**: Modify Tailwind config and create views
2. **Add Payment**: Integrate Stripe or PayPal
3. **Add Search**: Implement Algolia or Meilisearch
4. **Add Email**: Configure mail driver (SendGrid, Mailgun)
5. **Add Images**: Set up S3 or Cloudinary
6. **Add Tests**: Write feature and unit tests

## 📚 Resources

- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- Vercel Documentation: https://vercel.com/docs
- PostgreSQL Documentation: https://www.postgresql.org/docs

## 💡 Tips

1. **Use Eager Loading**: Prevent N+1 queries
   ```php
   Product::with('user')->get();
   ```

2. **Add Indexes**: Improve query performance
   ```php
   $table->index('column_name');
   ```

3. **Use Transactions**: For complex operations
   ```php
   DB::transaction(function () {
       // Your code
   });
   ```

4. **Cache Routes**: In production
   ```bash
   php artisan route:cache
   php artisan config:cache
   ```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

Proprietary - All rights reserved

## 🆘 Support

For issues or questions:
- Check the README.md
- Check VERCEL_DEPLOYMENT.md for deployment issues
- Open an issue on GitHub

---

**Happy coding! 🚀**
