#!/bin/sh
# Laravel Application Health Check Script

set -e

# Check if PHP is working
php -v > /dev/null 2>&1 || exit 1

# Check if Laravel can boot (artisan commands work)
php /var/www/html/artisan --version > /dev/null 2>&1 || exit 1

# Check if database is accessible
php /var/www/html/artisan db:show > /dev/null 2>&1 || exit 1

# Check if cache is working (Redis connectivity)
php -r "
    require '/var/www/html/vendor/autoload.php';
    \$app = require_once '/var/www/html/bootstrap/app.php';
    \$cache = \$app->make('cache');
    \$cache->store('redis')->put('healthcheck', 'ok', 10);
    exit(\$cache->store('redis')->get('healthcheck') === 'ok' ? 0 : 1);
" || exit 1

echo "All health checks passed"
exit 0
