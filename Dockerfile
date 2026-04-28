FROM php:8.4-apache

# Cài đặt các phần mở rộng PHP nâng cao và công cụ cần thiết
RUN apt-get update && apt-get install -y --no-install-recommends \
    mariadb-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql gd opcache \
    && a2enmod rewrite headers \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Cấu hình OPcache tối ưu hiệu năng cho Lab
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Cấu hình PHP chung
RUN { \
    echo 'memory_limit=256M'; \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    } > /usr/local/etc/php/conf.d/docker-php-custom.ini

WORKDIR /var/www/html/
COPY ./src .
COPY docker-entrypoint.sh /usr/local/bin/

# Thiết lập phân quyền nghiêm ngặt (750) theo yêu cầu hệ thống Lab
RUN chown -R www-data:www-data /var/www/html \
    && chmod 750 /var/www/html \
    && find . -type f -exec chmod 640 {} \; \
    && find . -type d -exec chmod 750 {} \; \
    && find ./public/imgs -type d -exec chmod 770 {} \; \
    && find ./public/imgs -type f -exec chmod 660 {} \; \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]