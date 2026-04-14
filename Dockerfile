FROM php:8.4-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html/
COPY ./src .
COPY docker-entrypoint.sh /usr/local/bin/

RUN chown -R www-data:www-data /var/www/html \
    && chmod 750 /var/www/html \
    && find . -type f -exec chmod 640 {} \; \
    && find . -type d -exec chmod 750 {} \; \
    # && chmod -R 777 /var/www/html/public/imgs/ \
    # && chmod +t -R /var/www/html/
    && find ./public/imgs -type d -exec chmod 770 {} \; \
    && find ./public/imgs -type f -exec chmod 660 {} \; \
    && chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]