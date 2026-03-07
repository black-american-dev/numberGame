#!/bin/bash

echo "Starting Laravel..."

php artisan config:clear
php artisan config:cache

echo "Running migrations..."
php artisan migrate --force || true

echo "Starting Apache..."
apache2-foreground