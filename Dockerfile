# Multi-stage build for production
FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql zip gd bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js and build assets
FROM node:20-alpine AS assets
WORKDIR /var/www/html
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Final production image
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng \
    libzip \
    zlib \
    freetype \
    libjpeg-turbo \
    libwebp

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd bcmath

WORKDIR /var/www/html

# Copy application from base stage
COPY --from=base /var/www/html /var/www/html

# Copy built assets from assets stage
COPY --from=assets /var/www/html/public/build /var/www/html/public/build

# Copy nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
