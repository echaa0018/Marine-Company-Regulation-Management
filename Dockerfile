FROM dunglas/frankenphp:1.4.4-php8.4-bookworm

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    git curl libpq-dev libmcrypt-dev zip unzip libjpeg-dev libpng-dev libzip-dev \
    libxml2-dev libonig-dev libcurl4-openssl-dev libldap2-dev && \
    ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install gd pgsql opcache pdo pdo_pgsql zip xml mbstring curl ldap

# Set working directory
WORKDIR /app

# Copy source code
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --optimize-autoloader

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash

# Add Bun to PATH (untuk shell non-interaktif)
ENV PATH="/root/.bun/bin:$PATH"

# Install frontend dependencies & build
RUN bun install && bun run build

# PHP ini tweak
RUN echo "variables_order = \"EGPCS\"" >> $PHP_INI_DIR/conf.d/990-php.ini

# Set permissions
RUN chmod -R 777 storage bootstrap/cache