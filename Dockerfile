# Используем официальный PHP с Apache (удобнее для веба)
FROM php:8.2-apache

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
    && docker-php-ext-install pdo pdo_sqlite mbstring tokenizer bcmath ctype

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем проект внутрь контейнера
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Настраиваем Apache для Laravel
RUN a2enmod rewrite
COPY ./public /var/www/html/public

# Открываем порт
EXPOSE 10000

# Запускаем Laravel через встроенный сервер PHP (Render не требует Apache start)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
