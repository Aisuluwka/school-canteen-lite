FROM php:8.2-cli

# Устанавливаем системные зависимости и PHP-расширения
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring bcmath gd

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /app

# Копируем проект
COPY . .

# Устанавливаем зависимости Laravel (все, без no-dev, чтобы не ломалось)
RUN composer install --optimize-autoloader --ignore-platform-reqs

# Генерируем ключ (если вдруг APP_KEY пустой)
RUN php artisan key:generate || true

# Открываем порт
EXPOSE 10000

# Запуск Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
