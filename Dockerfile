# Build Stage
FROM php:8.2-fpm-alpine AS build

# Install dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    mysql-client \
    && docker-php-ext-install pdo_mysql zip

WORKDIR /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application source
COPY . .

# Set app URL to avoid composer script error
ENV APP_URL=http://localhost
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP packages (no dev)
RUN composer install --no-dev --optimize-autoloader

# Runtime Stage
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    bash \
    libzip-dev \
    oniguruma-dev \
    mysql-client \
    && docker-php-ext-install pdo_mysql zip

WORKDIR /var/www

# Copy app and composer from build
COPY --from=build /var/www /var/www
COPY --from=build /usr/bin/composer /usr/bin/composer

# Expose port Laravel will run on
EXPOSE 8000

# Serve Laravel app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000

