
set -e


#!/usr/bin/env sh
set -e

mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache


chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
	composer install --no-dev --no-interaction --optimize-autoloader || true
fi

exec "$@"

set -e


if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
	composer install --no-dev --no-interaction --optimize-autoloader || true
fi

exec "$@"


