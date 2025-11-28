#!/usr/bin/env sh
set -e

# Instala dependências se necessário (primeiro container run)
if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
	composer install --no-dev --no-interaction --optimize-autoloader || true
fi

exec "$@"


