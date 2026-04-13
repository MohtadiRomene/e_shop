#!/usr/bin/env sh
set -e

APP_ENV="${APP_ENV:-prod}"
APP_DEBUG="${APP_DEBUG:-0}"

if [ -z "${APP_SECRET}" ]; then
  echo "APP_SECRET is missing."
  exit 1
fi

if [ -z "${DATABASE_URL}" ]; then
  echo "DATABASE_URL is missing."
  exit 1
fi

mkdir -p var/cache var/log
chown -R www-data:www-data var

if [ "${APP_DEBUG}" = "1" ]; then
  php bin/console cache:clear \
    --env="${APP_ENV}" \
    --no-interaction
else
  php bin/console cache:clear \
    --env="${APP_ENV}" \
    --no-debug \
    --no-interaction
fi

if [ "${RUN_MIGRATIONS:-0}" = "1" ]; then
  if php bin/console doctrine:migrations:migrate \
    --no-interaction \
    --allow-no-migration \
    --env="${APP_ENV}"; then
    echo "Migrations applied successfully."
  elif [ "${MIGRATIONS_STRICT:-0}" = "1" ]; then
    echo "Migrations failed and MIGRATIONS_STRICT=1. Exiting."
    exit 1
  else
    echo "Migrations failed but container startup continues."
  fi
fi

if [ "${ENSURE_DEFAULT_ADMIN:-0}" = "1" ]; then
  php bin/console app:ensure-default-admin \
    --no-interaction \
    --env="${APP_ENV}" || true
fi

# Ensure runtime cache/log dirs remain writable by php-fpm worker user.
chown -R www-data:www-data var

exec "$@"
