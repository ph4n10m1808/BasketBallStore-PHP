# ============================================================
# Stage 1: Builder — compile PHP extensions
# ============================================================
FROM php:8.4-apache AS builder

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql gd opcache \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# ============================================================
# Stage 2: Production — lean runtime image
# ============================================================
FROM php:8.4-apache

# Copy compiled extensions + their .ini from builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Install ONLY runtime libraries (no -dev packages)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng16-16t64 \
    libjpeg62-turbo \
    libfreetype6 \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Enable Apache modules for performance + functionality
RUN a2enmod rewrite headers deflate expires

# ─── Apache MPM: Tuned for high-concurrency stress tests ───
RUN { \
    echo '<IfModule mpm_prefork_module>'; \
    echo '  StartServers           10'; \
    echo '  MinSpareServers        10'; \
    echo '  MaxSpareServers        30'; \
    echo '  MaxRequestWorkers      300'; \
    echo '  MaxConnectionsPerChild 5000'; \
    echo '</IfModule>'; \
    } > /etc/apache2/conf-available/mpm-tuning.conf \
    && a2enconf mpm-tuning

# ─── OPcache: Aggressive production tuning ───
RUN { \
    echo 'opcache.memory_consumption=64'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=1000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.enable_file_override=1'; \
    echo 'opcache.save_comments=0'; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# ─── PHP: Reduced memory + upload settings ───
RUN { \
    echo 'memory_limit=128M'; \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'realpath_cache_size=4096K'; \
    echo 'realpath_cache_ttl=600'; \
    echo 'session.gc_maxlifetime=3600'; \
    echo 'session.gc_probability=1'; \
    echo 'session.gc_divisor=100'; \
    echo 'session.cookie_httponly=1'; \
    echo 'session.use_strict_mode=1'; \
    echo 'expose_php=Off'; \
    echo 'max_execution_time=30'; \
    echo 'max_input_time=30'; \
    echo 'output_buffering=4096'; \
    } > /usr/local/etc/php/conf.d/docker-php-custom.ini

# ─── Apache: Compression, caching, KeepAlive ───
RUN { \
    echo '<IfModule mod_deflate.c>'; \
    echo '  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css'; \
    echo '  AddOutputFilterByType DEFLATE application/javascript application/json'; \
    echo '  AddOutputFilterByType DEFLATE image/svg+xml'; \
    echo '  DeflateCompressionLevel 6'; \
    echo '</IfModule>'; \
    echo ''; \
    echo '<IfModule mod_expires.c>'; \
    echo '  ExpiresActive On'; \
    echo '  ExpiresByType image/jpeg "access plus 1 year"'; \
    echo '  ExpiresByType image/png "access plus 1 year"'; \
    echo '  ExpiresByType image/webp "access plus 1 year"'; \
    echo '  ExpiresByType text/css "access plus 1 month"'; \
    echo '  ExpiresByType application/javascript "access plus 1 month"'; \
    echo '  ExpiresByType image/x-icon "access plus 1 year"'; \
    echo '</IfModule>'; \
    echo ''; \
    echo '<IfModule mod_headers.c>'; \
    echo '  <FilesMatch "\.(jpg|jpeg|png|webp|gif|ico|css|js)$">'; \
    echo '    Header set Cache-Control "public, max-age=31536000, immutable"'; \
    echo '  </FilesMatch>'; \
    echo '</IfModule>'; \
    echo ''; \
    echo 'KeepAlive On'; \
    echo 'MaxKeepAliveRequests 100'; \
    echo 'KeepAliveTimeout 5'; \
    echo ''; \
    echo 'ServerTokens Prod'; \
    echo 'ServerSignature Off'; \
    } > /etc/apache2/conf-available/performance.conf \
    && a2enconf performance

WORKDIR /var/www/html/
COPY . .

# Copy vendor directory from builder stage
COPY --from=builder /app/vendor/ /var/www/html/vendor/

# ─── Security: Set Apache DocumentRoot to public/ ───
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY docker-entrypoint.sh /usr/local/bin/

# Thiết lập phân quyền nghiêm ngặt (750) theo yêu cầu hệ thống Lab
RUN chown -R www-data:www-data /var/www/html \
    && chmod 750 /var/www/html \
    && find . -type f -exec chmod 640 {} \; \
    && find . -type d -exec chmod 750 {} \; \
    && find ./public/assets/imgs -type d -exec chmod 770 {} \; \
    && find ./public/assets/imgs -type f -exec chmod 660 {} \; \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]