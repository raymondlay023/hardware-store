#!/bin/bash
# Environment Variable Validation Script for BangunanPro

set -e

echo "🔍 Validating environment variables..."

# Required variables for all environments
REQUIRED_VARS=(
    "APP_NAME"
    "APP_ENV"
    "APP_KEY"
    "APP_URL"
    "DB_CONNECTION"
    "DB_HOST"
    "DB_PORT"
    "DB_DATABASE"
    "DB_USERNAME"
    "DB_PASSWORD"
)

# Additional required vars for production
if [ "$APP_ENV" = "production" ]; then
    REQUIRED_VARS+=(
        "MAIL_MAILER"
        "MAIL_HOST"
        "REDIS_HOST"
    )
fi

# Track validation errors
ERRORS=0

# Check each required variable
for var in "${REQUIRED_VARS[@]}"; do
    if [ -z "${!var}" ]; then
        echo "❌ Missing required variable: $var"
        ERRORS=$((ERRORS + 1))
    else
        echo "✅ $var is set"
    fi
done

# Validate APP_KEY format
if [[ ! "$APP_KEY" =~ ^base64: ]]; then
    echo "⚠️  WARNING: APP_KEY should start with 'base64:'"
    echo "   Run: php artisan key:generate"
    ERRORS=$((ERRORS + 1))
fi

# Validate APP_DEBUG in production
if [ "$APP_ENV" = "production" ] && [ "$APP_DEBUG" = "true" ]; then
    echo "❌ CRITICAL: APP_DEBUG must be 'false' in production!"
    ERRORS=$((ERRORS + 1))
fi

# Validate database connection
echo ""
echo "🔍 Testing database connection..."
if command -v mysql &> /dev/null; then
    if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" &> /dev/null; then
        echo "✅ Database connection successful"
    else
        echo "❌ Cannot connect to database"
        ERRORS=$((ERRORS + 1))
    fi
else
    echo "⚠️  MySQL client not found, skipping database test"
fi

# Validate Redis connection (if configured)
if [ -n "$REDIS_HOST" ]; then
    echo ""
    echo "🔍 Testing Redis connection..."
    if command -v redis-cli &> /dev/null; then
        if [ -n "$REDIS_PASSWORD" ]; then
            if redis-cli -h "$REDIS_HOST" -p "${REDIS_PORT:-6379}" -a "$REDIS_PASSWORD" ping &> /dev/null; then
                echo "✅ Redis connection successful"
            else
                echo "❌ Cannot connect to Redis"
                ERRORS=$((ERRORS + 1))
            fi
        else
            echo "⚠️  WARNING: Redis has no password configured"
        fi
    else
        echo "⚠️  Redis CLI not found, skipping Redis test"
    fi
fi

# Final result
echo ""
echo "================================"
if [ $ERRORS -eq 0 ]; then
    echo "✅ All validations passed!"
    exit 0
else
    echo "❌ Found $ERRORS error(s)"
    echo "Please fix the issues above before deploying"
    exit 1
fi
