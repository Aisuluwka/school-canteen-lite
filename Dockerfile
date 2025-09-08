# Используем официальный PHP с Composer
FROM php:8.2-cli

# Устанавливаем system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring tokenizer bcmath ctype

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /app

# Копируем проект внутрь контейнера
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Генерируем ключ приложения (если не задан)
RUN php artisan key:generate || true

# Запускаем Laravel сервер
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
