#!/bin/sh
set -e

# Ensure storage directories exist
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear old cache
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Ensure APP_KEY exists
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is empty. Generating key..."
    php artisan key:generate --force || true
fi

# Ensure storage link exists
php artisan storage:link || true

# Run database migrations with force in production
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations and seeders..."
    php artisan migrate --force --seed || true
fi

# Start Supervisor (runs PHP-FPM + Nginx)
exec /usr/bin/supervisord -c /etc/supervisord.conf
