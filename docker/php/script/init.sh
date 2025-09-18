#!/bin/bash

echo "Initializing ESM Install"


echo "Set directory permissions"
chown -R www:www-data storage/
chown -R www:www-data bootstrap/
chmod -R 775 storage/
chmod -R 755 bootstrap/

echo "Installing dependencies"
composer install

echo "Running migrations"
php artisan migrate
php artisan key:generate

echo "Running Permission seeder"
#php artisan db:seed --class=RolePermissionSeeder

echo "Set symlinks"
php artisan storage:link

echo "Completed ESM setup"

