# 🚀 LuswaaFits - Complete Setup & Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. Local Development Setup
- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] PostgreSQL installed and running
- [ ] Git installed

### 2. Project Setup
- [ ] Clone/download project files
- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Copy `.env.example` to `.env`
- [ ] Run `php artisan key:generate`
- [ ] Create PostgreSQL database
- [ ] Update database credentials in `.env`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan db:seed` (optional)
- [ ] Run `php artisan storage:link`
- [ ] Run `npm run build`
- [ ] Test locally with `php artisan serve`

### 3. Test Key Features Locally
- [ ] User registration works
- [ ] User login works
- [ ] Product listing works
- [ ] Product creation works
- [ ] Cart functionality works
- [ ] Story creation works
- [ ] Profile editing works

## 🌐 Vercel Deployment Checklist

### Phase 1: Database Setup
- [ ] Create PostgreSQL database (Supabase/Neon/Railway)
- [ ] Note down connection details:
  - [ ] Host
  - [ ] Port (5432)
  - [ ] Database name
  - [ ] Username
  - [ ] Password
- [ ] Test connection locally

### Phase 2: Vercel Account & Project
- [ ] Create Vercel account at https://vercel.com
- [ ] Install Vercel CLI: `npm install -g vercel`
- [ ] Login to Vercel: `vercel login`
- [ ] Push code to Git repository (GitHub/GitLab/Bitbucket)

### Phase 3: Project Configuration
- [ ] Run `npm run build` to build assets
- [ ] Initialize Vercel project: `vercel`
- [ ] Link to Git repository in Vercel dashboard

### Phase 4: Environment Variables
Set these in Vercel Dashboard (Settings > Environment Variables):

- [ ] `APP_NAME=LuswaaFits`
- [ ] `APP_ENV=production`
- [ ] `APP_KEY=base64:YOUR_KEY_HERE` (from local .env)
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-domain.com`
- [ ] `DB_CONNECTION=pgsql`
- [ ] `DB_HOST=your-db-host`
- [ ] `DB_PORT=5432`
- [ ] `DB_DATABASE=your-db-name`
- [ ] `DB_USERNAME=your-db-user`
- [ ] `DB_PASSWORD=your-db-password`
- [ ] `SESSION_DRIVER=cookie`
- [ ] `QUEUE_CONNECTION=sync`
- [ ] `FILESYSTEM_DISK=public`
- [ ] `VERCEL=1`

### Phase 5: Domain Setup
- [ ] Add custom domain in Vercel Dashboard
- [ ] Configure DNS records:
  - [ ] Type: CNAME
  - [ ] Name: luswaafits (or your subdomain)
  - [ ] Value: cname.vercel-dns.com
- [ ] Wait for DNS propagation (5-30 minutes)
- [ ] Verify SSL certificate is active

### Phase 6: Database Migrations
Choose one method:

**Method A: Using Vercel CLI**
- [ ] Run `vercel link`
- [ ] Run `vercel exec -- php artisan migrate --force`

**Method B: Temporary Migration Endpoint**
- [ ] Add migration route (see VERCEL_DEPLOYMENT.md)
- [ ] Visit the migration URL
- [ ] Remove migration route immediately

**Method C: Direct Database Connection**
- [ ] Connect to PostgreSQL directly
- [ ] Run migrations manually

### Phase 7: File Storage Setup
Choose one:

**Option A: AWS S3**
- [ ] Create S3 bucket
- [ ] Create IAM user with S3 access
- [ ] Add AWS credentials to Vercel env vars
- [ ] Set `FILESYSTEM_DISK=s3`
- [ ] Install AWS SDK: `composer require league/flysystem-aws-s3-v3`

**Option B: Cloudinary**
- [ ] Sign up at cloudinary.com
- [ ] Install package: `composer require cloudinary-labs/cloudinary-laravel`
- [ ] Add Cloudinary credentials to env vars

### Phase 8: Production Deploy
- [ ] Deploy to production: `vercel --prod`
- [ ] Monitor deployment logs
- [ ] Check for any errors
- [ ] Verify deployment success

