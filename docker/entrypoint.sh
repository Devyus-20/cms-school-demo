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

# Ensure storage link exists
php artisan storage:link || true

# Run database migrations with force in production
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations and seeders..."
    php artisan migrate --force --seed || true
fi

# Optimize cache in production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing application cache..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Start Supervisor (runs PHP-FPM + Nginx)
exec /usr/bin/supervisord -c /etc/supervisord.conf
