@echo off
echo ========================================
echo BangunanPro Docker - Quick Start
echo ========================================
echo.

REM Stop XAMPP if running
echo [1/6] Checking XAMPP...
taskkill /F /IM mysql.exe 2>nul
taskkill /F /IM apache.exe 2>nul
echo XAMPP services stopped (if they were running)
echo.

REM Clean old containers
echo [2/6] Cleaning old Docker containers...
docker-compose down -v
echo.

REM Build images
echo [3/6] Building Docker images (this may take 5-10 minutes first time)...
docker-compose build
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERROR: Docker build failed!
    echo Check if Docker Desktop is running
    pause
    exit /b 1
)
echo.

REM Start services
echo [4/6] Starting Docker services...
docker-compose up -d
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERROR: Failed to start services!
    echo Run 'docker-compose logs' to see errors
    pause
    exit /b 1
)
echo.

REM Wait for MySQL
echo [5/6] Waiting for MySQL to be ready (30 seconds)...
timeout /t 30 /nobreak
echo.

REM Initialize database
echo [6/6] Setting up database...
docker-compose exec -T app php artisan migrate:fresh --seed --force
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo WARNING: Migration failed! Trying without seed...
    docker-compose exec -T app php artisan migrate --force
)
echo.

REM Clear cache
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
echo.

echo ========================================
echo    Setup Complete!
echo ========================================
echo.
echo Application is running at: http://localhost:8000
echo.
echo Default admin login:
echo   Email: admin@bangunanpro.com
echo   Password: password
echo.
echo Useful commands:
echo   docker-compose ps          - Check status
echo   docker-compose logs -f     - View logs
echo   docker-compose down        - Stop services
echo   docker-compose restart     - Restart services
echo.
echo Press any key to exit...
pause >nul
