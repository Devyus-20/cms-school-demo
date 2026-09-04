#!/bin/sh
set -e

# Cache configuration & routes if in production
if [ "$APP_ENV" = "production" ]; then
    echo "Running production optimization..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
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
