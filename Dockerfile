# =========================================================
# STAGE 1: Build CSS và JavaScript bằng Vite
# =========================================================
FROM node:22-alpine AS frontend

WORKDIR /app

# Cài dependency frontend trước để tận dụng Docker cache
COPY package.json package-lock.json ./

RUN npm ci

# Sao chép mã nguồn và build Vite
COPY . .

# Xóa file hot nếu từng chạy npm run dev
RUN rm -f public/hot \
    && npm run build


# =========================================================
# STAGE 2: Laravel chạy bằng PHP 8.3 + Apache
# =========================================================
FROM php:8.3-apache

# Cài thư viện hệ thống và PHP extensions Laravel cần
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        ca-certificates \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mysqli \
        mbstring \
        zip \
        gd \
        intl \
        bcmath \
        opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Lấy Composer từ image chính thức
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Sao chép mã nguồn Laravel
COPY . .

# Cài PHP dependencies cho production
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress

# Sao chép CSS/JS đã build từ stage frontend
COPY --from=frontend /app/public/build /var/www/html/public/build

# Xóa Vite hot file và cấp quyền đọc asset
RUN rm -f public/hot \
    && chmod -R 755 public/build

# Tạo các thư mục Laravel cần ghi dữ liệu
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Tạo liên kết public/storage
RUN rm -rf public/storage \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/storage

# Cấu hình Apache chạy từ thư mục public của Laravel
RUN sed -i \
    's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

# Cho phép Laravel sử dụng file .htaccess
RUN printf '%s\n' \
    '<Directory /var/www/html/public>' \
    '    Options Indexes FollowSymLinks' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    >> /etc/apache2/apache2.conf

# Tránh cảnh báo ServerName của Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]
