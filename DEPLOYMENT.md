# BangunanPro Deployment Guide

## Quick Start with Docker

### Prerequisites
- Docker Desktop (Windows/Mac) or Docker Engine (Linux)
- Docker Compose
- Git

### Local Development with Docker

1. **Clone repository**
```bash
git clone <repository-url> bangunanpro
cd bangunanpro
```

2. **Copy environment file**
```bash
cp .env.example .env
```

3. **Start Docker containers**
```bash
docker-compose up -d
```

4. **Install dependencies and setup**
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm install
docker-compose exec app npm run build
```

5. **Access application**
- URL: http://localhost:8000
- Admin: admin@bangunanpro.com / password

### Docker Services

The `docker-compose.yml` includes:
- **app**: PHP-FPM application
- **nginx**: Web server (port 8000)
- **mysql**: MySQL 8.0 database (port 3306)
- **redis**: Redis cache (port 6379)
- **queue**: Laravel queue worker
- **scheduler**: Laravel task scheduler

---

## Production Deployment

### Option 1: Laravel Forge + DigitalOcean (Recommended)

**Cost**: $43/month

**Setup Steps**:

1. **Create DigitalOcean Droplet** ($24/month)
   - Go to https://digitalocean.com
   - Create Droplet: Ubuntu 22.04, 4GB RAM, Singapore region
   - Note the IP address

2. **Connect Laravel Forge** ($19/month)
   - Sign up at https://forge.laravel.com
   - Connect DigitalOcean API key
   - Create new server using your droplet

3. **Deploy Application**
   - In Forge, create new site: `bangunanpro.com`
   - Connect Git repository
   - Set environment  variables
   - Enable Quick Deploy
   - Click "Deploy Now"

4. **Configure Database**
   - Forge auto-creates MySQL database
   - Update `.env` with database credentials
   - Run migrations via Forge

5. **Setup SSL**
   - In Forge, go to SSL
   - Request Let's Encrypt certificate
   - Auto-renewal enabled

6. **Configure Queue Worker**
   - In Forge, enable queue worker
   - Use connection: `database` or `redis`

**Advantages**:
- ✅ Zero server management
- ✅ One-click deployments
- ✅ Automatic SSL renewal
- ✅ Built-in backups
- ✅ Perfect for non-DevOps users

---

### Option 2: Manual VPS Deployment

**For advanced users who want full control**

#### Server Requirements
- Ubuntu 22.04 LTS
- PHP 8.2+
- MySQL 8.0+
- Nginx
- Redis (optional)
- Supervisor
- Node.js 20+

#### Step-by-Step Guide

**1. Server Setup**

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip \
    php8.2-gd php8.2-redis

# Install MySQL
sudo apt install -y mysql-server
sudo mysql_secure_installation

# Install Nginx
sudo apt install -y nginx

# Install Redis
sudo apt install -y redis-server

# Install Supervisor
sudo apt install -y supervisor

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

**2. Create Database**

```bash
sudo mysql
```

```sql
CREATE DATABASE bangunanpro;
CREATE USER 'bangunan'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON bangunanpro.* TO 'bangunan'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**3. Deploy Application**

