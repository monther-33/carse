#!/usr/bin/env bash
#
# Updates an installed server to the latest code. Run as root:
#
#   sudo bash /var/www/cars/deploy/update.sh
#
# Maintenance mode → backup → pull → build → migrate → seed new permissions/settings → live.
# If any step fails the system stays in maintenance mode (safer for the books): fix the cause,
# run the script again, or restore the backup taken at the start (docs/DEPLOYMENT.md §5).
#
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/cars}"
BRANCH="${BRANCH:-main}"

step() { printf '\n\033[1;34m==> %s\033[0m\n' "$1"; }
as_web() { sudo -u www-data -H "$@"; }

[ "$(id -u)" -eq 0 ] || { echo "Run as root (sudo)." >&2; exit 1; }
cd "$APP_DIR"
trap 'printf "\n\033[1;31mUpdate failed — the system is still in maintenance mode.\033[0m Fix the error above and run the script again, or restore the backup.\n" >&2' ERR

step "Maintenance mode"
as_web php artisan down --retry=60

step "Backup before updating"
as_web php artisan backup:run --disable-notifications

step "New code ($BRANCH)"
git fetch --quiet origin "$BRANCH"
git reset --hard "origin/$BRANCH"

step "Build"
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction
npm ci --no-audit --no-fund
npm run build
chown -R www-data:www-data storage bootstrap/cache

step "Database"
as_web php artisan migrate --force
as_web php artisan db:seed --force

step "Caches and workers"
as_web php artisan optimize
as_web php artisan queue:restart

as_web php artisan up
printf '\n\033[1;32mUpdated to %s.\033[0m\n' "$(git log -1 --format='%h %s')"
