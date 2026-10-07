#!/usr/bin/env bash
#
# First-time install of the showroom system on a fresh Ubuntu 22.04 / 24.04 server.
# Run as root:
#
#   sudo DOMAIN=cars.example.ly EMAIL=owner@example.ly bash deploy/install.sh
#
# Installs PHP 8.2 (FPM), Nginx, MariaDB, Composer and Node 20; creates the database and its
# user; clones the code; writes .env; builds; migrates and seeds the base data only (never the
# demo); sets up Nginx (+ HTTPS with certbot when EMAIL is given), the scheduler (cron), the
# queue worker and the backup folder. Passwords not given are generated and saved to
# /root/cars-credentials.txt.
#
# Settings (environment variables):
#   DOMAIN              required: the site's domain name, pointed at this server
#   EMAIL               optional: for the HTTPS certificate (empty = HTTP only; HTTPS is needed to install the app on phones)
#   REPO_URL, BRANCH    default: https://github.com/monther-33/carse.git, main
#   APP_DIR             default: /var/www/cars
#   DB_NAME, DB_USER    default: cars, cars
#   DB_PASS, ADMIN_PASSWORD, DEVELOPER_PASSWORD   default: generated
#   BACKUP_PATH         default: /var/backups/cars (better on another disk)
#   APP_NAME            default: معرض السيارات
#
set -euo pipefail

: "${DOMAIN:?Set DOMAIN, e.g. sudo DOMAIN=cars.example.ly bash deploy/install.sh}"
EMAIL="${EMAIL:-}"
REPO_URL="${REPO_URL:-https://github.com/monther-33/carse.git}"
BRANCH="${BRANCH:-main}"
APP_DIR="${APP_DIR:-/var/www/cars}"
APP_NAME="${APP_NAME:-معرض السيارات}"
DB_NAME="${DB_NAME:-cars}"
DB_USER="${DB_USER:-cars}"
BACKUP_PATH="${BACKUP_PATH:-/var/backups/cars}"
PHP_VERSION=8.2

random_password() { openssl rand -base64 24 | tr -d '/+=' | cut -c1-20; }
DB_PASS="${DB_PASS:-$(random_password)}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-$(random_password)}"
DEVELOPER_PASSWORD="${DEVELOPER_PASSWORD:-$(random_password)}"

step() { printf '\n\033[1;34m==> %s\033[0m\n' "$1"; }
as_web() { sudo -u www-data -H "$@"; }

[ "$(id -u)" -eq 0 ] || { echo "Run as root (sudo)." >&2; exit 1; }
if [ -f "$APP_DIR/.env" ]; then
    echo "$APP_DIR/.env already exists: this server is installed. Use deploy/update.sh to update." >&2
    exit 1
fi

step "System packages (PHP $PHP_VERSION, Nginx, MariaDB)"
export DEBIAN_FRONTEND=noninteractive LC_ALL=C.UTF-8
apt-get update -q
apt-get install -yq software-properties-common ca-certificates curl gnupg unzip git openssl
add-apt-repository -y ppa:ondrej/php
apt-get update -q
apt-get install -yq nginx mariadb-server mariadb-client \
    "php$PHP_VERSION-fpm" "php$PHP_VERSION-cli" "php$PHP_VERSION-mysql" "php$PHP_VERSION-mbstring" \
    "php$PHP_VERSION-gd" "php$PHP_VERSION-zip" "php$PHP_VERSION-intl" "php$PHP_VERSION-bcmath" \
    "php$PHP_VERSION-xml" "php$PHP_VERSION-curl"
update-alternatives --set php "/usr/bin/php$PHP_VERSION"

step "Node.js 20"
if ! command -v node >/dev/null || [ "$(node -v | cut -d. -f1 | tr -d v)" -lt 20 ]; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -yq nodejs
fi

step "Composer"
if ! command -v composer >/dev/null; then
    expected="$(curl -fsSL https://composer.github.io/installer.sig)"
    curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
    actual="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
    [ "$expected" = "$actual" ] || { echo "Composer installer checksum mismatch." >&2; exit 1; }
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet
    rm -f /tmp/composer-setup.php
fi

