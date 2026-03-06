#!/bin/bash

echo "Waiting for MySQL to be ready..."

while ! (echo > /dev/tcp/db/3306) 2>/dev/null; do
    echo "MySQL not ready yet, retrying in 2 seconds..."
    sleep 2
done

echo "MySQL is ready! Waiting 3 more seconds for full init..."
sleep 3

echo "Running migrations..."
php artisan migrate --force

echo "Starting Apache..."
apache2-foreground