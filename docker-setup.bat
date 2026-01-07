@echo off
REM Docker Development Setup Script for BangunanPro (Windows)

echo Starting BangunanPro Docker Setup...

REM Step 1: Stop and remove old containers
echo.
echo Cleaning up old containers...
docker-compose down -v

REM Step 2: Rebuild containers
echo.
echo Building Docker images...
docker-compose build --no-cache

REM Step 3: Start containers
echo.
echo Starting containers...
docker-compose up -d

REM Step 4: Wait for MySQL
echo.
echo Waiting for MySQL to initialize (30 seconds)...
timeout /t 30 /nobreak >nul

REM Step 5: Install Composer dependencies
echo.
echo Installing Composer dependencies...
docker-compose exec -T app composer install --no-interaction

REM Step 6: Generate app key
echo.
echo Generating application key...
docker-compose exec -T app php artisan key:generate

REM Step 7: Run migrations
echo.
echo Running database migrations...
docker-compose exec -T app php artisan migrate:fresh --seed --force

REM Step 8: Install NPM dependencies
echo.
echo Installing NPM dependencies...
docker-compose exec -T app npm install

REM Step 9: Build assets
echo.
echo Building frontend assets...
docker-compose exec -T app npm run build

REM Step 10: Set permissions
echo.
echo Setting permissions...
docker-compose exec -T app chmod -R 775 storage bootstrap/cache
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

REM Step 11: Cache config
echo.
echo Caching configuration...
docker-compose exec -T app php artisan config:cache

echo.
echo Setup complete!
echo.
echo Access your application at: http://localhost:8000
echo.
echo Default admin login:
echo   Email: admin@bangunanpro.com
echo   Password: password
echo.
echo View logs: docker-compose logs -f
echo Stop containers: docker-compose down

pause
