# ---------- Stage 1: Build frontend (Vite + Tailwind) ----------
FROM node:20 AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build


# ---------- Stage 2: PHP + Apache ----------
FROM php:8.4-apache

# Install extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Fix git ownership warning
RUN git config --global --add safe.directory /var/www/html

WORKDIR /var/www/html

# Copy Laravel project
COPY . .

# Copy built assets from node stage
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Copy docker env
#COPY docker/.env.docker /var/www/html/.env

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache config
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]