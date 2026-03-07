# ---------- Stage 1 ----------
FROM node:20-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build


# ---------- Stage 2 ----------
FROM php:8.4-apache-bookworm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd opcache

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN php artisan package:discover --ansi

COPY --from=node_builder /app/public/build /var/www/html/public/build

COPY docker/php.ini /usr/local/etc/php/conf.d/opcache.ini

RUN chown -R www-data:www-data storage bootstrap/cache

RUN a2enmod rewrite deflate
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]