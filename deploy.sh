#!/bin/bash

# Deploy Script via Otterwise & Laravel Best Practices

echo "🚀 Starting deployment..."

# 1. Enable Maintenance Mode
php artisan down || true

# 2. Pull latest code
git pull origin main

# 3. Install Dependencies
composer install --no-dev --optimize-autoloader
npm ci

# 4. Build Assets
npm run build

# 5. Run Migrations
php artisan migrate --force

# 6. Clear & Cache Configs
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# 7. Restart Queue Workers
php artisan queue:restart

# 8. Disable Maintenance Mode
php artisan up

echo "✅ Deployment complete!"
