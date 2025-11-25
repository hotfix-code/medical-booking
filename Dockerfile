# ============================
# Stage 1: Build dependencies
# - Uses the official Composer image to install PHP dependencies.
# - No application code is built or executed here.
# ============================
FROM composer:2.7 AS builder

WORKDIR /app

COPY composer.json composer.lock ./

# Skip GD in the builder stage (it is installed in the runtime image)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-req=ext-gd


# ============================
# Stage 2: Build frontend assets
# - Uses a lightweight Node.js Alpine image to compile frontend assets.
# - Runs npm build and outputs to public/assets/build.
# ============================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files for dependency installation
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm ci --only=production=false

# Copy necessary files for the build process
COPY vite.config.js postcss.config.js tailwind.config.js tsconfig.json ./
COPY resources ./resources
COPY public ./public

# Run the build command to compile assets
RUN npm run build


# ============================
# Stage 3: Production image with FrankenPHP
# - Uses the FrankenPHP Alpine image to run the app in production.
# ============================
FROM dunglas/frankenphp:1-php8.4-alpine

# Default document root is handled by the Caddyfile; keep app in /app
WORKDIR /app

# Install required PHP extensions
# FrankenPHP already ships several extensions; install the missing ones
RUN install-php-extensions \
    gd \
    bcmath \
    opcache \
    pdo_mysql \
    pdo_pgsql \
    zip

# PHP production settings (OPcache)
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.interned_strings_buffer=16" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# Copy vendor dependencies built in the builder stage
COPY --from=builder /app/vendor ./vendor

# Copy compiled frontend assets from the node-builder stage
COPY --from=node-builder /app/public/build ./public/build

# Copy the rest of the application code
COPY . .

# Copy the Caddyfile to configure Caddy/FrankenPHP and the web root
COPY Caddyfile /etc/caddy/Caddyfile

# Set correct permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Expose only internal port 80 (Traefik handles 443)
EXPOSE 80

# Health check so Traefik knows when the container is ready
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://localhost/ || exit 1

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
