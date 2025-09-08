# Используем официальный PHP с расширениями
FROM php:8.2-cli

# Устанавливаем system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем проект внутрь контейнера
WORKDIR /app
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Запускаем Laravel сервер
CMD php artisan serve --host=0.0.0.0 --port=10000
