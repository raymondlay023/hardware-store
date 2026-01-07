#!/bin/sh
# Docker Development Setup Script for BangunanPro

echo "🚀 Starting BangunanPro Docker Setup..."

# Step 1: Stop and remove old containers
echo "\n📦 Cleaning up old containers..."
docker-compose down -v

# Step 2: Rebuild containers
echo "\n🔨 Building Docker images..."
docker-compose build --no-cache

# Step 3: Start containers
echo "\n▶️  Starting containers..."
docker-compose up -d

# Step 4: Wait for MySQL to be ready
echo "\n⏳ Waiting for MySQL to initialize (30 seconds)..."
sleep 30

# Step 5: Install Composer dependencies
echo "\n📚 Installing Composer dependencies..."
docker-compose exec -T app composer install --no-interaction

# Step 6: Generate app key
echo "\n🔑 Generating application key..."
docker-compose exec -T app php artisan key:generate

# Step 7: Run migrations
echo "\n🗄️  Running database migrations..."
docker-compose exec -T app php artisan migrate:fresh --seed --force

# Step 8: Install NPM dependencies
echo "\n📦 Installing NPM dependencies..."
docker-compose exec -T app npm install

# Step 9: Build assets
echo "\n🎨 Building frontend assets..."
docker-compose exec -T app npm run build

# Step 10: Set permissions
echo "\n🔐 Setting permissions..."
docker-compose exec -T app chmod -R 775 storage bootstrap/cache
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

# Step 11: Cache config
echo "\n💾 Caching configuration..."
docker-compose exec -T app php artisan config:cache

echo "\n✅ Setup complete!"
echo "\n🌐 Access your application at: http://localhost:8000"
echo "\n👤 Default admin login:"
echo "   Email: admin@bangunanpro.com"
echo "   Password: password"
echo "\n📊 View logs: docker-compose logs -f"
echo "🛑 Stop containers: docker-compose down"
