#!/bin/bash
set -e

APP_DIR="/var/www/html"
ENV_FILE="$APP_DIR/.env"

echo "Initializing NewsAggregator Install"

echo "Set directory permissions"
chown -R www:www-data storage/
chown -R www:www-data bootstrap/
chmod -R 775 storage/
chmod -R 755 bootstrap/

echo "Installing dependencies"
composer install

echo "Running migrations"
php artisan migrate

# Ensure APP_KEY exists
if ! grep -qE '^APP_KEY=.+$' "$ENV_FILE"; then
  php artisan key:generate --ansi --force
fi

echo "Completed NewsAggregator setup"

exec "$@"