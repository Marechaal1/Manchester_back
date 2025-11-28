#!/bin/sh
set -e

mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

# Instalar dependências se necessário
if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
	composer install --no-dev --no-interaction --optimize-autoloader || true
fi

# Garantir .env e APP_KEY
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
	cp .env.example .env || true
fi

if [ -f ".env" ]; then
	# Gera APP_KEY se ausente
	if ! grep -q '^APP_KEY=base64:' .env; then
		php artisan key:generate --force || true
	fi
	# Atualiza cache de configuração
	php artisan config:clear || true
	php artisan config:cache || true
fi

exec "$@"


