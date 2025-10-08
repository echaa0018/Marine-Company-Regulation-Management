# Ini Bikinan Copliot Maaf Mas Kalau Gajelas :D

## Project Configuration

**Project Name:** ITAS Laravel Template (Bahan ECA)

**Purpose:** A Laravel-based enterprise application template with document management, user authentication, and LDAP integration capabilities.

**Tech Stack:** PHP 8.2+, Laravel 12, PostgreSQL, Redis, Livewire, TailwindCSS, Vite, Docker, LDAP

This project uses Laravel's standard `.env` file approach for local development configuration and environment variables for production deployments. All sensitive configuration values are externalized through environment variables to maintain security and deployment flexibility.

## Quick Start: Local Setup

### 1. Copy the Environment Template
```bash
cp .env.example .env
```

### 2. Environment Variables in .env.example

The following variables are defined in the `.env.example` file:

**Application Settings:**
- `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`
- `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`
- `APP_MAINTENANCE_DRIVER`, `PHP_CLI_SERVER_WORKERS`

**Security & Logging:**
- `BCRYPT_ROUNDS`, `LOG_CHANNEL`, `LOG_STACK`, `LOG_LEVEL`

**Database Configuration:**
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

**Session & Cache:**
- `SESSION_DRIVER`, `SESSION_LIFETIME`, `CACHE_STORE`, `REDIS_CLIENT`, `REDIS_HOST`, `REDIS_PORT`

**External Services:**
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`

**Frontend:**
- `VITE_APP_NAME`

### 3. Generate Application Key
```bash
php artisan key:generate
```

## Environment Variables Deep Dive

| Variable | Description | Example Value | Required? |
|----------|-------------|---------------|-----------|
| `APP_NAME` | Application name displayed in UI and emails | `ITAS Laravel Template` | Yes |
| `APP_ENV` | Environment type (local, staging, production) | `local` | Yes |
| `APP_KEY` | Laravel encryption key (auto-generated) | `base64:random_key_here` | Yes |
| `APP_DEBUG` | Enable debug mode (never true in production) | `true` | Yes |
| `APP_URL` | Base URL of the application | `http://localhost:8000` | Yes |
| `DB_CONNECTION` | Database driver type | `pgsql` | Yes |
| `DB_HOST` | Database server hostname | `127.0.0.1` | Yes |
| `DB_PORT` | Database server port | `5432` | Yes |
| `DB_DATABASE` | Database name | `itas_launchpad` | Yes |
| `DB_USERNAME` | Database username | `postgres` | Yes |
| `DB_PASSWORD` | Database password | `your_secure_password` | Yes |
| `REDIS_HOST` | Redis server hostname | `127.0.0.1` | No |
| `REDIS_PORT` | Redis server port | `6379` | No |
| `REDIS_PASSWORD` | Redis authentication password | `null` | No |
| `MAIL_MAILER` | Mail driver (smtp, log, etc.) | `smtp` | Yes |
| `MAIL_HOST` | SMTP server hostname | `smtp.mailtrap.io` | No |
| `MAIL_PORT` | SMTP server port | `587` | No |
| `MAIL_USERNAME` | SMTP username | `your_username` | No |
| `MAIL_PASSWORD` | SMTP password | `your_password` | No |
| `MAIL_FROM_ADDRESS` | Default sender email address | `noreply@yourcompany.com` | Yes |
| `AWS_ACCESS_KEY_ID` | AWS access key for S3 storage | `AKIAIOSFODNN7EXAMPLE` | No |
| `AWS_SECRET_ACCESS_KEY` | AWS secret key for S3 storage | `wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY` | No |
| `AWS_BUCKET` | S3 bucket name for file storage | `my-app-storage` | No |
| `SESSION_DRIVER` | Session storage driver | `database` | Yes |
| `CACHE_STORE` | Cache storage driver | `database` | Yes |
| `QUEUE_CONNECTION` | Queue driver for background jobs | `database` | Yes |

## Connecting to Services

### Database: PostgreSQL Setup