### Phase 9: Post-Deployment Testing
- [ ] Visit your production URL
- [ ] Test user registration
- [ ] Test user login
- [ ] Test product listing
- [ ] Test product creation
- [ ] Test cart functionality
- [ ] Test checkout process
- [ ] Test story creation
- [ ] Test profile editing
- [ ] Test image uploads (if using S3/Cloudinary)
- [ ] Test all routes work correctly
- [ ] Verify SSL is working (HTTPS)
- [ ] Check console for JavaScript errors
- [ ] Test on mobile devices

### Phase 10: Security Hardening
- [ ] Verify `APP_DEBUG=false` in production
- [ ] Ensure `.env` is in `.gitignore`
- [ ] Rotate database credentials
- [ ] Set up rate limiting
- [ ] Enable CSRF protection (already enabled by Laravel)
- [ ] Review CORS settings if needed
- [ ] Set secure session cookies

### Phase 11: Performance Optimization
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Add database indexes for frequently queried columns
- [ ] Enable database connection pooling
- [ ] Consider using CDN for static assets

### Phase 12: Monitoring & Maintenance
- [ ] Set up uptime monitoring (UptimeRobot/Pingdom)
- [ ] Set up error tracking (Sentry)
- [ ] Enable Vercel Analytics
- [ ] Set up database backups
- [ ] Configure automated backups schedule
- [ ] Set up alerts for downtime
- [ ] Monitor database usage
- [ ] Monitor file storage usage

## 📋 Troubleshooting Checklist

### If you get 500 errors:
- [ ] Check Vercel deployment logs
- [ ] Verify all environment variables are set
- [ ] Verify `APP_KEY` is set correctly
- [ ] Check database connection
- [ ] Review Laravel logs (if accessible)

### If routes don't work:
- [ ] Verify `vercel.json` is configured correctly
- [ ] Check `api/index.php` exists
- [ ] Clear route cache
- [ ] Check `.htaccess` rules

### If static assets don't load:
- [ ] Run `npm run build` before deployment
- [ ] Verify `public/build` directory exists
- [ ] Check asset paths in HTML
- [ ] Review `vite.config.js`

### If database connection fails:
- [ ] Verify database credentials
- [ ] Check database allows remote connections
- [ ] Verify firewall rules
- [ ] Test connection with PostgreSQL client
- [ ] For Supabase, enable connection pooling

### If file uploads don't work:
- [ ] Remember: Vercel has read-only filesystem
- [ ] Must use S3, Cloudinary, or similar
- [ ] Verify `FILESYSTEM_DISK` is set correctly
- [ ] Check storage credentials

### If sessions don't persist:
- [ ] Use `SESSION_DRIVER=cookie`
- [ ] Set `SESSION_DOMAIN` to your domain
- [ ] Enable `SESSION_SECURE_COOKIE=true` for HTTPS

## 🎯 Success Criteria

Your deployment is successful when:
- ✅ Site loads at your custom domain
- ✅ HTTPS is working (green padlock)
- ✅ Users can register and login
- ✅ Products can be listed and viewed
- ✅ Cart and checkout work
- ✅ Images upload successfully
- ✅ Stories can be created
- ✅ No console errors
- ✅ Mobile responsive
- ✅ All routes work correctly

## 📞 Support Resources

- **Laravel Docs**: https://laravel.com/docs
- **Vercel Docs**: https://vercel.com/docs
- **Supabase Docs**: https://supabase.com/docs
- **Stack Overflow**: Tag questions with `laravel` and `vercel`
- **Laravel Discord**: https://discord.gg/laravel

## 🎓 Learning Resources

- Laravel Bootcamp: https://bootcamp.laravel.com
- Laracasts: https://laracasts.com
- Laravel News: https://laravel-news.com

## 📝 Notes

- Keep this checklist handy during deployment
- Document any custom configurations you make
- Save database credentials securely
- Keep backup of environment variables
- Document your deployment process for team members

## 🚀 Ready to Launch?

Once all checkboxes are complete, you're ready for production! 

Remember:
- Test thoroughly before announcing
- Have a rollback plan
- Monitor closely after launch
- Be ready to respond to issues quickly

**Good luck with your deployment! 🎉**