```bash
# Create web root
sudo mkdir -p /var/www/bangunanpro
sudo chown -R $USER:$USER /var/www/bangunanpro
cd /var/www/bangunanpro

# Clone repository
git clone <repository-url> .

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
nano .env  # Edit database credentials

# Generate app key
php artisan key:generate

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force --seed

# Install Node dependencies and build assets
npm ci --production
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**4. Configure Nginx**

```bash
sudo nano /etc/nginx/sites-available/bangunanpro
```

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/bangunanpro/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/bangunanpro /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

**5. Setup SSL with Certbot**

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

**6. Configure Queue Worker**

```bash
sudo nano /etc/supervisor/conf.d/bangunanpro-worker.conf
```

```ini
[program:bangunanpro-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bangunanpro/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/bangunanpro/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start bangunanpro-worker:*
```

**7. Setup Scheduler**

```bash
crontab -e
```

Add:
```
* * * * * cd /var/www/bangunanpro && php artisan schedule:run >> /dev/null 2>&1
```

---

### Option 3: Docker on VPS

**Combine Docker with VPS for consistency**

1. **Install Docker on VPS**
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

2. **Deploy with Docker Compose**
```bash
git clone <repository-url> bangunanpro
cd bangunanpro
cp .env.example .env
# Edit .env for production

docker-compose -f docker-compose.yml up -d
docker-compose exec app php artisan migrate --force --seed
docker-compose exec app php artisan config:cache
```

---

## Environment Configuration

### Production .env Settings

```env
APP_NAME="BangunanPro"
APP_ENV=production
APP_KEY=base64:...  # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bangunanpro
DB_USERNAME=bangunan
DB_PASSWORD=strong_password_here

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bangunanpro.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Multi-Tenant Setup (For SaaS)

### Database Design

**Option 1**: Single database with tenant_id column (recommended for start)

Add to each tenant-specific table:
```php
$table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
$table->index('tenant_id');
```

**Option 2**: Separate database per tenant (for scale)

### Subdomain Routing

In `routes/web.php`:
```php
Route::domain('{tenant}.bangunanpro.com')->group(function () {
    // Tenant routes
});
```

### Tenant Middleware

Create `app/Http/Middleware/IdentifyTenant.php`:
```php
public function handle(Request $request, Closure $next)
{
    $tenant = Tenant::where('domain', $request->getHost())->first();
    
    if (!$tenant) {
        abort(404);
    }
    
    app()->instance('tenant', $tenant);
    
    return $next($request);
}
```

---

## Backup Strategy

### Automated Daily Backups

```bash
#!/bin/bash
# /root/backup.sh

BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)

# Database backup
mysqldump -u bangunan -pPASSWORD bangunanpro | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Application files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/bangunanpro/storage

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

# Upload to S3 (optional)
# aws s3 sync $BACKUP_DIR s3://your-bucket/backups/
```

Add to crontab:
```
0 2 * * * /root/backup.sh
```

---

## Monitoring & Health Checks

### Laravel Health Check Route

```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::has('test') !== null ? 'working' : 'failed',
    ]);
});
```

### Uptime Monitoring

Use services like:
- **UptimeRobot** (free)
- **Pingdom**
- **StatusCake**

---

## Scaling Checklist

When you reach 50+ tenants:

- [ ] Migrate cache to Redis cluster
- [ ] Separate queue workers to dedicated server
- [ ] Add database read replicas
- [ ] Implement CDN for static assets (CloudFlare)
- [ ] Add load balancer for multiple app servers
- [ ] Consider managed database (AWS RDS, DigitalOcean Managed DB)
- [ ] Implement full-text search (Meilisearch/Algolia)

---

## Troubleshooting

### Common Issues

**500 Error**:
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**Permission Denied**:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**Queue Not Processing**:
```bash
# Check supervisor
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart  bangunanpro-worker:*
```

**Database Connection Failed**:
- Check MySQL is running: `sudo systemctl status mysql`
- Verify credentials in `.env`
- Check firewall rules

---

## Security Checklist

Before going live:

- [ ] `APP_DEBUG=false` in production
- [ ] Strong database password
- [ ] SSL certificate installed
- [ ] Firewall configured (UFW/iptables)
- [ ] Regular automated backups running
- [ ] Fail2ban installed (prevent brute force)
- [ ] Server hardening completed
- [ ] Security headers configured
- [ ] Rate limiting enabled
- [ ] SQL injection prevention verified
- [ ] XSS  protection verified
- [ ] CSRF protection enabled

---

## Deployment Workflow

### Git-Based Deployment

```bash
#!/bin/bash
# deploy.sh

cd /var/www/bangunanpro

# Maintenance mode
php artisan down

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo supervisorctl restart bangunanpro-worker:*

# Exit maintenance mode
php artisan up

echo "Deployment completed!"
```

---

## Cost Estimates

### Year 1 Costs (0-30 customers)

| Item | Cost/Month | Annual |
|------|------------|--------|
| **Laravel Forge** | $19 | $228 |
| **DigitalOcean VPS (4GB)** | $24 | $288 |
| **Domain (.com)** | - | $12 |
| **Backup Storage (100GB)** | $5 | $60 |
| **Email (SendGrid)** | $15 | $180 |
| **Total** | **$63** | **$768** |

### Scaling Costs (30-100 customers)

| Item | Cost/Month |
|------|------------|
| **App Servers (2x 4GB)** | $48 |
| **Managed Database (8GB)** | $90 |
| **Load Balancer** | $12 |
| **Redis (Managed)** | $15 |
| **Backup & Monitoring** | $20 |
| **Total** | **~$185/month** |

---

## Support & Maintenance

### Daily Tasks
- Monitor error logs
- Check queue worker status
- Review failed jobs

### Weekly Tasks
- Verify backups are running
- Check disk space
- Review security logs
- Update dependencies if needed

### Monthly Tasks
- Review server performance
- Database optimization
- Clean old logs
- Update SSL certificates (automatic with Let's Encrypt)

---

## Getting Help

- **Laravel Documentation**: https://laravel.com/docs
- **Laravel Forge**: https://forge.laravel.com/docs
- **DigitalOcean Tutorials**: https://digitalocean.com/community/tutorials

---

**Ready to deploy? Start with Laravel Forge for easiest experience! 🚀**
