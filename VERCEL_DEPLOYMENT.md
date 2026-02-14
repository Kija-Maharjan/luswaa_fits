# Vercel Deployment Guide for LuswaaFits

## Prerequisites

1. **Vercel Account**: Sign up at https://vercel.com
2. **PostgreSQL Database**: Set up on one of these platforms:
   - Supabase (Recommended - Free tier)
   - Neon (Serverless PostgreSQL)
   - Railway
   - Heroku Postgres

## Step-by-Step Deployment

### 1. Set Up PostgreSQL Database

#### Using Supabase (Recommended)

1. Go to https://supabase.com and create a free account
2. Create a new project
3. Go to Settings > Database
4. Copy the connection details:
   - Host
   - Database name
   - Port (5432)
   - Username
   - Password

### 2. Prepare Your Local Project

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Generate application key
php artisan key:generate

# Copy the APP_KEY from .env file (starts with base64:)
```

### 3. Configure Vercel Project

#### Option A: Using Vercel CLI

```bash
# Install Vercel CLI
npm install -g vercel

# Login to Vercel
vercel login

# Deploy (will create new project)
vercel

# Follow the prompts:
# - Link to existing project? No
# - What's your project's name? luswaafits
# - In which directory is your code located? ./
```

#### Option B: Using Vercel Dashboard

1. Go to https://vercel.com/dashboard
2. Click "Add New Project"
3. Import your Git repository (GitHub, GitLab, or Bitbucket)
4. Configure project settings:
   - Framework Preset: Other
   - Root Directory: ./
   - Build Command: `composer install --no-dev && npm run build`
   - Output Directory: public

### 4. Set Environment Variables in Vercel

Go to your project settings > Environment Variables and add:

```env
APP_NAME=LuswaaFits
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://luswaafits.yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-database-password

SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

VERCEL=1
```

**Important**: Set these variables for all environments (Production, Preview, Development).

### 5. Configure Custom Subdomain

#### If You Have a Domain

1. In Vercel Dashboard, go to your project
2. Go to Settings > Domains
3. Click "Add Domain"
4. Enter your subdomain: `luswaafits.yourdomain.com`
5. Vercel will show you DNS records to add

#### Add DNS Records

In your domain registrar's DNS settings:

**For CNAME (Recommended)**:
```
Type: CNAME
Name: luswaafits
Value: cname.vercel-dns.com
TTL: Auto
```

**For A Record** (if CNAME not available):
```
Type: A
Name: luswaafits
Value: 76.76.21.21
TTL: Auto
```

Wait 5-30 minutes for DNS propagation.

#### If You Don't Have a Domain

Vercel provides a free subdomain:
- `your-project.vercel.app`
- You can customize the project name in Settings

### 6. Run Database Migrations

After deployment, you need to run migrations. There are several ways:

#### Method 1: Using Vercel CLI (Recommended)

```bash
# Install Vercel CLI if not already installed
npm install -g vercel

# Link to your project
vercel link

# Run migrations
vercel exec -- php artisan migrate --force

# Optionally seed the database
vercel exec -- php artisan db:seed --force
```

#### Method 2: Direct PostgreSQL Connection

```bash
# Connect to your database directly
psql -h your-supabase-host.supabase.co -U postgres -d postgres

# Run migration SQL manually
# (Not recommended for large migrations)
```

#### Method 3: Create a Migration Endpoint (Temporary)

Add this route in `routes/web.php` (remove after use):

```php
Route::get('/run-migrations', function() {
    if (config('app.env') !== 'production' || !request()->has('secret')) {
        abort(404);
    }
    
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations completed';
})->middleware('web');
```

Visit: `https://luswaafits.yourdomain.com/run-migrations?secret=your-secret`

**Remove this route immediately after use!**

### 7. File Storage Configuration

For production, you have several options for file storage:

#### Option A: Local Storage (Simple, Not Recommended for Vercel)

Vercel has read-only file system, so local storage won't persist between deployments.

#### Option B: AWS S3 (Recommended)

1. Create AWS S3 bucket
2. Add to environment variables:

```env
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=luswaafits-uploads
FILESYSTEM_DISK=s3
```

3. Install AWS SDK:
```bash
composer require league/flysystem-aws-s3-v3
```

#### Option C: Cloudinary (Easy Alternative)

1. Sign up at https://cloudinary.com
2. Install package:
```bash
composer require cloudinary-labs/cloudinary-laravel
```

3. Configure in environment variables

### 8. Verify Deployment

1. Visit your domain: `https://luswaafits.yourdomain.com`
2. Test key functionality:
   - User registration
   - Login
   - Product listing
   - Cart functionality
   - Story posting