#### Option 1: Using Docker (Recommended)
1. **Create a PostgreSQL container:**
   ```bash
   docker run --name postgres-itas \
     -e POSTGRES_DB=itas_launchpad \
     -e POSTGRES_USER=postgres \
     -e POSTGRES_PASSWORD=your_secure_password \
     -p 5432:5432 \
     -d postgres:15
   ```

2. **Update your `.env` file:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=itas_launchpad
   DB_USERNAME=postgres
   DB_PASSWORD=your_secure_password
   ```

3. **Run migrations:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

#### Option 2: Local PostgreSQL Installation
1. Install PostgreSQL from [postgresql.org](https://www.postgresql.org/download/)
2. Create a new database: `createdb itas_launchpad`
3. Update `.env` with your local PostgreSQL credentials

### Cache: Redis Setup

#### Using Docker (Recommended)
1. **Run Redis container:**
   ```bash
   docker run --name redis-itas \
     -p 6379:6379 \
     -d redis:7-alpine
   ```

2. **Update your `.env` file:**
   ```env
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   REDIS_PASSWORD=null
   ```

#### Local Redis Installation
1. Install Redis from [redis.io](https://redis.io/download)
2. Start Redis server: `redis-server`
3. Test connection: `redis-cli ping` (should return "PONG")

### Mail Service Setup

#### For Development (Using Mailtrap)
1. Sign up at [mailtrap.io](https://mailtrap.io)
2. Get your SMTP credentials from the inbox settings
3. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=587
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_FROM_ADDRESS=noreply@yourapp.com
   ```

#### For Development (Log Driver)
```env
MAIL_MAILER=log
```
Emails will be written to `storage/logs/laravel.log`

### LDAP Integration

This project includes LDAP authentication. Configure LDAP settings in `config/ldap.php` or through environment variables if available.

### File Storage (AWS S3)

For production file storage:
1. Create an S3 bucket in AWS
2. Create IAM user with S3 access
3. Update `.env`:
   ```env
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your_access_key
   AWS_SECRET_ACCESS_KEY=your_secret_key
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your-bucket-name
   ```

## Common Issues

### Issue 1: Database Connection Refused
**Error:** `SQLSTATE[08006] [7] could not connect to server: Connection refused`

**Solutions:**
- Ensure PostgreSQL is running: `docker ps` (for Docker) or check service status
- Verify database credentials in `.env` match your PostgreSQL setup
- Check if the database exists: `psql -U postgres -l`
- Ensure the port (5432) is not blocked by firewall

### Issue 2: Redis Connection Failed
**Error:** `Connection refused [tcp://127.0.0.1:6379]`

**Solutions:**
- Start Redis server: `redis-server` or `docker start redis-itas`
- Verify Redis is listening: `redis-cli ping`
- Check Redis configuration in `.env` file
- For Docker: ensure port 6379 is properly mapped

### Issue 3: Application Key Not Set
**Error:** `No application encryption key has been specified`

**Solution:**
```bash
php artisan key:generate
```

### Issue 4: Permission Issues with Storage
**Error:** `The stream or file could not be opened in append mode`

**Solutions:**
- Set proper permissions: `chmod -R 775 storage bootstrap/cache`
- Ensure web server user owns the directories: `chown -R www-data:www-data storage bootstrap/cache`

### Issue 5: Livewire Assets Not Found
**Error:** 404 errors for Livewire JavaScript files

**Solutions:**
```bash
npm install
npm run build
php artisan livewire:publish --assets
```

### Issue 6: LDAP Connection Issues
**Error:** LDAP authentication failures

**Solutions:**
- Verify LDAP server connectivity: `telnet ldap.yourcompany.com 389`
- Check LDAP configuration in `config/ldap.php`
- Ensure proper LDAP credentials and search parameters
- Test LDAP bind with ldapsearch utility

## Development Workflow

1. **Start development environment:**
   ```bash
   # Start database and Redis (if using Docker)
   docker start postgres-itas redis-itas
   
   # Install dependencies
   composer install
   npm install
   
   # Run migrations
   php artisan migrate
   
   # Start development servers
   php artisan serve
   npm run dev
   ```

2. **Access the application:**
   - Laravel app: http://localhost:8000
   - Vite dev server: http://localhost:5173

Remember to never commit your `.env` file to version control. Always use `.env.example` as a template for required environment variables.