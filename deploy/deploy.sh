#!/usr/bin/env bash
set -euo pipefail

WORK_TREE="/var/www/kyrex.de"
LOG_DIR="/home/sven/deploy-logs"
LOG_FILE="$LOG_DIR/kyrex.de-deploy.log"

mkdir -p "$LOG_DIR"
touch "$LOG_FILE"
exec > >(tee -a "$LOG_FILE") 2>&1

echo ""
echo "==== $(date '+%Y-%m-%d %H:%M:%S') | Deploy started ===="
trap 'echo "[ERROR] Deploy failed (Line $LINENO)"' ERR

cd "$WORK_TREE"

echo "Installing PHP dependencies..."
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

echo "Building Laravel caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Setting permissions..."
chmod -R ug+rwX storage bootstrap/cache || true

echo "==== $(date '+%Y-%m-%d %H:%M:%S') | Deploy finished ===="