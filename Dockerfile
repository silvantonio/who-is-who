FROM php:7.1.5-fpm

MAINTAINER Antonio Silva <silvantonio@gmail.com>

COPY . /var/www/html

RUN apt update \
    && apt install -y git \
    && apt install -y zlib1g-dev \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip \
    && rm -R -f /var/www/html/var/cache

RUN /usr/local/bin/php -r "readfile('http://getcomposer.org/installer');" | \
    /usr/local/bin/php -- --install-dir=/usr/bin/ --filename=composer \
    && /usr/local/bin/php /usr/bin/composer install --working-dir=/var/www/html

RUN chown -R www-data:www-data /var/www/html/var \
    && chmod 777 -R /var/www/html/var

WORKDIR /var/www/html/web

EXPOSE 9000
CMD ["php-fpm"]
