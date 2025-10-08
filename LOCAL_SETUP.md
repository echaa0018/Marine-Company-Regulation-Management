# Local Development Setup

## Prerequisites
- PHP installed (version 7.4 or higher)
- MySQL database server running
- Web browser

## Quick Setup Options

### Option 1: Using XAMPP (Recommended for beginners)
1. Download and install [XAMPP](https://www.apachefriends.org/download.html)
2. Start XAMPP Control Panel
3. Start "Apache" and "MySQL" services
4. Open phpMyAdmin: http://localhost/phpmyadmin
5. Create database by running the SQL from `database.sql`:
   ```sql
   CREATE DATABASE test;
   USE test;
   CREATE TABLE `users` (
     `id` int(11) NOT NULL auto_increment,
     `name` varchar(100) NOT NULL,
     `age` int(3) NOT NULL,
     `email` varchar(100) NOT NULL,
     PRIMARY KEY  (`id`)
   );
   ```
6. Copy this project folder to `C:\xampp\htdocs\crud-php-simple`
7. Access your app at: http://localhost/crud-php-simple

### Option 2: Using PHP Built-in Server (if you have PHP and MySQL installed)
1. Make sure MySQL is running with a database called "test"
2. Import the database schema from `database.sql`
3. Run from this directory:
   ```bash
   php -S localhost:8000
   ```
4. Open: http://localhost:8000

### Option 3: Skip local setup and deploy directly
If you don't want to set up locally, you can:
1. Push your code to GitHub
2. Deploy directly to Railway
3. Test on the live site

## Testing the Database Connection
The app should automatically connect using the settings in `dbConnection.php`:
- Host: localhost
- Database: test  
- Username: root
- Password: root (or whatever you set)

If you get connection errors, you may need to update the database credentials in `dbConnection.php`.

## Next Steps
Once everything works locally, follow `DEPLOYMENT.md` to deploy to Railway or another hosting service.