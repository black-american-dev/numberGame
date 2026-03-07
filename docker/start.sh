#!/bin/bash

echo "Waiting for external MySQL..."

until mysql \
  -h"$DB_HOST" \
  -P"$DB_PORT" \
  -u"$DB_USERNAME" \
  -p"$DB_PASSWORD" \
  -e "SELECT 1" "$DB_DATABASE" >/dev/null 2>&1
do
  echo "MySQL not ready yet, retrying..."
  sleep 3
done

echo "Database connection successful."

echo "Running migrations..."
php artisan migrate --force || true

echo "Starting Apache..."
apache2-foreground