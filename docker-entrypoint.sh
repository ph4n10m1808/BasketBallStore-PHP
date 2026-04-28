#!/bin/bash
set -e

# Fix ownership & permissions cho volume mount
chown -R www-data:www-data /var/www/html
find /var/www/html -type f -exec chmod 640 {} \;
find /var/www/html -type d -exec chmod 750 {} \;

# Upload directories cần writable bởi www-data (group)
if [ -d /var/www/html/public/imgs ]; then
  find /var/www/html/public/imgs -type d -exec chmod 770 {} \;
  find /var/www/html/public/imgs -type f -exec chmod 660 {} \;
fi

exec "$@"
