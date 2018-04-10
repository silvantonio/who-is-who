FROM php:7.1.5-fpm

MAINTAINER ANtonio Silva <silvantonio@gmail.com>

COPY . /var/www/html

RUN docker-php-ext-install bcmath \
    && rm -R -f /var/www/html/var/cache \
    && apt update \
    && apt install -y git \
    && /usr/local/bin/php -r "readfile('http://getcomposer.org/installer');" | /usr/local/bin/php -- --install-dir=/usr/bin/ --filename=composer \
    && /usr/local/bin/php /usr/bin/composer install --working-dir=/var/www/html \
    && chown -R www-data:www-data /var/www/html/var \
    && chmod 777 -R /var/www/html/var

WORKDIR /var/www/html/web

EXPOSE 9000
CMD ["php-fpm"]
