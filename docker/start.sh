#!/bin/bash
set -e

# Load .env file
export $(grep -v '^#' /var/www/html/.env | xargs)

echo "Waiting for MySQL to be ready..."
until mysqladmin ping -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent 2>/dev/null; do
  echo "MySQL not ready, retrying in 3s..."
  sleep 3
done

echo "MySQL is up! Running migrations..."
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
exec apache2-foreground
