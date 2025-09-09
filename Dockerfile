# Используем официальный PHP с нужными расширениями
FROM php:8.2-cli

# Устанавливаем system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring bcmath

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем проект внутрь контейнера
WORKDIR /app
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --optimize-autoloader


# Генерируем ключ приложения (если надо)
RUN php artisan key:generate --force

# Открываем порт
EXPOSE 10000

# Запускаем Laravel сервер
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