step "PHP settings (uploads, memory, time zone)"
for sapi in fpm cli; do
    cat > "/etc/php/$PHP_VERSION/$sapi/conf.d/99-cars.ini" <<'INI'
upload_max_filesize = 20M
post_max_size = 25M
memory_limit = 256M
max_execution_time = 300
date.timezone = Africa/Tripoli
INI
done
systemctl restart "php$PHP_VERSION-fpm"

step "Database $DB_NAME"
systemctl enable --now mariadb
mysql <<SQL
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
ALTER USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
SQL

step "Code ($REPO_URL, $BRANCH)"
if [ ! -d "$APP_DIR/.git" ]; then
    mkdir -p "$(dirname "$APP_DIR")"
    git clone --branch "$BRANCH" "$REPO_URL" "$APP_DIR"
fi
cd "$APP_DIR"

SCHEME=http
[ -n "$EMAIL" ] && SCHEME=https

step ".env"
cat > .env <<ENV
APP_NAME="$APP_NAME"
APP_ENV=production
APP_KEY=base64:$(openssl rand -base64 32)
APP_DEBUG=false
APP_URL=$SCHEME://$DOMAIN
APP_TIMEZONE=Africa/Tripoli
APP_LOCALE=ar
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=$([ "$SCHEME" = https ] && echo true || echo false)
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
MAIL_MAILER=log

DB_DUMP_PATH=
BACKUP_NAME=cars
BACKUP_PATH=$BACKUP_PATH
BACKUP_MAIL_TO=

ADMIN_PASSWORD=$ADMIN_PASSWORD
DEVELOPER_PASSWORD=$DEVELOPER_PASSWORD
ENV
chown root:www-data .env
chmod 640 .env

step "Build (Composer, front end)"
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction
npm ci --no-audit --no-fund
npm run build

step "Folders and permissions"
mkdir -p "$BACKUP_PATH"
chown -R www-data:www-data storage bootstrap/cache "$BACKUP_PATH"
chmod -R ug+rwX storage bootstrap/cache

step "Database tables and base data (no demo data)"
as_web php artisan migrate --force
as_web php artisan db:seed --force
php artisan storage:link
chown -R www-data:www-data storage bootstrap/cache   # anything the root commands above wrote
as_web php artisan optimize

step "Nginx"
PHP_SOCK="/run/php/php$PHP_VERSION-fpm.sock"
sed -e "s|__DOMAIN__|$DOMAIN|g" -e "s|__ROOT__|$APP_DIR/public|g" -e "s|__PHP_SOCK__|$PHP_SOCK|g" \
    deploy/nginx.conf > /etc/nginx/sites-available/cars
ln -sf /etc/nginx/sites-available/cars /etc/nginx/sites-enabled/cars
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx

if [ -n "$EMAIL" ]; then
    step "HTTPS certificate"
    apt-get install -yq certbot python3-certbot-nginx
    certbot --nginx -d "$DOMAIN" -m "$EMAIL" --agree-tos --redirect -n
fi

step "Scheduler (cron) and queue worker"
cat > /etc/cron.d/cars <<CRON
* * * * * www-data cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1
CRON
chmod 644 /etc/cron.d/cars
sed -e "s|__APP_DIR__|$APP_DIR|g" deploy/cars-queue.service > /etc/systemd/system/cars-queue.service
systemctl daemon-reload
systemctl enable --now cars-queue

step "Credentials"
cat > /root/cars-credentials.txt <<CRED
Showroom system — $SCHEME://$DOMAIN
Installed: $(date '+%Y-%m-%d %H:%M')

Admin:      username admin      password $ADMIN_PASSWORD
Developer:  username developer  password $DEVELOPER_PASSWORD
Database:   $DB_NAME  user $DB_USER  password $DB_PASS

Change the admin and developer passwords after the first sign-in.
CRED
chmod 600 /root/cars-credentials.txt

printf '\n\033[1;32mDone.\033[0m Open %s://%s\n' "$SCHEME" "$DOMAIN"
printf 'Passwords: /root/cars-credentials.txt (only root can read it).\n'
printf 'Then follow docs/DEPLOYMENT.md section 4 (settings, cashboxes, users, import).\n'
