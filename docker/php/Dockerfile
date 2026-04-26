FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod rewrite \
    && sed -i 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

RUN echo "upload_max_filesize = 20M\npost_max_size = 20M\nmemory_limit = 256M" \
    > /usr/local/etc/php/conf.d/uploads.ini