### 9. Post-Deployment Tasks

#### Set Up SSL

Vercel automatically provisions SSL certificates for custom domains. Verify:
- Green padlock in browser
- HTTPS redirect is working

#### Configure CORS (if using API)

In `config/cors.php`:
```php
'allowed_origins' => [env('APP_URL')],
```

#### Set Up Error Monitoring

Consider integrating:
- Sentry (error tracking)
- LogRocket (session replay)
- Google Analytics

### 10. Continuous Deployment

Vercel automatically deploys when you push to your Git repository:

1. Make changes to your code
2. Commit and push:
```bash
git add .
git commit -m "Update feature"
git push origin main
```
3. Vercel automatically builds and deploys

## Troubleshooting

### Issue: 500 Internal Server Error

**Solution**:
1. Check Vercel logs: Dashboard > Deployments > Your deployment > Logs
2. Verify environment variables are set correctly
3. Ensure APP_KEY is set
4. Check database connection

### Issue: Routes Not Working

**Solution**:
1. Verify `vercel.json` is configured correctly
2. Check that `api/index.php` exists
3. Clear route cache: `php artisan route:clear`

### Issue: Static Assets Not Loading

**Solution**:
1. Run `npm run build` before deployment
2. Verify `public/build` directory exists
3. Check `vite.config.js` configuration

### Issue: Database Connection Failed

**Solution**:
1. Verify database credentials
2. Check if database allows connections from Vercel IPs
3. For Supabase, ensure connection pooling is enabled
4. Try connection string format:
```env
DB_URL=postgresql://user:password@host:5432/database
```

### Issue: File Uploads Not Working

**Solution**:
- Vercel has read-only filesystem
- Must use S3, Cloudinary, or similar service
- Configure `FILESYSTEM_DISK=s3` in environment

### Issue: Session Not Persisting

**Solution**:
1. Use cookie-based sessions: `SESSION_DRIVER=cookie`
2. Ensure `SESSION_DOMAIN` matches your domain
3. Set `SESSION_SECURE_COOKIE=true` for HTTPS

## Performance Optimization

### 1. Optimize Database Queries

```bash
# Add database indexes
php artisan make:migration add_indexes_to_tables

# Use eager loading
Product::with('user')->get();
```

### 2. Enable Caching

```bash
# Cache routes
php artisan route:cache

# Cache config
php artisan config:cache

# Cache views
php artisan view:cache
```

### 3. Use CDN for Assets

Configure Cloudflare or similar CDN in front of Vercel.

### 4. Implement Database Connection Pooling

For PostgreSQL, use PgBouncer (Supabase provides this).

## Scaling Considerations

- **Database**: Upgrade to paid plan if needed
- **File Storage**: Monitor S3 usage
- **Vercel**: Pro plan for better performance
- **Database Pooling**: Essential for serverless

## Security Checklist

- ✅ HTTPS enabled (automatic with Vercel)
- ✅ Environment variables secured
- ✅ Database credentials rotated regularly
- ✅ CSRF protection enabled
- ✅ Input validation implemented
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection enabled
- ✅ Rate limiting configured

## Backup Strategy

### Database Backups

1. **Supabase**: Automatic daily backups on paid plans
2. **Manual backups**:
```bash
pg_dump -h host -U user -d database > backup.sql
```

### File Backups

If using S3, enable versioning and lifecycle policies.

## Monitoring

### Set Up Monitoring

1. **Uptime Monitoring**: UptimeRobot, Pingdom
2. **Error Tracking**: Sentry
3. **Performance**: Vercel Analytics (built-in)
4. **Database**: Provider's monitoring dashboard

## Cost Estimates

### Free Tier Setup
- Vercel: Free (Hobby plan)
- Supabase: Free (500MB database)
- Total: $0/month

### Production Setup
- Vercel Pro: $20/month
- Supabase Pro: $25/month
- AWS S3: ~$5/month (estimated)
- Total: ~$50/month

## Support Resources

- Vercel Documentation: https://vercel.com/docs
- Laravel Documentation: https://laravel.com/docs
- Supabase Documentation: https://supabase.com/docs
- Community: Discord, Stack Overflow

## Next Steps After Deployment

1. Set up custom email domain (SendGrid, Mailgun)
2. Implement payment gateway (Stripe, PayPal)
3. Add search functionality (Algolia, Meilisearch)
4. Set up automated testing
5. Configure staging environment
6. Implement CI/CD pipeline

---

Need help? Check the main README.md or open an issue on GitHub.
