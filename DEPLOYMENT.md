# Roulette Game - Deployment Guide

This guide will help you deploy your Laravel Roulette Game to **Railway.com** and **Render.com**.

## 🚀 Quick Deployment Options

### Option 1: Deploy to Railway.com (Recommended)

Railway.com offers excellent Laravel support with automatic deployments from GitHub.

#### Steps:

1. **Push to GitHub**
   ```bash
   cd /Users/jhayceecajiles/Desktop/Visual\ Studio/laravel/roulette-game
   git init
   git add .
   git commit -m "Initial commit - Roulette Game"
   git branch -M main
   git remote add origin YOUR_GITHUB_REPO_URL
   git push -u origin main
   ```

2. **Deploy on Railway**
   - Go to [railway.app](https://railway.app)
   - Sign up/Login with GitHub
   - Click "New Project" → "Deploy from GitHub repo"
   - Select your roulette-game repository
   - Railway will automatically detect the Laravel app and use the `Dockerfile`

3. **Configure Environment Variables**
   Railway will automatically set up most variables. You can add custom ones in the Railway dashboard:
   - `APP_URL`: Your Railway app URL (auto-generated)
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`

4. **Access Your App**
   - Railway will provide a URL like `https://your-app-name.railway.app`
   - Your roulette game will be live!

#### Railway Advantages:
- ✅ Automatic deployments from GitHub
- ✅ Built-in database support (if needed later)
- ✅ Excellent Laravel support
- ✅ Free tier available
- ✅ Automatic SSL certificates

---

### Option 2: Deploy to Render.com

Render.com also provides excellent Laravel hosting with Docker support.

#### Steps:

1. **Push to GitHub** (same as Railway)

2. **Deploy on Render**
   - Go to [render.com](https://render.com)
   - Sign up/Login with GitHub
   - Click "New" → "Web Service"
   - Connect your GitHub repository
   - Render will detect the `render.yaml` configuration

3. **Configure Settings**
   - **Name**: `roulette-game`
   - **Environment**: `Docker`
   - **Plan**: `Free`
   - Render will use the `render.yaml` file for configuration

4. **Access Your App**
   - Render will provide a URL like `https://roulette-game.onrender.com`
   - Your app will be live!

#### Render Advantages:
- ✅ Easy Docker deployment
- ✅ Free tier available
- ✅ Automatic SSL certificates
- ✅ Good performance

---

## 🔧 Manual Deployment (Alternative)

If you prefer manual deployment or want to use a different platform:

### Prerequisites
- PHP 8.2 or higher
- Composer
- SQLite support
- Web server (Apache/Nginx)

### Steps

1. **Prepare the application**
   ```bash
   cd /Users/jhayceecajiles/Desktop/Visual\ Studio/laravel/roulette-game
   
   # Install dependencies
   composer install --no-dev --optimize-autoloader
   
   # Set up environment
   cp .env.example .env
   php artisan key:generate
   
   # Set up storage
   php artisan storage:link
   mkdir -p storage/framework/{cache,views,sessions} bootstrap/cache
   mkdir -p public/storage/save
   chmod -R 775 storage bootstrap/cache
   
   # Set up database
   touch database/database.sqlite
   php artisan migrate --force
   ```

2. **Configure web server**
   - Point document root to `public/` directory
   - Ensure all requests go through `index.php`

---

## 📱 App Features After Deployment

Once deployed, your Roulette Game will have:

### 🎯 Core Features
- **Home Page**: Add up to 1000 player names
- **Roulette Wheel**: Beautiful animated spinning wheel
- **Winner Selection**: Fair random selection with 30% chance to use "Next to Win" list
- **Celebration**: Confetti animation for winners

### 👨‍💼 Admin Dashboard
- **URL**: `https://your-app-url.com/admin`
- **Username**: `jhaycee` or `dessa`
- **Password**: `password`
- **Features**:
  - Add names to "Next to Win" list
  - Clear the "Next to Win" list
  - View game statistics

### 🔍 Debug Features
- **Debug Tool**: Available on home page to check "Next to Win" list
- **File Storage**: Game data stored in JSON files

---

## 🛠️ Configuration Files

The following files have been configured for deployment:

### `render.yaml`
- Configures Render.com deployment
- Sets up environment variables
- Includes build commands for Laravel

### `railway.json`
- Configures Railway.com deployment
- Sets up Docker deployment
- Includes health check configuration

### `Dockerfile`
- Multi-stage Docker build
- Installs PHP 8.2 and required extensions
- Sets up Laravel application
- Creates necessary directories and permissions

---

## 🚨 Important Notes

### Database
- **SQLite**: Currently uses SQLite for simplicity
- **File Storage**: Game data stored in JSON files
- **Sessions**: Uses database sessions for admin authentication

### Security
- **Admin Credentials**: 
  - Username: `jhaycee` or `dessa`
  - Password: `password`
  - **Change these in production!**

### Performance
- **Free Tier Limitations**: Both Railway and Render have limitations on free tier
- **Sleep Mode**: Free apps may sleep after inactivity
- **Cold Starts**: First request after sleep may be slower

---

## 🔄 Updates and Maintenance

### Updating the App
1. Make changes locally
2. Commit and push to GitHub
3. Railway/Render will automatically redeploy

### Monitoring
- Check Railway/Render dashboards for logs
- Monitor app performance and errors
- Set up alerts if needed

---

## 🆘 Troubleshooting

### Common Issues

1. **App won't start**
   - Check logs in Railway/Render dashboard
   - Verify environment variables
   - Ensure database permissions

2. **Admin login not working**
   - Check session storage permissions
   - Verify database is accessible
   - Check admin credentials in `AdminController.php`

3. **Storage issues**
   - Ensure `public/storage/save` directory exists
   - Check file permissions (775)
   - Verify storage link is created

4. **Database errors**
   - Check SQLite file permissions
   - Run migrations: `php artisan migrate`
   - Verify database path in `.env`

### Getting Help
- Check Laravel logs in `storage/logs/`
- Use Railway/Render dashboard logs
- Verify all configuration files are correct

---

## 🎉 Success!

Once deployed, your Roulette Game will be accessible worldwide! Players can:
- Add their names and spin the wheel
- Enjoy the beautiful animations
- Admins can manage the "Next to Win" list

**Happy Deploying!** 🚀
