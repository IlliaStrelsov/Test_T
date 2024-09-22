FROM php:8.1-fpm

# Встановлюємо системні залежності та PHP-розширення
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Встановлюємо робочу директорію
WORKDIR /var/www

# Копіюємо вміст проекту
COPY . .

# Встановлюємо права доступу
RUN chown -R www-data:www-data /var/www

# Відкриваємо порт 9000 та запускаємо PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
