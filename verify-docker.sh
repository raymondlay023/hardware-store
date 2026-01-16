#!/bin/bash
# Docker Setup Verification Script

echo "🔍 Verifying Docker Configuration..."
echo ""

# Check if Docker is installed (on server)
if command -v docker &> /dev/null; then
    echo "✅ Docker is installed"
    docker --version
else
    echo "❌ Docker is not installed (normal if running locally)"
fi

echo ""
echo "📋 Checking project files..."

# Check Dockerfiles
if [ -f "Dockerfile" ]; then
    echo "✅ Dockerfile exists"
    echo "   Checking for required extensions..."
    if grep -q "mbstring" Dockerfile && grep -q "xml" Dockerfile && grep -q "dom" Dockerfile; then
        echo "   ✅ All required PHP extensions present"
    else
        echo "   ❌ Missing PHP extensions"
    fi
else
    echo "❌ Dockerfile not found"
fi

if [ -f "Dockerfile.dev" ]; then
    echo "✅ Dockerfile.dev exists"
else
    echo "❌ Dockerfile.dev not found"
fi

if [ -f "docker-compose.yml" ]; then
    echo "✅ docker-compose.yml exists"
    if grep -q "service_healthy" docker-compose.yml; then
        echo "   ✅ MySQL healthcheck configured"
    else
        echo "   ❌ MySQL healthcheck missing"
    fi
else
    echo "❌ docker-compose.yml not found"
fi

if [ -f ".dockerignore" ]; then
    echo "✅ .dockerignore exists"
else
    echo "⚠️  .dockerignore not found"
fi

echo ""
echo "🐳 Docker configuration files verified!"
echo ""
echo "Next steps:"
echo "1. Test locally: docker-compose build"
echo "2. Run locally: docker-compose up -d"
echo "3. Check extensions: docker-compose exec app php -m"
echo "4. Deploy to server when ready"
