# BangunanPro Production Deployment Checklist

## ✅ Pre-Deployment

### 1. Environment Configuration
```bash
# Copy .env.example to .env and configure:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_LOCALE=id
```

### 2. Database
```bash
# Run migrations
php artisan migrate --force

# Seed permissions (required)
php artisan db:seed --class=PermissionSeeder --force
```

### 3. Security
```bash
# Generate new app key (if fresh install)
php artisan key:generate

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Optimize Performance
```bash
# Install composer production dependencies
composer install --no-dev --optimize-autoloader

# Build assets
npm run build

# Optimize Laravel
php artisan optimize
```

---

## 🔐 Security Hardening

### Required .env Settings
```env
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE_COOKIE=strict
BCRYPT_ROUNDS=12
```

### Web Server (Nginx Example)
```nginx
# Force HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

# Main config
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/bangunanpro/public;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

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

---

## 📦 File Permissions
```bash
# Storage and cache writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔄 Queue & Scheduler

### Supervisor for Queue Worker
```ini
[program:bangunanpro-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bangunanpro/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/bangunanpro/storage/logs/worker.log
```

### Cron for Scheduler
```bash
# Add to crontab -e
* * * * * cd /var/www/bangunanpro && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔑 WhatsApp (Fonnte) Setup

1. Register at https://fonnte.com
2. Add device and get API token
3. Add to .env:
```env
FONNTE_TOKEN=your_token_here
FONNTE_ENABLED=true
```

---

## 📊 Monitoring (Recommended)

- **Laravel Telescope** (dev only)
- **Sentry** for error tracking
- **UptimeRobot** for uptime monitoring

---

## 🚀 Go Live Checklist

- [ ] SSL certificate installed (Let's Encrypt)
- [ ] Database backed up
- [ ] .env configured for production
- [ ] Storage linked: `php artisan storage:link`
- [ ] Queues running via Supervisor
- [ ] Scheduler configured in cron
- [ ] FONNTE_TOKEN set (if using WhatsApp)
- [ ] Admin password changed from default
- [ ] Test complete sale workflow
- [ ] Test PDF generation
- [ ] Monitor error logs for 24 hours
