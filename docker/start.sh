#!/bin/bash

echo "Waiting for MySQL..."

until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "MySQL not ready yet, retrying..."
    sleep 2
done

echo "MySQL is ready!"

php artisan migrate --force

apache2-foreground