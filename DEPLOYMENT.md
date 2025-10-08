# Deployment Guide for Railway

## Prerequisites
- A GitHub account
- Git installed on your computer
- Railway account (free at railway.app)

## Step 1: Push your code to GitHub
1. Create a new repository on GitHub
2. Push your local code to GitHub:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/yourusername/your-repo-name.git
   git push -u origin main
   ```

## Step 2: Deploy to Railway
1. Go to [railway.app](https://railway.app) and sign up/login
2. Click "New Project"
3. Select "Deploy from GitHub repo"
4. Choose your repository
5. Railway will automatically detect it's a PHP project and deploy

## Step 3: Add MySQL Database
1. In your Railway project dashboard, click "New Service"
2. Select "Database" → "Add MySQL"
3. Railway will automatically create the database and set environment variables

## Step 4: Import Database Schema
1. In Railway dashboard, click on your MySQL service
2. Go to "Data" tab
3. Use the query editor to run the contents of `database.sql`:
   ```sql
   CREATE TABLE IF NOT EXISTS `users` (
     `id` int(11) NOT NULL auto_increment,
     `name` varchar(100) NOT NULL,
     `age` int(3) NOT NULL,
     `email` varchar(100) NOT NULL,
     PRIMARY KEY  (`id`)
   );
   ```

## Step 5: Access Your Application
- Your app will be available at: `https://your-app-name.up.railway.app`
- Railway automatically provides the domain

## Environment Variables (Automatically Set by Railway)
Railway automatically sets these environment variables when you add a MySQL service:
- `MYSQLHOST`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`
- `MYSQLPORT`

## Alternative Free Hosting Options:

### InfinityFree (100% Free)
1. Sign up at [infinityfree.net](https://infinityfree.net)
2. Create a hosting account
3. Upload files via File Manager or FTP
4. Import database via phpMyAdmin
5. Update `dbConnection.php` with provided database credentials

### 000webhost (Free)
1. Sign up at [000webhost.com](https://000webhost.com)
2. Create a website
3. Upload files and import database
4. Update database connection settings

## Notes:
- Railway gives you $5/month in credits for free
- InfinityFree is completely free but has some limitations
- Always test your deployment after setup