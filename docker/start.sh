#!/bin/bash

echo "Optimizing Laravel..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force || true

echo "Starting Apache..."
apache2-foreground