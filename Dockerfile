FROM nixpacks/php

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    php8.2-mysql \
    php8.2-pdo \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-bcmath \
    php8.2-curl \
    php8.2-zip \
    php8.2-gd \
    && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY . /app

# Set working directory
WORKDIR /app

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate key and link storage
RUN php artisan key:generate --force
RUN php artisan storage:link --force

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
