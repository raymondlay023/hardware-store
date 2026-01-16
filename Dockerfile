# Multi-stage build for production
FROM php:8.2-fpm-alpine AS base

# Install build-time dependencies
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    oniguruma-dev

# Install runtime dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng \
    libzip \
    zlib \
    freetype \
    libjpeg-turbo \
    libwebp \
    libxml2 \
    oniguruma \
    zip \
    unzip \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        zip \
        gd \
        bcmath \
        mbstring \
        xml \
        dom \
        exif \
        opcache

# Install Redis extension via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# Remove build dependencies
RUN apk del .build-deps

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy dependency files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application files
COPY . .

# Run post-install scripts
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js and build assets
FROM node:20-alpine AS assets
WORKDIR /var/www/html
COPY package*.json ./
RUN npm ci --production
COPY . .
RUN npm run build

# Final production image
FROM php:8.2-fpm-alpine

# Install ONLY runtime dependencies (no -dev packages)
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng \
    libzip \
    zlib \
    freetype \
    libjpeg-turbo \
    libwebp \
    libxml2 \
    oniguruma \
    curl \
    && rm -rf /var/cache/apk/*

# Install build deps temporarily for PHP extensions
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        zip \
        gd \
        bcmath \
        mbstring \
        xml \
        dom \
        exif \
        opcache

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && rm -rf /tmp/pear

WORKDIR /var/www/html

# Copy production PHP configuration
COPY docker/php/production.ini /usr/local/etc/php/conf.d/production.ini

# Copy application from base stage
COPY --from=base /var/www/html /var/www/html

# Copy built assets from assets stage
COPY --from=assets /var/www/html/public/build /var/www/html/public/build

# Copy nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create necessary directories
RUN mkdir -p /var/log/supervisor /var/log/nginx /var/log/php-fpm

# Set permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/log/supervisor \
    /var/log/nginx \
    /var/log/php-fpm \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port
EXPOSE 80

# Start supervisor (runs as root, but services run as www-data)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
