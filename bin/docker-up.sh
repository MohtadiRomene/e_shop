#!/usr/bin/env sh
set -eu

ROOT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)"
cd "$ROOT_DIR"

if [ ! -f .env.container ]; then
  cp env.container.example .env.container
  echo "Created .env.container from env.container.example"
  echo "Edit .env.container and update APP_SECRET / passwords if needed."
fi

docker compose --env-file .env.container -f compose.container.yaml up -d --build
echo "Done. Open: http://localhost:8080"
