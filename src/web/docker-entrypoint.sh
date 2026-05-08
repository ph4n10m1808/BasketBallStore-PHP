#!/bin/bash
set -e

# Marker file to avoid repeated permission fixes on every restart
MARKER="/var/www/html/.permissions_set"

if [ ! -f "$MARKER" ]; then
    # Fix ownership & permissions cho volume mount (chỉ chạy lần đầu)
    chown -R www-data:www-data /var/www/html
    find /var/www/html -type f -exec chmod 640 {} \;
    find /var/www/html -type d -exec chmod 750 {} \;

    # Upload directories cần writable bởi www-data (group)
    if [ -d /var/www/html/public/assets/imgs ]; then
        find /var/www/html/public/assets/imgs -type d -exec chmod 770 {} \;
        find /var/www/html/public/assets/imgs -type f -exec chmod 660 {} \;
    fi

    touch "$MARKER"
fi

exec "$@"
